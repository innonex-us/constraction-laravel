@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => $page->meta_title ?? $page->title, 'metaDescription' => $page->meta_description])

@section('content')
@if(strtolower($page->slug) === 'about')
    {{-- Special About page layout --}}
    @php($latestSafety = \App\Models\SafetyRecord::orderByDesc('year')->first())
    @php($projectCount = \App\Models\Project::count())
    @php($serviceCount = \App\Models\Service::where('is_active', true)->count())
    @php($sectors = \App\Models\Project::query()->select('category')->distinct()->pluck('category')->filter())

    <section class="relative overflow-hidden blueprint">
        <div class="mx-auto max-w-7xl px-4 py-16">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <p class="text-emerald-300 uppercase tracking-wider text-xs">About Us</p>
                    <h1 class="mt-2 text-4xl md:text-6xl font-extrabold leading-tight neon">{{ $page->title }}</h1>
                    @if(!empty($settings?->headline))
                        <p class="mt-4 text-slate-300 text-lg max-w-prose">{{ $settings->headline }}</p>
                    @endif
                    <div class="mt-8 grid grid-cols-3 gap-4 text-center">
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                            <div class="text-sm text-slate-400">Projects</div>
                            <div class="mt-1 text-3xl font-bold">{{ max($projectCount, 100) }}+</div>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                            <div class="text-sm text-slate-400">Services</div>
                            <div class="mt-1 text-3xl font-bold">{{ $serviceCount }}</div>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                            <div class="text-sm text-slate-400">EMR</div>
                            <div class="mt-1 text-3xl font-bold">{{ number_format($latestSafety->emr ?? 0.62, 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-video rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                        @if($page->hero_image_url)
                            <img loading="lazy" decoding="async" fetchpriority="low" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" data-src="{{ $page->hero_image_fallback_url ?? $page->hero_image_url }}" @php($wsrc = $page->hero_image_srcset_webp ?? $page->hero_image_srcset) @if($wsrc) data-srcset="{{ $wsrc }}" sizes="(min-width:1024px) 50vw, 100vw" @endif class="w-full h-full object-cover" />
                        @else
                            <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1600&auto=format&fit=crop" class="w-full h-full object-cover" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <div class="grid lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8">
                <article class="prose prose-invert">
                    {!! $page->content !!}
                </article>
            </div>
            <aside class="lg:col-span-4 space-y-6">
                @if($sectors->count())
                <div class="rounded-xl bg-white/5 border border-white/10 p-5">
                    <div class="text-sm uppercase tracking-wider text-slate-400">Sectors</div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($sectors as $sec)
                            <a href="{{ route('projects.index', ['category' => $sec]) }}" class="px-3 py-1.5 rounded-full bg-white/5 border border-white/10 hover:border-white/30 text-sm">{{ $sec }}</a>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="rounded-xl bg-white/5 border border-white/10 p-5">
                    <div class="text-sm uppercase tracking-wider text-slate-400">Get in Touch</div>
                    <p class="text-slate-300 mt-2 text-sm">Have a project in mind? Let’s talk about how we can help.</p>
                    <a href="/contact" class="mt-4 inline-block px-4 py-2 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">Contact us</a>
                </div>
            </aside>
        </div>
    </section>

    @php($team = \App\Models\TeamMember::orderBy('order')->take(8)->get())
@else
    {{-- Default page layout --}}
    <section class="mx-auto max-w-4xl px-4 py-12">
        <h1 class="text-3xl md:text-4xl font-bold">{{ $page->title }}</h1>
        @if($page->hero_image_url)
            <div class="mt-6 aspect-video rounded-2xl overflow-hidden border border-white/10">
                <img loading="lazy" decoding="async" fetchpriority="low" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" data-src="{{ $page->hero_image_fallback_url ?? $page->hero_image_url }}" @php($wsrc2 = $page->hero_image_srcset_webp ?? $page->hero_image_srcset) @if($wsrc2) data-srcset="{{ $wsrc2 }}" sizes="(min-width:1024px) 60vw, 100vw" @endif class="w-full h-full object-cover" />
            </div>
        @endif
        <article class="prose prose-invert mt-6">
            {!! $page->content !!}
        </article>
    </section>
@endif

@if(isset($team) && $team && $team->count())
    <section class="mx-auto max-w-7xl px-4 pb-16">
        <h2 class="text-2xl md:text-3xl font-bold mb-6">Leadership</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($team as $member)
                <div class="rounded-2xl overflow-hidden border border-white/10 bg-white/5">
                    @if($member->photo_url)
                        <img loading="lazy" decoding="async" fetchpriority="low" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" data-src="{{ $member->photo_fallback_url ?? $member->photo_url }}" @php($pm = $member->photo_srcset_webp ?? $member->photo_srcset) @if($pm) data-srcset="{{ $pm }}" sizes="(min-width:1024px) 25vw, 100vw" @endif alt="{{ $member->name }}" class="w-full h-52 object-cover" />
                    @endif
                    <div class="p-4">
                        <div class="font-semibold">{{ $member->name }}</div>
                        <div class="text-sm text-slate-400">{{ $member->role }}</div>
                        @if($member->linkedin_url)
                            <a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener" class="text-emerald-300 text-sm">LinkedIn →</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
