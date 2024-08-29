<?php

namespace App\Http\Controllers\Cloudflare;

use App\Models\CloudflareAccount, App\Models\Domain;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DomainController extends Controller
{
    public function index(CloudflareAccount $cloudflareAccount)
    {
        $domains = $cloudflareAccount->domains;
        return Inertia::render('Domains/Index', ['domains' => $domains]);
    }

    public function create(CloudflareAccount $cloudflareAccount)
    {
        return Inertia::render('Domains/Create', ['cloudflareAccount' => $cloudflareAccount]);
    }

    public function store(Request $request, CloudflareAccount $cloudflareAccount)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $cloudflareAccount->domains()->create($request->all());

        return redirect()->route('domains.index', $cloudflareAccount);
    }

    public function destroy(CloudflareAccount $cloudflareAccount, Domain $domain)
    {
        $domain->delete();
        return redirect()->route('domains.index', $cloudflareAccount);
    }
}

