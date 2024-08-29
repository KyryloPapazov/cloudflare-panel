<?php

namespace App\Http\Controllers\Cloudflare;

use App\Models\Domain;
use App\Models\PageRule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageRuleController extends Controller
{
    public function index(Domain $domain)
    {
        $pageRules = $domain->pageRules;
        return Inertia::render('PageRules/Index', ['pageRules' => $pageRules]);
    }

    public function create(Domain $domain)
    {
        return Inertia::render('PageRules/Create', ['domain' => $domain]);
    }

    public function store(Request $request, Domain $domain)
    {
        $request->validate([
            'rule_name' => 'required|string|max:255',
            'rule_settings' => 'required|json',
        ]);

        $domain->pageRules()->create($request->all());

        return redirect()->route('page-rules.index', $domain);
    }

    public function destroy(Domain $domain, PageRule $pageRule)
    {
        $pageRule->delete();
        return redirect()->route('page-rules.index', $domain);
    }
}
