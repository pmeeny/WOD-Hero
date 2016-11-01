<?php
ini_set('memory_limit', '-1');

if(!function_exists('toPublicId')) {
    function toPublicId($id)
    {
        return $id * 14981488888 + 8259204988888;
    }
}

if(!function_exists('toInternalId')) {
    function toInternalId($publicId)
    {
        return ($publicId - 8259204988888) / 14981488888;
    }
}

function pre($value) {
    echo "<pre>",print_r($value, true),"</pre>";
}

add_action('wp_ajax_logout','logout');
function logout(){
    check_admin_referer('log-out');
    wp_logout();
    $redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : 'wp-login.php?loggedout=true';
    wp_safe_redirect( $redirect_to );
    exit();
}

/*** Start Check Activation Url Exists ***/
add_filter('query_vars', 'add_query_vars');
add_action('parse_request','parse_ipn_request');
add_action('generate_rewrite_rules','paypal_rewrite_rules');
function add_query_vars($vars) {
    return array_merge( array('activate'), $vars );
}

function parse_ipn_request( $wp ) {
    if (array_key_exists('activate', $wp->query_vars) && $wp->query_vars['activate'] == 'asm_activation') {
        require_once(TEMPLATEPATH.'/activate_template/asm_activate.php' );
    }
}

function paypal_rewrite_rules( $wp_rewrite ) {
    $wp_rewrite->rules = array_merge( array( 'activate/asm_activation' => '?activate=asm_activation' ), $wp_rewrite->rules );
}
/*** END Check Activation Url Exists ***/

function login_with_email_address($username) {
    $user = get_user_by('email',$username);
    if(!empty($user->user_login))
        $username = $user->user_login;
    return $username;
}
add_action('wp_authenticate','login_with_email_address');

function NewUserEmailNotification($user, $user_email, $key, $meta = '') {
    $sitename = get_bloginfo( 'name' );
    $blog_id = get_current_blog_id();

    $message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user) . "\r\n\r\n";
    $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

    @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);

    // Send email with activation link.
    $admin_email = get_option( 'admin_email' );
    if($admin_email == '')
        $admin_email = 'support@' . $_SERVER['SERVER_NAME'];
    $from_name = get_option( 'blogname' ) == '' ? $sitename : esc_html( get_option( 'blogname' ) );
    $message_headers = "From: \"{$from_name}\" <{$admin_email}>\n" . "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
    $message = sprintf(
        apply_filters( 'wpmu_signup_user_notification_email',
            __( "Hi %s,\n\nThank you for registering with %s.\n\nTo activate your account, please click the following link:\n\n%s\n\nThanks\n\nThe WOD Hero team." ),
            $user, $user_email, $key, $meta
        ),
        $user,
        $sitename,
        //site_url( "activate/?key=$key" )
        site_url("?activate=asm_activation&key=$key")
    );
    // TODO: Don't hard code activation link.
    $subject = sprintf(
        apply_filters( 'wpmu_signup_user_notification_subject',
            __( '%3$s - Activate your account' ),
            $user, $user_email, $key, $meta
        ),
        $from_name,
        $user,
        $sitename
    );
    wp_mail($user_email, $subject, $message, $message_headers);

    return false;
}

function syonencryptor($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    //pls set your unique hashing key
    $secret_key = 'SyonSuperKey';
    $secret_iv = 'Infomedia'.date('Y');
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        //decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function loginplus() {
    global $wpdb;
    extract($_POST);
    if(empty($email) && empty($password)){
        respond_by_json(true,"Please fill all required fields",'alert-danger','','');
    }
    if(wp_verify_nonce($securitycode,'logincode') )
    {
        $login_data = array();
        //$login_data['user_email'] = $email;
        $login_data['user_login'] = $email;
        $login_data['user_password'] = $password;
        $login_data['remember'] = true;

        $user = apply_filters('authenticate', null, $email, $password);

/*        update_user_meta($user->ID,'_user_status','active');
        update_user_meta($user->ID,'has_to_be_activated',true);*/

        if(get_user_meta($user->ID, 'has_to_be_activated', true ) != false ) {
            respond_by_json(true,"<strong>ERROR</strong>: Your account is not activated yet.",'alert-danger');
        }
        //print_r($user);die;
        if(is_wp_error($user))
        {
            respond_by_json(true,"<strong>ERROR</strong>: Either the email or password you entered is invalid",'alert-danger');
        }
        if($user->roles[0] != 'administrator'){
            if(get_user_meta($user->ID,'_user_status', true ) != 'active' ) {
                respond_by_json(true,"<strong>ERROR</strong>: Your account is not activated yet.",'alert-danger');
            }
        }
        if($user->roles[0] == 'trainer'){
            $redirect_to = site_url().'/options';
        }
        $user_verify = wp_signon($login_data,true);
    }

    $response = array();
    if(is_wp_error($user_verify))
    {
        respond_by_json(true,"<strong>ERROR</strong>: Either the email or password you entered is invalid",'alert-danger');
    }
    else
    {
        wp_set_auth_cookie($user_verify->data->ID,true);
        respond_by_json(true,"Login Successfully, please wait....",'alert-success',$redirect_to,'');
    }
}
add_action('wp_ajax_nopriv_loginplus','loginplus');

