<?php
add_action('init','andven_admin_init' );
add_action('admin_menu','andven_settings_page_init' );

function andven_admin_init() {
	$settings = get_option("andven_options");
	if(empty( $settings)){
		$settings = array();
		add_option("andven_options",$settings,'','yes');
	}
}

function andven_settings_page_init() {
$settings_page = add_theme_page('Theme Option','Theme Option', 'edit_theme_options', 'theme-settings', 'andven_settings_page' );
add_action( "load-{$settings_page}", 'andven_load_settings_page' );
}

function andven_load_settings_page() {
	if ( $_POST["andven-settings-submit"] == 'Y' ) {
		check_admin_referer( "andven-settings-page" );
		andven_save_theme_settings();
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('themes.php?page=theme-settings&'.$url_parameters));
		exit;
	}
}

function andven_save_theme_settings() {
	global $pagenow;
	$settings = get_option( "andven_options" );


	if ( $pagenow == 'themes.php' && $_GET['page'] == 'theme-settings' ){
		if ( isset ( $_GET['tab'] ) )
	        $tab = $_GET['tab'];
	    else
	        $tab='social_settings';

	    switch ( $tab ){
	        case 'social_settings':
				$settings['social_settings']	  = $_POST['andven_options'];
			break;
	        case 'contact_settings' :
				$settings['contact_settings']	  = $_POST['andven_options'];
			break;
			case 'general_setting' :
				$settings['general_setting']	  = $_POST['andven_options'];
				$settings['general_setting']['service_page']	  = $_POST['page_id'];
			break;
	    }
	}
	//echo '<pre>'; print_r($settings); die;
	$updated = update_option("andven_options",$settings);
}

function andven_admin_tabs( $current = 'social_settings' ) {
    $tabs = array( 'social_settings' => 'Social Settings', 'contact_settings' => 'Contact Settings', 'general_setting' =>'General Setting');
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=theme-settings&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}

