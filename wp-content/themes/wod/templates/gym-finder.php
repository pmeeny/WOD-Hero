<?php
/* Template Name: Gym Finder */

if(!(is_user_logged_in() )) {
    wp_redirect(site_url().'/login');
    exit();
}
$user_detail = wp_get_current_user();
if($user_detail->roles[0] == 'trainer'){
    wp_redirect(site_url().'/settings');
    exit();
}
get_header(); ?>

<head>

</head>

   <div class="clear"></div>

    <div class="bridcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url(); ?>">Home</a></li>
                <li class="active"><a><?php the_title(); ?></a></li>
            </ol>
        </div>
    </div>

    <div class="container">
        <div class="inner-content">
            <h2><?php if (get_post_meta(get_the_ID(), 'Title', true)) {
    echo get_post_meta(get_the_ID(), 'Title', true);
} else {
    echo get_the_title();
} ?></h2>
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <?php get_sidebar(); ?>
                </div>
                <div class="col-md-8 col-sm-8">

                    	<div class="map-responsive" id="map">


<?php echo do_shortcode('[google_maps id="395"]');?>


<!---

    <script src="https://maps.googleapis.com/maps/api/place/textsearch/json?query=restaurants+in+Dublin&key=AIzaSyD7Vdh3ndfddbeHeNt-ID-yTbPWxt-KGGk">
		
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7Vdh3ndfddbeHeNt-ID-yTbPWxt-KGGk&query=restaurants+in+Dublin&callback=initMap" async defer></script>
		
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7Vdh3ndfddbeHeNt-ID-yTbPWxt-KGGk&libraries=places&callback=initMap" async defer></script>
		
	<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
	<script>

		var map = L.map('map').setView([53.274, -6.256], 8);

		L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoicGF1bG1lZW5lZ2hhbiIsImEiOiJjaW11dzBwMXowMDhjdm5seThtMnFiNTE0In0.qfRDCyVqWEe9vjGqaafAOA', {
			maxZoom: 20,
			attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
				'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
				'Imagery © <a href="http://mapbox.com">Mapbox</a>',
			id: 'mapbox.streets'
		}).addTo(map);


L.marker([53.4222,-7.9713]).addTo(map).bindPopup("<b>Crossfit Cu Chulainn</b><br />I am a popup. for more info, click on the link");     
L.marker([53.288,-7.5209]).addTo(map).bindPopup("<b>CrossFit Tullamore</b><br />I am a popup.");
L.marker([40.3604,-74.2894]).addTo(map).bindPopup("<b>CrossFit Persist</b><br />I am a popup. for more info, click on the link"); 
L.marker([-35.727,174.3274]).addTo(map).bindPopup("<b>Far North CrossFit</b><br />I am a popup. for more info, click on the link"); 
L.marker([42.4787,-90.7068]).addTo(map).bindPopup("<b>CrossFit Dubuque</b><br />I am a popup. for more info, click on the link"); 
L.marker([38.8838,-77.1172]).addTo(map).bindPopup("<b>Ballston CrossFit</b><br />I am a popup. for more info, click on the link"); 
L.marker([-37.9423,145.062]).addTo(map).bindPopup("<b>CrossFit Moorabbin</b><br />I am a popup. for more info, click on the link"); 

		/*var popup = L.popup(); */

		/*function onMapClick(e) {
			popup
				.setLatLng(e.latlng)
				.setContent("You clicked the map at " + e.latlng.toString())
				.openOn(map);
		}

		map.on('click', onMapClick); */

	</script>
    -->                
                    </div>
                </div>
                </div>
                     </div>
        
            <?php get_footer(); ?>