function registerplus() {
    extract($_POST);

    if($email==''){
        respond_by_json(true,"Please enter email address.",'alert-danger');
    }
    else if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)){
        respond_by_json(true,"Please enter correct email address.",'alert-danger');
    }
    else if(email_exists($email))
    {
        respond_by_json(true,"Email address already exists.",'alert-danger');
    }
    else
    {
        if(wp_verify_nonce($securitycode,'registercode') )
        {
            $full_name = explode(' ',$name);

            $first_name = $full_name[0];
            $last_name = $full_name[1];



            if(!empty($user_type) && ($user_type=='normal_user' || $user_type=='trainer') )
            {
                $user_type = $user_type;
            }
            else
            {
                $user_type = 'normal_user';

            }

            $user_role = $user_type;

            $user_email_name = explode('@', $email);
            $user_login = $user_email_name[0];

            $user_data = array (
                'user_login' => $user_login,
                'user_pass' => $password,
                'user_email' => $email,
                'role' => $user_role,
                'display_name' =>$name,
            );
            $user_id = wp_insert_user( $user_data );
            //print_r($user_id);die;
           // wp_new_user_notification($user_id, $password);
            if (!is_wp_error($user_id)) {

                $code = syonencryptor('encrypt',$user_id);
                add_user_meta($user_id, 'has_to_be_activated', $code, true);
                add_user_meta( $user_id, 'meta_secret', $password, true );
                NewUserEmailNotification($user_login, $email, $code);

                if(!empty($first_name)){
                    update_user_meta($user_id,'first_name',$first_name);
                }
                if(!empty($last_name)){
                    update_user_meta($user_id,'last_name',$last_name);
                }

                if(!empty($gender)){
                    update_user_meta($user_id,'gender',$gender);
                }

                update_user_meta($user_id,'_user_status','active');
                respond_by_json(true,"Registration successful. Please check your email and activate your account.",'alert-success');
            }
        }
        else
        {
            respond_by_json(true,"Sign up request failed.",'alert-danger');
        }
    }
}
add_action('wp_ajax_nopriv_registerplus','registerplus');


function SocialRegister() {
    extract($_POST);

    if($email==''){
        respond_by_json(true,"Please enter email address.",'alert-danger');
    }
    else if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)){
        respond_by_json(true,"Please enter correct email address.",'alert-danger');
    }
    else if(email_exists($email))
    {
        respond_by_json(true,"Email address already exists.",'alert-danger');
    }
    else
    {
        if(wp_verify_nonce($securitycode,'registercode') )
        {
            $full_name = explode(' ',$name);

            $first_name = $full_name[0];
            $last_name = $full_name[1];

            if(!empty($user_type) && ($user_type=='normal_user' || $user_type=='trainer') )
            {
                $user_type = $user_type;
            }
            else
            {
                $user_type = 'normal_user';

            }

            $user_role = $user_type;
            $user_email_name = explode('@', $email);
            if(!empty($screen_name)){
                $user_login = $screen_name;
            }
            else{
                $user_login = $user_email_name[0];
            }

            $user_data = array (
                'user_login' => $user_login,
                'user_pass' => $password,
                'user_email' => $email,
                'role' => $user_role,
                'display_name' =>$name,
            );
            $user_id = wp_insert_user( $user_data );

            if (!is_wp_error($user_id)) {

                process_attachment('',$socialImage,$user_id);

                add_user_meta($user_id, '_socialId', $socialId, true);
                add_user_meta($user_id, '_social_type', $social_type, true);

                add_user_meta($user_id, 'has_to_be_activated', '', true);
                wp_social_user_notification($user_id, $password);

                if(!empty($first_name)){
                    update_user_meta($user_id,'first_name',$first_name);
                }
                if(!empty($last_name)){
                    update_user_meta($user_id,'last_name',$last_name);
                }


                if(!empty($gender)){
                    update_user_meta($user_id,'gender',$gender);
                }

                update_user_meta($user_id,'_user_status','active');

                respond_by_json(true,"Registration successfully.",'alert-success');
            }
        }
        else
        {
            respond_by_json(true,"Sign up request failed.",'alert-danger');
        }
    }
}
add_action('wp_ajax_nopriv_socialRegister','SocialRegister');

if ( !function_exists('wp_ajt_user_notification') ) {
    function wp_social_user_notification( $user_id, $plaintext_pass = '',$register_type='') {
        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $admin_email = get_option('admin_email');

        $message  = sprintf(__('New user registration on your  %s:'), get_option('blogname')) . "\r\n\r\n";
        //$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

        @wp_mail($admin_email, sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);

        if ( empty($plaintext_pass) )
            return;

        $message  = __('Hi there,') . "\r\n\r\n";
        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
        $message .= wp_login_url() . "\r\n";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";
        $message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "\r\n\r\n";
        $message .= __('WodHero!');

        wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);

    }
}




function useroptionform(){
    extract($_POST);
    //print_r($_POST);die;
    if($trainer_id==''){
        respond_by_json(true,"Please select personal trainer.",'alert-danger');
    }
    /*if($gender==''){
        respond_by_json(true,"Please select sex option.",'alert-danger');
    }*/
    /*else*/
    {
        if(wp_verify_nonce($securitycode,'useroptioncode') )
        {
            if (!is_wp_error($user_id)) {

                if($trainer_id != ''){
                    $trainer_last = get_user_meta( $user_id, 'my_trainer', true );
                    if($trainer_last){
                        update_user_meta($user_id, 'my_trainer', $trainer_id);
                    }else{
                        add_user_meta($user_id, 'my_trainer', $trainer_id, true);
                    }
                }

                if($gender != ''){
                    $gender_last = get_user_meta( $user_id, 'my_gender', true );
                    if($gender_last){
                        update_user_meta($user_id, 'my_gender', $gender);
                    }else{
                        add_user_meta($user_id, 'my_gender', $gender, true);
                    }
                }
                respond_by_json(true,"Options added successful.",'alert-success');
            }
        }
        else
        {
            respond_by_json(true,"Form submission failed.",'error');
        }
    }
}
add_action('wp_ajax_useroptionform','useroptionform');


function checkEmailExists(){
    $email = $_POST['username'];
    if(!empty($email)){
        if(email_exists($email))
        {
            $isAvailable = false;
        }
        else{
            $isAvailable = true;
        }
    }
    else{
        $isAvailable = false;
    }
    echo json_encode(array( 'valid' => $isAvailable)); die;
}
add_action('wp_ajax_emailExists','checkEmailExists');
add_action('wp_ajax_nopriv_emailExists','checkEmailExists');


function checkUserExists(){
    $username = $_POST['username'];
    if(!empty($username)){
        $isAvailable = false;
        if(username_exists($username))
        {
            $isAvailable = false;
        }
        else{
            $isAvailable = true;
        }
    }
    else{
        $isAvailable = false;
    }
    echo json_encode(array( 'valid' => $isAvailable)); die;
}
add_action('wp_ajax_nopriv_nameExists','checkUserExists');



