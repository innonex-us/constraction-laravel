@php($settings = $settings ?? \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings])

@section('content')
    {{-- Hero Slider --}}
    @if($heroSlides->count() > 0)
    <section class="relative overflow-hidden pt-16 pb-12">
        {{-- Background decoration --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 -left-20 w-[30rem] h-[30rem] rounded-full blur-3xl opacity-20"
                 style="background: radial-gradient(circle at center, var(--brand), transparent 60%)"></div>
            <div class="absolute -top-40 -right-10 w-[25rem] h-[25rem] rounded-full blur-3xl opacity-20"
                 style="background: radial-gradient(circle at center, var(--brand-2), transparent 60%)"></div>
        </div>
        <div class="mx-auto max-w-7xl px-4 relative z-10">
            <div id="hero-slider" class="relative h-[60vh] min-h-[400px] max-h-[600px] rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
            @foreach($heroSlides as $index => $slide)
            <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                {{-- Background Image/Video --}}
                <div class="absolute inset-0">
                    @if($slide->video_url)
                        <video src="{{ $slide->video_url }}" class="w-full h-full object-cover" autoplay muted loop playsinline></video>
                    @else
                        <picture>
                            @if($slide->image_srcset_webp)
                                <source srcset="{{ $slide->image_srcset_webp }}" type="image/webp">
                            @endif
                            <img 
                                src="{{ $slide->image_fallback_url ?? $slide->image_url }}" 
                                @if($slide->image_srcset) 
                                    srcset="{{ $slide->image_srcset }}" 
                                    sizes="100vw"
                                @endif
                                alt="{{ $slide->title }}"
                                class="w-full h-full object-cover"
                            />
                        </picture>
                    @endif
                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>
                
                {{-- Content --}}
                <div class="relative z-10 h-full flex items-center">
                    <div class="w-full px-8 md:px-12">
                        <div class="max-w-2xl">
                            @if($slide->subtitle)
                                <p class="text-emerald-300 uppercase tracking-wider text-sm font-semibold mb-4" data-aos="fade-up" data-aos-delay="100">
                                    {{ $slide->subtitle }}
                                </p>
                            @endif
                            
                            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-white mb-4" data-aos="fade-up" data-aos-delay="200">
                                {{ $slide->title }}
                            </h1>
                            
                            @if($slide->description)
                                <p class="text-lg md:text-xl text-slate-200 mb-6 max-w-xl leading-relaxed" data-aos="fade-up" data-aos-delay="300">
                                    {{ $slide->description }}
                                </p>
                            @endif
                            
                            @if($slide->button_text && $slide->button_url)
                                <div class="flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="400">
                                    <a href="{{ $slide->button_url }}" class="
                                        px-6 py-3 rounded-lg font-semibold text-base transition-all duration-300 transform hover:scale-105
                                        @if($slide->button_style === 'primary')
                                            bg-emerald-500 text-slate-900 hover:bg-emerald-400 shadow-lg hover:shadow-emerald-500/25
                                        @elseif($slide->button_style === 'secondary')
                                            bg-white text-slate-900 hover:bg-slate-100 shadow-lg
                                        @else
                                            border-2 border-white text-white hover:bg-white hover:text-slate-900
                                        @endif
                                    ">
                                        {{ $slide->button_text }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
            {{-- Navigation Arrows --}}
            @if($heroSlides->count() > 1)
            <button id="prev-slide" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-black/50 hover:bg-black/70 rounded-full flex items-center justify-center text-white transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button id="next-slide" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-black/50 hover:bg-black/70 rounded-full flex items-center justify-center text-white transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            
            {{-- Dots Indicator --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-2">
                @foreach($heroSlides as $index => $slide)
                <button class="slide-dot w-3 h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-emerald-500' : 'bg-white/50' }}" data-slide="{{ $index }}"></button>
                @endforeach
            </div>
            @endif
            </div>
        </div>
    </section>
    @else
    {{-- Fallback to original hero section if no slides --}}
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
    @endif

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

{{-- Hero Slider Styles and JavaScript --}}
@if($heroSlides->count() > 0)
<style>
.hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
    z-index: 1;
}

.hero-slide.active {
    opacity: 1;
    z-index: 2;
}

.slide-dot.active {
    background-color: rgb(16 185 129) !important;
    transform: scale(1.2);
}

#hero-slider {
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('hero-slider');
    if (!slider) return;
    
    const slides = slider.querySelectorAll('.hero-slide');
    const dots = slider.querySelectorAll('.slide-dot');
    const prevBtn = document.getElementById('prev-slide');
    const nextBtn = document.getElementById('next-slide');
    
    let currentSlide = 0;
    let autoPlayInterval;
    
    // Show slide function
    function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Show current slide
        if (slides[index]) {
            slides[index].classList.add('active');
            dots[index]?.classList.add('active');
            currentSlide = index;
        }
    }
    
    // Next slide
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }
    
    // Previous slide
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }
    
    // Auto play
    function startAutoPlay() {
        if (slides.length > 1) {
            autoPlayInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
        }
    }
    
    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }
    
    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            stopAutoPlay();
            startAutoPlay(); // Restart auto play
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            stopAutoPlay();
            startAutoPlay(); // Restart auto play
        });
    }
    
    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            stopAutoPlay();
            startAutoPlay(); // Restart auto play
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            stopAutoPlay();
            startAutoPlay();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            stopAutoPlay();
            startAutoPlay();
        }
    });
    
    // Pause on hover
    slider.addEventListener('mouseenter', stopAutoPlay);
    slider.addEventListener('mouseleave', startAutoPlay);
    
    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    slider.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        stopAutoPlay();
    });
    
    slider.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
        startAutoPlay();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                nextSlide(); // Swipe left - next slide
            } else {
                prevSlide(); // Swipe right - previous slide
            }
        }
    }
    
    // Initialize
    showSlide(0);
    startAutoPlay();
});
</script>
@endif
@endsection
