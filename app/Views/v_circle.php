<div class="gis-card">
    <div class="gis-card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-regular fa-circle-dot text-primary"></i>
            <span>Simulasi Multi-Radius Beam Antena (Near, Mid, Far Field)</span>
        </div>
        <div class="d-flex align-items-center gap-3 small text-secondary">
            <span><span class="d-inline-block rounded-circle bg-success me-1" style="width: 8px; height: 8px;"></span> Zona Kuat (Near)</span>
            <span><span class="d-inline-block rounded-circle bg-primary me-1" style="width: 8px; height: 8px;"></span> Zona Sedang (Mid)</span>
            <span><span class="d-inline-block rounded-circle bg-warning me-1" style="width: 8px; height: 8px;"></span> Zona Jauh (Far Field)</span>
        </div>
    </div>
    <div class="p-3">
        <div id="circleMap" style="width: 100%; height: 72vh; border-radius: 6px;"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });

        var map = L.map('circleMap', {
            center: [-6.8894, 107.5583],
            zoom: 13,
            layers: [osm]
        });

        var towers = <?= json_encode($lokasi ?? []) ?>;

        towers.forEach(function(t) {
            var lat = parseFloat(t.latitude);
            var lng = parseFloat(t.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                var maxRad = parseFloat(t.radius_jangkauan) || 3000;

                // Near Zone (Green)
                L.circle([lat, lng], { radius: maxRad * 0.3, color: '#059669', weight: 1.5, fillColor: '#10b981', fillOpacity: 0.15 }).addTo(map);

                // Mid Zone (Blue)
                L.circle([lat, lng], { radius: maxRad * 0.6, color: '#2563eb', weight: 1.5, fillColor: '#3b82f6', fillOpacity: 0.08 }).addTo(map);

                // Far Zone (Amber)
                L.circle([lat, lng], { radius: maxRad, color: '#d97706', weight: 1.5, fillColor: '#f59e0b', fillOpacity: 0.04 }).addTo(map);

                L.marker([lat, lng])
                    .bindPopup(`<b>${t.nama_lokasi}</b><br>Max Radius: ${maxRad} m<br>Frekuensi: ${t.frekuensi || 5.8} GHz`)
                    .addTo(map);
            }
        });
    });
</script>