function lostpassword(){
    $errors = retrieve_new_password();
    if (!$errors){
        $redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : 'wp-login.php?checkemail=confirm';
        respond_by_json(true,"Check your e-mail for the confirmation link.",'alert-success',$redirect_to);
    }
    else{
        respond_by_json(true,$errors,'alert-danger');
    }
}
add_action('wp_ajax_nopriv_lostpassword','lostpassword');

function resetpass(){

    $errors = new WP_Error();
    extract($_POST);
    $user = check_password_reset_key($key,$login);
    do_action( 'validate_password_reset',$errors,$user);
    if((!$errors->get_error_code() ) && isset($pass1) && !empty($pass1)) {
        reset_password($user,$pass1);
        respond_by_json(true,"Your password has been reset.",'alert-success',site_url('login'));
    }
    else
    {
        respond_by_json(true,'Sorry, your password does not appear to be valid.','alert-danger');
    }
}
add_action('wp_ajax_nopriv_resetpass','resetpass');

function retrieve_new_password() {

    global $wpdb, $wp_hasher;

    if(empty($_POST['user_login'])){
        respond_by_json(true,'Please enter username or email.','alert-danger');
    }
    else if (strpos($_POST['user_login'],'@')) {
        $user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
        if(empty($user_data)){
            respond_by_json(true,'There is no user registered with that email address.','alert-danger');
        }
    }
    else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login',$login);
        if(empty($user_data)){
            respond_by_json(true,'There is no user registered with that username.','alert-danger');
        }
    }

    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    do_action( 'retreive_password', $user_login );
    do_action( 'retrieve_password', $user_login );
    $allow = apply_filters( 'allow_password_reset', true, $user_data->ID );
    if(!$allow){
        $jmessage = 'Password reset is not allowed for this user';
        return $jmessage;
    }
    // Generate something random for a password reset key.
    $key = wp_generate_password( 20, false );

    do_action( 'retrieve_password_key', $user_login, $key );

    if(empty($wp_hasher)){
        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash(8,true);
    }

    $hashed = $wp_hasher->HashPassword( $key );
    $wpdb->update( $wpdb->users, array( 'user_activation_key' => time().":".$hashed ), array( 'user_login' => $user_login ) );

    $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
    $message .= network_home_url( '/' ) . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
    $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
    $message .= '<' . network_site_url("reset-password?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";

    if ( is_multisite() )
        $blogname = $GLOBALS['current_site']->site_name;
    else
        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $title = sprintf( __('[%s] Password Reset'), $blogname );

    $title = apply_filters( 'retrieve_password_title', $title );
    $message = apply_filters( 'retrieve_password_message', $message, $key );

    if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ){
        $jmessage = 'Possible reason: your host may have disabled the mail() function.';
        return $jmessage;
    }

}

remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
// add custom filter
add_filter( 'authenticate', 'my_authenticate_username_password', 20, 3 );
function my_authenticate_username_password( $user, $username, $password ) {

    // If an email address is entered in the username box,
    // then look up the matching username and authenticate as per normal, using that.
    if ( ! empty( $username ) ) {
        //if the username doesn't contain a @ set username to blank string
        //causes authenticate to fail
        if(strpos($username, '@') == FALSE){
            $user = get_user_by('login', $username);
        }
        else{
            $user = get_user_by( 'email', $username );
        }
    }
    if ( isset( $user->user_login, $user ) )
        $username = $user->user_login;

    // using the username found when looking up via email
    return wp_authenticate_username_password( NULL, $username, $password );
}


add_action('wp_ajax_get_workout_lists','get_workout_lists');
function get_workout_lists(){
    global $wpdb;
    $args=array(
        'post_type' => 'workout',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'workout_category',
                'field' => 'slug',
                'terms' => $_POST['cid']
            )
        )
    );
    $my_query = null;
    $my_query = new WP_Query($args);
    $state='';
    if(!empty($my_query)){
        if($my_query->have_posts()){
            $state.='<option value="">--Select Type--</option>';
            while ($my_query->have_posts()) : $my_query->the_post();
            $state.='<option value="'.get_the_ID().'">'.get_the_title($post->ID).'</option>';
            endwhile;
        }
        wp_send_json($response=array('set'=>true,'states'=>$state,'id'=>$_POST['sid']));
        exit;
    }
    else
    {
        wp_send_json($response=array('set'=>false));
        exit;
    }
}


add_action('wp_ajax_get_workout_lists_pb_specific','get_workout_lists_pb_specific');
function get_workout_lists_pb_specific(){
    global $wpdb;
    $args=array(
        'post_type' => 'workout',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'workout_category',
                'field' => 'slug',
                'terms' => $_POST['cid']
            )
        )
    );
    $my_query = null;
    $my_query = new WP_Query($args);
    $state='';
    if(!empty($my_query)){
        if($my_query->have_posts()){
            $state.='<option value="">--Select Type--</option>';
            while ($my_query->have_posts()) : $my_query->the_post();
            
            $workoutValue=get_the_ID();
             //   $workoutType =  $my_query->post["post_title"];
            $localhost=$_SERVER["HTTP_HOST"];
            if($localhost == "localhost:8888")   {
            if($workoutValue == 363 || $workoutValue == 364 || $workoutValue == 365 || $workoutValue == 370 || $workoutValue == 371 || $workoutValue == 372
               || $workoutValue == 375 || $workoutValue == 376 || $workoutValue == 377 || $workoutValue == 379 || $workoutValue == 380 ){
                $state.='<option value="'.get_the_ID().'">'.get_the_title($post->ID).'</option>';
            } }
            else
                if($workoutValue == 355 || $workoutValue == 354 || $workoutValue == 351 || $workoutValue == 352 || $workoutValue == 350 || $workoutValue == 347 || $workoutValue == 346
                    || $workoutValue == 345 || $workoutValue == 339 || $workoutValue == 338 || $workoutValue == 337 || $workoutValue == 336 ){
                $state.='<option value="'.get_the_ID().'">'.get_the_title($post->ID).'</option>';
            }
           
            endwhile;
        }
        wp_send_json($response=array('set'=>true,'states'=>$state,'id'=>$_POST['sid']));
        exit;
    }
    else
    {
        wp_send_json($response=array('set'=>false));
        exit;
    }
}




