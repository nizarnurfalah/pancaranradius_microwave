<div class="gis-card">
    <div class="gis-card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-map text-primary"></i>
            <span>View Map GIS Pemetaan Jaringan Tower</span>
        </div>
    </div>
    <div class="p-3">
        <div id="viewMapDiv" style="width: 100%; height: 75vh; border-radius: 6px;"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });
        var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });

        var map = L.map('viewMapDiv', {
            center: [-6.8844, 107.5419],
            zoom: 13,
            layers: [osm]
        });

        L.control.layers({ "OpenStreetMap": osm, "Esri Satelit": esriSat }).addTo(map);

        var towers = <?= json_encode($lokasi ?? []) ?>;
        var coords = [];

        towers.forEach(function(t) {
            var lat = parseFloat(t.latitude);
            var lng = parseFloat(t.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                coords.push([lat, lng]);
                L.marker([lat, lng])
                    .bindPopup(`<b>${t.nama_lokasi}</b><br>${t.alamat_lokasi}`)
                    .addTo(map);
            }
        });

        if (coords.length > 0) {
            map.fitBounds(L.latLngBounds(coords), { padding: [50, 50] });
        }
    });
</script>