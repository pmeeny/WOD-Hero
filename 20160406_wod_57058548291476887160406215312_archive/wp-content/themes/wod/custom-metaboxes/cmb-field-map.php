<?php
// Useful global constants
//define( 'PW_GOOGLE_MAPS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Render field
 */
function pw_map_field( $field, $meta ) {
	wp_enqueue_script( 'pw_google_maps_api', 'http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places', array(), null );
	wp_enqueue_script( 'pw_google_maps_init', CMB_META_BOX_URL . 'js/script.js', array( 'pw_google_maps_api' ), null );
	wp_enqueue_style( 'pw_google_maps_css', CMB_META_BOX_URL . 'css/style.css', array(), null );

	echo '<input type="text" class="map-search" id="' . $field['id'] . '" />';
	echo '<div class="map"></div>';
	echo '<input type="hidden" class="latitude" name="' . $field['id'] . '[latitude]" value="' . ( isset( $meta['latitude'] ) ? $meta['latitude'] : '' ) . '" />';
	echo '<input type="hidden" class="longitude" name="' . $field['id'] . '[longitude]" value="' . ( isset( $meta['longitude'] ) ? $meta['longitude'] : '' ) . '" />';
	echo '<input type="hidden" class="address" name="' . $field['id'] . '[address]" value="' . ( isset( $meta['address'] ) ? $meta['address'] : '' ) . '" />';

	if ( ! empty( $field['desc'] ) ) echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
}
add_filter( 'cmb_render_pw_map', 'pw_map_field', 10, 2 );

/**
 * Split latitude/longitude values into two meta fields
 */
function pw_map_sanitise( $meta_value, $field ) {
	$latitude = $meta_value['latitude'];
	$longitude = $meta_value['longitude'];
	$address = $meta_value['address'];

	if(! empty( $latitude )){
		update_post_meta( get_the_ID(), $field['id'] .'_pro_address', $address);
	}

	if ( ! empty( $latitude ) ) {
		update_post_meta( get_the_ID(), $field['id'] . '_latitude', $latitude );
	}

	if ( ! empty( $longitude ) ) {
		update_post_meta( get_the_ID(), $field['id'] . '_longitude', $longitude );
	}

	return $meta_value;
}