<?php

namespace App\Http\Controllers;

use App\Models\Partner;

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

    public function forEmployers()
    {
        return view('pages.for-employers');
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
