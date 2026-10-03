<div class="row g-3">
    <div class="col-lg-4">
        <div class="gis-card h-100">
            <div class="gis-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-crosshairs text-primary"></i>
                    <span>Inspektor Titik Koordinat Peta</span>
                </div>
            </div>
            <div class="p-3">
                <p class="small text-secondary mb-3">
                    Klik pada peta atau geser marker untuk menyalin koordinat Latitude dan Longitude secara tepat.
                </p>

                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Latitude (Lintang)</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control font-monospace" id="latDisplay" readonly>
                        <button class="btn btn-outline-secondary" onclick="copyCoord('latDisplay')"><i class="fa-solid fa-copy"></i></button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Longitude (Bujur)</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control font-monospace" id="lngDisplay" readonly>
                        <button class="btn btn-outline-secondary" onclick="copyCoord('lngDisplay')"><i class="fa-solid fa-copy"></i></button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Format Gabungan (Lat, Lng)</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control font-monospace" id="latlngDisplay" readonly>
                        <button class="btn btn-primary" onclick="copyCoord('latlngDisplay')"><i class="fa-solid fa-copy me-1"></i> Salin</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="gis-card">
            <div class="gis-card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-map text-primary"></i>
                    <span>Peta Penunjuk Koordinat</span>
                </div>
            </div>
            <div class="p-3">
                <div id="coordMap" style="width: 100%; height: 65vh; border-radius: 6px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    var map, marker;

    document.addEventListener("DOMContentLoaded", function() {
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });

        map = L.map('coordMap', {
            center: [-6.8844, 107.5419],
            zoom: 14,
            layers: [osm]
        });

        marker = L.marker([-6.8844, 107.5419], { draggable: true }).addTo(map);
        updateCoordDisplays(-6.8844, 107.5419);

        marker.on('drag', function(e) {
            updateCoordDisplays(e.latlng.lat, e.latlng.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordDisplays(e.latlng.lat, e.latlng.lng);
        });
    });

    function updateCoordDisplays(lat, lng) {
        document.getElementById('latDisplay').value = lat.toFixed(6);
        document.getElementById('lngDisplay').value = lng.toFixed(6);
        document.getElementById('latlngDisplay').value = lat.toFixed(6) + ', ' + lng.toFixed(6);
        marker.bindPopup("<b>Koordinat:</b><br>" + lat.toFixed(6) + ", " + lng.toFixed(6)).openPopup();
    }

    function copyCoord(id) {
        var copyText = document.getElementById(id);
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        Swal.fire({
            icon: 'success',
            title: 'Tersalin!',
            text: copyText.value,
            timer: 1500,
            showConfirmButton: false
        });
    }
</script>