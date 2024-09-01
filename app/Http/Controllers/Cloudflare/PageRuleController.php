<?php

namespace App\Http\Controllers\Cloudflare;

use App\Models\Domain;
use App\Models\PageRule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PageRuleController extends Controller
{
    // Показать список Page Rules для домена
    public function index($domainId)
    {
        $domain = Domain::findOrFail($domainId);
        $account = $domain->cloudflareAccount;

        $cloudflareRules = $this->fetchPageRulesFromCloudflare($account, $domain->cloudflare_zone_id);

        if ($cloudflareRules !== null) {
            // Synchronize Cloudflare data with the local database
            $this->syncPageRulesWithDatabase($domain, $cloudflareRules);

            // Get all page rules from the database after synchronization
            $pageRules = PageRule::where('domain_id', $domainId)->get();

            // Remove local page rules that no longer exist on Cloudflare
            $this->deleteLocalRulesNotOnCloudflare($domain, $cloudflareRules);

            return Inertia::render('PageRules/Index', [
                'domain' => $domain,
                'pageRules' => $pageRules,
                'success' => 'Page Rules synchronized successfully with Cloudflare.',
            ]);
        } else {
            // If the request failed, return an error message
            $pageRules = PageRule::where('domain_id', $domainId)->get();

            return Inertia::render('PageRules/Index', [
                'domain' => $domain,
                'pageRules' => $pageRules,
                'error' => 'Failed to fetch Page Rules from Cloudflare.',
            ]);
        }
    }

    /**
     * Deletes local page rules that no longer exist on Cloudflare.
     *
     * @param Domain $domain
     * @param array $cloudflareRules
     * @return void
     */
    private function deleteLocalRulesNotOnCloudflare($domain, $cloudflareRules)
    {
        $cloudflareRuleIds = array_column($cloudflareRules, 'id');

        // Fetch local page rules that are not in the Cloudflare response
        $localRules = PageRule::where('domain_id', $domain->id)->get();

        foreach ($localRules as $localRule) {
            if (!in_array($localRule->cloudflare_rule_id, $cloudflareRuleIds)) {
                $localRule->delete();
            }
        }
    }


    // Форма для создания нового Page Rule
    public function create($domainId)
    {
        $domain = Domain::findOrFail($domainId);

        return Inertia::render('PageRules/Create', [
            'domain' => $domain,
        ]);
    }

    // Сохранить новый Page Rule в базе данных и на Cloudflare
    public function store(Request $request, $domainId)
    {
        $request->validate([
            'target_url' => 'required|string|max:255',
            'actions' => 'required|array',
        ]);

        $domain = Domain::findOrFail($domainId);
        $account = $domain->cloudflareAccount;

        $formattedActions = $this->formatActionsForCloudflare($request->actions);

        $response = $this->createOrUpdatePageRuleOnCloudflare($account, $domain->cloudflare_zone_id, [
            'targets' => [
                [
                    'target' => 'url',
                    'constraint' => [
                        'operator' => 'matches',
                        'value' => $request->target_url
                    ]
                ]
            ],
            'actions' => $formattedActions,
            'status' => 'active',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            PageRule::create([
                'domain_id' => $domain->id,
                'target_url' => $request->target_url,
                'actions' => $formattedActions,
                'status' => 'active',
                'cloudflare_rule_id' => $data['result']['id'],
            ]);

            return redirect()->route('domains.pagerules.index', $domainId)->with('success', 'Page Rule added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add Page Rule to Cloudflare.');
        }
    }

    // Редактировать Page Rule
    public function edit($domainId, $ruleId)
    {
        $domain = Domain::findOrFail($domainId);
        $pageRule = PageRule::where('id', $ruleId)->where('domain_id', $domainId)->firstOrFail();

        return Inertia::render('PageRules/Edit', [
            'domain' => $domain,
            'pageRule' => $pageRule,
        ]);
    }

    // Обновить данные Page Rule в базе данных и на Cloudflare
    public function update(Request $request, $domainId, $ruleId)
    {
        $request->validate([
            'target_url' => 'required|string|max:255',
            'actions' => 'required|array',
            'status' => 'required|string|in:active,disabled',
        ]);

        $domain = Domain::findOrFail($domainId);
        $pageRule = PageRule::where('id', $ruleId)->where('domain_id', $domainId)->firstOrFail();
        $account = $domain->cloudflareAccount;

        try {
            $formattedActions = $this->formatActionsForCloudflare($request->actions);
        }catch(\Exception $e) {
            $formattedActions = $this->formatActionsForToogle($request->actions);
            $formattedActions = $this->formatActionsForCloudflare($formattedActions);
        };



        $response = $this->createOrUpdatePageRuleOnCloudflare($account, $domain->cloudflare_zone_id, [
            'targets' => [
                [
                    'target' => 'url',
                    'constraint' => [
                        'operator' => 'matches',
                        'value' => $request->target_url
                    ]
                ]
            ],
            'actions' => $formattedActions,
            'status' => $request->status,
        ], $pageRule->cloudflare_rule_id);

        if ($response->successful()) {
            $pageRule->update([
                'target_url' => $request->target_url,
                'actions' => $request->actions,
                'status' => $request->status,
            ]);

            return redirect()->route('domains.pagerules.index', $domainId)->with('success', 'Page Rule updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update Page Rule on Cloudflare.');
        }
    }


    public function destroy($id)
    {
        $pageRule = PageRule::findOrFail($id);
        $domain = $pageRule->domain;
        $account = $domain->cloudflareAccount;

        $response = $this->deletePageRuleFromCloudflare($account, $domain->cloudflare_zone_id, $pageRule->cloudflare_rule_id);

        if ($response->successful()) {
            $pageRule->delete();
            return redirect()->route('domains.pagerules.index', $domain->id)->with('success', 'Page Rule deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete Page Rule from Cloudflare.');
        }
    }

    /**
     * Fetch Page Rules from Cloudflare.
     *
     * @param CloudflareAccount $account
     * @param string $zoneId
     * @return array|null
     */
    private function fetchPageRulesFromCloudflare($account, $zoneId)
    {
        $response = Http::withToken($account->api_key)
            ->get("https://api.cloudflare.com/client/v4/zones/{$zoneId}/pagerules");

        if ($response->successful()) {
            return $response->json()['result'];
        } else {
            \Log::error('Failed to fetch page rules from Cloudflare for zone ' . $zoneId);
            return null;
        }
    }

    /**
     * Sync Page Rules with the database.
     *
     * @param Domain $domain
     * @param array $cloudflareRules
     * @return void
     */
    private function syncPageRulesWithDatabase($domain, $cloudflareRules)
    {
        foreach ($cloudflareRules as $cloudflareRule) {
            PageRule::updateOrCreate(
                [
                    'cloudflare_rule_id' => $cloudflareRule['id'],
                    'domain_id' => $domain->id,
                ],
                [
                    'target_url' => $cloudflareRule['targets'][0]['constraint']['value'],
                    'actions' => $cloudflareRule['actions'],
                    'status' => $cloudflareRule['status'],
                ]
            );
        }
    }

    /**
     * Format actions for Cloudflare API.
     *
     * @param array $actions
     * @return array
     */
    private function formatActionsForCloudflare(array $actions)
    {
        return array_map(function ($action) {
            if (!isset($action['id']) || !array_key_exists('value', $action)) {
                throw new \InvalidArgumentException("Missing 'id' or 'value' key in action array.");
            }

            $formattedValue = is_null($action['value']) ? ' ' : $action['value'];

            return [
                'id' => $action['id'],
                'value' => $formattedValue
            ];
        }, $actions);
    }

    private function formatActionsForToogle(array $actions)
    {
        return array_map(function ($action) {
            if (!isset($action['id']) || !array_key_exists('value', $action)) {
                return[
                    'id' => $action['id'],
                    'value' =>  null ];

            }

            $formattedValue = is_null($action['value']) ? ' ' : $action['value'];

            return [
                'id' => $action['id'],
                'value' => $formattedValue
            ];
        }, $actions);
    }

    /**
     * Create or Update Page Rule on Cloudflare.
     *
     * @param CloudflareAccount $account
     * @param string $zoneId
     * @param array $data
     * @param string|null $ruleId
     * @return \Illuminate\Http\Client\Response
     */
    private function createOrUpdatePageRuleOnCloudflare($account, $zoneId, $data, $ruleId = null)
    {
        $url = "https://api.cloudflare.com/client/v4/zones/{$zoneId}/pagerules" . ($ruleId ? "/{$ruleId}" : "");
        $method = $ruleId ? 'put' : 'post';

        return Http::withToken($account->api_key)->$method($url, $data);
    }

    /**
     * Delete a Page Rule from Cloudflare.
     *
     * @param CloudflareAccount $account
     * @param string $zoneId
     * @param string $ruleId
     * @return \Illuminate\Http\Client\Response
     */
    private function deletePageRuleFromCloudflare($account, $zoneId, $ruleId)
    {
        return Http::withToken($account->api_key)
            ->delete("https://api.cloudflare.com/client/v4/zones/{$zoneId}/pagerules/{$ruleId}");
    }
}
