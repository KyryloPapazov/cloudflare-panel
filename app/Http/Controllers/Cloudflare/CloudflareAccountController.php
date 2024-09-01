<?php

namespace App\Http\Controllers\Cloudflare;

use App\Http\Controllers\Controller;
use App\Models\CloudflareAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
class CloudflareAccountController extends Controller
{

    // Отобразить список всех аккаунтов Cloudflare для текущего пользователя
    public function index()
    {
        $accounts = Auth::user()->cloudflareAccounts;

        return Inertia::render('Cloudflare/Index', [
            'accounts' => $accounts
        ]);
    }

    // Отобразить форму для создания нового аккаунта
    public function create()
    {
        return Inertia::render('Cloudflare/Create');
    }

    // Сохранить новый аккаунт в базе данных
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'api_key' => 'required|string|max:255',
        ]);

        $response = Http::withToken($request['api_key'])
            ->get('https://api.cloudflare.com/client/v4/zones');

        if ($response->successful()) {
            Auth::user()->cloudflareAccounts()->create($request->all());

            return redirect()->route('cloudflare-accounts.index')->with('success', 'Account added successfully.');
        }
        session()->flash('error', 'Failed to add accounts, please check your API key in https://dash.cloudflare.com/profile/api-tokens.');
        return redirect()->route('cloudflare-accounts.create');

    }

    // Отобразить детали конкретного аккаунта
    public function show($id)
    {
        $account = CloudflareAccount::findOrFail($id);

        return Inertia::render('Cloudflare/Show', [
            'account' => $account
        ]);
    }

    // Отобразить форму для редактирования аккаунта
    public function edit($id)
    {
        $account = CloudflareAccount::findOrFail($id);

        return Inertia::render('Cloudflare/Edit', [
            'account' => $account
        ]);
    }

    // Обновить данные аккаунта в базе данных
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'api_key' => 'required|string|max:255',
        ]);

        $account = CloudflareAccount::findOrFail($id);
        $account->update($request->all());

        return redirect()->route('cloudflare-accounts.index')->with('success', 'Account updated successfully.');
    }

    // Удалить аккаунт из базы данных
    public function destroy($id)
    {
        $account = CloudflareAccount::findOrFail($id);
        $account->delete();

        return redirect()->route('cloudflare-accounts.index')->with('success', 'Account deleted successfully.');
    }
}