function andven_settings_page() {
	global $pagenow;
	$settings = get_option("andven_options");
	?>

	<div class="wrap">
		<h2>Theme Settings</h2>

		<?php
			if ( 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Theme Settings updated.</p></div>';

			if ( isset ( $_GET['tab'] ) ) andven_admin_tabs($_GET['tab']); else andven_admin_tabs('social_settings');
		?>

		<div id="poststuff">
			<form method="post" action="<?php admin_url( 'themes.php?page=theme-settings' ); ?>">
				<?php
				wp_nonce_field( "andven-settings-page" );

				if ( $pagenow == 'themes.php' && $_GET['page'] == 'theme-settings' ){

					if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
					else $tab = 'social_settings';

					echo '<table class="form-table">';
					switch ( $tab ){
						case 'social_settings' :
						$social_setting = $settings['social_settings'];

							?>
                            <tr valign="top">
                            <th scope="row">Facebook URL:</th>
                            <td><?php  printf('<input type="text" id="facebook_url" name="andven_options[facebook_url]" value="%s" style="width:%s;" />',
                                       isset( $social_setting['facebook_url'] ) ? esc_attr( $social_setting['facebook_url']) : '',"50%"); ?></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">Twitter URL:</th>
                            <td><?php printf('<input type="text" id="twitter_url" name="andven_options[twitter_url]" value="%s" style="width:%s;" />',
                                      isset( $social_setting['twitter_url'] ) ? esc_attr( $social_setting['twitter_url']) : '',"50%"); ?></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">Youtube URL:</th>
                            <td><?php printf('<input type="text" id="youtube_url" name="andven_options[youtube_url]" value="%s" style="width:%s;" />',
                                      isset( $social_setting['youtube_url'] ) ? esc_attr( $social_setting['youtube_url']) : '',"50%");?></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">GPlus URL:</th>
                            <td><?php printf('<input type="text" id="gplus_url" name="andven_options[gplus_url]" value="%s" style="width:%s;" />',
                                      isset( $social_setting['gplus_url'] ) ? esc_attr( $social_setting['gplus_url']) : '',"50%");?></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">LinkedIn URL:</th>
                            <td><?php printf('<input type="text" id="linkedin_url" name="andven_options[linkedin_url]" value="%s" style="width:%s;" />',
                                      isset( $social_setting['linkedin_url'] ) ? esc_attr( $social_setting['linkedin_url']) : '',"50%");?></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">Instagram URL:</th>
                                <td><?php printf('<input type="text" id="insta_url" name="andven_options[insta_url]" value="%s" style="width:%s;" />',
                                        isset( $social_setting['insta_url'] ) ? esc_attr( $social_setting['insta_url']) : '',"50%");?></td>
                            </tr>



							<?php
						break;
						case 'general_setting' :
						$general_setting = $settings['general_setting'];
							?>

                            <tr valign="top">
                            <th scope="row">Service Page:</th>
                            <td><?php wp_dropdown_pages(array('child_of'=>0,'post_type'=>'page','selected'=>$general_setting['service_page'],'name'=>'page_id','show_option_none'=>'Select Page','depth'=>1)); ?></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">Slider Short Code:</th>
                            <td><?php printf('<input type="text" id="slider_code" name="andven_options[slider_code]" value="%s" style="width:%s;" />',
                                      isset( $general_setting['slider_code'] ) ? esc_attr( $general_setting['slider_code']) : '',"50%");?></td>
                            </tr>

							<?php
						break;
						case 'contact_settings' :
						$contact_settings = $settings['contact_settings'];
							?>
                            <tr valign="top">
                            <th scope="row">E-Mail:</th>
                            <td><?php printf('<input type="text" id="email_id" name="andven_options[email_id]" value="%s" style="width:%s;" />',
                                      isset( $contact_settings['email_id'] ) ? esc_attr( $contact_settings['email_id']) : '',"50%");?></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">Mobile/Phone:</th>
                            <td><?php printf('<input type="text" id="contact_no" name="andven_options[contact_no]" value="%s" style="width:%s;" />',
                                      isset( $contact_settings['contact_no'] ) ? esc_attr( $contact_settings['contact_no']) : '',"50%");?></td>
                            </tr>

                             <tr valign="top">
                            <th scope="row">Website:</th>
                            <td><?php printf('<input type="text" id="website" name="andven_options[website]" value="%s" style="width:%s;" />',
                                      isset( $contact_settings['website'] ) ? esc_attr( $contact_settings['website']) : '',"50%");?></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">Latitude:</th>
                            <td><?php printf('<input type="text" id="latitude" name="andven_options[latitude]" value="%s" style="width:%s;" />',
                                      isset( $contact_settings['latitude'] ) ? esc_attr( $contact_settings['latitude']) : '',"50%");?></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">Longitude:</th>
                            <td><?php printf('<input type="text" id="longitude" name="andven_options[longitude]" value="%s" style="width:%s;" />',
                                      isset( $contact_settings['longitude'] ) ? esc_attr( $contact_settings['longitude']) : '',"50%");?></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">Address:</th>
                            <td><textarea name="andven_options[address]" rows="5" cols="62"><?php echo $contact_settings['address']; ?></textarea></td>
                            </tr>

                            <tr valign="top">
                            <th scope="row">Opening Hours:</th>
                            <td><textarea name="andven_options[opn_hour]" rows="5" cols="62"><?php echo $contact_settings['opn_hour']; ?></textarea></td>
                            </tr>

							<?php
						break;
					}
					echo '</table>';
				}
				?>
				<p class="submit" style="clear: both;">
					<input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
					<input type="hidden" name="andven-settings-submit" value="Y" />
				</p>
			</form>
			
			
		</div>

	</div>
<?php
}

function get_lat_long($address){
    $address = str_replace(" ", "+", $address);
    $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");
    $json = json_decode($json);

    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    return $lat.','.$long;
}

?>