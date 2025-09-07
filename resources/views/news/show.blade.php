@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => $post->meta_title ?? $post->title, 'metaDescription' => $post->meta_description ?? $post->excerpt])

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12">
    <a href="{{ route('news.index') }}" class="text-sm text-emerald-300">← Back to news</a>
    <h1 class="mt-2 text-3xl md:text-4xl font-bold">{{ $post->title }}</h1>
    @if($post->published_at)
        <p class="text-slate-500 text-sm mt-1">{{ $post->published_at->format('F d, Y') }}</p>
    @endif
    @if($post->featured_image_url)
        <div class="mt-6 aspect-video rounded-2xl overflow-hidden border border-white/10">
            <img loading="lazy" decoding="async" fetchpriority="low" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" data-src="{{ $post->featured_image_fallback_url ?? $post->featured_image_url }}" @php($wsrc = $post->featured_image_srcset_webp ?? $post->featured_image_srcset) @if($wsrc) data-srcset="{{ $wsrc }}" sizes="(min-width:1024px) 60vw, 100vw" @endif class="w-full h-full object-cover" />
        </div>
    @endif
    <article class="prose prose-invert mt-6">
        {!! $post->body !!}
    </article>

    @if(isset($latest) && $latest->count())
    <div class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">Latest</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            @foreach($latest as $p)
                <a href="{{ route('news.show', $p->slug) }}" class="p-4 rounded-xl bg-white/5 border border-white/10 hover:border-white/30 transition">
                    <h3 class="font-semibold">{{ $p->title }}</h3>
                    <p class="text-slate-400 text-sm">{{ $p->excerpt }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif
  </section>
@endsection
