<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\EmployersPageContent;
use App\Models\CompanyValue;
use App\Models\TeamMember;

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
        $teamMember1 = TeamMember::getByKey('team_member_1');
        $teamMember2 = TeamMember::getByKey('team_member_2');
        $teamMember3 = TeamMember::getByKey('team_member_3');
        
        return view('pages.about', compact('teamMember1', 'teamMember2', 'teamMember3'));
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
