<?phpsession_start();ob_start();require_once(TEMPLATEPATH . '/wod.php');require_once(TEMPLATEPATH . '/theme-settings.php');require_once(TEMPLATEPATH . '/includes/custom_functions.php');require_once(TEMPLATEPATH . '/custom-metaboxes/functions.php');require_once(TEMPLATEPATH . '/includes/class-userProfilePic.php');require_once(TEMPLATEPATH . '/includes/socials-signin.php');require_once(TEMPLATEPATH . '/includes/pagination.class.php');add_theme_support('post-thumbnails');function add_ajaxurl_cdata_to_front(){    ?>    <script type="text/javascript">        //<![CDATA[        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';        var tempurl = '<?php echo get_template_directory_uri(); ?>';        var siteurl = '<?php echo get_site_url(); ?>';        var admin_url = '<?php echo admin_url(); ?>';        //]]>    </script><?php}add_action('wp_head', 'add_ajaxurl_cdata_to_front', 1);function wod_theme_scripts(){    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'));    wp_enqueue_script('formValidation', get_template_directory_uri() . '/js/framework/formValidation.min.js', array('jquery'));    wp_enqueue_script('bootstrapValidator', get_template_directory_uri() . '/js/framework/bootstrap.min.js', array('jquery'));    wp_enqueue_script('moment_min_js', get_template_directory_uri() . '/js/fullcalendar/moment.min.js', array('jquery'));    wp_enqueue_script('fullcalendar_min_js', get_template_directory_uri() . '/js/fullcalendar/fullcalendar.min.js', array('jquery'));    wp_enqueue_script('mmenu', get_template_directory_uri() . '/js/jquery.mmenu.min.all.js', array('jquery'));    wp_enqueue_script('highcharts', get_template_directory_uri() . '/js/highcharts.js', array('jquery'));    wp_enqueue_script('custom_function', get_template_directory_uri() . '/js/custom-function.js', array('jquery'));    wp_enqueue_script('jquery_form_min', get_template_directory_uri() . '/js/jquery.form.min.js', array('jquery'));    wp_enqueue_script('jquery_facebook', get_template_directory_uri() . '/js/jquery.social-login.js', array('jquery'));    wp_enqueue_script('bootstrap-time-picker', get_template_directory_uri() . '/js/bootstrap-datetimepicker.min.js', array('jquery'));    wp_enqueue_script('jquery-ui-datepicker');}add_action('wp_enqueue_scripts', 'wod_theme_scripts');function wod_theme_styles(){    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css', '', '');    wp_enqueue_style('style', get_template_directory_uri() . '/style.css', '', '');    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', '', '');    wp_enqueue_style('mmenu', get_template_directory_uri() . '/css/jquery.mmenu.all.css', '', '');    wp_enqueue_style('fawesome', 'https://fonts.googleapis.com/css?family=Lato:400,100,300,300italic,700,900', '', '');    wp_enqueue_style('fullcalendar_css', get_template_directory_uri() . '/js/fullcalendar/fullcalendar.css', '', '');    wp_enqueue_style('fullcalendar_print_css', get_template_directory_uri() . '/js/fullcalendar/fullcalendar.print.css', '', '', 'print');    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');    wp_enqueue_style('bootstrap-time-picker-css', get_template_directory_uri() . '/css/bootstrap-datetimepicker.min.css', '', '');    wp_enqueue_style('layout', get_template_directory_uri() . '/css/style.css', '', '');}add_action('wp_enqueue_scripts', 'wod_theme_styles');function wod_admin_load_scripts(){    $screen = get_current_screen();    if ($screen->id == 'appearance_page_hmdesign-settings') {//wp_enqueue_script('hmdesign-admin',get_template_directory_uri().'/js/jquery.easytabs.js',array('jquery'),NULL,true);    }}add_action('admin_enqueue_scripts', 'wod_admin_load_scripts');function wod_widgets_init(){    register_sidebar(array(        'name' => 'Primary',        'id' => 'sidebar-primary',        'description' => 'Default Sidebar',        'before_widget' => '<div id="%1$s" class="%2$s sidebar">',        'after_widget' => '</div>',        'before_title' => '<h2>',        'after_title' => '</h2>',));    register_sidebar(array(        'name' => 'Left Sidebar',        'id' => 'sidebar-left',        'description' => 'Appears in left section of the site.',        'before_widget' => '<div id="%1$s" class="%2$s">',        'after_widget' => '</div>',        'before_title' => '<h2>',        'after_title' => '</h2>',));    register_sidebar(array(        'name' => 'Right Sidebar',        'id' => 'sidebar-right',        'description' => 'Appears in right section of the site.',        'before_widget' => '<div id="%1$s" class="%2$s sidebar">',        'after_widget' => '</div>',        'before_title' => '<h2>',        'after_title' => '</h2>',));     register_sidebar(array(        'name' => 'Footer Sidebar',        'id' => 'sidebar-footer',        'description' => 'Appears in footer section of the site.',        'before_widget' => '<div id="%1$s" class="%2$s col-md-3 col-sm-3">',        'after_widget' => '</div>',        'before_title' => '<h3>',        'after_title' => '</h3>',));    register_sidebar(array(        'name' => 'header Sidebar',        'id' => 'sidebar-header',        'description' => 'Appears in header section of the site.',        'before_widget' => '<div id="%1$s" class="%2$s">',        'after_widget' => '</div>',        'before_title' => '<h2>',        'after_title' => '</h2>',));    register_sidebar(array(        'name' => 'Content Bottom',        'id' => 'sidebar-bottom',        'description' => 'Appears on bottom of the page content of the site.',        'before_widget' => '<div id="%1$s" class="%2$s">',        'after_widget' => '</div>',        'before_title' => '<h2>',        'after_title' => '</h2>',));    register_sidebar(array(        'name' => 'Content Top',        'id' => 'sidebar-top',        'description' => 'Appears on top of the page content of the site.',        'before_widget' => '<div id="%1$s" class="%2$s col-md-5 col-sm-5">',        'after_widget' => '</div>',        'before_title' => '<h2>',        'after_title' => '</h2>',));    register_sidebar(array(        'name' => 'Content',        'id' => 'sidebar-content',        'description' => 'Appears on the page of the site.',        'before_widget' => '<div id="%1$s" class="%2$s col-md-3 sideBar">',        'after_widget' => '</div>',        'before_title' => '<div class="panel-heading"><h3>',        'after_title' => '</h3></div>',));}add_action('widgets_init', 'wod_widgets_init');function is_child($page_id_or_slug){    global $post;    if (!is_int($page_id_or_slug)) {        $page = get_page_by_path($page_id_or_slug);        $page_id_or_slug = $page->ID;    }    if (is_page() && $post->post_parent == $page_id_or_slug) {        return true;    } else {        return false;    }}add_action('init', 'create_post_type');function create_post_type(){    register_post_type('Gym',        array(            'labels' => array(                'name' => __('Gym'),                'singular_name' => __('gym')            ),            'public' => true,            'has_archive' => true,            'supports' => array('title', 'excerpt', 'page-attributes'),        )    );}add_action('after_setup_theme', 'home_img_setup');function home_img_setup(){    add_theme_support('post-thumbnails'); // This feature enables post-thumbnail support for a theme    add_image_size('home-image', 350, 300, true);}add_filter('image_size_names_choose', 'home_img');function home_img($sizes){    $custom_sizes = array(        'home-image' => 'Home Image'    );    return array_merge($sizes, $custom_sizes);}function category_set_post_types($query){    if ($query->is_category):        $query->set('post_type', 'any');    endif;    return $query;}add_action('pre_get_posts', 'category_set_post_types');function catch_first_image(){    global $post, $posts;    $first_img = '';    ob_start();    ob_end_clean();    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);    $first_img = $matches[1][0];    if (empty($first_img)) {        $first_img = get_bloginfo('template_url') . "/images/no_image.png";    }    return $first_img;}add_role(    'normal_user',    __('Normal User'),    array(        'read' => true, // true allows this capability        'edit_posts' => true,        'delete_posts' => false, // Use false to explicitly deny    ));add_role(    'trainer',    __('Trainer'),    array(        'read' => true, // true allows this capability        'edit_posts' => true,        'delete_posts' => false, // Use false to explicitly deny    ));function respond_by_json($boolean, $message, $class, $redirect = '', $user_role = ''){    $response = array();    $response["class"] = $class;    $response["message"] = $message;    $response["success"] = $boolean;    $response["redirect"] = $redirect;    $response["user_role"] = $user_role;    echo json_encode($response);    exit();}function wod_main_menu(){    $copyright = array(        'theme_location' => 'primary',        'menu' => 'Main menu',        'container' => '',        'container_class' => false,        'container_id' => false,        'menu_id' => '',        'link_after' => '',        'echo' => false,        'items_wrap' => '%3$s',        'menu_class' => '');    echo strip_tags(wp_nav_menu($copyright), '<li>,<a>');}function getInstaID($username){    $username = strtolower($username); // sanitization    $token = "2200883466.5b9e1e6.bd6482d878404f158d1a685b10e80f6e";    $token = "51568925.1677ed0.a869a710355548f3abbf522675a24c06";    $url = "https://api.instagram.com/v1/users/search?q=" . $username . "&access_token=" . $token;    //https://api.instagram.com/v1/users/search?q=chapter_2_fitness&access_token=2200883466.5b9e1e6.bd6482d878404f158d1a685b10e80f6e   // https://api.instagram.com/v1/users/search?q=chapter_2_fitness&access_token=51568925.1677ed0.a869a710355548f3abbf522675a24c06   // https://instagram.com/oauth/authorize/?client_id=a03737b9290a4a3795f5194e71e9ba77&redirect_uri=http://localhost&response_type=token   // https://api.instagram.com/oauth/authorize/?client_id=a03737b9290a4a3795f5194e71e9ba77&redirect_uri=REDIRECT-URI&response_type=code    $get = file_get_contents($url);    $json = json_decode($get);    foreach ($json->data as $user) {        if ($user->username == $username) {            return $user->id;        }    }    return '00000000'; // return this if nothing is found}add_filter( 'bbp_verify_nonce_request_url', 'my_bbp_verify_nonce_request_url', 999, 1 );function my_bbp_verify_nonce_request_url( $requested_url ){    return 'http://localhost:8888' . $_SERVER['REQUEST_URI'];}add_filter( 'bbp_no_breadcrumb', '__return_true' );function filter_callback($val){    $val = trim($val);    return $val != '';}function CallAPI($method, $url, $data = false){    $curl = curl_init();    switch ($method) {        case "POST":            curl_setopt($curl, CURLOPT_POST, 1);            if ($data)                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);            break;        case "PUT":            curl_setopt($curl, CURLOPT_PUT, 1);            break;        default:            if ($data)                $url = sprintf("%s?%s", $url, http_build_query($data));    }    curl_setopt($curl, CURLOPT_URL, $url);    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);    curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 2);    curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-Requested-With:XMLHttpRequest'));    $result = curl_exec($curl);    if ($result === false) {        echo 'Curl error: ' . curl_error($curl);    } else {        return $result;    }    curl_close($curl);}function getInstagramFeeds($insta_user_id){    $result = CallAPI('GET', 'https://api.instagram.com/v1/users/' . $insta_user_id . '/media/recent/?access_token=2200883466.5b9e1e6.bd6482d878404f158d1a685b10e80f6e', '');    return json_decode($result);}include("includes/codebird.php");/* * Add a new code for admin user can go in backendnormal user can't go in backend. */add_action('init', 'admin_area_show_only_admin_callback');function admin_area_show_only_admin_callback(){    if (is_admin() && !current_user_can('activate_plugins') && !in_array( $GLOBALS['pagenow'], array( 'admin-ajax.php' ))) {        wp_redirect(site_url('mydashboard'));    }}show_admin_bar(false);?>