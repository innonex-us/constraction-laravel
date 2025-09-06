@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => $project->meta_title ?? $project->title, 'metaDescription' => $project->meta_description ?? $project->excerpt])

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12">
    <a href="{{ route('projects.index') }}" class="text-sm text-emerald-300">← Back to projects</a>
    <h1 class="mt-2 text-3xl md:text-4xl font-bold">{{ $project->title }}</h1>
    <p class="text-slate-400">{{ $project->location }} @if($project->client) • Client: {{ $project->client }} @endif</p>
    @if($project->featured_image_url)
        <div class="mt-6 aspect-video rounded-2xl overflow-hidden border border-white/10">
            <img loading="lazy" decoding="async" fetchpriority="low" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" data-src="{{ $project->featured_image_url }}" @if($project->featured_image_srcset) data-srcset="{{ $project->featured_image_srcset }}" sizes="(min-width:1024px) 60vw, 100vw" @endif class="w-full h-full object-cover" />
        </div>
    @endif
    <article class="prose prose-invert mt-6">
        {!! $project->content !!}
    </article>

    @if(isset($related) && $related->count())
    <div class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">Related projects</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            @foreach($related as $p)
                <a href="{{ route('projects.show', $p->slug) }}" class="p-4 rounded-xl bg-white/5 border border-white/10 hover:border-white/30 transition">
                    <h3 class="font-semibold">{{ $p->title }}</h3>
                    <p class="text-slate-400 text-sm">{{ $p->location }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif
  </section>
@endsection
