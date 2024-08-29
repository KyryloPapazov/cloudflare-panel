<?php

namespace App\Http\Controllers\Cloudflare;

use Illuminate\Http\Request;
use App\Models\CloudflareAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CloudflareAccountController extends Controller
{
    public function index()
    {
        $accounts = Auth::user()->cloudflareAccounts;
        return response()->json($accounts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'api_key' => 'required|string|max:255',
        ]);

        $account = Auth::user()->cloudflareAccounts()->create($request->all());

        return response()->json($account, 201);
    }

    public function show($id)
    {
        $account = CloudflareAccount::findOrFail($id);

        return response()->json($account);
    }

    public function destroy($id)
    {
        $account = CloudflareAccount::findOrFail($id);
        $account->delete();

        return response()->json(null, 204);
    }
}
