<div class="gis-card">
    <div class="gis-card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-map-pin text-primary"></i>
            <span>Visualisasi Marker & Titik Tower Antena</span>
        </div>
        <div class="small text-secondary">
            Klik pada setiap marker tower untuk melihat informasi spesifikasi dan jangkauan.
        </div>
    </div>
    <div class="p-3">
        <div id="markerMap" style="width: 100%; height: 72vh; border-radius: 6px;"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });
        var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });

        var map = L.map('markerMap', {
            center: [-6.8844, 107.5419],
            zoom: 13,
            layers: [osm]
        });

        L.control.layers({ "OpenStreetMap": osm, "Esri Satelit": esriSat }).addTo(map);

        var towers = <?= json_encode($lokasi ?? []) ?>;
        var markers = [];

        var towerIcon = L.divIcon({
            className: 'tower-marker',
            html: '<div style="width:32px;height:32px;background:#1d4ed8;border:2.5px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;box-shadow:0 2px 5px rgba(0,0,0,0.3);"><i class="fa-solid fa-tower-broadcast"></i></div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16],
            popupAnchor: [0, -16]
        });

        towers.forEach(function(t) {
            var lat = parseFloat(t.latitude);
            var lng = parseFloat(t.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                var rad = parseFloat(t.radius_jangkauan) || 2500;
                
                L.circle([lat, lng], {
                    radius: rad,
                    color: '#2563eb',
                    weight: 1.5,
                    fillColor: '#3b82f6',
                    fillOpacity: 0.08
                }).addTo(map);

                var m = L.marker([lat, lng], { icon: towerIcon })
                    .bindPopup(`
                        <div style="min-width:220px; font-family:inherit;">
                            <h6 class="fw-bold text-primary mb-1">${t.nama_lokasi}</h6>
                            <span class="badge bg-light border text-dark mb-2">${t.kode_tower || 'TWR'}</span>
                            <div class="small text-secondary mb-2">${t.alamat_lokasi}</div>
                            <div class="small border-top pt-2 text-dark">
                                <div><strong>Frekuensi:</strong> ${t.frekuensi || 5.8} GHz</div>
                                <div><strong>Radius:</strong> ${rad} meter</div>
                                <div><strong>Tinggi:</strong> ${t.tinggi_tower || 45} m</div>
                            </div>
                        </div>
                    `)
                    .addTo(map);
                markers.push(m);
            }
        });

        if (markers.length > 0) {
            var group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds(), { padding: [50, 50] });
        }
    });
</script>