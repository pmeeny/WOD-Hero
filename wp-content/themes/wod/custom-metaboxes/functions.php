<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_sample_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_wod_';

    $meta_boxes['workout_details'] = array(
        'id'         => 'workout_options',
        'title'      => __( 'Workout Options', 'cmb' ),
        'pages'      => array( 'workout', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields'     => array(
            array(
                'name'    => __( 'Weight Unit', 'cmb' ),
                'desc'    => __( 'Muliple Choice', 'cmb' ),
                'id'      => $prefix . 'weight_unit',
                'type'    => 'multicheck',
                'options' => array(
                    'kg' => __( 'Kg', 'cmb' ),
                    'lb' => __( 'lb', 'cmb' ),
                    'km' => __( 'Km', 'cmb' ),
                    'cm' => __( 'Cm', 'cmb' ),
                    'inch' => __( 'Inch', 'cmb' ),
					'metres' => __( 'Metres', 'cmb' ),
					'calories' => __( 'Calories', 'cmb' ),
                ),
                // 'inline'  => true, // Toggles display to inline
            ),

			array(
                'name'    => __( 'Reps', 'cmb' ),
                'desc'    => __( 'Select one option', 'cmb' ),
                'id'      => $prefix . 'reps',
                'type'    => 'radio',
                'options' => array(
                    'yes' => __( 'Yes', 'cmb' ),
                    'no' => __( 'No', 'cmb' ),
                ),
            ),
            array(
                'name'    => __( 'Time', 'cmb' ),
                'desc'    => __( 'Select one option', 'cmb' ),
                'id'      => $prefix . 'times',
                'type'    => 'radio',
                'options' => array(
                    'yes' => __( 'Yes', 'cmb' ),
                    'no' => __( 'No', 'cmb' ),
                ),
            ),
        ),
    );

    $meta_boxes['gym_details'] = array(
        'id'         => 'gym_detail',
        'title'      => __( 'Gym Detail', 'cmb' ),
        'pages'      => array( 'gym', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields'     => array(
            array(
                'name' => __( 'Address', 'cmb' ),
                'desc' => __( 'Please enter gym address (Optional)', 'cmb' ),
                'id'   => $prefix . 'gym_address',
                'type' => 'textarea_small',
            )
        ),
    );

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}

require_once 'cmb-field-map.php';