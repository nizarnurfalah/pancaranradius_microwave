<div class="row g-3">
    <!-- Map Column -->
    <div class="col-lg-7">
        <div class="gis-card h-100">
            <div class="gis-card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-map-pin text-primary"></i>
                    <span>Ubah Posisi Koordinat & Radius</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-2 px-3" onclick="getCurrentLocation()">
                    <i class="fa-solid fa-location-crosshairs me-1"></i> Posisi Saya Saat Ini
                </button>
            </div>
            <div class="p-3">
                <div id="editMap" style="width: 100%; height: 550px; border-radius: 6px;"></div>
                <div class="small text-secondary mt-2 text-center">
                    <i class="fa-solid fa-circle-info text-primary me-1"></i> Geser marker atau klik pada peta untuk menyesuaikan posisi tower.
                </div>
            </div>
        </div>
    </div>

    <!-- Form Column -->
    <div class="col-lg-5">
        <div class="gis-card">
            <div class="gis-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                    <span>Edit Informasi Tower Microwave</span>
                </div>
            </div>
            <div class="p-3">
                <?= form_open_multipart('Lokasi/updateData/' . $lokasi['id_lokasi']) ?>
                <?= csrf_field() ?>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small text-secondary fw-semibold">Kode Tower</label>
                        <input type="text" class="form-control form-control-sm" name="kode_tower" value="<?= old('kode_tower', $lokasi['kode_tower'] ?? 'TWR-' . $lokasi['id_lokasi']) ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label small text-secondary fw-semibold">Status Operasional</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="Aktif" <?= ($lokasi['status'] ?? '') === 'Aktif' ? 'selected' : '' ?>>Aktif (Online)</option>
                            <option value="Maintenance" <?= ($lokasi['status'] ?? '') === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            <option value="Non-Aktif" <?= ($lokasi['status'] ?? '') === 'Non-Aktif' ? 'selected' : '' ?>>Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Nama Titik Tower / Site <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="nama_lokasi" required value="<?= old('nama_lokasi', $lokasi['nama_lokasi']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea class="form-control form-control-sm" name="alamat_lokasi" rows="2" required><?= old('alamat_lokasi', $lokasi['alamat_lokasi']) ?></textarea>
                </div>

                <!-- Coordinates -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small text-dark fw-semibold">Latitude <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm font-monospace" name="latitude" id="latInput" required value="<?= old('latitude', $lokasi['latitude']) ?>" oninput="syncFromInput()">
                    </div>
                    <div class="col-6">
                        <label class="form-label small text-dark fw-semibold">Longitude <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm font-monospace" name="longitude" id="lngInput" required value="<?= old('longitude', $lokasi['longitude']) ?>" oninput="syncFromInput()">
                    </div>
                </div>

                <!-- Microwave & RF Specs -->
                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <label class="form-label small text-secondary fw-semibold">Tinggi (m)</label>
                        <input type="number" class="form-control form-control-sm" name="tinggi_tower" value="<?= old('tinggi_tower', $lokasi['tinggi_tower'] ?? 45) ?>" min="1" step="0.5">
                    </div>
                    <div class="col-4">
                        <label class="form-label small text-secondary fw-semibold">Frekuensi (GHz)</label>
                        <input type="number" class="form-control form-control-sm" name="frekuensi" value="<?= old('frekuensi', $lokasi['frekuensi'] ?? 5.8) ?>" step="0.1" min="0.1">
                    </div>
                    <div class="col-4">
                        <label class="form-label small text-secondary fw-semibold">Daya (dBm)</label>
                        <input type="number" class="form-control form-control-sm" name="daya_pancar" value="<?= old('daya_pancar', $lokasi['daya_pancar'] ?? 27) ?>" step="1">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-dark fw-semibold">Radius Jangkauan Coverage (Meter) <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" name="radius_jangkauan" id="radiusInput" value="<?= old('radius_jangkauan', $lokasi['radius_jangkauan'] ?? 2500) ?>" min="50" step="50" oninput="updateRadiusPreview()">
                        <span class="input-group-text bg-light">Meter</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold">Tipe Antena</label>
                    <select name="tipe_antena" class="form-select form-select-sm">
                        <option value="Solid Dish Microwave (0.6m)" <?= ($lokasi['tipe_antena'] ?? '') === 'Solid Dish Microwave (0.6m)' ? 'selected' : '' ?>>Solid Dish Microwave (0.6m)</option>
                        <option value="High Performance Microwave Dish (1.2m)" <?= ($lokasi['tipe_antena'] ?? '') === 'High Performance Microwave Dish (1.2m)' ? 'selected' : '' ?>>High Performance Microwave Dish (1.2m)</option>
                        <option value="Dual Polarized Microwave Dish" <?= ($lokasi['tipe_antena'] ?? '') === 'Dual Polarized Microwave Dish' ? 'selected' : '' ?>>Dual Polarized Microwave Dish</option>
                        <option value="Sectoral Microwave Antenna" <?= ($lokasi['tipe_antena'] ?? '') === 'Sectoral Microwave Antenna' ? 'selected' : '' ?>>Sectoral Microwave Antenna</option>
                        <option value="Omnidirectional BTS Antenna" <?= ($lokasi['tipe_antena'] ?? '') === 'Omnidirectional BTS Antenna' ? 'selected' : '' ?>>Omnidirectional BTS Antenna</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small text-secondary fw-semibold">Foto Lokasi</label>
                    <?php if(!empty($lokasi['foto_lokasi'])): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('foto/' . $lokasi['foto_lokasi']) ?>" alt="Foto" class="rounded border" style="height: 45px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control form-control-sm" name="foto_lokasi" accept="image/*">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill rounded-2 py-2 fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Perbarui Data Tower
                    </button>
                    <a href="<?= base_url('Lokasi') ?>" class="btn btn-outline-secondary rounded-2 px-3 py-2">
                        Batal
                    </a>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
    var map, marker, radiusCircle;

    document.addEventListener("DOMContentLoaded", function() {
        initEditMap();
    });

    function initEditMap() {
        var defaultLat = parseFloat(document.getElementById('latInput').value) || -6.884467;
        var defaultLng = parseFloat(document.getElementById('lngInput').value) || 107.541979;
        var defaultRadius = parseFloat(document.getElementById('radiusInput').value) || 2500;

        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });
        var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });

        map = L.map('editMap', {
            center: [defaultLat, defaultLng],
            zoom: 14,
            layers: [osm]
        });

        L.control.layers({ "OpenStreetMap": osm, "Esri Satelit": esriSat }).addTo(map);

        var towerIcon = L.divIcon({
            className: 'tower-edit-icon',
            html: '<div style="width:34px;height:34px;background:#1d4ed8;border:2.5px solid #ffffff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;box-shadow:0 2px 6px rgba(0,0,0,0.3);"><i class="fa-solid fa-tower-broadcast"></i></div>',
            iconSize: [34, 34],
            iconAnchor: [17, 17]
        });

        marker = L.marker([defaultLat, defaultLng], { draggable: true, icon: towerIcon }).addTo(map);

        radiusCircle = L.circle([defaultLat, defaultLng], {
            radius: defaultRadius,
            color: '#2563eb',
            weight: 1.5,
            fillColor: '#3b82f6',
            fillOpacity: 0.1
        }).addTo(map);

        marker.on('drag', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            document.getElementById('latInput').value = lat.toFixed(6);
            document.getElementById('lngInput').value = lng.toFixed(6);
            radiusCircle.setLatLng([lat, lng]);
        });

        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            marker.setLatLng([lat, lng]);
            radiusCircle.setLatLng([lat, lng]);
            document.getElementById('latInput').value = lat.toFixed(6);
            document.getElementById('lngInput').value = lng.toFixed(6);
        });
    }

    function updateRadiusPreview() {
        var r = parseFloat(document.getElementById('radiusInput').value) || 2500;
        if (radiusCircle) {
            radiusCircle.setRadius(r);
        }
    }

    function syncFromInput() {
        var lat = parseFloat(document.getElementById('latInput').value);
        var lng = parseFloat(document.getElementById('lngInput').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            radiusCircle.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
        }
    }

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                marker.setLatLng([lat, lng]);
                radiusCircle.setLatLng([lat, lng]);
                map.flyTo([lat, lng], 15);
                document.getElementById('latInput').value = lat.toFixed(6);
                document.getElementById('lngInput').value = lng.toFixed(6);
            }, function() {
                Swal.fire('Info', 'Tidak dapat mengambil lokasi GPS.', 'info');
            });
        }
    }
</script>
