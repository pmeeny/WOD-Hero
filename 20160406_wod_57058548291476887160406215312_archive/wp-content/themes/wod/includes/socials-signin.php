<?php
$consumer_key = 'I8Eh9vZrwygZH69za8KRV6IDw';
$consumer_secrate = '0ZJQqMqpe6WsrmgJzjMoVmBZw2jqe2hd1ss25pbTiHvQhPTcnL';



define('TWITTER_CONSUMER_KEY', $consumer_key);
define('TWITTER_CONSUMER_SECRET', $consumer_secrate);
define('TWITTER_REQUEST_URL',admin_url('admin-ajax.php').'?action=twitterdata');
define('TWITTER_ACCESS_URL', 'https://api.twitter.com/oauth/access_token');
define('TWITTER_AUTHORIZE_URL', 'https://api.twitter.com/oauth/authorize');

function TwitterLogin()
{
    require_once('twitter_signin/twitteroauth.php');

    if (!isset($_SESSION['twitter'])){
        $oauth = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
        try {
            if (isset($_GET['oauth_token'], $_SESSION['oauth_token_secret'])) {
                //	die('uihi');
                $oauth->setToken($_GET['oauth_token'], $_SESSION['oauth_token_secret']);
                $accessToken = $oauth->getAccessToken(TWITTER_ACCESS_URL);
                $_SESSION['oauth_token'] = $accessToken['oauth_token'];
                $_SESSION['oauth_token_secret'] = $accessToken['oauth_token_secret'];

                $get = $twitteroauth->get('https://api.twitter.com/1.1/account/verify_credentials.json');

                if (!isset($get['user_id'])) {
                    throw new Exception('Authentication failed.');
                }
            } else {

                $requestToken = $oauth->getRequestToken(TWITTER_REQUEST_URL);
                $_SESSION['oauth_token'] = $requestToken['oauth_token'];
                $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
                header('Location: '.TWITTER_AUTHORIZE_URL.'?oauth_token=' . $requestToken['oauth_token']);
                die();
            }
        } catch (Exception $e) {
            var_dump($oauth->debugInfo);
            die($e->getMessage());
        }
    }
}
add_action('wp_ajax_nopriv_twitterLogin','TwitterLogin');

add_action('wp_ajax_nopriv_twitterdata','TwitterData');
function TwitterData()
{
    require_once('twitter_signin/twitteroauth.php');

    if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
        $_SESSION['oauth_status'] = 'oldtoken';
        unset($_SESSION['twitter']);
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);

        wp_redirect(admin_url('admin-ajax.php').'?action=twitterLogin');
    }
    $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

    $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
    $_SESSION['access_token'] = $access_token;

    unset($_SESSION['oauth_token']);
    unset($_SESSION['oauth_token_secret']);

    /* If HTTP response is 200 continue otherwise send to connect page to retry */
    if (200 == $connection->http_code) {
        /* The user has been verified and the access tokens can be saved for future use */
        $_SESSION['status'] = 'verified';
        $access_token = $_SESSION['access_token'];
        /* Create a TwitterOauth object with consumer/user tokens. */
        $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

        /* If method is set change API call made. Test is called by default. */
        $content = $connection->get('account/verify_credentials',array('include_email'=>'true'));
	//pre($content); die;	
        $response = twitterUserExists($content);
        //pre($response); die;
        if($response['flag'] == 'login'){
        ?>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                opener.location.href = "<?php echo site_url('mydashboard');?>";
                window.close();
            });
        </script>
    <?php
        }
        else{ ?>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    opener.location.href = "<?php echo site_url('social-sign-in');?>";
                    window.close();
                });
            </script>
    <?php }
    } else {
        /* Save HTTP status for error dialog on connect page.*/
        unset($_SESSION['twitter']);
       // unset($_SESSION['access_token']);
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);

        wp_redirect(admin_url('admin-ajax.php').'?action=twitterLogin');
    }

}


