<!-- Code php à intégrer en dehors du module afin d'écrire la légende de la carte
<div class="custom"><br />
<ul class="fa-ul">
<li><span class="fa-li"> <span class="fa-layers fa-fw"><i class="fas fa-parking" style="color: green;" data-fa-transform="grow-20"></i> <i class="fa-inverse fas fa-bicycle" data-fa-transform="shrink-5 down-10 right-8"></i> </span></span>   &nbsp;&nbsp;Parkings vélo existants (selon la Mairie de Suresnes)</li>
<br>
<li><span class="fa-li"> <span class="fa-layers fa-fw"> <i class="fas fa-parking" style="color: blue;" data-fa-transform="grow-20"></i> <i class="fa-inverse fas fa-bicycle" data-fa-transform="shrink-5 down-10 right-8"></i> </span></span>  &nbsp;&nbsp;Parkings vélo prévus (selon la Mairie de Suresnes)</li>
</ul>
<br />
</div>
-->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
<link href="/components/com_sppagebuilder/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//use.fontawesome.com/releases/v5.1.0/css/all.css" rel="stylesheet" type="text/css" />

<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>

<style>
		html, body {
			height: 100%;
			margin: 0;
		}
		#map {
			width: 100%;
			height: 850px;
		}
</style>

<!-- fin de head & début de body -->

<div id='map'></div>
	<script defer src="/media/custom/fontawesome-free-5.11.2-web/js/all.js"></script> <!--load all styles -->
	<script src="/media/custom/suresnes_geo.js" type="text/javascript"></script>
	<script src="/media/custom/parking_velo.js" type="text/javascript"></script>

<script>

	var mapbox = 'Maps © <a href="https://www.mapbox.com/">Mapbox</a>, Data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
		thunder = 'Maps © <a href="https://www.thunderforest.com/">Thunderforest</a>, Data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
		cylcosm = 'Maps © <a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',

		mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw',
		tileUrl = 'https://tile.thunderforest.com/cycle/{z}/{x}/{y}{r}.png?apikey=b28a57409cfe4550b0a96821b7074fa3',
		cyclUrl = 'https://dev.{s}.tile.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png';

	var grayscale   = L.tileLayer(mbUrl, {id: 'mapbox.light', attribution: mapbox}),
		streets  = L.tileLayer(mbUrl, {id: 'mapbox.streets',   attribution: mapbox}),
		cycle = L.tileLayer(tileUrl, {attribution: thunder}),
		cyclo = L.tileLayer(cyclUrl, {maxZoom: 20, attribution: cylcosm});

	var map = L.map('map', {
			center: [48.8710, 2.218551],
			zoom: 15,
	        layers: [cyclo]
		});

	var baseLayers = {
		"CyclOSM": cyclo,
		"Noir & Blanc": grayscale,
		"Couleur": streets
		};

function onEachFeature(feature, layer) {
	var popupContent =
		"<p><b>Numéro de  Station</b>: " + feature.properties.id + "<br>" +
		"<b>Nom de la  Station</b>: " + feature.properties.name + "<br>" +
		"<b>Adresse</b>: " + feature.properties.address + "<br>" +
		"<b>Nombre d'arceaux</b>: " + feature.properties.description + "<br>" +
		"</p>" ;
	layer.bindPopup(popupContent);
	}

	var park_blue = L.divIcon({
		html: '<i class="fas fa-parking" style="color:blue" data-fa-transform="grow-20"></i><i class="fa-inverse fas fa-bicycle" data-fa-transform="shrink-5 down-10 right-8"></i>',
		className: 'fa-layers fa-fw'
		});

	var park_green = L.divIcon({
		html: '<i class="fas fa-parking" style="color:green" data-fa-transform="grow-20"></i><i class="fa-inverse fas fa-bicycle" data-fa-transform="shrink-5 down-10 right-8"></i>',
		className: 'fa-layers fa-fw'
		});

	var forecast = L.geoJSON(parking_blue, {onEachFeature: onEachFeature, pointToLayer: function(feature, latlon) {return L.marker(latlon, { icon:  park_blue});}});
		installed = L.geoJSON(parking_green, {onEachFeature: onEachFeature, pointToLayer: function(feature, latlon) {return L.marker(latlon, { icon:  park_green});}}).addTo(map);

	L.tileLayer('https://{s}.tiles.openrailwaymap.org/standard/{z}/{x}/{y}.png').addTo(map);

	var ville = L.geoJSON(suresnes, {  fill: 0, color: 'purple'}).addTo(map),
		suburb = L.geoJSON(quartier, { weight: 2, fill: 0}),
		eq_lmv = L.geoJSON(eqlmv, { weight: 2, fill: 0, color: 'red'});

	var city = {
		"Parkings existants": installed,
		"Parkings prévus": forecast,
		"Suresnes": ville,
		"Quartiers": suburb,
		"limites de l'EQ-LMV": eq_lmv
	};

L.control.layers(baseLayers, city).addTo(map);

	<!-- Watermark-->

	L.Control.Watermark = L.Control.extend({onAdd: function(map) {
		var img = L.DomUtil.create('img');
			img.src = '/images/logos/logoLMV_rectangle.png';
			img.style.width = '200px';
			return img;
		},
			onRemove: function(map) {/* Nothing to do here*/}
		}
	);

	L.control.watermark = function(opts) {return new L.Control.Watermark(opts);}
	L.control.watermark({ position: 'bottomleft' }).addTo(map);

</script>