add_action('wp_ajax_enabled_disabled','options_enabled_disabled');
function options_enabled_disabled(){
    $post_id = $_POST['post_id'];
    $response = array();
    $weight_unit = get_post_meta($post_id,'_wod_weight_unit',true);
    $_wod_reps = get_post_meta($post_id,'_wod_reps',true);
    $_wod_times = get_post_meta($post_id,'_wod_times',true);

    if($_wod_reps == 'yes'){
        $response['reps'] = 'yes';
        if(!empty($weight_unit)){
            $response['weight'] = 'yes';
            $unit_weight = '';
            foreach($weight_unit as $val){
                $unit_weight.='<option value="'.$val.'">'.$val.'</option>';
            }
            $response['weight_unit'] = $unit_weight;
        }
    }

    if($_wod_times == 'yes'){
        $response['times'] = 'yes';
        if(!empty($weight_unit)){
            $response['weight'] = 'yes';
            $unit_weight = '';
            foreach($weight_unit as $val){
                $unit_weight.='<option value="'.$val.'">'.$val.'</option>';
            }
            $response['weight_unit'] = $unit_weight;
        }
    }

    echo json_encode($response); die;
}

function addWorkoutPB(){

    global $wpdb,$current_user;
    $db_process = false;
    extract($_POST);
    if(!empty($PERSONALBEST))
    {
        foreach($PERSONALBEST as $key=>$val)
        {
            extract($val);
            $dataInsert = array();
            $dataInsert['user_id'] = get_current_user_id();

            $trainee_info = get_userdata(get_current_user_id());
            $trainer_id = get_user_meta( get_current_user_id(), 'my_trainer', true );
            $gym_name_id = get_user_meta( $trainer_id, 'gym_name', true );
            $gym_name = get_the_title($gym_name_id);

            $dataInsert['gym_name'] = $gym_name;

            $dataInsert['wk_category'] = $workout_cat;
            $dataInsert['wk_name'] = $workout_name;
            if(!empty($box_jump['text'])){
                $dataInsert['box_jump'] = json_encode($box_jump);
            }
            if(!empty($distance['text'])){
                $dataInsert['distance'] = json_encode($distance);
            }
            if(!empty($weight['text'])){
                $dataInsert['weight'] = json_encode($weight);
            }
            if(!empty($times['text']['hours']) || !empty($times['text']['mins']) || !empty($times['text']['secs'])){
               $times['text'] = implode(':',$times['text']);
               $dataInsert['times'] = json_encode($times);
            }

            $over_all_publish = !empty($over_all_publish) ? absint($over_all_publish) : 0;
            $dataInsert['over_all_publish'] = $over_all_publish;
            if($rounds != ""){
                $dataInsert['rounds'] = $rounds;
            }
            if(!empty($weight['text'])) {
                $dataInsert['pbweight'] = $weight['text'];
            }
            if($reps == ""){ $reps=1; }
            $dataInsert['reps'] = $reps;
            $dataInsert['complete_date'] = $completed_date;
            $dataInsert['publish'] = (isset($publish_pb) && $publish_pb == 'yes') ? 1 : '0';
            $dataInsert['add_date'] = time();
            $wpdb->insert($wpdb->prefix.'add_workout',$dataInsert);
            if(!empty($wpdb->insert_id)){
                $db_process = true;
            }
            else
            {
                $db_process = false;
            }
        }
    }


    if($db_process){
        respond_by_json(true,'Workout has been added successfully, Well Done.','alert-success','','');
    }
    else{
        respond_by_json(true,'Something went wrong please try again.','alert-danger','','');
    }
}
add_action('wp_ajax_addWorkoutPB','addWorkoutPB');


function fetchUrl($url){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    // You may need to add the line below
    // curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

    $feedData = curl_exec($ch);
    curl_close($ch);

    return $feedData;

}

function getWorkoutData(){
    global $wpdb;
    if((isset($_REQUEST['start']) && isset($_REQUEST['end'])) && (isset($_REQUEST['userId']) && !empty($_REQUEST['userId']))){
        $start = $_REQUEST['start'];
        $end = $_REQUEST['end'];
        $userId = toInternalId($_REQUEST['userId']);

        $start_time_date = explode('-',$start);
        $start_symptom_date_time = mktime(0, 0, 1,$start_time_date[1],$start_time_date[2],$start_time_date[0]);

        $end_time_date = explode('-',$end);
        $end_symptom_date_time = mktime(23, 59, 59,$end_time_date[1],$end_time_date[2],$end_time_date[0]);

        $results = array();
        $sql = "SELECT * FROM ".$wpdb->prefix."add_workout as t1 WHERE (UNIX_TIMESTAMP(t1.complete_date) between '".$start_symptom_date_time."' AND '".$end_symptom_date_time."') AND t1.user_id = ".$userId."";

        $workout_results = $wpdb->get_results($sql,ARRAY_A);
        $new_workout_array = array();
        if(!empty($workout_results)){
            $i=1;
            foreach($workout_results as $result){
                $new_workout_array[$result['complete_date']]['wk_category'][] = $result['wk_category'];
                $new_workout_array[$result['complete_date']]['publish'][] = $result['publish'];
                $i++;
            }

            foreach($new_workout_array as $key => $result){

                $image_html_data = $image_html= '';
                if(in_array(1,$result['publish'])){
                    $image_html.='<li><img src="'.get_bloginfo('template_url').'/images/icon-best.png"></li>';
                }
                $wk_category = array_unique($result['wk_category']);
    
                foreach($wk_category as $val){
                    if($val == 'running'){
                        $image_html.='<li><img src="'.get_bloginfo('template_url').'/images/icon-running.png"></li>';
                    }
                    if($val == 'cardio'){
                        $image_html.='<li><img src="'.get_bloginfo('template_url').'/images/icon-cardio.png"></li>';
                    }
                    if($val == 'strength'){
                        $image_html.='<li><img src="'.get_bloginfo('template_url').'/images/icon-strength.png"></li>';
                    }
                }

                $image_html_data = '<ul class="workout_images">'.$image_html.'</ul>';

                $jsonevents[] = array(
                    'title' => '',
                    'start' => $key,
                    'end' => $key,
                    'start_date' => $key,
                    'images_data' => $image_html_data,
                );
            }

            echo json_encode($jsonevents);die;
        }

    }
}
add_action('wp_ajax_getWorkoutData','getWorkoutData');

