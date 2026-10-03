<div id="map" style="width: 100%; height: 80vh;"></div>


<script>
var peta1 = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'

    });

    var peta2 = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
    });

    var peta3 = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',{
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
    });

    var peta4 = L.tileLayer('https://tileserver.memomaps.de/tilegen/{z}/{x}/{y}.png', {
        attribution:  'Map <a href="https://memomaps.de/">memomaps.de</a> <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

const map = L.map('map', {
	center: [-6.884467614011853, 107.54197971555296],
	zoom: 14,
	layers: [peta2]
    });

const baseLayers = {
	'OpenTopotMap': peta1,
	'OpenStreetMap.HOT': peta2,
    'Carto': peta3,
    'OpenVKARTE': peta4,
    };

//	<!-- Area Halaman Web Auto Panning adalah Kabupaten Solok  -->
var southWest = L.latLng(-0.45523052570373423, 100.247648808454),
	northEast = L.latLng(-0.7865170635145529, 100.81996783932388),
	bounds = L.latLngBounds(southWest, northEast);
    
const layerControl = L.control.layers(baseLayers, null, {
    collapsed: false
}).addTo(map);

//pemetaan lokasi
    const gedung = L.icon({
    iconUrl: '<?= base_url('marker/tower1.svg')?>',
    iconSize:     [38, 95], // size of the icon
    });

<?php  foreach ($lokasi as $key => $value) { ?>
    L.marker([<?= $value['latitude'] ?>, <?= $value['longitude'] ?> ], {
        icon : gedung
    })
    .bindPopup ('<img src= "<?= base_url('foto/' . $value ['foto_lokasi']) ?>" width="250px" ><br>' +
    '<b><?= $value ['nama_lokasi'] ?> </b><br>' + 
        'Alaamat : <?= $value['alamat_lokasi'] ?> <br>')
    .addTo(map);
<?php } ?>


</script>