<?php

// app/Http/Controllers/Cloudflare/PageRuleController.php

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

        // Получаем список Page Rules из Cloudflare
        $response = Http::withToken($account->api_key)
            ->get("https://api.cloudflare.com/client/v4/zones/{$domain->cloudflare_zone_id}/pagerules");

        if ($response->successful()) {
            $cloudflareRules = $response->json()['result'];

            // Синхронизируем данные Cloudflare с локальной базой данных
            foreach ($cloudflareRules as $cloudflareRule) {
                PageRule::updateOrCreate(
                    [
                        'cloudflare_rule_id' => $cloudflareRule['id'],
                        'domain_id' => $domain->id,
                    ],
                    [
                        'target_url' => $cloudflareRule['targets'][0]['constraint']['value'],  // URL для Page Rule
                        'actions' => $cloudflareRule['actions'],  // Действия для Page Rule
                        'status' => $cloudflareRule['status'],  // Статус правила
                    ]
                );
            }

            // Получаем обновленный список Page Rules из базы данных
            $pageRules = PageRule::where('domain_id', $domainId)->get();

            return Inertia::render('PageRules/Index', [
                'domain' => $domain,
                'pageRules' => $pageRules,
                'success' => 'Page Rules synchronized successfully with Cloudflare.',
            ]);
        } else {
            // Если запрос не удался, вернуть сообщение об ошибке
            $pageRules = PageRule::where('domain_id', $domainId)->get();

            return Inertia::render('PageRules/Index', [
                'domain' => $domain,
                'pageRules' => $pageRules,
                'error' => 'Failed to fetch Page Rules from Cloudflare.',
            ]);
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

        // Создание Page Rule в Cloudflare
        $response = Http::withToken($account->api_key)
            ->post("https://api.cloudflare.com/client/v4/zones/{$domain->cloudflare_zone_id}/pagerules", [
                'targets' => [
                    [
                        'target' => 'url',
                        'constraint' => [
                            'operator' => 'matches',
                            'value' => $request->target_url
                        ]
                    ]
                ],
                'actions' => array_map(function ($action) {
                    // Map the actions to ensure they are formatted correctly
                    $formattedAction = ['id' => strtolower(str_replace(' ', '_', $action['settingType']))];
                    if (isset($action['ssl'])) {
                        $formattedAction['value'] = $action['ssl'];
                    } elseif (isset($action['appsDisabled'])) {
                        $formattedAction['value'] = null;
                    } elseif (isset($action['ipGeolocation'])) {
                        $formattedAction['value'] = $action['ipGeolocation'] ? 'on' : 'off';
                    }
                    return $formattedAction;
                }, $request->actions),  // Ensure 'actions' is an array of correctly formatted objects
                'status' => 'active',
            ]);
        var_dump($response->json());

        if ($response->successful()) {
            $data = $response->json();
            PageRule::create([
                'domain_id' => $domain->id,
                'target_url' => $request->target_url,
                'actions' => $request->actions,
                'status' => 'active',
                'cloudflare_rule_id' => $data['result']['id'],
            ]);

            return redirect()->route('pagerules.index', $domainId)->with('success', 'Page Rule added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add Page Rule to Cloudflare.');
        }
    }

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
        echo "<h1>Paage rule</h1>";
        var_dump($pageRule);

//        print_r($domain);

        // Обновление Page Rule в Cloudflare
        $response = Http::withToken($account->api_key)
            ->put("https://api.cloudflare.com/client/v4/zones/{$domain->cloudflare_zone_id}/pagerules/{$pageRule->cloudflare_rule_id}", [
                'targets' => [['target' => 'url', 'constraint' => ['operator' => 'matches', 'value' => $request->target_url]]],
                'actions' => $request->actions,
                'status' => $request->status,
            ]);

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

    // Удалить Page Rule
    public function destroy($id)
    {
        $pageRule = PageRule::findOrFail($id);
        $domain = $pageRule->domain;
        $account = $domain->cloudflareAccount;

        // Удаление Page Rule из Cloudflare
        $response = Http::withToken($account->api_key)
            ->delete("https://api.cloudflare.com/client/v4/zones/{$domain->cloudflare_zone_id}/pagerules/{$pageRule->cloudflare_rule_id}");

        if ($response->successful()) {
            $pageRule->delete();
            return redirect()->route('pagerules.index', $domain->id)->with('success', 'Page Rule deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete Page Rule from Cloudflare.');
        }
    }
}
