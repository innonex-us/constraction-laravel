@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => 'Trade Partners'])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold">Trade Partners</h1>
            <p class="text-slate-300 mt-2">Our qualified trade partners and subcontractors.</p>
        </div>
        <a href="{{ route('partners.prequal') }}" class="px-4 py-2 rounded-lg bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/20 transition w-max">
            Apply for Prequalification
        </a>
    </div>

    @if($partners->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($partners as $partner)
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10" data-aos="fade-up">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $partner->company_name }}</h3>
                            @if($partner->trade)
                                <p class="text-slate-400 text-sm">{{ $partner->trade }}</p>
                            @endif
                        </div>
                        @if($partner->years_in_business)
                            <span class="px-2 py-1 rounded-md bg-emerald-500/10 text-emerald-300 text-xs">
                                {{ $partner->years_in_business }} years
                            </span>
                        @endif
                    </div>

                    <div class="space-y-2 text-sm">
                        @if($partner->contact_name)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-slate-300">{{ $partner->contact_name }}</span>
                            </div>
                        @endif

                        @if($partner->email)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:{{ $partner->email }}" class="text-emerald-300 hover:text-emerald-200">{{ $partner->email }}</a>
                            </div>
                        @endif

                        @if($partner->phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <a href="tel:{{ $partner->phone }}" class="text-slate-300 hover:text-emerald-300">{{ $partner->phone }}</a>
                            </div>
                        @endif

                        @if($partner->website)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                <a href="{{ $partner->website }}" target="_blank" rel="noopener" class="text-emerald-300 hover:text-emerald-200">Visit Website</a>
                            </div>
                        @endif

                        @if($partner->city && $partner->state)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-slate-400">{{ $partner->city }}, {{ $partner->state }}</span>
                            </div>
                        @endif
                    </div>

                    @if($partner->license_number)
                        <div class="mt-4 pt-4 border-t border-white/10 text-xs text-slate-400">
                            License: {{ $partner->license_number }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-slate-400 mb-4">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">No Trade Partners Listed</h3>
            <p class="text-slate-400 mb-6">We're currently building our network of qualified trade partners.</p>
            <a href="{{ route('partners.prequal') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/20 transition">
                Apply for Prequalification
            </a>
        </div>
    @endif
</section>
@endsection
