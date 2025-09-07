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
                        From preconstruction to delivery, we provide end‑to‑end construction services across markets.
                    </p>
                    <div class="mt-8 flex items-center gap-4">
                        <a href="/projects" class="px-6 py-3 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">Explore Projects</a>
                        <a href="/services" class="px-6 py-3 rounded-lg border border-white/20 hover:border-white/40 transition">Our Services</a>
                    </div>
                    <dl class="mt-10 grid grid-cols-3 gap-6 text-center">
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10" data-aos="zoom-in">
                            <dt class="text-sm text-slate-400">Years</dt>
                            <dd class="mt-1 text-3xl font-bold">25+</dd>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10" data-aos="zoom-in" data-aos-delay="100">
                            <dt class="text-sm text-slate-400">Projects</dt>
                            <dd class="mt-1 text-3xl font-bold">500+</dd>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10" data-aos="zoom-in" data-aos-delay="200">
                            <dt class="text-sm text-slate-400">Safety EMR</dt>
                            <dd class="mt-1 text-3xl font-bold">0.62</dd>
                        </div>
                    </dl>
                </div>
                <div class="relative" data-aos="fade-left">
                    <div class="aspect-video rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                        @if($settings?->hero_video_url)
                            <video src="{{ $settings->hero_video_url }}" class="w-full h-full object-cover" autoplay muted loop playsinline></video>
                        @else
                            <img src="https://images.unsplash.com/photo-1581091870686-8e2980a57f5b?q=80&w=1600&auto=format&fit=crop" alt="Construction" class="w-full h-full object-cover" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php($badges = \App\Models\Badge::query()->where('is_active', true)->orderBy('order')->get())
    @if($badges->count())
    <section class="mx-auto max-w-7xl px-4 py-8">
        <h3 class="text-xl font-semibold mb-4">Certifications & Affiliations</h3>
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

    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="flex items-end justify-between gap-6 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">Services</h2>
            <a href="/services" class="text-emerald-300 hover:text-emerald-200">View all</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold">{{ $service->name }}</h3>
                        <span aria-hidden="true" class="text-slate-400 group-hover:translate-x-1 transition">→</span>
                    </div>
                    <p class="mt-2 text-slate-400">{{ $service->excerpt }}</p>
                </a>
            @empty
                <p class="text-slate-400">No services yet.</p>
            @endforelse
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="flex items-end justify-between gap-6 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">Featured Projects</h2>
            <a href="/projects" class="text-emerald-300 hover:text-emerald-200">View all</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
                <a href="{{ route('projects.show', $project->slug) }}" class="group rounded-2xl overflow-hidden border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                    <div class="aspect-video overflow-hidden">
                        @php($pf = $project->featured_image_fallback_url ?? $project->featured_image_url)
                        @php($ps = $project->featured_image_srcset_webp ?? $project->featured_image_srcset)
                        <img loading="lazy" src="{{ $pf ?: 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?q=80&w=1600&auto=format&fit=crop' }}" @if($ps) srcset="{{ $ps }}" sizes="(min-width:1024px) 33vw, 100vw" @endif class="w-full h-full object-cover group-hover:scale-[1.03] transition" />
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold">{{ $project->title }}</h3>
                        <p class="text-slate-400 text-sm">{{ $project->location }}</p>
                    </div>
                </a>
            @empty
                <p class="text-slate-400">No projects yet.</p>
            @endforelse
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16">
        <h2 class="text-2xl md:text-3xl font-bold mb-8">What clients say</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @forelse($testimonials as $t)
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10" data-aos="zoom-in">
                    <p class="text-slate-300">“{{ $t->content }}”</p>
                    <div class="mt-4 text-sm text-slate-400">— {{ $t->author_name }} @if($t->company) • {{ $t->company }} @endif</div>
                </div>
            @empty
                <p class="text-slate-400">No testimonials yet.</p>
            @endforelse
        </div>
    </section>

    {{-- Clients marquee --}}
    <section class="mx-auto max-w-7xl px-4 py-8">
        <div class="marquee rounded-2xl border border-white/10 bg-white/5 p-5" data-aos="fade-up">
            <div class="marquee-track">
                @php($logos = [
                    'https://dummyimage.com/140x48/0b1220/94a3b8&text=HealthCo',
                    'https://dummyimage.com/140x48/0b1220/94a3b8&text=TechCorp',
                    'https://dummyimage.com/140x48/0b1220/94a3b8&text=City+Works',
                    'https://dummyimage.com/140x48/0b1220/94a3b8&text=Port+Authority',
                    'https://dummyimage.com/140x48/0b1220/94a3b8&text=EduTrust',
                    'https://dummyimage.com/140x48/0b1220/94a3b8&text=BioLabs',
                ])
                @foreach(array_merge($logos, $logos) as $logo)
                    <img loading="lazy" src="{{ $logo }}" alt="Client" class="h-8 opacity-70 hover:opacity-100 transition" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Latest news / insights --}}
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="flex items-end justify-between gap-6 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">Latest News</h2>
            <a href="{{ route('news.index') }}" class="text-emerald-300 hover:text-emerald-200">All posts</a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @forelse(($posts ?? collect()) as $post)
                <a href="{{ route('news.show', $post->slug) }}" class="group rounded-2xl overflow-hidden border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                    <div class="aspect-[16/10] overflow-hidden bg-white/5">
                        <img loading="lazy" src="{{ $post->featured_image ?: 'https://images.unsplash.com/photo-1581091870686-8e2980a57f5b?q=80&w=1600&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition" />
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold">{{ $post->title }}</h3>
                        <p class="text-slate-400 text-sm mt-1">{{ $post->excerpt }}</p>
                        @if($post->published_at)
                            <p class="text-slate-500 text-xs mt-3">{{ $post->published_at->format('M d, Y') }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-slate-400">No posts yet.</p>
            @endforelse
        </div>
    </section>

    {{-- CTA banner --}}
    <section class="mx-auto max-w-7xl px-4 pb-20">
        <div class="shine-border rounded-2xl p-0.5" data-aos="zoom-in">
            <div class="rounded-2xl bg-gradient-to-br from-emerald-500/10 to-sky-500/10 px-6 py-10 md:px-10 md:py-12 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold">Ready to build something great?</h3>
                    <p class="text-slate-300 mt-2">Let’s discuss your project and how we can help.</p>
                </div>
                <a href="/contact" class="px-6 py-3 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">Get in touch</a>
            </div>
        </div>
    </section>
@endsection
