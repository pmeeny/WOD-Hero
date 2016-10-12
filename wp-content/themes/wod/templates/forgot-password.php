<?php
/* Template Name: Forgot Password */

if(is_user_logged_in() ) {
    wp_redirect(site_url());
   exit();
}

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

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
                            <h3>Forgot Your Password</h3>
                            <div class="alert" id="lgnMessage" style="display: none;"></div>

                        <form name="lostpassword" id="lostpassword" method="post">
                            <div class="form-group">
                                <div class="input-group">
                                    <input style="margin: 0;" type="text" name="user_login" id="user_login" class="require form-control" placeholder="Email address"/>
                                    <span class="input-group-addon" style="padding: 0"><input style="padding: 12px;" type="submit" name="submit" class="button btnBlue" value="Submit" /></span>
                                </div>
                            </div>
                        </form>

                            <div class="clear"></div>
                            <p class="green-bg">If you have your username and password then login to your account.<a href="<?php echo site_url().'/login'; ?>">Click Here</a></p>
                            <p class="green-bg">Not a member yet? Create a new account <a href="<?php echo site_url().'/sign-up'; ?>">Click Here</a></p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endwhile; ?>
<?php get_footer(); ?>