function getSingleWorkoutDetail(){
    global $wpdb;
    if(isset($_POST['workout_date']) && !empty($_POST['workout_date'])){
        $userId = get_current_user_id();
        $sql = "SELECT * FROM ".$wpdb->prefix."add_workout WHERE complete_date = '".$_POST['workout_date']."' AND user_id = ".$userId."";
        $workout_results = $wpdb->get_results($sql,ARRAY_A);

        $html = '';

        $html.='<div class="dialog">
                <div class="header11">
                    <h3 class="title1" id="myModalLabel">My WOD completed on ' .date('d M Y', strtotime($_POST['workout_date'])).'</h3> 

                </div>
                <div class="content">

                <div class="body">';
        $html.='<table class="table">';
        $personalBest = array();
        $workOutDetails = array();
            foreach($workout_results as $result){

                if($result['publish'] == 1){
                    $personalBests[] = $result;
                }

                $box_jump = json_decode($result['box_jump'],true);
                $distance = json_decode($result['distance'],true);
                $weight = json_decode($result['weight'],true);
                $times = json_decode($result['times'],true);
                $addDate = json_decode($result['add_date'],true);
                $reps = json_decode($result['reps'],true);
                $rounds = json_decode($result['rounds'],true);
                $isPB = json_decode($result['publish'],true);

                $time_slot  =  explode(':',$times['text']);

                array_push($workOutDetails, $addDate, array(get_the_title($result['wk_name']), json_decode($result['reps'],true) ,$weight['text'],
                    time_formatted_pad($time_slot[0]).':'.time_formatted_pad($time_slot[1]).':'.time_formatted_pad($time_slot[2]), $distance['text']." ".$distance['unit'], $isPB, $rounds));

            }
        $n=0;
        $i=0;
        for ($i = $n; $i < count($workOutDetails); $i++) {
            if(($i % 2) == 0){
                $postedDate=$workOutDetails[$i];
                $workOutDetailsNewArray = array($workOutDetails[$i+1]);

                if(!($workOutDetailsNewArray[0][3]=="00:00:00")) {

                    //if (strpos($html, $workOutDetailsNewArray[0][3]) !== true){
                    $html .= '<td><b><h4>Overall Time: ' . $workOutDetailsNewArray[0][3] .  " ,Rounds: " . $workOutDetailsNewArray[0][6]. '</h4></b></td></td><tr></tr>';
                    //}
                }
                if($workOutDetailsNewArray[0][5]=="1") {

                    $html .= '<td><b><h4>Strength: ' . '</h4></b></td><tr></tr>';
                }

                $metric="";
                if($workOutDetailsNewArray[0][1] != ""){
                    $metric=$workOutDetailsNewArray[0][1]." reps";
                    if(!($workOutDetailsNewArray[0][2]) ==""){
                        $metric=$metric."@".$workOutDetailsNewArray[0][2]."kg";
                    }
                }
                if(($workOutDetailsNewArray[0][4] != "") && ($workOutDetailsNewArray[0][4] != " ")){
                    $metric= $workOutDetailsNewArray[0][4];
                }

                if ((!(strpos($html, $workOutDetailsNewArray[0][0]."(". $metric. ")") == true)) ) {

                        $html .= '<td>'.$workOutDetailsNewArray[0][0]."(". $metric. ")".'</td>';// add this back
                    //$html .= ''.$workOutDetailsNewArray[0][0]."(". $metric. ")".'';
                    }
                }

                for ($j = $n+2; $j < count($workOutDetails); $j++) {
                    if(($j % 2) == 0) {
                        if ($workOutDetails[$j] == $postedDate) {
                            $workOutDetailsNewArray1 = array($workOutDetails[$j + 1]);

                            // second workout
                            $metric="";
                            if($workOutDetailsNewArray1[0][1] != ""){
                                $metric=$workOutDetailsNewArray1[0][1]." reps";
                                if(!($workOutDetailsNewArray1[0][2]) ==""){
                                    $metric=$metric."@".$workOutDetailsNewArray1[0][2]."kg";
                                }
                            }
                            if(($workOutDetailsNewArray1[0][4] != "") && ($workOutDetailsNewArray1[0][4] != " ")){
                                $metric= $workOutDetailsNewArray1[0][4];
                            }
                                if ((!(strpos($html, $workOutDetailsNewArray1[0][0]."(". $metric. ")") == true)))  {

                                    $html .= '<td>'.$workOutDetailsNewArray1[0][0]."(". $metric. ")".'</td>';

                            }
                    }

                }
            }
            $n=$n+1;
            $html.='</tr>';
        }
        $html.='</tr>';

        $html.='</table>';

        if(!empty($personalBests)){
            $html.='<h3>My Personal Best</h3>';

            $html.='<table class="table">';
            foreach($personalBests as $result){

                $box_jump = json_decode($result['box_jump'],true);
                $distance = json_decode($result['distance'],true);
                $weight = json_decode($result['weight'],true);
                $times = json_decode($result['times'],true);

                $html.='<tr>';
                $html.='<td>'.get_the_title($result['wk_name']).'</td>';
                if(!empty($box_jump)){
                    $html.='<td>'.$box_jump['text'].' '.$box_jump['unit'].'</td>';
                }
                if(!empty($distance)){
                    $html.='<td>'.$distance['text'].' '.$distance['unit'].'</td>';
                }
                if(!empty($weight)){
                    $html.='<td>'.$weight['text'].' '.$weight['unit'].'</td>';
                }
                if(!empty($times)){
                    $unit = !empty($times['unit']) ? $times['unit'] : 'hours/mins/secs';
                    $time_slot  =  explode(':',$times['text']);
                    $html.='<td>'.time_formatted_pad($time_slot[0]).':'.time_formatted_pad($time_slot[1]).':'.time_formatted_pad($time_slot[2]).' '.$unit.'</td>';

                }
                else{
                    $html.='<td>'.$result['reps'].' reps</td>';
                }
                $html.='</tr>';

            }
            $html.='</table>';
        }

        $html.='</div></div></div>';

        echo json_encode(array('workout_detail'=>$html)); die;
    }
}
add_action('wp_ajax_workoutDetail','getSingleWorkoutDetail');


