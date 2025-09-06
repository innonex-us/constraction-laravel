@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => 'Contact'])

@section('content')
<section class="mx-auto max-w-3xl px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-bold mb-6">Contact Us</h1>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 text-emerald-300 border border-emerald-500/30">
            {{ session('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('contact.submit') }}" class="grid gap-4">
        @csrf
        <div>
            <label class="block text-sm mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" required />
            @error('name')<p class="text-red-400 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" required />
                @error('email')<p class="text-red-400 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
                @error('phone')<p class="text-red-400 text-sm">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm mb-1">Subject</label>
            <input type="text" name="subject" value="{{ old('subject') }}" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" />
            @error('subject')<p class="text-red-400 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm mb-1">Message</label>
            <textarea name="message" rows="6" class="w-full rounded-lg bg-white/5 border border-white/10 px-3 py-2" required>{{ old('message') }}</textarea>
            @error('message')<p class="text-red-400 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="mt-2">
            <button class="px-6 py-3 rounded-lg bg-emerald-500 text-slate-900 font-semibold hover:bg-emerald-400 transition">Send</button>
        </div>
    </form>
  </section>
@endsection

