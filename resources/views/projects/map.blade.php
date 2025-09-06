@php($settings = \App\Models\SiteSetting::first())
@extends('layouts.app', ['settings' => $settings, 'title' => 'Projects Map'])

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-bold mb-6">Projects Map</h1>
    <div id="map" class="w-full h-[520px] rounded-2xl border border-white/10"></div>
</section>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const map = L.map('map');
  const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  const projects = @json($projects);
  const bounds = L.latLngBounds();
  projects.forEach(p => {
    const m = L.marker([p.lat, p.lng]).addTo(map);
    const img = p.featured_image ? `<div style="margin-top:8px"><img src="${p.featured_image}" style="max-width:220px;border-radius:8px"/></div>` : '';
    m.bindPopup(`<strong>${p.title}</strong><br/><span style="color:#94a3b8">${p.location||''}</span>${img}<div style=\"margin-top:8px\"><a href=\"/projects/${p.slug}\" style=\"color:#34d399\">View project →</a></div>`);
    bounds.extend([p.lat, p.lng]);
  });
  if (projects.length) map.fitBounds(bounds.pad(0.2)); else map.setView([39,-98], 4);
});
</script>
@endpush
@endsection

