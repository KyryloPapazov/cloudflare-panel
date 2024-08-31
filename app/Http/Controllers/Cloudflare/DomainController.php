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
        $account = CloudflareAccount::findOrFail($id);

        // Получаем список всех зон (доменов) из Cloudflare
        $response = Http::withToken($account->api_key)
            ->get('https://api.cloudflare.com/client/v4/zones');

        // Проверяем успешность запроса
        if ($response->successful()) {
            $zones = $response->json()['result']; // Получаем результат

            // Обновляем информацию о доменах в базе данных
            foreach ($zones as $zone) {
                // Делаем дополнительный запрос, чтобы получить SSL настройки для каждой зоны
                $sslResponse = Http::withToken($account->api_key)
                    ->get("https://api.cloudflare.com/client/v4/zones/{$zone['id']}/settings/ssl");

                // Проверяем успешность запроса для SSL
                $sslMode = 'off'; // Значение по умолчанию
                if ($sslResponse->successful()) {
                    $sslMode = $sslResponse->json()['result']['value'] ?? 'off';
                } else {
                    // Логгирование ошибки или обработка случая, если запрос не удался
                    print_r("error_not_succes_SSL_response");
                    die();
                }

                // Обновление или создание записи о домене
                Domain::updateOrCreate(
                    ['cloudflare_account_id' => $account->id, 'name' => $zone['name']],
                    [
                        'status' => $zone['status'],
                        'cloudflare_zone_id' => $zone['id'],
                        'ssl_mode' => $sslMode, // Устанавливаем SSL режим
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
        $accounts = Auth::user()->cloudflareAccounts;

        return Inertia::render('Domains/Create', [
            'accounts' => $accounts
        ]);
    }

    // Сохранить новый домен в базе данных и на Cloudflare
    public function store(Request $request)
    {
        echo $request;

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
        echo $request;

//        $response = Http::withToken($account->api_key)
//            ->get("https://api.cloudflare.com/client/v4/zones");
//        var_dump($response->json());

        if ($response->successful()) {
            return redirect()->route('cloudflare-domains.index', $account->id)->with('success', 'Domain added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add domain.');
        }
    }

    // Показать форму для редактирования домена
//    public function edit($id)
//    {
//        $domain = Domain::findOrFail($id);
//
//        return Inertia::render('Domains/Edit', [
//            'domain' => $domain
//        ]);
//    }

    // Обновить режим SSL/TLS для домена
    public function update(Request $request, $id)
    {
        $request->validate([
            'ssl_mode' => 'required|in:off,flexible,full,strict',
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

            return redirect()->route('cloudflare-domains.index', $account->id)->with('success', 'SSL/TLS mode updated successfully.');
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


        $response = Http::withToken($account->api_key)
            ->delete("https://api.cloudflare.com/client/v4/zones/{$domain->cloudflare_zone_id}");


        // Проверяем успешность запроса
        if ($response->successful()) {
            $domain->delete(); // Удаляем домен из базы данных
            return redirect()->route('cloudflare-domains.index', $id)->with('success', 'Domain deleted successfully.');
        } else {
            // Если запрос не удался, выводим сообщение об ошибке
            return redirect()->back()->with('error', 'Failed to delete domain.');
        }
    }

}

