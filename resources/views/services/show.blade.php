@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => $service->meta_title ?? $service->name, 'metaDescription' => $service->meta_description ?? $service->excerpt])

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12">
    <a href="{{ route('services.index') }}" class="text-sm text-emerald-300">← Back to services</a>
    <h1 class="mt-2 text-3xl md:text-4xl font-bold">{{ $service->name }}</h1>
    @if($service->image)
        <div class="mt-6 aspect-video rounded-2xl overflow-hidden border border-white/10">
            <img loading="lazy" src="{{ $service->image }}" class="w-full h-full object-cover" />
        </div>
    @endif
    <article class="prose prose-invert max-w-none mt-6">
        {!! nl2br(e($service->content)) !!}
    </article>

    @if(isset($related) && $related->count())
    <div class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">Related services</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            @foreach($related as $s)
                <a href="{{ route('services.show', $s->slug) }}" class="p-4 rounded-xl bg-white/5 border border-white/10 hover:border-white/30 transition">
                    <h3 class="font-semibold">{{ $s->name }}</h3>
                    <p class="text-slate-400 text-sm">{{ $s->excerpt }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif
  </section>
@endsection
