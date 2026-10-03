<div class="row g-3">
    <!-- Left Inspector Panel -->
    <div class="col-lg-4">
        <div class="gis-card h-100">
            <div class="gis-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-bullseye text-primary"></i>
                    <span>Analisis Uji Titik Dalam Radius</span>
                </div>
            </div>
            <div class="p-3">
                <p class="small text-secondary mb-3">
                    Pilih tower referensi atau geser target marker pada peta untuk menguji apakah koordinat berada di dalam jangkauan sinyal microwave.
                </p>

                <!-- Tower Selector -->
                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Pilih Tower Pusat</label>
                    <select id="selectCenterTower" class="form-select form-select-sm" onchange="changeCenterTower()">
                        <?php foreach($lokasi ?? [] as $t): ?>
                            <option value="<?= esc($t['latitude']) ?>,<?= esc($t['longitude']) ?>,<?= esc($t['radius_jangkauan']) ?>,<?= esc($t['nama_lokasi']) ?>">
                                <?= esc($t['kode_tower'] ?? 'TWR') ?> - <?= esc($t['nama_lokasi']) ?> (Radius <?= esc($t['radius_jangkauan']) ?>m)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Live Radius Setting -->
                <div class="mb-3">
                    <label class="form-label small text-secondary">Radius Uji Jangkauan (Meter)</label>
                    <div class="input-group input-group-sm">
                        <input type="number" id="testRadius" class="form-control" value="2500" step="100" min="100" oninput="updateCenterCircle()">
                        <span class="input-group-text bg-light text-secondary">Meter</span>
                    </div>
                </div>

                <hr class="my-3">

                <!-- Target Coordinates -->
                <div class="mb-2">
                    <label class="form-label small text-dark fw-semibold">Latitude Target</label>
                    <input type="text" class="form-control form-control-sm font-monospace" id="Latitude" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Longitude Target</label>
                    <input type="text" class="form-control form-control-sm font-monospace" id="Longitude" readonly>
                </div>

                <!-- Status Card -->
                <div class="p-3 rounded-2 bg-light border" id="coverageStatusBox">
                    <div class="small text-secondary mb-1">Status Cakupan Sinyal:</div>
                    <div class="fs-6 fw-bold mb-2" id="coverageStatusText">Menghitung...</div>
                    <div class="d-flex justify-content-between small text-secondary">
                        <span>Jarak ke Tower:</span>
                        <strong class="text-dark" id="distanceText">0 m</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Area -->
    <div class="col-lg-8">
        <div class="gis-card">
            <div class="gis-card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-map-location text-primary"></i>
                    <span>Peta Uji Radius - Drag Target Marker</span>
                </div>
                <button class="btn btn-sm btn-outline-primary rounded-2 px-3" onclick="resetToCenter()">
                    <i class="fa-solid fa-arrows-to-circle me-1"></i> Reset Posisi Target
                </button>
            </div>
            <div class="p-3">
                <div id="testMap" style="width: 100%; height: 580px; border-radius: 6px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    var map;
    var centerMarker, centerCircle;
    var targetMarker;
    var distanceLine;

    var centerPos = [-6.889439, 107.558329];
    var targetPos = [-6.880328, 107.548986];
    var currentRadius = 2500;

    document.addEventListener("DOMContentLoaded", function() {
        initRadiusMap();
    });

    function initRadiusMap() {
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });
        var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });

        map = L.map('testMap', {
            center: centerPos,
            zoom: 14,
            layers: [osm]
        });

        L.control.layers({ "OpenStreetMap Standar": osm, "Esri Satelit": esriSat }).addTo(map);

        var centerIcon = L.divIcon({
            className: 'center-tower-icon',
            html: '<div style="width:34px;height:34px;background:#1d4ed8;border:2.5px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;box-shadow:0 2px 6px rgba(0,0,0,0.3);"><i class="fa-solid fa-tower-broadcast"></i></div>',
            iconSize: [34, 34],
            iconAnchor: [17, 17]
        });

        centerMarker = L.marker(centerPos, { icon: centerIcon }).addTo(map).bindPopup("<b>Tower Pusat Referensi</b>");

        centerCircle = L.circle(centerPos, {
            radius: currentRadius,
            color: '#2563eb',
            weight: 1.5,
            opacity: 0.8,
            fillColor: '#3b82f6',
            fillOpacity: 0.1
        }).addTo(map);

        var targetIcon = L.divIcon({
            className: 'target-marker-icon',
            html: '<div style="width:28px;height:28px;background:#dc2626;border:2.5px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;box-shadow:0 2px 5px rgba(0,0,0,0.3);"><i class="fa-solid fa-crosshairs"></i></div>',
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        targetMarker = L.marker(targetPos, { draggable: true, icon: targetIcon }).addTo(map);
        distanceLine = L.polyline([centerPos, targetPos], { color: '#d97706', weight: 2, dashArray: '4, 5' }).addTo(map);

        targetMarker.on('drag', function(e) {
            targetPos = [e.latlng.lat, e.latlng.lng];
            evaluateCoverage();
        });

        map.on('click', function(e) {
            targetPos = [e.latlng.lat, e.latlng.lng];
            targetMarker.setLatLng(targetPos);
            evaluateCoverage();
        });

        evaluateCoverage();
    }

    function evaluateCoverage() {
        var cLatlng = L.latLng(centerPos[0], centerPos[1]);
        var tLatlng = L.latLng(targetPos[0], targetPos[1]);
        var dist = cLatlng.distanceTo(tLatlng);

        distanceLine.setLatLngs([centerPos, targetPos]);

        document.getElementById('Latitude').value = targetPos[0].toFixed(6);
        document.getElementById('Longitude').value = targetPos[1].toFixed(6);
        document.getElementById('distanceText').innerText = Math.round(dist).toLocaleString('id-ID') + ' Meter (' + (dist/1000).toFixed(2) + ' km)';

        var statusBox = document.getElementById('coverageStatusBox');
        var statusText = document.getElementById('coverageStatusText');

        if (dist <= currentRadius) {
            statusBox.style.border = '1px solid #10b981';
            statusBox.style.background = '#f0fdf4';
            statusText.className = 'fs-6 fw-bold text-success';
            statusText.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> DALAM RADIUS COVERAGE (SINYAL TERSEDIA)';
            targetMarker.bindPopup("<b>Status: DALAM RADIUS (" + Math.round(dist) + "m)</b>").openPopup();
        } else {
            statusBox.style.border = '1px solid #ef4444';
            statusBox.style.background = '#fef2f2';
            statusText.className = 'fs-6 fw-bold text-danger';
            statusText.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> DI LUAR RADIUS COVERAGE (OUT OF COVERAGE)';
            targetMarker.bindPopup("<b>Status: DI LUAR RADIUS (" + Math.round(dist) + "m)</b>").openPopup();
        }
    }

    function changeCenterTower() {
        var sel = document.getElementById('selectCenterTower');
        if (!sel.value) return;
        var parts = sel.value.split(',');
        centerPos = [parseFloat(parts[0]), parseFloat(parts[1])];
        currentRadius = parseFloat(parts[2]) || 2500;

        document.getElementById('testRadius').value = currentRadius;
        centerMarker.setLatLng(centerPos);
        centerCircle.setLatLng(centerPos);
        centerCircle.setRadius(currentRadius);
        map.setView(centerPos, 14);
        evaluateCoverage();
    }

    function updateCenterCircle() {
        currentRadius = parseFloat(document.getElementById('testRadius').value) || 2500;
        centerCircle.setRadius(currentRadius);
        evaluateCoverage();
    }

    function resetToCenter() {
        targetPos = [centerPos[0] + 0.005, centerPos[1] + 0.005];
        targetMarker.setLatLng(targetPos);
        evaluateCoverage();
    }
</script>