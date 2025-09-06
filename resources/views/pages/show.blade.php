@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => $page->meta_title ?? $page->title, 'metaDescription' => $page->meta_description])

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12">
    <h1 class="text-3xl md:text-4xl font-bold">{{ $page->title }}</h1>
    @if($page->hero_image)
        <div class="mt-6 aspect-video rounded-2xl overflow-hidden border border-white/10">
            <img src="{{ $page->hero_image }}" class="w-full h-full object-cover" />
        </div>
    @endif
    <article class="prose prose-invert max-w-none mt-6">
        {!! nl2br(e($page->content)) !!}
    </article>
  </section>
@endsection

