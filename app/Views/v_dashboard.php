<?php
// Calculate dashboard stats
$total_tower = count($lokasi ?? []);
$tower_aktif = 0;
$total_radius = 0;
$freq_counts = [];

foreach ($lokasi ?? [] as $t) {
    if (($t['status'] ?? 'Aktif') === 'Aktif') {
        $tower_aktif++;
    }
    $total_radius += (float)($t['radius_jangkauan'] ?? 0);
    $f = (string)($t['frekuensi'] ?? 5.8);
    $freq_counts[$f] = ($freq_counts[$f] ?? 0) + 1;
}

$avg_radius = $total_tower > 0 ? round($total_radius / $total_tower) : 0;
arsort($freq_counts);
$top_freq = !empty($freq_counts) ? array_key_first($freq_counts) . ' GHz' : '5.8 GHz';
?>

<!-- KPI Stat Cards Formal -->
<div class="row g-3 mb-4">
    <!-- Stat 1: Total Towers -->
    <div class="col-xl-3 col-md-6">
        <div class="gis-card p-3 d-flex align-items-center justify-content-between h-100">
            <div>
                <div class="text-secondary small fw-semibold text-uppercase">Total Tower BTS</div>
                <div class="fs-3 fw-bold text-dark mt-1"><?= $total_tower ?> <span class="fs-6 text-secondary fw-normal">Titik</span></div>
                <div class="small text-muted mt-1"><i class="fa-solid fa-tower-broadcast me-1 text-primary"></i> Terdaftar di GIS</div>
            </div>
            <div class="rounded-3 d-flex align-items-center justify-content-center bg-light text-primary" style="width: 48px; height: 48px; font-size: 1.35rem;">
                <i class="fa-solid fa-tower-broadcast"></i>
            </div>
        </div>
    </div>

    <!-- Stat 2: Active Towers -->
    <div class="col-xl-3 col-md-6">
        <div class="gis-card p-3 d-flex align-items-center justify-content-between h-100">
            <div>
                <div class="text-secondary small fw-semibold text-uppercase">Tower Aktif</div>
                <div class="fs-3 fw-bold text-dark mt-1"><?= $tower_aktif ?> <span class="fs-6 text-success fw-normal">/ <?= $total_tower ?></span></div>
                <div class="small text-success mt-1"><i class="fa-solid fa-circle-check me-1"></i> <?= $total_tower > 0 ? round(($tower_aktif/$total_tower)*100) : 0 ?>% Operasional</div>
            </div>
            <div class="rounded-3 d-flex align-items-center justify-content-center bg-light text-success" style="width: 48px; height: 48px; font-size: 1.35rem;">
                <i class="fa-solid fa-signal"></i>
            </div>
        </div>
    </div>

    <!-- Stat 3: Avg Coverage Radius -->
    <div class="col-xl-3 col-md-6">
        <div class="gis-card p-3 d-flex align-items-center justify-content-between h-100">
            <div>
                <div class="text-secondary small fw-semibold text-uppercase">Rata-Rata Radius</div>
                <div class="fs-3 fw-bold text-dark mt-1"><?= number_format($avg_radius, 0, ',', '.') ?> <span class="fs-6 text-secondary fw-normal">m</span></div>
                <div class="small text-muted mt-1"><i class="fa-solid fa-circle-dot me-1 text-primary"></i> Jangkauan transmisi</div>
            </div>
            <div class="rounded-3 d-flex align-items-center justify-content-center bg-light text-primary" style="width: 48px; height: 48px; font-size: 1.35rem;">
                <i class="fa-solid fa-circle-nodes"></i>
            </div>
        </div>
    </div>

    <!-- Stat 4: Dominant Frequency -->
    <div class="col-xl-3 col-md-6">
        <div class="gis-card p-3 d-flex align-items-center justify-content-between h-100">
            <div>
                <div class="text-secondary small fw-semibold text-uppercase">Frekuensi Dominan</div>
                <div class="fs-3 fw-bold text-dark mt-1"><?= $top_freq ?></div>
                <div class="small text-muted mt-1"><i class="fa-solid fa-wave-square me-1 text-warning"></i> Gelombang Mikro</div>
            </div>
            <div class="rounded-3 d-flex align-items-center justify-content-center bg-light text-warning" style="width: 48px; height: 48px; font-size: 1.35rem;">
                <i class="fa-solid fa-radio"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main GIS Map Card -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="gis-card h-100">
            <div class="gis-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-primary"></i>
                    <span>Peta Sebaran Tower & Radius Jangkauan Microwave</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleLinks()" id="btnToggleLinks">
                        <i class="fa-solid fa-draw-polygon me-1"></i> <span id="linkBtnText">Sembunyikan Garis Link</span>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="resetMapView()">
                        <i class="fa-solid fa-expand me-1"></i> Reset View
                    </button>
                </div>
            </div>
            <div class="p-3">
                <div id="overviewMap" style="width: 100%; height: 500px; border-radius: 6px;"></div>
                <div class="d-flex flex-wrap align-items-center justify-content-between mt-2 pt-2 border-top small text-secondary">
                    <div class="d-flex align-items-center gap-3">
                        <span><span class="d-inline-block rounded-circle bg-primary me-1" style="width: 10px; height: 10px;"></span> Lingkaran Biru: Radius Jangkauan</span>
                        <span><span class="d-inline-block rounded-circle bg-warning me-1" style="width: 10px; height: 10px;"></span> Garis Kuning: Link Microwave Antar Tower</span>
                    </div>
                    <div>
                        <span class="text-muted">Peta: OpenStreetMap Standar (Jelas & Resmi)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Microwave & Fresnel Calculator Widget -->
    <div class="col-lg-4">
        <div class="gis-card h-100">
            <div class="gis-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-calculator text-primary"></i>
                    <span>Kalkulator Cepat Fresnel & Link</span>
                </div>
            </div>
            <div class="p-3">
                <p class="small text-secondary mb-3">
                    Hitung estimasi Free Space Path Loss (FSPL) & Radius Zona Fresnel 1 ($R_1$) antara dua titik pemancar.
                </p>

                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Jarak Antar Tower ($d$)</label>
                    <div class="input-group input-group-sm">
                        <input type="number" id="quickDistance" class="form-control" value="5.2" step="0.1" min="0.1" oninput="runQuickCalc()">
                        <span class="input-group-text bg-light text-secondary">km</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Frekuensi Gelombang ($f$)</label>
                    <select id="quickFreq" class="form-select form-select-sm" onchange="runQuickCalc()">
                        <option value="2.4">2.4 GHz (WiFi Outdoor / ISM)</option>
                        <option value="5.8" selected>5.8 GHz (Standard Microwave Link)</option>
                        <option value="7.5">7.5 GHz (Licensed Microwave)</option>
                        <option value="11.0">11.0 GHz (Licensed Long Haul)</option>
                        <option value="18.0">18.0 GHz (High Capacity Metro)</option>
                        <option value="23.0">23.0 GHz (Short Haul High Throughput)</option>
                    </select>
                </div>

                <div class="p-3 rounded-2 bg-light border mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-secondary">Radius Max Fresnel ($R_1$):</span>
                        <strong class="text-primary fs-6" id="resFresnel">8.21 m</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-secondary">60% Clearance Radius:</span>
                        <strong class="text-dark fs-6" id="resClearance">4.93 m</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-secondary">Path Loss (FSPL):</span>
                        <strong class="text-danger fs-6" id="resFSPL">122.0 dB</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-secondary">Kelayakan Link:</span>
                        <span class="badge bg-success" id="resStatus">Line of Sight (LOS) Bagus</span>
                    </div>
                </div>

                <a href="<?= base_url('Home/kalkulator') ?>" class="btn btn-outline-primary btn-sm w-100 rounded-2 py-2 fw-medium">
                    <i class="fa-solid fa-up-right-from-square me-1"></i> Buka Kalkulator Lengkap 2D
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tower Table List Formal -->
<div class="row g-3">
    <div class="col-12">
        <div class="gis-card">
            <div class="gis-card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-list text-primary"></i>
                    <span>Daftar Titik Tower BTS & Microwave</span>
                </div>
                <?php if(session()->get('role') === 'admin'): ?>
                    <a href="<?= base_url('Lokasi/inputLokasi') ?>" class="btn btn-sm btn-primary rounded-2 px-3">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Tower Baru
                    </a>
                <?php endif; ?>
            </div>
            <div class="p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-formal border">
                        <thead>
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th>Kode & Nama Tower</th>
                                <th>Alamat Lokasi</th>
                                <th>Koordinat</th>
                                <th>Tinggi</th>
                                <th>Frekuensi</th>
                                <th>Radius</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($lokasi)): ?>
                                <?php foreach($lokasi as $idx => $t): ?>
                                    <tr>
                                        <td class="text-secondary"><?= $idx + 1 ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?= esc($t['nama_lokasi']) ?></div>
                                            <div class="small text-muted font-monospace"><?= esc($t['kode_tower'] ?? 'TWR-' . $t['id_lokasi']) ?> &bull; <?= esc($t['tipe_antena'] ?? 'Microwave Dish') ?></div>
                                        </td>
                                        <td class="small text-secondary" style="max-width: 200px;"><?= esc($t['alamat_lokasi']) ?></td>
                                        <td class="small font-monospace text-dark">
                                            <?= round((float)$t['latitude'], 5) ?>, <?= round((float)$t['longitude'], 5) ?>
                                        </td>
                                        <td class="small text-dark fw-medium"><?= esc($t['tinggi_tower'] ?? 45) ?> m</td>
                                        <td><span class="badge bg-light border text-dark"><?= esc($t['frekuensi'] ?? 5.8) ?> GHz</span></td>
                                        <td><span class="badge bg-light border text-primary fw-bold"><?= number_format((float)($t['radius_jangkauan'] ?? 2500), 0, ',', '.') ?> m</span></td>
                                        <td>
                                            <span class="badge <?= ($t['status'] ?? 'Aktif') === 'Aktif' ? 'bg-success' : (($t['status'] ?? '') === 'Maintenance' ? 'bg-warning text-dark' : 'bg-danger') ?> rounded-pill">
                                                <?= esc($t['status'] ?? 'Aktif') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary rounded-2 px-2 py-1" onclick="focusTower(<?= (float)$t['latitude'] ?>, <?= (float)$t['longitude'] ?>, '<?= esc($t['nama_lokasi']) ?>')">
                                                <i class="fa-solid fa-location-crosshairs"></i> Fokus
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-secondary py-4">Belum ada data lokasi tower terdaftar.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Script for Dashboard -->
<script>
    var map;
    var towerMarkers = [];
    var radiusCircles = [];
    var microwaveLines = [];
    var linksVisible = true;

    document.addEventListener("DOMContentLoaded", function() {
        initOverviewMap();
        runQuickCalc();
    });

    function initOverviewMap() {
        // Free, highly reliable tile providers with NO API KEY requirement
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> kontributor'
        });

        var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

        var esriTopo = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community'
        });

        var osmHot = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors, Tiles style by Humanitarian OpenStreetMap Team'
        });

        // Init map with OpenStreetMap as default
        map = L.map('overviewMap', {
            center: [-6.8844, 107.5419],
            zoom: 13,
            layers: [osm]
        });

        var baseMaps = {
            "OpenStreetMap Standar": osm,
            "Esri Satelit (Foto Udara)": esriSat,
            "Esri Topografi": esriTopo,
            "OpenStreetMap Humaniter": osmHot
        };
        L.control.layers(baseMaps).addTo(map);

        // Tower data from PHP
        var towers = <?= json_encode($lokasi ?? []) ?>;
        var towerCoords = [];

        var towerIcon = L.divIcon({
            className: 'custom-tower-marker',
            html: '<div style="width: 32px; height: 32px; background: #1d4ed8; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 13px;"><i class="fa-solid fa-tower-broadcast"></i></div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16],
            popupAnchor: [0, -16]
        });

        towers.forEach(function(t) {
            var lat = parseFloat(t.latitude);
            var lng = parseFloat(t.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                var latlng = [lat, lng];
                towerCoords.push(latlng);

                // Radius Circle (Professional clean Blue)
                var radiusVal = parseFloat(t.radius_jangkauan) || 2500;
                var circle = L.circle(latlng, {
                    radius: radiusVal,
                    color: '#2563eb',
                    weight: 1.5,
                    opacity: 0.7,
                    fillColor: '#3b82f6',
                    fillOpacity: 0.1
                }).addTo(map);
                radiusCircles.push(circle);

                // Popup
                var popupContent = `
                    <div style="min-width: 220px; font-family: inherit;">
                        <div class="fw-bold text-primary mb-1" style="font-size: 0.95rem;">${t.nama_lokasi}</div>
                        <span class="badge bg-light border text-dark mb-2">${t.kode_tower || 'TWR'}</span>
                        <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 6px;">
                            <i class="fa-solid fa-location-dot me-1 text-danger"></i> ${t.alamat_lokasi}
                        </div>
                        <hr style="margin: 6px 0;">
                        <div style="font-size: 0.8rem; display: flex; justify-content: space-between; margin-bottom: 3px;">
                            <span class="text-secondary">Frekuensi:</span>
                            <strong>${t.frekuensi || 5.8} GHz</strong>
                        </div>
                        <div style="font-size: 0.8rem; display: flex; justify-content: space-between; margin-bottom: 3px;">
                            <span class="text-secondary">Tinggi Tower:</span>
                            <strong>${t.tinggi_tower || 45} m</strong>
                        </div>
                        <div style="font-size: 0.8rem; display: flex; justify-content: space-between; margin-bottom: 3px;">
                            <span class="text-secondary">Radius Coverage:</span>
                            <strong class="text-primary">${radiusVal.toLocaleString('id-ID')} m</strong>
                        </div>
                        <div style="font-size: 0.8rem; display: flex; justify-content: space-between;">
                            <span class="text-secondary">Status:</span>
                            <span class="badge ${t.status === 'Aktif' ? 'bg-success' : 'bg-warning'}">${t.status}</span>
                        </div>
                    </div>
                `;

                var marker = L.marker(latlng, { icon: towerIcon })
                    .bindPopup(popupContent)
                    .addTo(map);

                towerMarkers.push(marker);
            }
        });

        // Draw Microwave links
        if (towerCoords.length > 1) {
            for (var i = 0; i < towerCoords.length - 1; i++) {
                var line = L.polyline([towerCoords[i], towerCoords[i+1]], {
                    color: '#d97706',
                    weight: 2,
                    dashArray: '5, 6',
                    opacity: 0.85
                }).addTo(map);

                var d = (L.latLng(towerCoords[i]).distanceTo(L.latLng(towerCoords[i+1])) / 1000).toFixed(2);
                line.bindTooltip(`Link Microwave: ${d} km`, { sticky: true });
                microwaveLines.push(line);
            }
        }

        if (towerCoords.length > 0) {
            map.fitBounds(L.latLngBounds(towerCoords), { padding: [40, 40] });
        }
    }

    function toggleLinks() {
        linksVisible = !linksVisible;
        microwaveLines.forEach(function(l) {
            if (linksVisible) {
                map.addLayer(l);
            } else {
                map.removeLayer(l);
            }
        });
        document.getElementById('linkBtnText').innerText = linksVisible ? 'Sembunyikan Garis Link' : 'Tampilkan Garis Link';
    }

    function resetMapView() {
        if (towerMarkers.length > 0) {
            var group = new L.featureGroup(towerMarkers);
            map.fitBounds(group.getBounds(), { padding: [50, 50] });
        } else {
            map.setView([-6.8844, 107.5419], 13);
        }
    }

    function focusTower(lat, lng, name) {
        map.flyTo([lat, lng], 15, { duration: 1.2 });
        towerMarkers.forEach(function(m) {
            var mLat = m.getLatLng().lat;
            var mLng = m.getLatLng().lng;
            if (Math.abs(mLat - lat) < 0.0001 && Math.abs(mLng - lng) < 0.0001) {
                m.openPopup();
            }
        });
    }

    function runQuickCalc() {
        var d = parseFloat(document.getElementById('quickDistance').value) || 1;
        var f = parseFloat(document.getElementById('quickFreq').value) || 5.8;

        var r1 = 8.656 * Math.sqrt(d / f);
        var clearance = 0.6 * r1;
        var fspl = 20 * Math.log10(d) + 20 * Math.log10(f) + 92.45;

        document.getElementById('resFresnel').innerText = r1.toFixed(2) + ' m';
        document.getElementById('resClearance').innerText = clearance.toFixed(2) + ' m';
        document.getElementById('resFSPL').innerText = fspl.toFixed(1) + ' dB';

        var statusBadge = document.getElementById('resStatus');
        if (fspl < 125) {
            statusBadge.className = 'badge bg-success';
            statusBadge.innerText = 'Line of Sight (LOS) Sangat Bagus';
        } else if (fspl < 140) {
            statusBadge.className = 'badge bg-warning text-dark';
            statusBadge.innerText = 'LOS Cukup Baik';
        } else {
            statusBadge.className = 'badge bg-danger';
            statusBadge.innerText = 'Redaman Sangat Tinggi';
        }
    }
</script>