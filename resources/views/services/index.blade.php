@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-bold mb-8">Our Services</h1>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $service)
            <a href="{{ route('services.show', $service->slug) }}" class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                @if($service->image_url)
                    <div class="aspect-video overflow-hidden rounded-xl mb-4">
                        @php($sf = $service->image_fallback_url ?? $service->image_url)
                        @php($ss = $service->image_srcset_webp ?? $service->image_srcset)
                        <img loading="lazy" decoding="async" fetchpriority="low"
                             src="{{ $sf }}" @if($ss) srcset="{{ $ss }}" sizes="(min-width:1024px) 33vw, 100vw" @endif
                             alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition" />
                    </div>
                @endif
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
    <div class="mt-8">{{ $services->links() }}</div>
  </section>
@endsection