add_action('wp_ajax_get_personal_best_for_graph1','get_personal_best_for_graph1');


function get_personal_best_for_graph1(){
    global $wpdb;
    if(!empty($_POST['wid'])) {
        echo getLastPersonalBestWithWorkoutId();
        //echo get_personal_best_for_graph_json_option($_POST['wid'], $_POST['graph_plot_by']);
        exit();
    }
}



function getLastPersonalBestWithWorkoutId(){
    global $wpdb;
    $wk_id=$_POST['wid'];
    error_log($wk_id);
    $userId = get_current_user_id();
    //if($userId != '' && $wk_id != '') {

        if($userId != '') {

        $sql = "SELECT * FROM " . $wpdb->prefix . "add_workout WHERE user_id = " . $userId ."  AND wk_name =". $wk_id  ." order by id desc limit 20 ";

        error_log("SQL is");
        error_log($sql);
        $workout_results = $wpdb->get_results($sql, ARRAY_A);
        //echo "<pre>"; print_r($workout_results);die;
        $html = '';
        $personalBest = array();
        foreach ($workout_results as $result) {

            if ($result['publish'] == 1) {
                $personalBests[] = $result;
            }
        }
        if (!empty($personalBests)) {

            $html .= '<table class="table"';
            foreach ($personalBests as $result) {

                $box_jump = json_decode($result['box_jump'], true);
                $distance = json_decode($result['distance'], true);
                $weight = json_decode($result['weight'], true);

                $html .= '<tr>';
                $html .= '<td>' . get_the_title($result['wk_name']) . '</td>';
                if (!empty($box_jump)) {
                    $html .= '<td>' . $box_jump['text'] . '' . $box_jump['unit'] . '</td>';
                }
                if (!empty($distance)) {
                    $html .= '<td>' . $distance['text'] . '' . $distance['unit'] . '</td>';
                }
                if (!empty($weight)) {
                    $html .= '<td>' . $weight['text'] . '' . $weight['unit'] . '</td>';
                }
                if (!empty($result['add_date'])) {
                    $html .= '<td>' . date('d M Y', strtotime($result['complete_date'])) . '</td>';
                }
                $html .= '</tr>';

            }
            $html .= '</table>';
        }
    }
    //$html="<body>dd</body>";
    echo $html;
    exit();
}
add_action('wp_ajax_LastPersonalBestWithWorkoutId','getLastPersonalBestWithWorkoutId');


function getLastPersonalBest(){
    global $wpdb;
    $userId = get_current_user_id();
    if($userId != '') {
        $sql = "SELECT * FROM " . $wpdb->prefix . "add_workout WHERE user_id = " . $userId ." order by id desc limit 20 ";
        $workout_results = $wpdb->get_results($sql, ARRAY_A);
        //echo "<pre>"; print_r($workout_results);die;
        $html = '';
        $personalBest = array();
        foreach ($workout_results as $result) {

            if ($result['publish'] == 1) {
                $personalBests[] = $result;
            }
        }

        if (!empty($personalBests)) {

            $html .= '<table class="table"';
            foreach ($personalBests as $result) {

                $box_jump = json_decode($result['box_jump'], true);
                $distance = json_decode($result['distance'], true);
                $weight = json_decode($result['weight'], true);

                $html .= '<tr>';
                $html .= '<td>' . get_the_title($result['wk_name']) . '</td>';
                if (!empty($box_jump)) {
                    $html .= '<td>' . $box_jump['text'] . '' . $box_jump['unit'] . '</td>';
                }
                if (!empty($distance)) {
                    $html .= '<td>' . $distance['text'] . '' . $distance['unit'] . '</td>';
                }
                if (!empty($weight)) {
                    $html .= '<td>' . $weight['text'] . '' . $weight['unit'] . '</td>';
                }
                if (!empty($result['add_date'])) {
                    $html .= '<td>' . date('d M Y', strtotime($result['complete_date'])) . '</td>';
                }
                $html .= '</tr>';

            }
            $html .= '</table>';
        }
    }

    return $html;
}
add_action('wp_ajax_LastPersonalBest','getLastPersonalBest');


