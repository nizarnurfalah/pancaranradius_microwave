<div class="row g-3">
    <div class="col-lg-4">
        <div class="gis-card h-100">
            <div class="gis-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-location-arrow text-primary"></i>
                    <span>Deteksi Posisi & Tower Terdekat</span>
                </div>
            </div>
            <div class="p-3">
                <p class="small text-secondary mb-3">
                    Gunakan sensor GPS browser Anda untuk mendeteksi koordinat saat ini dan menghitung jarak langsung ke tower BTS terdekat.
                </p>

                <button class="btn btn-primary w-100 rounded-2 py-2 fw-medium mb-3" onclick="locateUser()">
                    <i class="fa-solid fa-crosshairs me-1"></i> Lacak Posisi Saya Sekarang
                </button>

                <div class="p-3 rounded-2 bg-light border mb-3">
                    <div class="small text-secondary mb-1">Koordinat Pengguna:</div>
                    <div class="font-monospace small text-dark fw-bold" id="userCoordText">Belum dilacak</div>
                </div>

                <div class="p-3 rounded-2 bg-light border" id="nearestTowerBox" style="display: none;">
                    <div class="small text-primary fw-bold mb-1"><i class="fa-solid fa-tower-broadcast me-1"></i> Tower Terdekat:</div>
                    <div class="fs-6 fw-bold text-dark mb-1" id="nearestName">-</div>
                    <div class="small text-secondary mb-2" id="nearestAddress">-</div>
                    <div class="d-flex justify-content-between small text-secondary border-top pt-2">
                        <span>Jarak Langsung:</span>
                        <strong class="text-primary fs-6" id="nearestDist">-</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="gis-card">
            <div class="gis-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-map-location text-primary"></i>
                    <span>Peta Geolokasi Terhadap Jaringan Tower</span>
                </div>
            </div>
            <div class="p-3">
                <div id="geoMap" style="width: 100%; height: 65vh; border-radius: 6px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    var map;
    var userMarker, userCircle;
    var nearestLine;
    var towers = <?= json_encode($lokasi ?? []) ?>;

    document.addEventListener("DOMContentLoaded", function() {
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });

        map = L.map('geoMap', {
            center: [-6.8844, 107.5419],
            zoom: 13,
            layers: [osm]
        });

        var towerIcon = L.divIcon({
            className: 'tower-marker',
            html: '<div style="width:30px;height:30px;background:#1d4ed8;border:2px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;box-shadow:0 2px 5px rgba(0,0,0,0.3);"><i class="fa-solid fa-tower-broadcast"></i></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        towers.forEach(function(t) {
            var lat = parseFloat(t.latitude);
            var lng = parseFloat(t.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                L.marker([lat, lng], { icon: towerIcon })
                    .bindPopup(`<b>${t.nama_lokasi}</b><br>${t.alamat_lokasi}`)
                    .addTo(map);

                L.circle([lat, lng], {
                    radius: parseFloat(t.radius_jangkauan) || 2500,
                    color: '#2563eb',
                    weight: 1,
                    fillOpacity: 0.05
                }).addTo(map);
            }
        });
    });

    function locateUser() {
        if (!navigator.geolocation) {
            Swal.fire('Error', 'Browser tidak mendukung Geolokasi GPS.', 'error');
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            var uLat = position.coords.latitude;
            var uLng = position.coords.longitude;
            var userPos = [uLat, uLng];

            document.getElementById('userCoordText').innerText = uLat.toFixed(6) + ', ' + uLng.toFixed(6);

            if (userMarker) map.removeLayer(userMarker);
            if (userCircle) map.removeLayer(userCircle);
            if (nearestLine) map.removeLayer(nearestLine);

            var userIcon = L.divIcon({
                className: 'user-pin-icon',
                html: '<div style="width:32px;height:32px;background:#dc2626;border:2.5px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;box-shadow:0 2px 5px rgba(0,0,0,0.3);"><i class="fa-solid fa-user"></i></div>',
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            userMarker = L.marker(userPos, { icon: userIcon }).addTo(map).bindPopup("<b>Posisi Anda Saat Ini</b>").openPopup();
            userCircle = L.circle(userPos, { radius: position.coords.accuracy || 100, color: '#dc2626', fillOpacity: 0.15 }).addTo(map);

            var nearest = null;
            var minDistance = Infinity;

            towers.forEach(function(t) {
                var tLat = parseFloat(t.latitude);
                var tLng = parseFloat(t.longitude);
                if (!isNaN(tLat) && !isNaN(tLng)) {
                    var d = L.latLng(userPos).distanceTo(L.latLng(tLat, tLng));
                    if (d < minDistance) {
                        minDistance = d;
                        nearest = t;
                    }
                }
            });

            if (nearest) {
                var nearestPos = [parseFloat(nearest.latitude), parseFloat(nearest.longitude)];
                nearestLine = L.polyline([userPos, nearestPos], { color: '#d97706', weight: 2.5, dashArray: '5, 6' }).addTo(map);

                document.getElementById('nearestTowerBox').style.display = 'block';
                document.getElementById('nearestName').innerText = nearest.nama_lokasi;
                document.getElementById('nearestAddress').innerText = nearest.alamat_lokasi;
                document.getElementById('nearestDist').innerText = (minDistance < 1000) ? Math.round(minDistance) + ' Meter' : (minDistance/1000).toFixed(2) + ' km';

                map.fitBounds(L.latLngBounds([userPos, nearestPos]), { padding: [60, 60] });
            }
        }, function(err) {
            Swal.fire('GPS Info', 'Gagal melacak lokasi perangkat: ' + err.message, 'warning');
        });
    }
</script>
