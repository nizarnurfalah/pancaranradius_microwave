<div class="gis-card mb-4">
    <!-- Map Controls & Filter Header -->
    <div class="gis-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-map-location-dot text-primary"></i>
            <span>Peta Sebaran Tower & Radius Jangkauan Transmisi</span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <input type="text" id="mapSearchInput" class="form-control form-control-sm" placeholder="Cari nama/kode tower..." style="width: 200px;" oninput="filterMarkers()">
            
            <select id="mapFreqFilter" class="form-select form-select-sm" style="width: 150px;" onchange="filterMarkers()">
                <option value="all">Semua Frekuensi</option>
                <option value="2.4">2.4 GHz</option>
                <option value="5.8">5.8 GHz</option>
                <option value="7.5">7.5 GHz</option>
                <option value="11.0">11.0 GHz</option>
                <option value="18.0">18.0 GHz</option>
            </select>

            <button class="btn btn-sm btn-outline-secondary" id="btnToggleLinks" onclick="toggleLinks()">
                <i class="fa-solid fa-draw-polygon me-1 text-warning"></i> <span id="linkText">Sembunyikan Garis Link</span>
            </button>
            <button class="btn btn-sm btn-outline-secondary" onclick="fitAllTowers()">
                <i class="fa-solid fa-expand me-1"></i> Fit All
            </button>
        </div>
    </div>

    <!-- Map Body -->
    <div class="p-3">
        <div id="fullMap" style="width: 100%; height: 75vh; border-radius: 6px;"></div>
    </div>
</div>

<script>
    var map;
    var markersData = [];
    var radiusCircles = [];
    var linkLines = [];
    var showLinks = true;

    var towers = <?= json_encode($lokasi ?? []) ?>;

    document.addEventListener("DOMContentLoaded", function() {
        initFullMap();
    });

    function initFullMap() {
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });
        var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });
        var esriTopo = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });

        map = L.map('fullMap', {
            center: [-6.8844, 107.5419],
            zoom: 13,
            layers: [osm]
        });

        L.control.layers({
            "OpenStreetMap Standar": osm,
            "Esri Satelit": esriSat,
            "Esri Topografi": esriTopo
        }).addTo(map);

        renderTowers();
        fitAllTowers();
    }

    function renderTowers() {
        markersData.forEach(m => map.removeLayer(m.marker));
        radiusCircles.forEach(c => map.removeLayer(c));
        linkLines.forEach(l => map.removeLayer(l));
        markersData = [];
        radiusCircles = [];
        linkLines = [];

        var coords = [];

        towers.forEach(function(t) {
            var lat = parseFloat(t.latitude);
            var lng = parseFloat(t.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                var latlng = [lat, lng];
                coords.push(latlng);

                var radius = parseFloat(t.radius_jangkauan) || 2500;
                var circle = L.circle(latlng, {
                    radius: radius,
                    color: '#2563eb',
                    weight: 1.5,
                    opacity: 0.7,
                    fillColor: '#3b82f6',
                    fillOpacity: 0.08
                }).addTo(map);
                radiusCircles.push(circle);

                var iconHtml = `
                    <div style="width:32px;height:32px;background:#1d4ed8;border:2.5px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;box-shadow:0 2px 5px rgba(0,0,0,0.3);">
                        <i class="fa-solid fa-tower-broadcast"></i>
                    </div>
                `;
                var customIcon = L.divIcon({
                    className: 'custom-tower-div',
                    html: iconHtml,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                    popupAnchor: [0, -16]
                });

                var photoHtml = t.foto_lokasi ? `<img src="<?= base_url('foto/') ?>/${t.foto_lokasi}" style="width:100%;height:120px;object-fit:cover;border-radius:6px;margin-bottom:8px;">` : '';

                var popup = `
                    <div style="min-width:240px;font-family:inherit;">
                        ${photoHtml}
                        <div class="fw-bold text-primary" style="font-size:1rem;">${t.nama_lokasi}</div>
                        <span class="badge bg-light border text-dark mb-2">${t.kode_tower || 'TWR'}</span>
                        <div class="small text-secondary mb-2"><i class="fa-solid fa-location-dot text-danger me-1"></i>${t.alamat_lokasi}</div>
                        <div style="font-size:0.8rem;background:#f8fafc;padding:8px;border-radius:6px;border:1px solid #e2e8f0;color:#0f172a;">
                            <div><strong>Frekuensi:</strong> ${t.frekuensi || 5.8} GHz</div>
                            <div><strong>Tinggi Tower:</strong> ${t.tinggi_tower || 45} meter</div>
                            <div><strong>Radius Coverage:</strong> ${radius.toLocaleString('id-ID')} meter</div>
                            <div><strong>Tipe Antena:</strong> ${t.tipe_antena || 'Microwave Dish'}</div>
                            <div><strong>Status:</strong> <span class="badge ${t.status === 'Aktif' ? 'bg-success' : 'bg-warning'}">${t.status}</span></div>
                        </div>
                    </div>
                `;

                var marker = L.marker(latlng, { icon: customIcon })
                    .bindPopup(popup)
                    .addTo(map);

                markersData.push({
                    tower: t,
                    marker: marker,
                    circle: circle
                });
            }
        });

        if (coords.length > 1) {
            for (var i = 0; i < coords.length - 1; i++) {
                var line = L.polyline([coords[i], coords[i+1]], {
                    color: '#d97706',
                    weight: 2,
                    dashArray: '5, 6',
                    opacity: 0.85
                });
                var d = (L.latLng(coords[i]).distanceTo(L.latLng(coords[i+1])) / 1000).toFixed(2);
                line.bindTooltip(`Link Microwave: ${d} km`, { sticky: true });
                if (showLinks) line.addTo(map);
                linkLines.push(line);
            }
        }
    }

    function toggleLinks() {
        showLinks = !showLinks;
        linkLines.forEach(function(l) {
            if (showLinks) {
                map.addLayer(l);
            } else {
                map.removeLayer(l);
            }
        });
        document.getElementById('linkText').innerText = showLinks ? 'Sembunyikan Garis Link' : 'Tampilkan Garis Link';
    }

    function filterMarkers() {
        var q = document.getElementById('mapSearchInput').value.toLowerCase();
        var f = document.getElementById('mapFreqFilter').value;

        markersData.forEach(function(item) {
            var matchSearch = item.tower.nama_lokasi.toLowerCase().includes(q) || (item.tower.kode_tower && item.tower.kode_tower.toLowerCase().includes(q));
            var matchFreq = (f === 'all') || (parseFloat(item.tower.frekuensi) == parseFloat(f));

            if (matchSearch && matchFreq) {
                map.addLayer(item.marker);
                map.addLayer(item.circle);
            } else {
                map.removeLayer(item.marker);
                map.removeLayer(item.circle);
            }
        });
    }

    function fitAllTowers() {
        var group = [];
        markersData.forEach(function(m) {
            if (map.hasLayer(m.marker)) {
                group.push(m.marker);
            }
        });
        if (group.length > 0) {
            var fg = new L.featureGroup(group);
            map.fitBounds(fg.getBounds(), { padding: [50, 50] });
        }
    }
</script>