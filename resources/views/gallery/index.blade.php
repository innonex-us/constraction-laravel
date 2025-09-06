@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => 'Gallery'])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-bold mb-8">Gallery</h1>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
        @forelse($items as $item)
            <button class="group relative rounded-xl overflow-hidden border border-white/10 hover:border-white/30 transition" data-lightbox data-src="{{ $item->image }}" data-caption="{{ $item->title }}">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition" />
                </div>
                <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/60 to-transparent">
                    <div class="text-sm font-medium">{{ $item->title }}</div>
                    @if($item->category)
                        <div class="text-xs text-slate-300">{{ $item->category }}</div>
                    @endif
                </div>
            </button>
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
    <div id="lightbox-data" class="hidden">
        @foreach($items as $item)
            <div data-src="{{ $item->image }}" data-caption="{{ $item->title }}"></div>
        @endforeach
    </div>
  </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const lightbox = document.getElementById('lightbox');
  const img = document.getElementById('lightbox-img');
  const cap = document.getElementById('lightbox-cap');
  const data = Array.from(document.querySelectorAll('#lightbox-data [data-src]')).map(e => ({src: e.dataset.src, caption: e.dataset.caption}));
  let index = 0;

  const open = (i) => { index = i; const it = data[index]; img.src = it.src; cap.textContent = it.caption || ''; lightbox.classList.remove('hidden'); lightbox.classList.add('flex'); };
  const close = () => { lightbox.classList.add('hidden'); lightbox.classList.remove('flex'); };
  const next = () => open((index + 1) % data.length);
  const prev = () => open((index - 1 + data.length) % data.length);

  document.querySelectorAll('[data-lightbox]').forEach((btn, i) => btn.addEventListener('click', () => open(i)));
  document.querySelector('[data-lightbox-close]')?.addEventListener('click', close);
  document.querySelector('[data-lightbox-next]')?.addEventListener('click', next);
  document.querySelector('[data-lightbox-prev]')?.addEventListener('click', prev);
  lightbox.addEventListener('click', (e) => { if (e.target === lightbox) close(); });
  document.addEventListener('keydown', (e) => { if (lightbox.classList.contains('hidden')) return; if (e.key === 'Escape') close(); if (e.key === 'ArrowRight') next(); if (e.key === 'ArrowLeft') prev(); });
});
</script>
@endpush

