<?php
add_post_type_support('page',array('excerpt')); 
add_theme_support('post-thumbnails');
add_theme_support('post-thumbnails',array('post','workout'));

//add_post_type_support('testimonial',array('thumbnail')); 

class Wti_Custom_Nav_Menu_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'description' => __('Use this widget to add one of your custom menus as a widget in Footer Section.') );
        parent::__construct( 'custom_nav_menu', __('Footer Menu Widget'), $widget_ops );
    }

    function widget($args, $instance) {
        // Get menu
        $nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

        if ( !$nav_menu )
            return;

        $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        echo $args['before_widget'];

        if ( !empty($instance['title']) )
            echo $args['before_title'] . $instance['title'] . $args['after_title'];

        wp_nav_menu(
                array(
                    'fallback_cb' => '',
                    'container' => '',
                    'menu_class' => $instance['menu_class'],
                    'menu' => $nav_menu
                )
            );

        echo $args['after_widget'];
    }

    function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags ( stripslashes ( $new_instance['title'] ) );
        $instance['menu_class'] = strip_tags ( stripslashes ( trim ( $new_instance['menu_class'] ) ) );
        $instance['nav_menu'] = (int) $new_instance['nav_menu'];

        return $instance;
    }

    function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : '';
        $menu_class = isset( $instance['menu_class'] ) ? $instance['menu_class'] : '';
        $nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

        // Get menus
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

        // If no menus exists, direct the user to go and create some.
        if ( !$menus ) {
            echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
            return;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
        </p>
        
	<?php /*?>        
		<p>
            <label for="<?php echo $this->get_field_id('menu_class'); ?>"><?php _e('Menu Class:') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('menu_class'); ?>" name="<?php echo $this->get_field_name('menu_class'); ?>" value="<?php echo $menu_class; ?>" />
        </p>
	<?php */?>        

		<p>
            <label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
            <select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
        <?php
            foreach ( $menus as $menu ) {
                echo '<option value="' . $menu->term_id . '"'
                    . selected( $nav_menu, $menu->term_id, false )
                    . '>'. $menu->name . '</option>';
            }
        ?>
            </select>
        </p>
        <?php
    }}
	
function wti_custom_nav_menu_widget() {
    register_widget('Wti_Custom_Nav_Menu_Widget');
}
add_action ( 'widgets_init', 'wti_custom_nav_menu_widget', 1 );
	
/*add_action( 'init', 'register_taxonomy_page_category' );
function register_taxonomy_page_category() {

   		$labels = array( 
        'name' => _x( 'Page Category', 'page_category' ),
        'singular_name' => _x( 'Page Category', 'page_category' ),
        'search_items' => _x( 'Search Page Category', 'page_category' ),
        'popular_items' => _x( 'Popular Page Category', 'page_category' ),
        'all_items' => _x( 'All Page Category', 'page_category' ),
        'parent_item' => _x( 'Parent Page Category', 'page_category' ),
        'parent_item_colon' => _x( 'Parent Page Category:', 'page_category' ),
        'edit_item' => _x( 'Edit Page Category', 'page_category' ),
        'update_item' => _x( 'Update Page Category', 'page_category' ),
        'add_new_item' => _x( 'Add New Page Category', 'page_category' ),
        'new_item_name' => _x( 'New Page Category', 'page_category' ),
        'separate_items_with_commas' => _x( 'Separate page category with commas', 'page_category' ),
        'add_or_remove_items' => _x( 'Add or remove page category', 'page_category' ),
        'choose_from_most_used' => _x( 'Choose from the most used page category', 'page_category' ),
        'menu_name' => _x( 'Page Category', 'page_category' ),);

    	$args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' =>false,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true);
    	register_taxonomy( 'page_category', array('page'), $args );
}	*/

function andven_get_custom_field($value) {
	global $post;

    $custom_field = get_post_meta($post->ID, $value, true );
    if ( !empty( $custom_field ) )
	    return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

    return false;
}


function andven_add_custom_meta_box() {
	add_meta_box('andven', __('Testimonial','andven'),'andven_meta_box_output','testimonial','normal','low');
	add_meta_box('andven', __('Icon Class','andven'),'andven_meta_box_icon','page','normal','low');
}
add_action( 'add_meta_boxes', 'andven_add_custom_meta_box' );


function andven_meta_box_output( $post ) {

	wp_nonce_field( 'andven_meta_box_nonce', 'andven_meta_box_nonce' ); ?>
	<p>
		<label for="andven_textfield"><?php _e( 'Location', 'andven' ); ?>:</label>
		<input type="text" name="andven_location" id="andven_location" value="<?php echo andven_get_custom_field('_andven_location'); ?>" size="50" />
    </p>
	<?php
}