function twitterUserExists($login_object){
    global $wpdb;
    //pre($login_object,1); die;
    //$sql = "SELECT t1.* From $wpdb->users as t1 LEFT JOIN $wpdb->usermeta as t2 ON t2.user_id = t1.ID Where t2.meta_key = '_socialId' AND t2.meta_value = '".$login_object->id."' AND t1.user_login = '".$login_object->screen_name."'";
    $sql = "SELECT t1.* From $wpdb->users as t1 LEFT JOIN $wpdb->usermeta as t2 ON t2.user_id = t1.ID Where t2.meta_key = '_socialId' AND t2.meta_value = '".$login_object->id."' AND t1.user_email = '".$login_object->email."'";
    $results =  $wpdb->get_row($sql,ARRAY_A);
    if(!empty($results)){
        if(!empty($login_object->profile_image_url)){
            $attach_id = get_user_meta($results['ID'], 'profile_picture', true);
            if(empty($attach_id)){
                process_attachment('',$login_object->profile_image_url,$results['ID']);
            }

        }
        wp_set_auth_cookie($results['ID']);
        $response['flag'] = 'login';
    }
    else if(username_exists($login_object->screen_name)){
        $user = get_user_by('name', $login_object->screen_name );
        if(!empty($login_object->profile_image_url)){
            $attach_id = get_user_meta($user->ID, 'profile_picture', true);
            if(empty($attach_id)){
                process_attachment('',$login_object->profile_image_url,$user->ID);
            }

        }
        wp_set_auth_cookie($results['ID']);
        $response['flag'] = 'login';
    }
    else{
        $_SESSION['SocialLogin']['name'] = $login_object->name;
        $_SESSION['SocialLogin']['socialImage'] = str_replace('_normal','',$login_object->profile_image_url);
	$_SESSION['SocialLogin']['email'] = $login_object->email;
        $_SESSION['SocialLogin']['socialId'] = $login_object->id;
        $_SESSION['SocialLogin']['screen_name'] = $login_object->screen_name;
        $_SESSION['SocialLogin']['social_type'] = 'twitter';
        $response['flag'] = 'register';
    }
    return $response;
}


function process_attachment( $post, $url, $user_id ) {
    $attachment_id 		= '';
    $attachment_url 	= '';
    $attachment_file 	= '';
    $upload_dir 		= wp_upload_dir();

    // If same server, make it a path and move to upload directory
    /*if ( strstr( $url, $upload_dir['baseurl'] ) ) {
        $url = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $url );
    } else*/
    if ( strstr( $url, site_url() ) ) {
        $abs_url 	= str_replace( trailingslashit( site_url() ), trailingslashit( ABSPATH ), $url );
        $new_name 	= wp_unique_filename( $upload_dir['path'], basename( $url ) );
        $new_url 	= trailingslashit( $upload_dir['path'] ) . $new_name;
        if ( copy( $abs_url, $new_url ) ) {
            $url = basename( $new_url );
        }
    }

    // if the URL is absolute, but does not contain address, then upload it assuming base_site_url
    if ( preg_match( '|^/[\w\W]+$|', $url ) )
        $url = rtrim( site_url(), '/' ) . $url;

    $upload = fetch_remote_file( $url, $post );

    if ( is_wp_error( $upload ) )
        return $upload;

    if ( $info = wp_check_filetype( $upload['file'] ) )
        $post['post_mime_type'] = $info['type'];
    else
        return new WP_Error( 'attachment_processing_error', __('Invalid file type', 'wordpress-importer') );

    $post['guid']       = $upload['url'];
    $attachment_file 	= $upload['file'];
    $attachment_url 	= $upload['url'];

    $attach_id = get_user_meta($user_id, 'profile_picture', true);
    if(!empty($attach_id)){
        deleteExistingImg($attach_id,$user_id);
    }

    // as per wp-admin/includes/upload.php
    $attachment_id = wp_insert_attachment( $post, $upload['file'] );
    $attach_data = wp_generate_attachment_metadata($attachment_id, $attachment_file);
    wp_update_attachment_metadata($attachment_id, $attach_data);

    update_user_meta($user_id,'profile_picture',$attachment_id);

    unset( $upload );

    if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG )
            $this->log->add( 'csv-import', sprintf( __( '> > Inserted image attachment "%s"', 'wc_csv_import' ), $url ) );

        $attachments[] = $attachment_id;
    }
    return $attachment_id;
}

/**
 * Attempt to download a remote file attachment
 */
