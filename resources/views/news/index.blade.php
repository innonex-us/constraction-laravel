@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => 'News'])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-bold mb-8">News</h1>
    <div class="grid md:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <a href="{{ route('news.show', $post->slug) }}" class="group rounded-2xl overflow-hidden border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                <div class="aspect-[16/10] overflow-hidden bg-white/5">
                    <img loading="lazy" src="{{ $post->featured_image ?: 'https://images.unsplash.com/photo-1581091870686-8e2980a57f5b?q=80&w=1600&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition" />
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold">{{ $post->title }}</h3>
                    <p class="text-slate-400 text-sm mt-1">{{ $post->excerpt }}</p>
                    @if($post->published_at)
                        <p class="text-slate-500 text-xs mt-3">{{ $post->published_at->format('M d, Y') }}</p>
                    @endif
                </div>
            </a>
        @empty
            <p class="text-slate-400">No posts yet.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $posts->links() }}</div>
</section>
@endsection
