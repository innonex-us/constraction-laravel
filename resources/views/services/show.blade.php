@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => $service->meta_title ?? $service->name, 'metaDescription' => $service->meta_description ?? $service->excerpt])

@section('content')
<section class="relative overflow-hidden blueprint">
    <div class="mx-auto max-w-7xl px-4 py-14">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <div>
                <a href="{{ route('services.index') }}" class="text-sm text-emerald-300">← All services</a>
                <h1 class="mt-2 text-4xl md:text-5xl font-extrabold leading-tight neon">{{ $service->name }}</h1>
                @if($service->excerpt)
                    <p class="mt-3 text-slate-300 text-lg max-w-prose">{{ $service->excerpt }}</p>
                @endif
                <div class="mt-6 flex items-center gap-3 text-sm text-slate-300">
                    <a href="/contact" class="px-4 py-2 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">Request a quote</a>
                    <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-lg border border-white/10 hover:border-white/30 transition">See projects</a>
                </div>
            </div>
            <div class="relative">
                @if($service->image_url)
                    <div class="aspect-video rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                        <img loading="lazy" decoding="async" fetchpriority="low" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" data-src="{{ $service->image_url }}" @if($service->image_srcset) data-srcset="{{ $service->image_srcset }}" sizes="(min-width:1024px) 50vw, 100vw" @endif alt="{{ $service->name }}" class="w-full h-full object-cover" />
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12">
    <div class="grid lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8">
            <article class="prose prose-invert service-content">
                {!! $service->content !!}
            </article>
            
            {{-- Gallery Section --}}
            @if($service->gallery && count($service->gallery) > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">Gallery</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($service->gallery_images as $index => $image)
                        <div class="group cursor-pointer" onclick="openLightbox({{ $index }})">
                            <div class="aspect-square rounded-lg overflow-hidden border border-white/10 hover:border-white/30 transition">
                                <picture>
                                    @if($image['srcset_webp'])
                                        <source srcset="{{ $image['srcset_webp'] }}" type="image/webp">
                                    @endif
                                    <img 
                                        loading="lazy" 
                                        decoding="async"
                                        src="{{ $image['fallback_url'] }}" 
                                        @if($image['srcset_jpg']) 
                                            srcset="{{ $image['srcset_jpg'] }}" 
                                            sizes="(min-width:1024px) 33vw, (min-width:640px) 50vw, 100vw"
                                        @endif
                                        alt="Gallery image {{ $index + 1 }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                </picture>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <aside class="lg:col-span-4 space-y-6">
            <div class="rounded-xl bg-white/5 border border-white/10 p-5">
                <div class="text-sm uppercase tracking-wider text-slate-400">Ready to start?</div>
                <p class="text-slate-300 mt-2 text-sm">Let’s turn your vision into reality.</p>
                <a href="/contact" class="mt-4 inline-block px-4 py-2 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">Contact us</a>
            </div>
            @if(isset($related) && $related->count())
            <div class="rounded-xl bg-white/5 border border-white/10 p-5">
                <div class="text-sm uppercase tracking-wider text-slate-400">Related services</div>
                <div class="mt-3 grid gap-3">
                    @foreach($related as $s)
                        <a href="{{ route('services.show', $s->slug) }}" class="block p-3 rounded-lg bg-white/5 border border-white/10 hover:border-white/30">
                            <div class="font-medium">{{ $s->name }}</div>
                            <div class="text-slate-400 text-xs line-clamp-2">{{ $s->excerpt }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>
    </div>
</section>

@if(isset($projects) && $projects->count())
<section class="mx-auto max-w-7xl px-4 pb-16">
    <div class="flex items-end justify-between gap-6 mb-6">
        <h2 class="text-2xl md:text-3xl font-bold">Featured Projects</h2>
        <a href="{{ route('projects.index') }}" class="text-emerald-300 hover:text-emerald-200">View all</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($projects as $project)
            <a href="{{ route('projects.show', $project->slug) }}" class="group rounded-2xl overflow-hidden border border-white/10 hover:border-white/30 transition">
                <div class="aspect-video overflow-hidden">
                    <img loading="lazy" decoding="async" fetchpriority="low" src="{{ $project->featured_image_url ?: ($settings?->logo_url ?: '') }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition" />
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

{{-- Lightbox Modal --}}
@if($service->gallery && count($service->gallery) > 0)
<div id="lightbox" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl w-full">
            <!-- Close button -->
            <button onclick="closeLightbox()" class="absolute -top-12 right-0 text-white/70 hover:text-white text-2xl z-10">
                ✕
            </button>
            
            <!-- Navigation arrows -->
            <button onclick="previousImage()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-3xl z-10">
                ‹
            </button>
            <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-3xl z-10">
                ›
            </button>
            
            <!-- Image container -->
            <div class="bg-white/5 rounded-lg overflow-hidden border border-white/20">
                <img id="lightbox-image" src="" alt="" class="w-full h-auto max-h-[80vh] object-contain">
            </div>
            
            <!-- Image counter -->
            <div class="text-center mt-4 text-white/70">
                <span id="image-counter"></span>
            </div>
        </div>
    </div>
</div>

<script>
let galleryImages = @json($service->gallery_images);
let currentImageIndex = 0;

function openLightbox(index) {
    currentImageIndex = index;
    updateLightboxImage();
    document.getElementById('lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.style.overflow = '';
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
    updateLightboxImage();
}

function previousImage() {
    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
    updateLightboxImage();
}

function updateLightboxImage() {
    const image = galleryImages[currentImageIndex];
    const lightboxImg = document.getElementById('lightbox-image');
    lightboxImg.src = image.fallback_url;
    lightboxImg.alt = `Gallery image ${currentImageIndex + 1}`;
    
    document.getElementById('image-counter').textContent = 
        `${currentImageIndex + 1} of ${galleryImages.length}`;
}

// Close lightbox on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') previousImage();
});

// Close lightbox when clicking outside image
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});
</script>
@endif
@endsection