function fetch_remote_file( $url, $post ) {

    // extract the file name and extension from the url
    $file_name 		= basename( current( explode( '?', $url ) ) );
    $wp_filetype 	= wp_check_filetype( $file_name, null );
    $parsed_url 	= @parse_url( $url );

    // Check parsed URL
    if ( ! $parsed_url || ! is_array( $parsed_url ) )
        return false;

    // Ensure url is valid
    $url = str_replace( " ", '%20', $url );

    // Get the file
    $response = wp_remote_get( $url, array(
        'timeout' => 10
    ) );

    if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 )
        return false;

    // Ensure we have a file name and type
    if ( ! $wp_filetype['type'] ) {

        $headers = wp_remote_retrieve_headers( $response );
        if ( isset( $headers['content-disposition'] ) && strstr( $headers['content-disposition'], 'filename=' ) ) {
            $disposition = end( explode( 'filename=', $headers['content-disposition'] ) );
            $disposition = sanitize_file_name( $disposition );
            $file_name   = $disposition;
        } elseif ( isset( $headers['content-type'] ) && strstr( $headers['content-type'], 'image/' ) ) {
            $file_name = 'image.' . str_replace( 'image/', '', $headers['content-type'] );
        }
        unset( $headers );
    }
    // Upload the file
    $upload = wp_upload_bits( $file_name, '', wp_remote_retrieve_body( $response ) );
    if ( $upload['error'] )
        return new WP_Error( 'upload_dir_error', $upload['error'] );
    // Get filesize
    $filesize = filesize( $upload['file'] );
    if ( 0 == $filesize ) {
        @unlink( $upload['file'] );
        unset( $upload );
        return new WP_Error( 'import_file_error', __('Zero size file downloaded', 'wc_csv_import') );
    }
    unset( $response );
    return $upload;
}

function deleteExistingImg($attach_id='',$user_id){
    wp_delete_attachment($attach_id, true);
    update_user_meta($user_id,'profile_picture','');
}

/*
 * Facebook Login
 * */

add_action( 'wp_ajax_nopriv_social_login', 'social_login_callback' );
function social_login_callback()
{
    extract($_POST);
    global $wpdb;
    $sql = "SELECT t1.ID,t1.user_email From $wpdb->users as t1 LEFT JOIN $wpdb->usermeta as t2 ON t2.user_id = t1.ID Where t2.meta_key = '_socialId' AND t2.meta_value = '".$ID."' AND t1.user_email = '".$email."'";
    $results =  $wpdb->get_row($sql,ARRAY_A);
    if(!empty($results)){
        if(!empty($socialImage)){
            $attach_id = get_user_meta($results['ID'], 'profile_picture', true);
            if(empty($attach_id)){
                process_attachment('',$socialImage,$results['ID']);
            }
        }
        wp_forcely_login_user($results['ID']);
        $arr = array('success'=>true, 'redirect_to'=>site_url('mydashboard'));
        echo json_encode($arr);
        exit();
    }
    elseif(email_exists($email)){
        $user = get_user_by( 'email', $email );
        if(!empty($socialImage)){
            $attach_id = get_user_meta($user->ID, 'profile_picture', true);
            if(empty($attach_id)){
                process_attachment('',$socialImage,$user->ID);
            }


        }
        wp_forcely_login_user($user->ID);
        $arr = array('success'=>true, 'redirect_to'=>site_url('mydashboard'));
        echo json_encode($arr);
        exit();
    }
    else
    {
        $_SESSION['SocialLogin'] = array('name' => $first_name.' '.$last_name, 'socialImage' => $socialImage, 'socialId' => $socialId,
            'screen_name' =>'', 'email'=>$email ,'social_type'=>$social_type, 'gender'=>$gender, 'socialImage'=>$socialImage);
        $arr = array('success'=>true,'redirect_to'=>site_url('social-sign-in'));
        echo json_encode($arr);
        exit();
    }
    exit();
}

if(!function_exists('wp_forcely_login_user'))
{
    function wp_forcely_login_user($user_id=0, $email='')
    {
        if(!empty($user_id) || !empty($email))
        {
            $user = get_user_by( 'id', $user_id );
            if( $user ) {
                wp_set_current_user( $user_id, $user->user_login );
                wp_set_auth_cookie( $user_id );
            }
        }
    }
}

/*
 * Set avatar image
 *
 * */
add_filter('get_avatar_data', 'get_avatar_data_modify', 9999 ,2);
function get_avatar_data_modify($arg = array(), $id_or_email='')
{
    $profile_picture = get_user_meta($id_or_email, 'profile_picture',true);
    if(!empty($profile_picture))
    {
        $arg['url'] = wp_get_attachment_url( $profile_picture );
    }

    return $arg;
}

