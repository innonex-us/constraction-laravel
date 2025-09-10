@php($settings = $settings ?? \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings])

@section('content')
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 -left-20 w-[40rem] h-[40rem] rounded-full blur-3xl opacity-30"
                 style="background: radial-gradient(circle at center, var(--brand), transparent 60%)"></div>
            <div class="absolute -top-40 -right-10 w-[30rem] h-[30rem] rounded-full blur-3xl opacity-30"
                 style="background: radial-gradient(circle at center, var(--brand-2), transparent 60%)"></div>
        </div>
        <div class="mx-auto max-w-7xl px-4 pt-16 pb-12">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                <div data-aos="fade-right">
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight neon">
                        {{ $settings->headline ?? 'Building the future with precision and care.' }}
                    </h1>
                    <p class="mt-4 text-slate-300 text-lg max-w-prose">
                        {{ $settings->subheadline ?? 'From preconstruction to delivery, we provide end‑to‑end construction services across markets.' }}
                    </p>
                    <div class="mt-8 flex items-center gap-4">
                        <a href="/projects" class="px-6 py-3 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">Explore Projects</a>
                        <a href="/services" class="px-6 py-3 rounded-lg border border-white/20 hover:border-white/40 transition">Our Services</a>
                    </div>
                    <dl class="mt-10 grid grid-cols-3 gap-6 text-center">
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10" data-aos="zoom-in">
                            <dt class="text-sm text-slate-400">Years</dt>
                            <dd class="mt-1 text-3xl font-bold">{{ $settings->stat_years ?? '25+' }}</dd>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10" data-aos="zoom-in" data-aos-delay="100">
                            <dt class="text-sm text-slate-400">Projects</dt>
                            <dd class="mt-1 text-3xl font-bold">{{ $settings->stat_projects ?? '500+' }}</dd>
                        </div>
                        <a href="/safety" class="block p-4 rounded-xl bg-white/5 border border-white/10 hover:border-white/30 transition group" data-aos="zoom-in" data-aos-delay="200">
                            <dt class="text-sm text-slate-400 group-hover:text-emerald-300 transition">Safety EMR</dt>
                            <dd class="mt-1 text-3xl font-bold">{{ $settings->stat_emr ?? '0.62' }}</dd>
                        </a>
                    </dl>
                </div>
                <div class="relative" data-aos="fade-left">
                    <div class="aspect-video rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                        @if($settings?->hero_video_url)
                            <video src="{{ $settings->hero_video_url }}" class="w-full h-full object-cover" autoplay muted loop playsinline></video>
                        @elseif($settings?->hero_image_url)
                            @php($hf = $settings->hero_image_fallback_url ?? $settings->hero_image_url)
                            @php($hs = $settings->hero_image_srcset_webp ?? $settings->hero_image_srcset)
                            <img loading="lazy" src="{{ $hf }}" @if($hs) srcset="{{ $hs }}" sizes="(min-width:768px) 50vw, 100vw" @endif alt="Hero Image" class="w-full h-full object-cover" />
                        @else
                            <img src="https://images.unsplash.com/photo-1581091870686-8e2980a57f5b?q=80&w=1600&auto=format&fit=crop" alt="Construction" class="w-full h-full object-cover" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php($badges = \App\Models\Badge::query()->where('is_active', true)->orderBy('order')->get())
    @if($settings?->show_badges_section && $badges->count())
    <section class="mx-auto max-w-7xl px-4 py-8">
        <h3 class="text-xl font-semibold mb-4">{{ $settings->badges_section_heading ?? 'Certifications & Affiliations' }}</h3>
        <div class="flex flex-wrap items-center gap-6 opacity-80 hover:opacity-100 transition">
            @foreach($badges as $b)
                @php($src = $b->image_url)
                @if(!$src) @continue @endif
                @if($b->url)
                    <a href="{{ $b->url }}" target="_blank" rel="noopener" class="block">
                        <img loading="lazy" src="{{ $src }}" alt="{{ $b->name }}" class="badge-logo" />
                    </a>
                @else
                    <img loading="lazy" src="{{ $src }}" alt="{{ $b->name }}" class="badge-logo" />
                @endif
            @endforeach
        </div>
    </section>
    @endif

    @if($settings?->show_services_section && $services->count())
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="flex items-end justify-between gap-6 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">{{ $settings->services_section_heading ?? 'Services' }}</h2>
            <a href="/services" class="text-emerald-300 hover:text-emerald-200">View all</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold">{{ $service->name }}</h3>
                        <span aria-hidden="true" class="text-slate-400 group-hover:translate-x-1 transition">→</span>
                    </div>
                    <p class="mt-2 text-slate-400">{{ $service->excerpt }}</p>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if($settings?->show_projects_section && $projects->count())
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="flex items-end justify-between gap-6 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">{{ $settings->projects_section_heading ?? 'Featured Projects' }}</h2>
            <a href="/projects" class="text-emerald-300 hover:text-emerald-200">View all</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <a href="{{ route('projects.show', $project->slug) }}" class="group rounded-2xl overflow-hidden border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                    <div class="aspect-video overflow-hidden">
                        @php($pf = $project->featured_image_fallback_url ?? $project->featured_image_url)
                        @php($ps = $project->featured_image_srcset_webp ?? $project->featured_image_srcset)
                        <img loading="lazy" src="{{ $pf ?: ($settings?->logo_url ?: '') }}" @if($ps) srcset="{{ $ps }}" sizes="(min-width:1024px) 33vw, 100vw" @endif class="w-full h-full object-cover group-hover:scale-[1.03] transition" />
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold">{{ $project->title }}</h3>
                        <p class="text-slate-400 text-sm">{{ $project->location }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if($settings?->show_testimonials_section && $testimonials->count())
    <section class="mx-auto max-w-7xl px-4 py-16">
        <h2 class="text-2xl md:text-3xl font-bold mb-8">{{ $settings->testimonials_section_heading ?? 'What clients say' }}</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($testimonials as $t)
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 flex flex-col gap-4" data-aos="zoom-in">
                    <p class="text-slate-300 flex-1">“{{ $t->content }}”</p>
                    <div class="flex items-center gap-3">
                        @if($t->avatar_image_url)
                            <img src="{{ $t->avatar_image_url }}" alt="{{ $t->author_name }}" class="h-10 w-10 rounded-full object-cover" loading="lazy" decoding="async" />
                        @endif
                        <div class="text-sm text-slate-300">
                            <div class="font-medium">{{ $t->author_name }}</div>
                            <div class="text-slate-400">@if($t->author_title){{ $t->author_title }}@endif @if($t->company) • {{ $t->company }}@endif</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if($settings?->show_clients_section && $clients->count())
    {{-- Clients marquee --}}
    <section class="mx-auto max-w-7xl px-4 py-8">
        <h3 class="text-xl font-semibold mb-4">{{ $settings->clients_section_heading ?? 'Our Clients' }}</h3>
        <div class="marquee rounded-2xl border border-white/10 bg-white/5 p-5" data-aos="fade-up">
            <div class="marquee-track">
                @foreach(array_merge($clients->all(), $clients->all()) as $client)
                    @if($client->website_url)
                        <a href="{{ $client->website_url }}" target="_blank" rel="noopener" class="block">
                            <img loading="lazy" src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="h-8 opacity-70 hover:opacity-100 transition" />
                        </a>
                    @else
                        <img loading="lazy" src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="h-8 opacity-70 hover:opacity-100 transition" />
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($settings?->show_news_section && $posts->count())
    {{-- Latest news / insights --}}
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="flex items-end justify-between gap-6 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">{{ $settings->news_section_heading ?? 'Latest News' }}</h2>
            <a href="{{ route('news.index') }}" class="text-emerald-300 hover:text-emerald-200">All posts</a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach(($posts ?? collect()) as $post)
                <a href="{{ route('news.show', $post->slug) }}" class="group rounded-2xl overflow-hidden border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                    <div class="aspect-[16/10] overflow-hidden bg-white/5">
                        @php($imgUrl = $post->featured_image_url ?: ($settings?->logo_url ?: 'https://images.unsplash.com/photo-1581091870686-8e2980a57f5b?q=80&w=1600&auto=format&fit=crop'))
                        <img loading="lazy" decoding="async" fetchpriority="low" src="{{ $imgUrl }}" @if($post->featured_image_srcset) srcset="{{ $post->featured_image_srcset }}" sizes="(min-width:1024px) 33vw, (min-width:640px) 50vw, 100vw" @endif class="w-full h-full object-cover group-hover:scale-[1.03] transition" />
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold">{{ $post->title }}</h3>
                        <p class="text-slate-400 text-sm mt-1">{{ $post->excerpt }}</p>
                        @if($post->published_at)
                            <p class="text-slate-500 text-xs mt-3">{{ $post->published_at->format('M d, Y') }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Trade Partners CTA --}}
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">Looking for Quality Trade Partners?</h2>
            <p class="text-slate-300 mb-8 max-w-2xl mx-auto">We maintain strong relationships with qualified subcontractors and trade partners who share our commitment to safety and excellence.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/partners" class="px-6 py-3 rounded-lg bg-white/5 border border-white/10 text-slate-200 hover:border-white/30 hover:bg-white/10 transition">
                    View Our Partners
                </a>
                <a href="/partners/prequal" class="px-6 py-3 rounded-lg bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/20 transition">
                    Apply for Prequalification
                </a>
            </div>
        </div>
    </section>

    {{-- CTA banner --}}
    <section class="mx-auto max-w-7xl px-4 pb-20">
        <div class="shine-border rounded-2xl p-0.5" data-aos="zoom-in">
            <div class="rounded-2xl bg-gradient-to-br from-emerald-500/10 to-sky-500/10 px-6 py-10 md:px-10 md:py-12 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold">{{ $settings->cta_heading ?? 'Ready to build something great?' }}</h3>
                    <p class="text-slate-300 mt-2">{{ $settings->cta_text ?? 'Let’s discuss your project and how we can help.' }}</p>
                </div>
                <a href="{{ $settings->cta_button_url ?? '/contact' }}" class="px-6 py-3 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">{{ $settings->cta_button_text ?? 'Get in touch' }}</a>
            </div>
        </div>
    </section>
@endsection
