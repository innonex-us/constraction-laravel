@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-bold mb-6">Projects</h1>

    <form method="get" class="mb-6 p-4 rounded-xl bg-white/5 border border-white/10">
        <div class="grid md:grid-cols-4 gap-3">
            <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search..."
                   class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            <select name="category" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2">
                <option value="">All categories</option>
                @foreach(($categories ?? []) as $c)
                    <option value="{{ $c }}" @selected(($category ?? '')===$c)>{{ $c }}</option>
                @endforeach
            </select>
            <select name="status" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2">
                <option value="">All status</option>
                @foreach(($statuses ?? []) as $s)
                    <option value="{{ $s }}" @selected(($status ?? '')===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-emerald-500 text-slate-900 font-semibold">Filter</button>
                <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-lg border border-white/10">Reset</a>
            </div>
        </div>
    </form>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <a href="{{ route('projects.show', $project->slug) }}" class="group rounded-2xl overflow-hidden border border-white/10 hover:border-white/30 transition" data-aos="fade-up">
                <div class="aspect-video overflow-hidden">
                    <img loading="lazy" src="{{ $project->featured_image ?: 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?q=80&w=1600&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition" />
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold">{{ $project->title }}</h3>
                    <p class="text-slate-400 text-sm">{{ $project->location }} @if($project->category) • {{ $project->category }} @endif</p>
                </div>
            </a>
        @empty
            <p class="text-slate-400">No projects yet.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $projects->links() }}</div>
  </section>
@endsection