function get_personal_best_for_graph_json_option( $wk_id = 0, $graph_plot_by='weekly' )
{



    global $wpdb;
    if($wk_id !='') {
        if ($graph_plot_by == 'weekly') {
            //Weekly plot map
            $current_workouts_details = $wpdb->get_results($wpdb->prepare("SELECT * FROM  " . $wpdb->prefix . "add_workout  WHERE 1=%d AND user_id = %d  AND publish=%d AND  wk_name=%d AND complete_date >=
DATE_ADD(CURDATE(), INTERVAL -7 DAY)", array(1, get_current_user_id(), 1, $wk_id)));


            $total_weight_val = array('Monday' => array(0), 'Tuesday' => array(0), 'Wednesday' => array(0), 'Thursday' => array(0), 'Friday' => array(0), 'Saturday' => array(0), 'Sunday' => array(0));
            $total_time_val = array('Monday' => array(0), 'Tuesday' => array(0), 'Wednesday' => array(0), 'Thursday' => array(0), 'Friday' => array(0), 'Saturday' => array(0), 'Sunday' => array(0));
            $total_distance_val = array('Monday' => array(0), 'Tuesday' => array(0), 'Wednesday' => array(0), 'Thursday' => array(0), 'Friday' => array(0), 'Saturday' => array(0), 'Sunday' => array(0));
            $total_repeat_val = array('Monday' => array(0), 'Tuesday' => array(0), 'Wednesday' => array(0), 'Thursday' => array(0), 'Friday' => array(0), 'Saturday' => array(0), 'Sunday' => array(0));
            $total_box_jump_val = array('Monday' => array(0), 'Tuesday' => array(0), 'Wednesday' => array(0), 'Thursday' => array(0), 'Friday' => array(0), 'Saturday' => array(0), 'Sunday' => array(0));


        } else {

            //Weekly plot map
            $current_workouts_details = $wpdb->get_results($wpdb->prepare("SELECT * FROM  " . $wpdb->prefix . "add_workout  WHERE 1=%d AND user_id = %d  AND publish=%d AND wk_name=%d AND complete_date >=
DATE_ADD(CURDATE(), INTERVAL -12 MONTH)", array(1, get_current_user_id(), 1, $wk_id)));

            $total_weight_val = array('January' => array(0), 'February' => array(0), 'March' => array(0), 'April' => array(0), 'May' => array(0), 'June' => array(0), 'July' => array(0), 'August' => array(0), 'September' => array(0), 'October' => array(0), 'November' => array(0), 'December' => array(0));
            $total_time_val = array('January' => array(0), 'February' => array(0), 'March' => array(0), 'April' => array(0), 'May' => array(0), 'June' => array(0), 'July' => array(0), 'August' => array(0), 'September' => array(0), 'October' => array(0), 'November' => array(0), 'December' => array(0));
            $total_distance_val = array('January' => array(0), 'February' => array(0), 'March' => array(0), 'April' => array(0), 'May' => array(0), 'June' => array(0), 'July' => array(0), 'August' => array(0), 'September' => array(0), 'October' => array(0), 'November' => array(0), 'December' => array(0));
            $total_repeat_val = array('January' => array(0), 'February' => array(0), 'March' => array(0), 'April' => array(0), 'May' => array(0), 'June' => array(0), 'July' => array(0), 'August' => array(0), 'September' => array(0), 'October' => array(0), 'November' => array(0), 'December' => array(0));
            $total_box_jump_val = array('January' => array(0), 'February' => array(0), 'March' => array(0), 'April' => array(0), 'May' => array(0), 'June' => array(0), 'July' => array(0), 'August' => array(0), 'September' => array(0), 'October' => array(0), 'November' => array(0), 'December' => array(0));

        }

        $time = false;
        $weight = false;
        $distance = false;
        $reps = false;
        $box_jump = false;

        if (!empty($current_workouts_details))
        {


            foreach ($current_workouts_details as $key => $val)
            {
                $total_box_jump_obj = !empty($val->box_jump) ? json_decode($val->box_jump) :'';
                $total_distance_obj = !empty($val->distance) ? json_decode($val->distance) : '';
                $total_weight_obj = !empty($val->weight) ?  json_decode($val->weight) :'';
                $total_times_obj = !empty($val->times) ? json_decode($val->times) :'';

                if ($graph_plot_by == 'weekly')
                {
                    $new_key = date('l', strtotime($val->complete_date));
                }
                else
                {
                    $new_key = date('F', strtotime($val->complete_date));
                }




                if (isset($total_weight_obj->text) && !empty($total_weight_obj->text)) {
                    
                    $weight = true;
                    switch ($total_weight_obj->unit) {
                        case 'lb':
                            $total_weight_val[trim($new_key)][$key] = floatval($total_weight_obj->text * 0.453592);
                            break;

                        default:
                            $total_weight_val[trim($new_key)][$key] = $total_weight_obj->text;
                            break;

                    }


                }

                if (!empty($total_box_jump_obj) && !empty($total_box_jump_obj->text)) {
                    $box_jump = true;
                    switch ($total_box_jump_obj->unit) {
                        case 'cm':
                            $total_box_jump_val[trim($new_key)][$key] = floatval($total_box_jump_obj->text / 2.54);
                            break;

                        default:
                            $total_box_jump_val[trim($new_key)][$key] = $total_box_jump_obj->text;
                            break;

                    }
                }


                if (!empty($total_distance_obj) && !empty($total_distance_obj->text)) {
                    $distance = true;
                    switch ($total_distance_obj->unit) {
                        default:
                            $total_distance_val[trim($new_key)][$key] = $total_distance_obj->text;
                            break;
                    }
                }
                

                if (!empty($total_times_obj) && !empty($total_times_obj->text)) {
                    $time_chunk = explode(':', $total_times_obj->text);
                    $time = true;
                    if (!empty($time_chunk[0]) || !empty($time_chunk[1]) || !empty($time_chunk[2])) {
                        $total_time_val[trim($new_key)][$key] = floatval(($time_chunk[0]*60) + $time_chunk[1] + ($time_chunk[2]*0.0166667));
                    }
                }





                if (!empty($val->reps)) {
                    $reps = true;
                    $total_repeat_val[trim($new_key)][$key] = $val->reps;
                }


            }

            $yAxis_title = '';
            $yAxis_title_arr = array();

            $weight_arr = array();
            if ($weight)
            {
                //Weight Repeat with
                $weight_arr['name'] = 'Weight';
                $weight_data = array();
                $total_reps_data = array();
                $total_reps_arr = array();


                foreach ($total_weight_val as $key => $val)
                {


                    if(is_array($val))
                    {


                       $max_val = (float) max($val);
                       $max_val_key = array_search($max_val, $val);
                       $weight_data[] = (float) number_format($max_val, 2, '.', '');
                       $total_reps_data[] = (float) number_format($total_repeat_val[$key][$max_val_key],2, '.', '');

                    }
                    else
                    {
                       $weight_data[] =  (float) number_format($val, 2, '.', '');
                       $total_reps_data[] = (float) number_format($total_repeat_val[$key], 2, '.', '');
                    }

                }
              
                $weight_arr['data'] = $weight_data;
                $yAxis_title_arr[] = 'Weight';

                if ($reps)
                {

                    $total_reps_arr['name'] = 'Repeat';
                    $total_reps_arr['data'] = $total_reps_data;
                    $yAxis_title_arr[] = 'Repeat';
                }

            }


            $time_arr = array();
            $distance_arr = array();
            if ($distance)
            {
                $distance_arr['name'] = 'Distance';
                $time_data = array();
                $distance_data = array();
                foreach ($total_distance_val as $key => $val)
                {
                     if(is_array($val))
                    {
                        $max_val = (float) max($val);
                        $max_val_key = array_search($max_val, $val);
                        $distance_data[] = (float)  number_format($max_val, 2, '.', '');
                        $time_data[] = (float)  number_format($total_time_val[$key][$max_val_key], 2, '.', '');

                    }
                    else
                    {
                        $distance_data[] =  (float) number_format($val, 2, '.', '');
                        $time_data[] = (float) number_format($total_time_val[$key], 2, '.', '');
                    }


                }
                $distance_arr['data'] = $distance_data;
                $yAxis_title_arr[] = 'Kilometer';
                if ($time) {
                    $time_arr['name'] = 'Time';
                    $time_arr['data'] = $time_data;
                    $yAxis_title_arr[] = 'Minutes';
                }
            }


            $box_jump_arr = array();
            if ($box_jump)
            {
                $box_jump_arr['name'] = 'Box Jump';
                $box_jump_data = array();
                $time_data = array();


                foreach ($total_box_jump_val as $key => $val)
                {
                    if(is_array($val))
                    {
                        $max_val = (float) max($val);
                        $max_val_key = array_search($max_val, $val);
                        $box_jump_data[] = (float) number_format($max_val, 2, '.', '');
                        $time_data[] = (float) number_format($total_time_val[$key][$max_val_key], 2, '.', '');

                    }
                    else
                    {
                        $box_jump_data[] =  (float) number_format($val, 2, '.', '');
                        $time_data[] = (float) number_format($total_time_val[$key], 2, '.', '');;
                    }

                }

                $box_jump_arr['data'] = $box_jump_data;
                $yAxis_title_arr[] = 'Inch';

                if ($time) {
                    $time_arr['name'] = 'Time';
                    $time_arr['data'] = $time_data;
                    $yAxis_title_arr[] = 'Minutes';
                }

            }


            $options_obj = array();
            $r = 0;
            if (!empty($weight_arr)) {
                $options_obj[$r] = $weight_arr;
                $r++;
            }

            if (!empty($time_arr)) {
                $options_obj[$r] = $time_arr;
                $r++;
            }

            if (!empty($box_jump_arr)) {
                $options_obj[$r] = $box_jump_arr;
                $r++;
            }


            if (!empty($total_reps_arr)) {
                $options_obj[$r] = $total_reps_arr;
                $r++;
            }


            if (!empty($distance_arr)) {
                $options_obj[$r] = $distance_arr;
                $r++;
            }


            //pre($options_obj);
            $yAxis_title = implode(' / ', $yAxis_title_arr);


            $options = array(
                'title' => array(
                    'text' => 'My personal bests',
                    'x' => -20,
                ),
                'xAxis' => array(
                    'categories' => ($graph_plot_by == 'weekly') ? array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') : array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                ),

                'yAxis' => array(
                    'title' => array('text' => $yAxis_title),
                    'plotLines' => array(array('value' => 0, 'width' => 1, 'color' => '#808080')),
                ),

                'legend' => array('layout' => 'vertical', 'align' => 'right', 'verticalAlign' => 'middle', 'borderWidth' => 0),
                'series' => $options_obj,
            );


            return json_encode($options);
        }
        else
        {
            return 0;

        }
    }

}
add_action('wp_ajax_get_personal_best_for_graph','get_personal_best_for_graph');


function get_personal_best_for_graph(){
    global $wpdb;
    if(!empty($_POST['wid'])) {
        echo get_personal_best_for_graph_json_option($_POST['wid'], $_POST['graph_plot_by']);
        exit();
    }
}

/*
 * wod hero user_info save
 */

add_action( 'wp_ajax_wod_hero', 'wod_hero_action_callback' );
function wod_hero_action_callback()
{
    extract($_POST);

    if (wp_verify_nonce($wod_hero_user_info_security_code, 'wod_hero_user_info_code'))
    {

        $current_user_detail = wp_get_current_user();
        $profile_picture = get_user_meta(get_current_user_id(), 'profile_picture');
        $display_name = $_POST['display_name'];
        // pre($_POST); exit();
        if (empty($display_name) || (empty($trainer_id) && $current_user_detail->roles[0] != 'trainer')) {
            wp_redirect(site_url('/settings/') . '?error=' . urlencode('Sorry! Please fill the required field*.'));
            exit();
        }


        update_user_meta($current_user_detail->ID, 'gender', $_POST['gender']);
        $display_name_arr = explode(' ', $_POST['display_name']);
        $last_name = !empty($display_name_arr[1]) ? $display_name_arr[1] :'';
        wp_update_user(array('ID' => $current_user_detail-> ID, 'display_name'=>$display_name, 'first_name' => !empty($display_name_arr[0]) ? $display_name_arr[0] :'', 'last_name' => $last_name));

        $user_id = $current_user_detail->ID;
        if ($current_user_detail->roles[0] == 'trainer') {

           /* if ($gym_name) {*/
                $gym_name_last = get_user_meta($user_id, 'gym_name', true);
                 update_user_meta($user_id, 'gym_name', $gym_name);
           /* }*/

            /*if ($insta_link) {*/
                $insta_last = get_user_meta($user_id, 'my_instagram', true);
                update_user_meta($user_id, 'my_instagram', $insta_link);
            /*}*/

           /* if ($fb_link) {*/
                $fb_last = get_user_meta($user_id, 'my_facebook', true);
                update_user_meta($user_id, 'my_facebook', $fb_link);
           /* }*/

            /*if ($tw_link) {*/
                $tw_last = get_user_meta($user_id, 'my_twitter', true);
                update_user_meta($user_id, 'my_twitter', $tw_link);
            /*}*/


        } else{

            /*if ($trainer_id) {*/
                $trainer_last = get_user_meta($user_id, 'my_trainer', true);
                update_user_meta($user_id, 'my_trainer', $trainer_id);
            /*}*/

           /* if ($gender) {*/
                $gender_last = get_user_meta($user_id, 'my_gender', true);
                update_user_meta($user_id, 'my_gender', $gender);

           /* }*/

        }



    }
    else {
        wp_redirect(site_url('/settings/') . '?error=' . urlencode('Form submission failed!, Please fill in the required field.'));
        exit();

    }

    wp_redirect(site_url('/settings/') . '?msg=' . urlencode('Successfully updated!'));
    exit();

}



function time_formatted_pad($number) {
    if( $number < 10 )
    {
        return str_pad((int) $number,2,"0",STR_PAD_LEFT);
    }
    return   $number;
}

