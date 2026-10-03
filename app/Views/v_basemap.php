<div class="gis-card">
    <div class="gis-card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-layer-group text-primary"></i>
            <span>Pilihan Base Map Layer (OpenStreetMap, Satelit, Topografi)</span>
        </div>
        <div class="small text-secondary">
            Gunakan kontrol layer di kanan atas untuk mengganti tampilan dasar peta.
        </div>
    </div>
    <div class="p-3">
        <div id="baseMapDiv" style="width: 100%; height: 72vh; border-radius: 6px;"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        });

        var esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });

        var esriTopo = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });

        var osmHot = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors, Humanitarian Team'
        });

        var map = L.map('baseMapDiv', {
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

        L.control.layers(baseMaps, null, { collapsed: false }).addTo(map);
    });
</script>