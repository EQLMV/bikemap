<div id='map'></div>

<script src="/media/media/js/jardins_partages.js" type="text/javascript"></script>
<script src="/media/media/js/suresnes_geo.js" type="text/javascript"></script>
<script src="/media/media/js/parking_velo.js" type="text/javascript"></script>

<script>

var mbAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

	var grayscale   = L.tileLayer(mbUrl, {id: 'mapbox.light', attribution: mbAttr}),
		streets  = L.tileLayer(mbUrl, {id: 'mapbox.streets',   attribution: mbAttr});
//           var     stamen = L.tileLayer.provider('Stamen.Watercolor');

	var map = L.map('map', {
		center: [48.8710, 2.218551],
		zoom: 15,
	        layers: [grayscale]
//		layers: [Stamen.Watercolor]
	});

	var baseLayers = {
		"Noir & Blanc": grayscale,
		"Couleur": streets
	};

/*
var Thunderforest_OpenCycleMap = L.tileLayer('https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey={apikey}', {
	attribution: '&copy; <a href="http://www.thunderforest.com/">Thunderforest</a>, &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
	apikey: '<your apikey>',
	maxZoom: 22
});*/

function onEachFeature(feature, layer) {
		var popupContent = 
"<p><b>Nom de la  Station</b>: " + feature.properties.name + "<br>" +
"<b>Adresse</b>: " + feature.properties.address + "<br>" +
"<b>Nombre d'arceaux</b>: " + feature.properties.description + "<br>" +
"</p>" ;
		layer.bindPopup(popupContent);
	}

var ville = L.geoJSON(suresnes, {  fill: 0});
//var suburb = L.geoJSON(quartier, { weight: 2, fillOpacity: 0});
var suburb = L.geoJSON(quartier, { weight: 2, fill: 0});
var eq_lmv = L.geoJSON(eqlmv, { weight: 2, fill: 0, color: 'red'});


var city = {
"Suresnes": ville,
"Quartiers": suburb,
"EQ-LMV": eq_lmv
 };

L.control.layers(baseLayers, city).addTo(map);


/*L.geoJSON(jardins_partages, { weight: 2, color: '#f19315', fillColor: '#f19315', fillOpacity: 0.7}).addTo(map).bindPopup('Parcours du quartier Ecluse-Belvédère');


var fontAwesomeIcon = L.divIcon({
    html: '<i class="fas fa-tree" data-fa-transform="grow-20" style="color:green""></i>',
    className: 'myDivIcon'
});

L.marker([48.87103, 2.22532],{ icon:  fontAwesomeIcon}).addTo(map)
    .bindPopup('A pretty CSS3 popup.<br> Easily customizable.') */

var park_blue = L.divIcon({
    html: '<i class="fas fa-parking" style="color:blue" data-fa-transform="grow-20"></i><i class="fa-inverse fas fa-bicycle" data-fa-transform="shrink-5 down-10 right-8"></i>',
    className: 'fa-layers fa-fw'
});

var park_green = L.divIcon({
    html: '<i class="fas fa-parking" style="color:green" data-fa-transform="grow-20"></i><i class="fa-inverse fas fa-bicycle" data-fa-transform="shrink-5 down-10 right-8"></i>',
    className: 'fa-layers fa-fw'
});


//L.marker([48.87, 2.225],{ icon:  park_blue}).addTo(map);


L.geoJSON(parking_blue, {
onEachFeature: onEachFeature,
    pointToLayer: function(feature, latlon) {
  return L.marker(latlon, { icon:  park_blue});
}
}).addTo(map);

L.geoJSON(parking_green, {
onEachFeature: onEachFeature,
    pointToLayer: function(feature, latlon) {
  return L.marker(latlon, { icon:  park_green});
}
}).addTo(map);

//L.geoJSON(departs, { onEachFeature: onEachFeature}).addTo(map);

  <!-- Watermark-->

	L.Control.Watermark = L.Control.extend({
		onAdd: function(map) {
			var img = L.DomUtil.create('img');
			
			img.src = '/images/logos/logoLMV_rectangle.png';
			img.style.width = '200px';
			
			return img;
		},
		
		onRemove: function(map) {
			// Nothing to do here
		}
	});

	L.control.watermark = function(opts) {
		return new L.Control.Watermark(opts);
	}
	
	L.control.watermark({ position: 'bottomleft' }).addTo(map);

</script>
