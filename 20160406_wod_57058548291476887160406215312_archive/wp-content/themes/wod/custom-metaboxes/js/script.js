(function ($) {

 	$('.cmb-type-pw_map').each(function() {
		var searchInput = $('.map-search', this).get(0);
		var mapCanvas = $('.map', this).get(0);
		var latitude = $('.latitude', this);
		var longitude = $('.longitude', this);
        var pro_address = $('.address', this);
		var latLng = new google.maps.LatLng(54.800685, -4.130859);
		var zoom = 5;
        var geocoder;
        geocoder = new google.maps.Geocoder(); // Get Address From latitude and longitude

        // Map
		if(latitude.val().length > 0 && longitude.val().length > 0) {
			latLng = new google.maps.LatLng(latitude.val(), longitude.val());
			zoom = 17;
		}

		var mapOptions = {
			center: latLng,
			zoom: zoom,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(mapCanvas, mapOptions);


		// Marker
		var markerOptions = {
			map: map,
            draggable: true,
			title: 'Drag to set the exact location'
		};
		var marker = new google.maps.Marker(markerOptions);

		if(latitude.val().length > 0 && longitude.val().length > 0) {
			marker.setPosition(latLng);
		}
      //  alert("hello");
		google.maps.event.addListener(marker, 'drag', function() {
			latitude.val(marker.getPosition().lat());
			longitude.val(marker.getPosition().lng());
		});

        var infowindow = new google.maps.InfoWindow({
            maxWidth: 200,
            maxHeight: 200
        });

        // Get Address From latitude and longitude
        if(latitude.val().length > 0 && longitude.val().length > 0) {
            geocoder.geocode({'latLng': latLng}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        infowindow.setContent(results[1].formatted_address);
                        pro_address.val(results[1].formatted_address);
                        infowindow.open(map, marker);
                    } else {
                        alert('No results found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });
        }

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker);
        });

		// Search
		var autocomplete = new google.maps.places.Autocomplete(searchInput);
		autocomplete.bindTo('bounds', map);

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);
			}

            marker.setIcon(/** @type {google.maps.Icon} */({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));

            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }


            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);

            pro_address.val(place.name);
            latitude.val(place.geometry.location.lat());
			longitude.val(place.geometry.location.lng());
		});

		$(searchInput).keypress(function(e) {
			if(e.keyCode == 13) {
				e.preventDefault();
			}
		});
    });

}(jQuery));