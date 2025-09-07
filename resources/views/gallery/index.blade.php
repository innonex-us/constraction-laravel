@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => 'Gallery'])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold">Gallery</h1>
            <p class="text-slate-400 mt-1">Explore moments from our jobsites, milestones, and project details.</p>
        </div>
        <form method="get" class="w-full md:w-auto">
            <div class="flex gap-2">
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search photos..." class="w-full md:w-72 rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
                @if(!empty($category))
                    <input type="hidden" name="category" value="{{ $category }}" />
                @endif
                <button class="px-4 py-2 rounded-lg bg-emerald-500 text-slate-900 font-semibold">Search</button>
                <a href="{{ route('gallery.index') }}" class="px-4 py-2 rounded-lg border border-white/10">Reset</a>
            </div>
        </form>
    </div>

    @if(($categories ?? collect())->count())
    <div class="overflow-x-auto -mx-1 mb-6">
        <div class="px-1 flex items-center gap-2 text-sm">
            <a href="{{ route('gallery.index', array_filter(['q' => $q ?? null])) }}" class="px-3 py-1.5 rounded-full border {{ empty($category) ? 'border-emerald-400 text-emerald-300' : 'border-white/10 text-slate-300 hover:border-white/30' }}">All</a>
            @foreach($categories as $c)
                <a href="{{ route('gallery.index', array_filter(['category' => $c, 'q' => $q ?? null])) }}" class="px-3 py-1.5 rounded-full border {{ ($category ?? '') === $c ? 'border-emerald-400 text-emerald-300' : 'border-white/10 text-slate-300 hover:border-white/30' }}">{{ $c }}</a>
            @endforeach
        </div>
    </div>
    @endif

    <div id="gallery-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($items as $item)
            <a href="#" class="group relative overflow-hidden rounded-xl border border-white/10 hover:border-white/30 transition" data-lightbox-src="{{ $item->image_fallback_url }}" data-lightbox-caption="{{ $item->title }}">
                <div class="aspect-[4/3]">
                    <img loading="lazy" decoding="async" fetchpriority="low"
                         src="data:image/gif;base64,R0lGODlhAQABAAAAACw="
                         data-src="{{ $item->image_fallback_url }}"
                         @php($srcsetWebp = $item->image_srcset_webp)
                         @php($srcsetJpg = $item->image_srcset)
                         @if($srcsetWebp || $srcsetJpg)
                             data-srcset="{{ $srcsetWebp ?: $srcsetJpg }}"
                             sizes="(min-width:1024px) 33vw, (min-width:640px) 50vw, 100vw"
                         @endif
                         alt="{{ $item->title }}"
                         class="w-full h-full object-cover group-hover:opacity-95 transition" />
                </div>
                <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/60 to-transparent">
                    <div class="text-sm font-medium">{{ $item->title }}</div>
                    @if($item->category)
                        <div class="text-xs text-slate-300">{{ $item->category }}</div>
                    @endif
                </div>
            </a>
        @empty
            <p class="text-slate-400">No images yet.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $items->links() }}</div>
</section>

<div id="lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 p-4">
    <button class="absolute top-4 right-4 text-slate-200 text-2xl" data-lightbox-close aria-label="Close">×</button>
    <figure class="max-w-5xl w-full">
        <img id="lightbox-img" src="" alt="" class="w-full h-auto rounded-lg shadow-2xl" />
        <figcaption id="lightbox-cap" class="mt-3 text-center text-slate-300"></figcaption>
    </figure>
    <button class="absolute left-4 top-1/2 -translate-y-1/2 text-3xl text-white/80" data-lightbox-prev aria-label="Previous">‹</button>
    <button class="absolute right-4 top-1/2 -translate-y-1/2 text-3xl text-white/80" data-lightbox-next aria-label="Next">›</button>
  </div>

@push('scripts')
<script>
function initGalleryLightbox() {
  const lightbox = document.getElementById('lightbox');
  if (!lightbox || lightbox.dataset.bound) return; // prevent duplicate binding
  const img = document.getElementById('lightbox-img');
  const cap = document.getElementById('lightbox-cap');
  const items = Array.from(document.querySelectorAll('#gallery-grid [data-lightbox-src]'))
                     .map(el => ({ el, src: el.dataset.lightboxSrc, caption: el.dataset.lightboxCaption || '' }));
  let index = 0;

  const open = (i) => {
    index = i; const it = items[index];
    img.src = it.src; cap.textContent = it.caption;
    lightbox.classList.remove('hidden'); lightbox.classList.add('flex');
  };
  const close = () => { lightbox.classList.add('hidden'); lightbox.classList.remove('flex'); };
  const next = () => open((index + 1) % items.length);
  const prev = () => open((index - 1 + items.length) % items.length);

  items.forEach((it, i) => it.el.addEventListener('click', (e) => { e.preventDefault(); open(i); }));
  document.querySelector('[data-lightbox-close]')?.addEventListener('click', close);
  document.querySelector('[data-lightbox-next]')?.addEventListener('click', next);
  document.querySelector('[data-lightbox-prev]')?.addEventListener('click', prev);
  lightbox.addEventListener('click', (e) => { if (e.target === lightbox) close(); });
  document.addEventListener('keydown', (e) => { if (lightbox.classList.contains('hidden')) return; if (e.key === 'Escape') close(); if (e.key === 'ArrowRight') next(); if (e.key === 'ArrowLeft') prev(); });

  lightbox.dataset.bound = '1';
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initGalleryLightbox);
} else {
  initGalleryLightbox();
}
window.addEventListener('turbo:load', initGalleryLightbox);
</script>
@endpush