function andven_meta_box_icon( $post ) {

	wp_nonce_field( 'andven_meta_box_nonce', 'andven_meta_box_nonce' ); ?>
	<p>
		<label for="andven_textfield"><?php _e( 'Icon Class', 'andven' ); ?>:</label>
		<input type="text" name="andven_icon_class" id="andven_icon_class" value="<?php echo andven_get_custom_field('_andven_icon_class'); ?>" size="50" />
    </p>
	<?php
}

// Save the Metabox values
function andven_meta_box_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['andven_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['andven_meta_box_nonce'], 'andven_meta_box_nonce' ) ) return;


	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post' ) ) return;

    // Save the textfield
	if( isset( $_POST['andven_location'] ) )
		update_post_meta($post_id,'_andven_location',esc_attr($_POST['andven_location']));

	if( isset( $_POST['andven_icon_class'] ) )
		update_post_meta($post_id,'_andven_icon_class',esc_attr($_POST['andven_icon_class']));

}
add_action( 'save_post', 'andven_meta_box_save' );

add_filter('manage_edit-testimonial_columns', 'add_new_testimonial_columns');
function add_new_testimonial_columns($new_columns) {

  	unset($new_columns['date']);
  	$new_columns['location'] = __('Location');
    $new_columns['images'] = __('Images');
    $new_columns['date'] = _x('Date', 'column name');
    return $new_columns;

}

/*add_action('manage_testimonial_posts_custom_column','manage_testimonial_columns',10,2);
function manage_testimonial_columns($column_name,$id) {
    global $wpdb;
    switch ($column_name) {

    case 'location':
        echo get_post_meta($id,'_andven_location',true);
        break;

    case 'images':
        echo get_the_post_thumbnail($id,array(60,60));
        break;
    default:
        break;
    }
}
*/

add_action( 'init', 'register_wod_workout' );
function register_wod_workout() {

    	$labels = array( 
        'name' => _x( 'Workout', 'workout' ),
        'singular_name' => _x( 'Workout', 'workout' ),
        'add_new' => _x( 'Add New', 'workout' ),
        'add_new_item' => _x( 'Add New Workout', 'workout' ),
        'edit_item' => _x( 'Edit Workout', 'workout' ),
        'new_item' => _x( 'New Workout', 'workout' ),
        'view_item' => _x( 'View Workout', 'workout' ),
        'search_items' => _x( 'Search Workout', 'workout' ),
        'not_found' => _x( 'No workout found', 'workout' ),
        'not_found_in_trash' => _x( 'No workout found in Trash', 'workout' ),
        'parent_item_colon' => _x( 'Parent Workout:', 'workout' ),
        'menu_name' => _x( 'Workout', 'workout' ),);

    	$args = array( 
        'labels' => $labels,
        'hierarchical' => false,  
        'supports' => array( 'title','excerpt' ),
        'taxonomies' => array( 'workout_category' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post');
        register_post_type( 'workout', $args );
}  

add_action( 'init', 'register_taxonomy_workout_category' );
function register_taxonomy_workout_category() {

    	$labels = array( 
        'name' => _x( 'Categories', 'workout_category' ),
        'singular_name' => _x( 'Category', 'workout_category' ),
        'search_items' => _x( 'Search Categories', 'workout_category' ),
        'popular_items' => _x( 'Popular Categories', 'workout_category' ),
        'all_items' => _x( 'All Categories', 'workout_category' ),
        'parent_item' => _x( 'Parent Category', 'workout_category' ),
        'parent_item_colon' => _x( 'Parent Category:', 'workout_category' ),
        'edit_item' => _x( 'Edit Category', 'workout_category' ),
        'update_item' => _x( 'Update Category', 'workout_category' ),
        'add_new_item' => _x( 'Add New Category', 'workout_category' ),
        'new_item_name' => _x( 'New Category', 'workout_category' ),
        'separate_items_with_commas' => _x( 'Separate categories with commas', 'workout_category' ),
        'add_or_remove_items' => _x( 'Add or remove categories', 'workout_category' ),
        'choose_from_most_used' => _x( 'Choose from the most used categories', 'workout_category' ),
        'menu_name' => _x( 'Categories', 'workout_category' ),);

    	$args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => false,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' =>array('slug'=>'workout-category'),
        'query_var' => true);
   		register_taxonomy( 'workout_category', array('workout'), $args );
}
?>