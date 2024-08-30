<?php

namespace App\Http\Controllers\Cloudflare;

use App\Models\CloudflareAccount, App\Models\Domain;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class DomainController extends Controller
{
    // Показать список доменов
    public function index($id)
    {

//        $domains = Domain::with('cloudflareAccount')->findOrFail($id);
//        $domains = Domain::with('cloudflareAccount')->where('cloudflare_account_id', $id)->get();
        $account = CloudflareAccount::findOrFail($id);

        $apiToken = $account->api_key;

        $response = Http::withToken($apiToken)
            ->get('https://api.cloudflare.com/client/v4/zones');


        if ($response->successful()) {
            $zones = $response->json()['result']; // Получаем результат
            var_dump($zones);

            // Обновляем информацию о доменах в базе данных
            foreach ($zones as $zone) {
                Domain::updateOrCreate(
                    ['cloudflare_account_id' => $account->id, 'name' => $zone['name']], // Поиск по account_id и имени домена
                    [
                        'status' => $zone['status'],
                        'cloudflare_zone_id' => $zone['id'], // Сохраняем cloudflare_zone_id
                        'ssl_mode' => $zone['settings']['ssl']['value'] ?? 'off', // Получаем SSL режим

                    ]
                );
            }

            // Получаем все домены из базы данных после обновления
            $domains = Domain::with('cloudflareAccount')->where('cloudflare_account_id', $id)->get();

            return Inertia::render('Domains/Index', [
                'domains' => $domains,
                'success' => 'Domains synchronized successfully.',

            ]);
        } else {
            // Если запрос не удался, передаем ошибку на страницу
            $domains = Domain::with('cloudflareAccount')->where('cloudflare_account_id', $id)->get();

            return Inertia::render('Domains/Index', [
                'domains' => $domains,
                'error' => 'Failed to fetch domains from Cloudflare.',
            ]);
        }
    }

    // Форма для добавления нового домена
    public function create()
    {
//        $accounts = CloudflareAccount::all()->where('user_id', 1)->get();
        $accounts =  Auth::user()->cloudflareAccounts;

        return Inertia::render('Domains/Create', [
            'accounts' => $accounts
        ]);
    }

    // Сохранить новый домен в базе данных и на Cloudflare
    public function store(Request $request)
    {

        $request->validate([
            'cloudflare_account_id' => 'required|exists:cloudflare_accounts,id',
            'name' => 'required|string|max:255',
        ]);

        $account = CloudflareAccount::find($request->cloudflare_account_id);

        $response = Http::withToken($account->api_key)
            ->post("https://api.cloudflare.com/client/v4/zones", [
                'name' => $request->name,
//                'account' => ['id' => $account->id],
            ]);
        var_dump($response->json());
        $response = Http::withToken($account->api_key)
            ->get("https://api.cloudflare.com/client/v4/zones");
        var_dump($response->json());

        if ($response->successful()) {
            $data = $response->json();
            Domain::create([
                'cloudflare_account_id' => $account->id,
                'name' => $data['result']['name'],
                'status' => $data['result']['status'],
            ]);

            return redirect()->route('domains.index')->with('success', 'Domain added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add domain.');
        }
    }

    // Показать форму для редактирования домена
    public function edit($id)
    {
        $domain = Domain::findOrFail($id);

        return Inertia::render('Domains/Edit', [
            'domain' => $domain
        ]);
    }

    // Обновить режим SSL/TLS для домена
    public function update(Request $request, $id)
    {
        $request->validate([
            'ssl_mode' => 'required|in:off,flexible,full,full_strict',
        ]);

        $domain = Domain::findOrFail($id);
        $account = $domain->cloudflareAccount;

        $response = Http::withToken($account->api_key)
            ->patch("https://api.cloudflare.com/client/v4/zones/{$domain->cloudflare_zone_id}/settings/ssl", [
                'value' => $request->ssl_mode,
            ]);

        if ($response->successful()) {
            $domain->ssl_mode = $request->ssl_mode;
            $domain->save();

            return redirect()->route('domains.index')->with('success', 'SSL/TLS mode updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update SSL/TLS mode.');
        }
    }

    // Удалить домен
    public function destroy($id)
    {
        // Находим домен в базе данных
        $domain = Domain::findOrFail($id);
        $account = $domain->cloudflareAccount;

        // Выводим значения для отладки
        var_dump("API Key: ", $account->api_key);
        var_dump("Zone ID: ", $domain->cloudflare_zone_id);

        $response = Http::withToken($account->api_key)
            ->delete("https://api.cloudflare.com/client/v4/zones/{$domain->cloudflare_zone_id}");


        // Проверяем успешность запроса
        if ($response->successful()) {
            $domain->delete(); // Удаляем домен из базы данных
            return redirect()->route('domains.index')->with('success', 'Domain deleted successfully.');
        } else {
            // Если запрос не удался, выводим сообщение об ошибке
            return redirect()->back()->with('error', 'Failed to delete domain.');
        }
    }

    }

