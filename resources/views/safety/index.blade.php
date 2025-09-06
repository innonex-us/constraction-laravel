@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => 'Safety'])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-bold">Safety</h1>
    <p class="text-slate-300 mt-2 max-w-prose">We are committed to the highest standards of safety on every project. Our metrics reflect a culture of care and continuous improvement.</p>

    @if($latest)
    <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-xl bg-white/5 border border-white/10 text-center">
            <div class="text-sm text-slate-400">Year</div>
            <div class="mt-1 text-3xl font-bold">{{ $latest->year }}</div>
        </div>
        <div class="p-5 rounded-xl bg-white/5 border border-white/10 text-center">
            <div class="text-sm text-slate-400">EMR</div>
            <div class="mt-1 text-3xl font-bold counter" data-target="{{ number_format($latest->emr ?? 0, 2, '.', '') }}">0.00</div>
        </div>
        <div class="p-5 rounded-xl bg-white/5 border border-white/10 text-center">
            <div class="text-sm text-slate-400">TRIR</div>
            <div class="mt-1 text-3xl font-bold counter" data-target="{{ number_format($latest->trir ?? 0, 2, '.', '') }}">0.00</div>
        </div>
        <div class="p-5 rounded-xl bg-white/5 border border-white/10 text-center">
            <div class="text-sm text-slate-400">OSHA Recordables</div>
            <div class="mt-1 text-3xl font-bold counter" data-target="{{ (int)($latest->osha_recordables ?? 0) }}">0</div>
        </div>
    </div>
    @endif

    <div class="mt-10 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-slate-400">
                <tr>
                    <th class="text-left py-2 pr-4">Year</th>
                    <th class="text-left py-2 pr-4">EMR</th>
                    <th class="text-left py-2 pr-4">TRIR</th>
                    <th class="text-left py-2 pr-4">LTIR</th>
                    <th class="text-left py-2 pr-4">Total Hours</th>
                    <th class="text-left py-2 pr-4">Recordables</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $r)
                <tr class="border-t border-white/10">
                    <td class="py-2 pr-4">{{ $r->year }}</td>
                    <td class="py-2 pr-4">{{ $r->emr }}</td>
                    <td class="py-2 pr-4">{{ $r->trir }}</td>
                    <td class="py-2 pr-4">{{ $r->ltir }}</td>
                    <td class="py-2 pr-4">{{ number_format($r->total_hours) }}</td>
                    <td class="py-2 pr-4">{{ $r->osha_recordables }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const counters = document.querySelectorAll('.counter');
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = parseFloat(el.dataset.target);
      const decimals = (el.dataset.target.split('.')[1] || '').length;
      const start = 0; const dur = 1000; const t0 = performance.now();
      function tick(now){
        const p = Math.min(1, (now - t0)/dur);
        const val = start + (target - start) * p;
        el.textContent = val.toFixed(decimals);
        if (p<1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
      io.unobserve(el);
    });
  }, { threshold: 0.6 });
  counters.forEach(c => io.observe(c));
});
</script>
@endpush
@endsection

