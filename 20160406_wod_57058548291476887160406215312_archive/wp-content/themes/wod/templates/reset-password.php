<?php
/* Template Name: Reset Password */

if(is_user_logged_in() ) {
    wp_redirect(site_url());
    exit();
}



if(isset($_GET['key']) && isset($_GET['login'])){
    $user = check_password_reset_key($_GET['key'],$_GET['login']);
    //pre($user); die;
    $message = 'Enter your new password below.';
    $class = 'alert alert-danger';
    if ( is_wp_error($user) ) {
        if ( $user->get_error_code() === 'expired_key' )
            wp_redirect( site_url( 'wp-login.php?action=lostpassword&error=expiredkey' ) );
        else
            wp_redirect( site_url( 'wp-login.php?action=lostpassword&error=invalidkey' ) );
        exit;
    }
    else
    {
        $action = 'resetpass';
    }
}
else{
    wp_redirect(site_url());
     exit();
}

get_header();

?>

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
            <h2><?php if(get_post_meta( get_the_ID(), 'Title', true )){
                    echo get_post_meta( get_the_ID(), 'Title', true );
                }else{ echo get_the_title(); } ?></h2>
            <div class="row">
                <div class="col-md-10  col-md-offset-2">
                    <div class="col-md-10 col-sm-6 grayStyle">
                        <div class="box-inner">
                            <h3>Reset Your Password</h3>
                            <div class="alert" id="lgnMessage" style="display: none;"></div>

                            <form name="resetpass" id="resetpass" method="post">
                                <div class="form-group">
                                    <input type="password" name="pass1" id="pass1" class="require form-control" autocomplete="off" placeholder="New Password"/>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="pass2" id="pass2" class="require form-control" autocomplete="off" placeholder="Confirm new password"/>
                                </div>
                                <input type="hidden" name="key" id="key" value="<?php echo $_GET['key']; ?>">
                                <input type="hidden" name="login" id="login" value="<?php echo $_GET['login']; ?>">
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <input type="submit" class="button btnBlue add" name="submit" value="Submit" />
                                </div>
                                <div class="clearfix"></div>
                            </form>

                            <div class="clearfix"></div>
                            <p class="green-bg">Have username and password then login to your account.<a href="<?php echo site_url().'/login'; ?>">Click Here</a></p>
                            <p class="green-bg">Not A member yet? Create new account <a href="<?php echo site_url().'/sign-up'; ?>">Click Here</a></p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>