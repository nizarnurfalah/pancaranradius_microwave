<div class="row g-3 mb-4">
    <!-- Configuration & Parameters Panel -->
    <div class="col-lg-4">
        <div class="gis-card h-100">
            <div class="gis-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-sliders text-primary"></i>
                    <span>Parameter Link Microwave</span>
                </div>
            </div>
            <div class="p-3">
                <!-- Preset Tower Selector -->
                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold">Titik Pemancar (Tower A)</label>
                    <select id="selectTowerA" class="form-select form-select-sm" onchange="loadPresetTower('A')">
                        <option value="">-- Pilih dari Database atau Klik di Peta --</option>
                        <?php foreach($lokasi ?? [] as $t): ?>
                            <option value="<?= esc($t['latitude']) ?>,<?= esc($t['longitude']) ?>,<?= esc($t['tinggi_tower']) ?>,<?= esc($t['nama_lokasi']) ?>">
                                <?= esc($t['kode_tower'] ?? 'TWR') ?> - <?= esc($t['nama_lokasi']) ?> (<?= esc($t['tinggi_tower']) ?>m)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold">Titik Penerima (Tower B)</label>
                    <select id="selectTowerB" class="form-select form-select-sm" onchange="loadPresetTower('B')">
                        <option value="">-- Pilih dari Database atau Klik di Peta --</option>
                        <?php foreach($lokasi ?? [] as $t): ?>
                            <option value="<?= esc($t['latitude']) ?>,<?= esc($t['longitude']) ?>,<?= esc($t['tinggi_tower']) ?>,<?= esc($t['nama_lokasi']) ?>">
                                <?= esc($t['kode_tower'] ?? 'TWR') ?> - <?= esc($t['nama_lokasi']) ?> (<?= esc($t['tinggi_tower']) ?>m)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr class="my-3">

                <!-- Manual Coordinates & Heights -->
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label small text-dark fw-semibold">Tinggi Tower A (h1)</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="heightA" class="form-control" value="50" min="1" oninput="calculateMicrowave()">
                            <span class="input-group-text bg-light">m</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label small text-dark fw-semibold">Tinggi Tower B (h2)</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="heightB" class="form-control" value="45" min="1" oninput="calculateMicrowave()">
                            <span class="input-group-text bg-light">m</span>
                        </div>
                    </div>
                </div>

                <!-- Frequency & RF Parameters -->
                <div class="mb-2">
                    <label class="form-label small text-dark fw-semibold">Frekuensi Transmisi ($f$)</label>
                    <select id="paramFreq" class="form-select form-select-sm" onchange="calculateMicrowave()">
                        <option value="2.4">2.4 GHz (WiFi Outdoor / ISM)</option>
                        <option value="5.8" selected>5.8 GHz (Standard Microwave Link)</option>
                        <option value="7.5">7.5 GHz (Licensed Microwave)</option>
                        <option value="11.0">11.0 GHz (Licensed Long Haul)</option>
                        <option value="18.0">18.0 GHz (High Capacity Metro)</option>
                        <option value="23.0">23.0 GHz (Short Haul High Throughput)</option>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small text-secondary">Tx Power ($P_{tx}$)</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="paramTxPower" class="form-control" value="27" oninput="calculateMicrowave()">
                            <span class="input-group-text bg-light">dBm</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label small text-secondary">Antenna Gain ($G$)</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="paramGain" class="form-control" value="28" oninput="calculateMicrowave()">
                            <span class="input-group-text bg-light">dBi</span>
                        </div>
                    </div>
                </div>

                <button class="btn btn-sm btn-primary w-100 rounded-2 fw-medium" onclick="calculateMicrowave()">
                    <i class="fa-solid fa-calculator me-2"></i> Hitung Ulang Analisis Link
                </button>
            </div>
        </div>
    </div>

    <!-- Calculation Results & Fresnel Ellipsoid Profile -->
    <div class="col-lg-8">
        <div class="gis-card h-100">
            <div class="gis-card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-line text-success"></i>
                    <span>Hasil Analisis Propagasi & Fresnel Zone</span>
                </div>
                <span class="badge bg-success" id="linkStatusBadge">Link Feasible (LOS OK)</span>
            </div>
            <div class="p-3">
                <!-- KPI Stats Grid -->
                <div class="row g-2 text-center mb-3">
                    <div class="col-md-3 col-6">
                        <div class="p-2 rounded bg-light border">
                            <div class="text-secondary small">Jarak ($d$)</div>
                            <div class="fs-5 fw-bold text-dark" id="outDistance">4.85 km</div>
                            <div class="small text-muted" id="outDistanceM">4.850 m</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-2 rounded bg-light border">
                            <div class="text-secondary small">Radius Fresnel ($R_1$)</div>
                            <div class="fs-5 fw-bold text-primary" id="outFresnel">7.92 m</div>
                            <div class="small text-muted">Midpoint clearance</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-2 rounded bg-light border">
                            <div class="text-secondary small">Path Loss (FSPL)</div>
                            <div class="fs-5 fw-bold text-danger" id="outFSPL">121.4 dB</div>
                            <div class="small text-muted">Free Space Loss</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-2 rounded bg-light border">
                            <div class="text-secondary small">RSL / Sinyal</div>
                            <div class="fs-5 fw-bold text-success" id="outRSL">-38.4 dBm</div>
                            <div class="small text-muted">Estimated Level</div>
                        </div>
                    </div>
                </div>

                <!-- 2D Canvas Visualization of Fresnel Ellipsoid -->
                <div class="p-2 rounded bg-light border mb-2 position-relative">
                    <div class="small text-secondary fw-semibold mb-1 d-flex justify-content-between">
                        <span><i class="fa-solid fa-wave-square text-primary me-1"></i> Profil 2D Fresnel Zone 1 & Line of Sight (LOS)</span>
                        <span class="text-success small">Area Hijau = 60% Clearance Zone</span>
                    </div>
                    <canvas id="fresnelCanvas" height="150" style="width: 100%; border-radius: 6px; background: #ffffff; border: 1px solid #e2e8f0;"></canvas>
                </div>
                <div class="small text-muted text-center">
                    Garis Biru: Line of Sight (LOS) &bull; Elips Biru: 1st Fresnel Zone &bull; Elips Hijau: 60% Clearance Zone
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Map for Link Positioning -->
<div class="row g-3">
    <div class="col-12">
        <div class="gis-card">
            <div class="gis-card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-map-location text-primary"></i>
                    <span>Peta Interaktif - Titik Tower A & B</span>
                </div>
                <div class="small text-muted">
                    Klik di peta atau geser marker untuk memindahkan posisi tower
                </div>
            </div>
            <div class="p-3">
                <div id="calcMap" style="width: 100%; height: 480px; border-radius: 6px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    var calcMap;
    var markerA, markerB;
    var linkLine;

    var posA = [-6.889439, 107.558329];
    var posB = [-6.865278, 107.541138];

    document.addEventListener("DOMContentLoaded", function() {
        initCalcMap();
        calculateMicrowave();
    });

    function initCalcMap() {
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });
        var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });
        var esriTopo = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });

        calcMap = L.map('calcMap', {
            center: [-6.8773, 107.5497],
            zoom: 13,
            layers: [osm]
        });

        L.control.layers({ "OpenStreetMap Standar": osm, "Esri Satelit": esriSat, "Esri Topografi": esriTopo }).addTo(calcMap);

        var iconA = L.divIcon({
            className: 'tower-icon-a',
            html: '<div style="width:32px;height:32px;background:#1d4ed8;border:2px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;box-shadow:0 2px 6px rgba(0,0,0,0.3);">A</div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        var iconB = L.divIcon({
            className: 'tower-icon-b',
            html: '<div style="width:32px;height:32px;background:#d97706;border:2px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;box-shadow:0 2px 6px rgba(0,0,0,0.3);">B</div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        markerA = L.marker(posA, { draggable: true, icon: iconA }).addTo(calcMap).bindPopup("<b>Tower A (Pemancar)</b>");
        markerB = L.marker(posB, { draggable: true, icon: iconB }).addTo(calcMap).bindPopup("<b>Tower B (Penerima)</b>");

        linkLine = L.polyline([posA, posB], { color: '#1d4ed8', weight: 2.5, dashArray: '5, 6' }).addTo(calcMap);

        markerA.on('drag', function(e) {
            posA = [e.latlng.lat, e.latlng.lng];
            updateMapLinks();
            calculateMicrowave();
        });

        markerB.on('drag', function(e) {
            posB = [e.latlng.lat, e.latlng.lng];
            updateMapLinks();
            calculateMicrowave();
        });

        calcMap.on('click', function(e) {
            var dA = calcMap.distance(e.latlng, posA);
            var dB = calcMap.distance(e.latlng, posB);
            if (dA < dB) {
                posA = [e.latlng.lat, e.latlng.lng];
                markerA.setLatLng(posA);
            } else {
                posB = [e.latlng.lat, e.latlng.lng];
                markerB.setLatLng(posB);
            }
            updateMapLinks();
            calculateMicrowave();
        });
    }

    function updateMapLinks() {
        linkLine.setLatLngs([posA, posB]);
    }

    function loadPresetTower(target) {
        var sel = document.getElementById('selectTower' + target);
        if (!sel.value) return;
        var parts = sel.value.split(',');
        var lat = parseFloat(parts[0]);
        var lng = parseFloat(parts[1]);
        var h = parseFloat(parts[2]) || 45;

        if (target === 'A') {
            posA = [lat, lng];
            markerA.setLatLng(posA);
            document.getElementById('heightA').value = h;
        } else {
            posB = [lat, lng];
            markerB.setLatLng(posB);
            document.getElementById('heightB').value = h;
        }
        updateMapLinks();
        calcMap.fitBounds(L.latLngBounds([posA, posB]), { padding: [50, 50] });
        calculateMicrowave();
    }

    function calculateMicrowave() {
        var hA = parseFloat(document.getElementById('heightA').value) || 45;
        var hB = parseFloat(document.getElementById('heightB').value) || 45;
        var f = parseFloat(document.getElementById('paramFreq').value) || 5.8;
        var pTx = parseFloat(document.getElementById('paramTxPower').value) || 27;
        var gain = parseFloat(document.getElementById('paramGain').value) || 28;

        var latlngA = L.latLng(posA[0], posA[1]);
        var latlngB = L.latLng(posB[0], posB[1]);
        var distM = latlngA.distanceTo(latlngB);
        var distKm = distM / 1000;

        var r1 = 8.656 * Math.sqrt(distKm / f);
        var clearance = 0.6 * r1;
        var fspl = 20 * Math.log10(distKm) + 20 * Math.log10(f) + 92.45;
        var rsl = pTx + (gain * 2) - fspl - 2;

        document.getElementById('outDistance').innerText = distKm.toFixed(2) + ' km';
        document.getElementById('outDistanceM').innerText = Math.round(distM).toLocaleString('id-ID') + ' m';
        document.getElementById('outFresnel').innerText = r1.toFixed(2) + ' m';
        document.getElementById('outFSPL').innerText = fspl.toFixed(1) + ' dB';
        document.getElementById('outRSL').innerText = rsl.toFixed(1) + ' dBm';

        var badge = document.getElementById('linkStatusBadge');
        if (rsl > -65) {
            badge.className = 'badge bg-success';
            badge.innerText = 'Link Sangat Kuat (Excellent LOS)';
        } else if (rsl > -75) {
            badge.className = 'badge bg-primary';
            badge.innerText = 'Link Handal (Good LOS)';
        } else if (rsl > -85) {
            badge.className = 'badge bg-warning text-dark';
            badge.innerText = 'Sinyal Sedang (Marginal)';
        } else {
            badge.className = 'badge bg-danger';
            badge.innerText = 'Sinyal Buruk / Redaman Tinggi';
        }

        drawFresnelCanvas(hA, hB, r1, clearance);
    }

    function drawFresnelCanvas(hA, hB, r1, clearance) {
        var canvas = document.getElementById('fresnelCanvas');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var w = canvas.width = canvas.parentElement.clientWidth;
        var h = canvas.height = 140;

        ctx.clearRect(0, 0, w, h);

        var marginX = 50;
        var marginY = 20;

        var yA = h - marginY - (hA * 0.7);
        var yB = h - marginY - (hB * 0.7);
        var xA = marginX;
        var xB = w - marginX;

        // Ground line
        ctx.strokeStyle = '#94a3b8';
        ctx.lineWidth = 1.5;
        ctx.beginPath();
        ctx.moveTo(0, h - marginY);
        ctx.lineTo(w, h - marginY);
        ctx.stroke();

        // Tower A
        ctx.strokeStyle = '#1d4ed8';
        ctx.lineWidth = 3.5;
        ctx.beginPath();
        ctx.moveTo(xA, h - marginY);
        ctx.lineTo(xA, yA);
        ctx.stroke();

        // Tower B
        ctx.strokeStyle = '#d97706';
        ctx.beginPath();
        ctx.moveTo(xB, h - marginY);
        ctx.lineTo(xB, yB);
        ctx.stroke();

        // Midpoint
        var midX = (xA + xB) / 2;
        var midY = (yA + yB) / 2;
        var rx = (xB - xA) / 2;
        var ry = Math.min(35, r1 * 2.5);
        var ry60 = ry * 0.6;

        ctx.save();
        ctx.translate(midX, midY);
        var angle = Math.atan2(yB - yA, xB - xA);
        ctx.rotate(angle);

        // 100% Fresnel Zone
        ctx.fillStyle = 'rgba(37, 99, 235, 0.08)';
        ctx.strokeStyle = 'rgba(37, 99, 235, 0.5)';
        ctx.lineWidth = 1.5;
        ctx.beginPath();
        ctx.ellipse(0, 0, rx, ry, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.stroke();

        // 60% Clearance Zone
        ctx.fillStyle = 'rgba(16, 185, 129, 0.12)';
        ctx.strokeStyle = 'rgba(16, 185, 129, 0.7)';
        ctx.lineWidth = 1.5;
        ctx.beginPath();
        ctx.ellipse(0, 0, rx, ry60, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.stroke();

        // Line of Sight
        ctx.strokeStyle = '#1d4ed8';
        ctx.lineWidth = 1.5;
        ctx.setLineDash([4, 4]);
        ctx.beginPath();
        ctx.moveTo(-rx, 0);
        ctx.lineTo(rx, 0);
        ctx.stroke();

        ctx.restore();

        // Labels
        ctx.fillStyle = '#1d4ed8';
        ctx.font = '11px sans-serif';
        ctx.fillText('Tower A (' + hA + 'm)', xA - 30, yA - 6);

        ctx.fillStyle = '#d97706';
        ctx.fillText('Tower B (' + hB + 'm)', xB - 30, yB - 6);
    }
</script>
