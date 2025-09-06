<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? ($settings->site_name ?? 'Construction Co.') }}</title>
    <meta name="description" content="{{ $metaDescription ?? ($settings->headline ?? 'We build with excellence.') }}" />
    <meta property="og:title" content="{{ $title ?? ($settings->site_name ?? 'Construction Co.') }}" />
    <meta property="og:description" content="{{ $metaDescription ?? ($settings->headline ?? '') }}" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="theme-color" content="{{ $settings->primary_color ?? '#10b981' }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <style>
        :root{
            --brand: {{ $settings->primary_color ?? '#10b981' }};
            --brand-2: {{ $settings->secondary_color ?? '#0ea5e9' }};
        }
        body{ font-family: 'Outfit', system-ui, sans-serif; }
        .glass{ backdrop-filter: blur(10px); background: color-mix(in oklab, white 8%, transparent); }
        .neon{ text-shadow: 0 0 12px color-mix(in oklab, var(--brand) 50%, transparent); }
        .gradient{ background-image: radial-gradient(1200px 400px at 10% -10%, color-mix(in oklab, var(--brand) 35%, transparent), transparent),
            radial-gradient(900px 300px at 90% -10%, color-mix(in oklab, var(--brand-2) 35%, transparent), transparent);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/instant.page@5.2.0/instantpage.min.js" type="module" defer></script>
</head>
<body class="min-h-dvh bg-slate-950 text-slate-100 gradient">
<header class="sticky top-0 z-50">
    <div class="glass border-b border-white/5">
        <div class="mx-auto max-w-7xl px-4 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                @if(!empty($settings?->logo_path))
                    <img src="{{ asset($settings->logo_path) }}" alt="Logo" class="h-8 w-auto" />
                @endif
                <span class="font-semibold tracking-wide">{{ $settings->site_name ?? 'Construction Co.' }}</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="/services" class="hover:text-emerald-300 transition">Services</a>
                <a href="/projects" class="hover:text-emerald-300 transition">Projects</a>
                <a href="/gallery" class="hover:text-emerald-300 transition">Gallery</a>
                <a href="/news" class="hover:text-emerald-300 transition">News</a>
                <a href="/page/about" class="hover:text-emerald-300 transition">About</a>
                <a href="/contact" class="hover:text-emerald-300 transition">Contact</a>
                <a href="/admin" class="ml-2 px-3 py-1.5 rounded-md bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/20">Admin</a>
            </nav>
            <button id="menu-btn" class="md:hidden inline-flex items-center justify-center rounded-md border border-white/10 px-3 py-2" aria-expanded="false" aria-controls="mobile-nav">
                <span class="sr-only">Open menu</span>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="text-slate-200"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
            </button>
        </div>
    </div>
    <div id="mobile-nav" class="md:hidden hidden bg-black/60 border-b border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-3 grid gap-3 text-sm">
            <a href="/services" class="hover:text-emerald-300 transition">Services</a>
            <a href="/projects" class="hover:text-emerald-300 transition">Projects</a>
            <a href="/gallery" class="hover:text-emerald-300 transition">Gallery</a>
            <a href="/news" class="hover:text-emerald-300 transition">News</a>
            <a href="/page/about" class="hover:text-emerald-300 transition">About</a>
            <a href="/contact" class="hover:text-emerald-300 transition">Contact</a>
            <a href="/admin" class="px-3 py-1.5 rounded-md bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/20 w-max">Admin</a>
        </div>
    </div>
</header>

<main>
    {{ $slot ?? '' }}
    @yield('content')
</main>

<footer class="mt-20 border-t border-white/5 bg-black/50">
    <div class="hazard-tape"></div>
    <div class="mx-auto max-w-7xl px-4 py-12">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <div class="flex items-center gap-2">
                    @if(!empty($settings?->logo_path))
                        <img src="{{ asset($settings->logo_path) }}" alt="Logo" class="h-8 w-auto" />
                    @endif
                    <div class="text-xl font-semibold">{{ $settings->site_name ?? 'Construction Co.' }}</div>
                </div>
                @if(!empty($settings?->headline))
                    <p class="text-slate-400 mt-3">{{ $settings->headline }}</p>
                @endif
                <div class="mt-5 text-sm space-y-2 text-slate-300">
                    @if(!empty($settings?->phone))
                        <div>Phone: <a class="text-emerald-300 hover:text-emerald-200" href="tel:{{ preg_replace('/\D+/', '', $settings->phone) }}">{{ $settings->phone }}</a></div>
                    @endif
                    @if(!empty($settings?->email))
                        <div>Email: <a class="text-emerald-300 hover:text-emerald-200" href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></div>
                    @endif
                    @if(!empty($settings?->address))
                        <div class="text-slate-400">{{ $settings->address }}</div>
                    @endif
                </div>
                <div class="mt-5">
                    <a href="/contact" class="inline-block px-4 py-2 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">Get a quote</a>
                </div>
            </div>

            <div>
                <div class="text-sm uppercase tracking-wider text-slate-400">Services</div>
                @php($footerServices = \App\Models\Service::query()->where('is_active', true)->orderBy('order')->take(6)->get())
                <ul class="mt-3 space-y-2 text-slate-300">
                    @forelse($footerServices as $srv)
                        <li><a class="hover:text-emerald-300" href="{{ route('services.show', $srv->slug) }}">{{ $srv->name }}</a></li>
                    @empty
                        <li class="text-slate-500">Add services in Admin → Services</li>
                    @endforelse
                </ul>
            </div>

            <div>
                <div class="text-sm uppercase tracking-wider text-slate-400">Company</div>
                <ul class="mt-3 space-y-2 text-slate-300">
                    <li><a class="hover:text-emerald-300" href="/page/about">About</a></li>
                    <li><a class="hover:text-emerald-300" href="{{ route('safety.index') }}">Safety</a></li>
                    <li><a class="hover:text-emerald-300" href="{{ route('projects.index') }}">Projects</a></li>
                    <li><a class="hover:text-emerald-300" href="{{ route('gallery.index') }}">Gallery</a></li>
                    <li><a class="hover:text-emerald-300" href="{{ route('news.index') }}">News</a></li>
                    <li><a class="hover:text-emerald-300" href="/contact">Contact</a></li>
                </ul>
            </div>

            <div>
                <div class="text-sm uppercase tracking-wider text-slate-400">Resources</div>
                <ul class="mt-3 space-y-2 text-slate-300">
                    <li><a class="hover:text-emerald-300" href="{{ route('projects.map') }}">Projects Map</a></li>
                    <li><a class="hover:text-emerald-300" href="{{ route('partners.prequal') }}">Trade Partner Prequal</a></li>
                    <li><a class="hover:text-emerald-300" href="/admin">Admin</a></li>
                </ul>
                @php($links = (array)($settings->social_links ?? []))
                @if(!empty($links))
                <div class="mt-4 flex items-center gap-3">
                    @if(!empty($links['linkedin']))
                        <a href="{{ $links['linkedin'] }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-emerald-300" aria-label="LinkedIn">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM0 8h5v16H0V8zm7.5 0h4.8v2.2h.07c.67-1.2 2.3-2.47 4.73-2.47 5.06 0 6 3.33 6 7.66V24h-5v-7.6c0-1.8-.03-4.12-2.5-4.12-2.5 0-2.88 1.95-2.88 3.98V24h-5V8z"/></svg>
                        </a>
                    @endif
                    @if(!empty($links['facebook']))
                        <a href="{{ $links['facebook'] }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-emerald-300" aria-label="Facebook">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.16 1.8.16v2h-1c-1 0-1.4.63-1.4 1.3V12h2.4l-.4 3h-2v7A10 10 0 0 0 22 12"/></svg>
                        </a>
                    @endif
                    @if(!empty($links['instagram']))
                        <a href="{{ $links['instagram'] }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-emerald-300" aria-label="Instagram">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 1.2.06 1.9.26 2.4.43.6.23 1 .5 1.5 1 .5.5.8.9 1 1.5.17.5.37 1.2.43 2.4.07 1.3.07 1.7.07 4.9s0 3.6-.07 4.9c-.06 1.2-.26 1.9-.43 2.4-.23.6-.5 1-1 1.5-.5.5-.9.8-1.5 1-.5.17-1.2.37-2.4.43-1.3.07-1.7.07-4.9.07s-3.6 0-4.9-.07c-1.2-.06-1.9-.26-2.4-.43-.6-.23-1-.5-1.5-1-.5-.5-.8-.9-1-1.5-.17-.5-.37-1.2-.43-2.4C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.9c.06-1.2.26-1.9.43-2.4.23-.6.5-1 1-1.5.5-.5.9-.8 1.5-1 .5-.17 1.2-.37 2.4-.43C8.4 2.2 8.8 2.2 12 2.2m0 1.8c-3.1 0-3.5 0-4.7.07-1 .05-1.6.22-2 .36-.5.2-.8.4-1.2.8-.3.3-.6.7-.8 1.2-.14.3-.31 1-.36 2-.07 1.2-.07 1.6-.07 4.7s0 3.5.07 4.7c.05 1 .22 1.6.36 2 .2.5.4.8.8 1.2.3.3.7.6 1.2.8.3.14 1 .31 2 .36 1.2.07 1.6.07 4.7.07s3.5 0 4.7-.07c1-.05 1.6-.22 2-.36.5-.2.8-.4 1.2-.8.3-.3.6-.7.8-1.2.14-.3.31-1 .36-2 .07-1.2.07-1.6.07-4.7s0-3.5-.07-4.7c-.05-1-.22-1.6-.36-2-.2-.5-.4-.8-.8-1.2-.3-.3-.7-.6-1.2-.8-.3-.14-1-.31-2-.36-1.2-.07-1.6-.07-4.7-.07m0 2.6a5.4 5.4 0 1 1 0 10.8 5.4 5.4 0 0 1 0-10.8m0 1.8a3.6 3.6 0 1 0 0 7.2 3.6 3.6 0 0 0 0-7.2m5.5-2.3a1.3 1.3 0 1 1 0 2.6 1.3 1.3 0 0 1 0-2.6"/></svg>
                        </a>
                    @endif
                    @if(!empty($links['x']) || !empty($links['twitter']))
                        @php($tw = $links['x'] ?? $links['twitter'])
                        <a href="{{ $tw }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-emerald-300" aria-label="Twitter">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M18.2 2H21l-6.6 7.6L22 22h-6.8l-4.6-5.9L5 22H2.2l7.1-8.1L2 2h6.9l4.1 5.4L18.2 2Zm-1.2 18h1.8L7.1 4H5.2l11.8 16Z"/></svg>
                        </a>
                    @endif
                    @if(!empty($links['youtube']))
                        <a href="{{ $links['youtube'] }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-emerald-300" aria-label="YouTube">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.6 3.5 12 3.5 12 3.5s-7.6 0-9.4.6A3 3 0 0 0 .5 6.2 31.5 31.5 0 0 0 0 12a31.5 31.5 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.8.6 9.4.6 9.4.6s7.6 0 9.4-.6a3 3 0 0 0 2.1-2.1A31.5 31.5 0 0 0 24 12a31.5 31.5 0 0 0-.5-5.8ZM9.6 15.5v-7l6.3 3.5-6.3 3.5Z"/></svg>
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-5 text-sm text-slate-400 flex flex-col md:flex-row items-center justify-between gap-3">
            <p>&copy; {{ now()->year }} {{ $settings->site_name ?? 'Construction Co.' }}. All rights reserved.</p>
            <p>Built with Laravel + Filament • <a class="text-emerald-300 hover:text-emerald-200" href="{{ route('safety.index') }}">Safety</a> • <a class="text-emerald-300 hover:text-emerald-200" href="{{ route('partners.prequal') }}">Prequalify</a></p>
        </div>
    </div>
</footer>

<script>
    AOS.init({ duration: 800, once: true, easing: 'ease-out-cubic' });
    // Mobile nav toggle
    const menuBtn = document.getElementById('menu-btn');
    const mobileNav = document.getElementById('mobile-nav');
    if (menuBtn && mobileNav) {
        const open = () => { mobileNav.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); menuBtn.setAttribute('aria-expanded','true'); };
        const close = () => { mobileNav.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); menuBtn.setAttribute('aria-expanded','false'); };
        menuBtn.addEventListener('click', () => mobileNav.classList.contains('hidden') ? open() : close());
        mobileNav.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
        window.addEventListener('resize', () => { if (window.innerWidth >= 768) close(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
    }
    // Lightweight lazy loader: for images with data-src / data-srcset
    const lazyImgs = Array.from(document.querySelectorAll('img[data-src], img[data-srcset]'));
    if ('IntersectionObserver' in window && lazyImgs.length) {
        const io = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const img = entry.target;
                if (img.dataset.src) img.src = img.dataset.src;
                if (img.dataset.srcset) img.srcset = img.dataset.srcset;
                img.addEventListener('load', () => img.classList.add('loaded'), { once: true });
                io.unobserve(img);
            });
        }, { rootMargin: '200px' });
        lazyImgs.forEach(img => io.observe(img));
    }
</script>
@stack('scripts')

<a href="/contact" class="fixed bottom-5 right-5 z-50 rounded-full bg-emerald-500 text-slate-900 font-semibold shadow-lg hover:bg-emerald-400 transition px-5 py-3">
    Contact Us
</a>
</body>
</html>
