@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => 'Trade Partner Prequalification'])

@section('content')
<section class="mx-auto max-w-3xl px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-bold mb-3">Trade Partner Prequalification</h1>
    <p class="text-slate-300 mb-6">Tell us about your company to get prequalified for upcoming opportunities.</p>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 text-emerald-300 border border-emerald-500/30">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('partners.prequal.submit') }}" class="grid gap-4">
        @csrf
        <div>
            <label class="block text-sm mb-1">Company Name *</label>
            <input name="company_name" value="{{ old('company_name') }}" required class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            @error('company_name')<p class="text-red-400 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">Contact Name</label>
                <input name="contact_name" value="{{ old('contact_name') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm mb-1">Trade</label>
                <input name="trade" value="{{ old('trade') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm mb-1">Phone</label>
                <input name="phone" value="{{ old('phone') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">Years in Business</label>
                <input type="number" name="years_in_business" value="{{ old('years_in_business') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm mb-1">License Number</label>
                <input name="license_number" value="{{ old('license_number') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm mb-1">Annual Revenue ($)</label>
                <input type="number" name="annual_revenue" value="{{ old('annual_revenue') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm mb-1">Bonding Capacity ($)</label>
                <input type="number" name="bonding_capacity" value="{{ old('bonding_capacity') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm mb-1">EMR</label>
                <input type="number" step="0.01" name="emr" value="{{ old('emr') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm mb-1">TRIR</label>
                <input type="number" step="0.01" name="trir" value="{{ old('trir') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm mb-1">Safety Contact</label>
                <input name="safety_contact" value="{{ old('safety_contact') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm mb-1">Insurance Carrier</label>
                <input name="insurance_carrier" value="{{ old('insurance_carrier') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">Coverage</label>
                <input name="coverage" value="{{ old('coverage') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm mb-1">Website</label>
                <input name="website" value="{{ old('website') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">Address</label>
                <input name="address" value="{{ old('address') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm mb-1">City</label>
                    <input name="city" value="{{ old('city') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm mb-1">State</label>
                    <input name="state" value="{{ old('state') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm mb-1">ZIP</label>
                    <input name="zip" value="{{ old('zip') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
                </div>
            </div>
        </div>
        <div>
            <label class="block text-sm mb-1">Notes</label>
            <textarea name="notes" rows="5" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2">{{ old('notes') }}</textarea>
        </div>
        <div>
            <button class="px-6 py-3 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">Submit</button>
        </div>
    </form>
</section>
@endsection

