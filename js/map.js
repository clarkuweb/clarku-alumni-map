	
	window.addEventListener('load', function() {
		const map = L.map('alumni-map', {attributionControl:false, zoomSnap: 0}).setView([22, 0], 2);
		

		
		var bounds = L.latLngBounds([[85, 180],[-35, -180]]);
		var wantedZoom = map.getBoundsZoom(bounds, true);
		var center = bounds.getCenter();
		map.setView(center, wantedZoom);
		
		/* countriesData */
		const geojson = L.geoJson(countriesData, {
			style: styleCountry,
			filter: filterCountries,
			onEachFeature: initCountry
		}).addTo(map);
		
		calculateTotals();

	})
	
	var alumniCountries = 5;
	var alumniTotal = 29286; // US alumni
	
	function calculateTotals() {
		var data = alumniData.regions;

		for(var x in data) {
			if( data[x].alumni ) {
				alumniCountries++;
				alumniTotal += (data[x].alumni * 1);
			}
		}
		
		var p = document.createElement('P');
		p.innerHTML = "<strong>" + formatNumber(alumniTotal) + "</strong> Clark Alumni in <strong>" + alumniCountries + "</strong> countries."
		var m = document.getElementById("alumni-map");
		m.parentNode.insertBefore(p, m);
	}
	
	function formatNumber(n) {
		return (n*1).toLocaleString(
			undefined, 
			{ minimumFractionDigits: 0 }
		);	
	}
	
	function initCountry(feature, layer) {
		var deets = getCountryDetails(feature);
		if(null === deets) {
			return;
		}
		layer.bindPopup( popText(deets) );
	}

	function filterCountries(feature, layer) {
// 		var deets = getCountryDetails(feature);
// 		return (null !== deets);
		
		if("AQ" == feature.id) {
			return false;
		}
		return true;
		
	}

	function popText(deets) {
		var alum = ( 1 == deets.alumni ) ? "Alumnus" : "Alumni";
		return "<p class='intro-copy-with-divider'>" + deets.name + "</p><ul><li>" + formatNumber(deets.alumni) + " Clark " + alum + "</li></ul>";
	}

	function styleCountry(feature) {
		var deets = getCountryDetails(feature);
	
		if( null === deets) {
			return {
				weight: 0,
				fillOpacity: 1,
				fillColor: '#ddd'
			};
		}
	
		return {
			weight: 1,
			opacity: 1,
			color: 'white',
			dashArray: '3',
			fillOpacity: 1,
			fillColor: '#cc0000'
		};
	}

	function getCountryDetails(feature) {
		var countryID = feature.id;
		var data = alumniData.regions;

		for(var x in data){
			if( countryID == data[x].id ) {
				return data[x];
			}
		}
		return null;
	}
