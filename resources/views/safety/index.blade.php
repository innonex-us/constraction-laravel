@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => 'Safety'])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-8 lg:py-16">
    <div class="mobile-native lg:px-0">
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold">Safety</h1>
        <p class="text-slate-300 mt-2 max-w-prose text-sm md:text-base">We are committed to the highest standards of safety on every project. Our metrics reflect a culture of care and continuous improvement.</p>
    </div>

    @if($latest)
    <div class="mt-6 lg:mt-8 px-4 lg:px-0">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
            <div class="mobile-card lg:p-5 lg:rounded-xl lg:bg-white/5 lg:border lg:border-white/10 text-center">
                <div class="text-xs lg:text-sm text-slate-400">Year</div>
                <div class="mt-1 text-xl lg:text-3xl font-bold">{{ $latest->year }}</div>
            </div>
            <div class="mobile-card lg:p-5 lg:rounded-xl lg:bg-white/5 lg:border lg:border-white/10 text-center">
                <div class="text-xs lg:text-sm text-slate-400">EMR</div>
                <div class="mt-1 text-xl lg:text-3xl font-bold counter" data-target="{{ number_format($latest->emr ?? 0, 2, '.', '') }}">0.00</div>
            </div>
            <div class="mobile-card lg:p-5 lg:rounded-xl lg:bg-white/5 lg:border lg:border-white/10 text-center">
                <div class="text-xs lg:text-sm text-slate-400">TRIR</div>
                <div class="mt-1 text-xl lg:text-3xl font-bold counter" data-target="{{ number_format($latest->trir ?? 0, 2, '.', '') }}">0.00</div>
            </div>
            <div class="mobile-card lg:p-5 lg:rounded-xl lg:bg-white/5 lg:border lg:border-white/10 text-center">
                <div class="text-xs lg:text-sm text-slate-400">OSHA Recordables</div>
                <div class="mt-1 text-xl lg:text-3xl font-bold counter" data-target="{{ (int)($latest->osha_recordables ?? 0) }}">0</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Mobile-optimized table -->
    <div class="mt-8 lg:mt-10">
        <h2 class="text-lg lg:text-xl font-semibold mb-4 px-4 lg:px-0">Historical Safety Records</h2>
        
        <!-- Mobile card view -->
        <div class="lg:hidden space-y-4 px-4">
            @foreach($records as $r)
            <div class="mobile-card">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-lg font-semibold">{{ $r->year }}</h3>
                    <span class="px-2 py-1 rounded-md bg-emerald-500/10 text-emerald-300 text-xs">
                        {{ number_format($r->total_hours) }} hours
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-slate-400">EMR</div>
                        <div class="font-medium">{{ $r->emr }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400">TRIR</div>
                        <div class="font-medium">{{ $r->trir }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400">LTIR</div>
                        <div class="font-medium">{{ $r->ltir }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400">Recordables</div>
                        <div class="font-medium">{{ $r->osha_recordables }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop table view -->
        <div class="hidden lg:block overflow-x-auto">
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

