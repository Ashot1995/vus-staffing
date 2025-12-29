<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\EmployersPageContent;
use App\Models\CompanyValue;

class PageController extends Controller
{
    public function home()
    {
        $partners = Partner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('pages.home', compact('partners'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function companyValues()
    {
        $values = CompanyValue::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('pages.company-values', compact('values'));
    }

    public function forEmployers()
    {
        $content = EmployersPageContent::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();
        
        return view('pages.for-employers', compact('content'));
    }

    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}
