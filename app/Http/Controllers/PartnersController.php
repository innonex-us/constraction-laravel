<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Prequalification;

class PartnersController extends Controller
{
    public function index(): View
    {
        // Show all prequalified partners
        $partners = Prequalification::query()
            ->whereNotNull('company_name') // Basic filter to ensure valid entries
            ->orderBy('company_name')
            ->get();
        
        return view('partners.index', compact('partners'));
    }

    public function prequalForm(): View
    {
        return view('partners.prequal');
    }

    public function prequalSubmit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'trade' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'years_in_business' => 'nullable|integer|min:0|max:200',
            'annual_revenue' => 'nullable|integer|min:0',
            'bonding_capacity' => 'nullable|integer|min:0',
            'emr' => 'nullable|numeric|min:0',
            'trir' => 'nullable|numeric|min:0',
            'safety_contact' => 'nullable|string|max:255',
            'insurance_carrier' => 'nullable|string|max:255',
            'coverage' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'zip' => 'nullable|string|max:10',
            'website' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        Prequalification::create($data);

        return back()->with('success', 'Thanks! Your prequalification was submitted. We will review and contact you.');
    }
}
