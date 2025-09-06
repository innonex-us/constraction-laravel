@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-bold mb-8">Projects</h1>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <a href="{{ route('projects.show', $project->slug) }}" class="group rounded-2xl overflow-hidden border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                <div class="aspect-video overflow-hidden">
                    <img src="{{ $project->featured_image ?: 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?q=80&w=1600&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition" />
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold">{{ $project->title }}</h3>
                    <p class="text-slate-400 text-sm">{{ $project->location }}</p>
                </div>
            </a>
        @empty
            <p class="text-slate-400">No projects yet.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $projects->links() }}</div>
  </section>
@endsection

