<?php
/* Template Name: Login */
if(is_user_logged_in() ) {
    wp_redirect(site_url());
    exit();
}
get_header(); ?>


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

                <div class="col-md-10 col-md-offset-1 light-blue">

                    <div class="col-md-6 col-sm-6 grayStyle">

                        <div class="box-inner">

                            <h3>Already A Member? Login Here</h3>

                            <div class="alert" id="lgnMessage" style="display: none;"></div>

                            <form class="" method="post" id="wod_login">

                                <div class="form-group">

                                    <input type="text" class="form-control" name="email" id="email" placeholder="Email Address">

                                </div>

                                <div class="form-group">

                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">

                                </div>

                                <div class="ftext"> <a class="forgot" href="<?php echo site_url('/forgot-password/'); ?>">Forgot Your Password?</a>

                                    <input type="hidden" name="redirect_to" value="<?php echo (isset($_REQUEST['redirect_to'])) ? urldecode($_REQUEST['redirect_to']) : site_url().'/mydashboard';?>">

                                    <?php wp_nonce_field('logincode','securitycode',false); ?>

                                    <input type="submit" value="Login" name="submit"> </div>

                            </form>

                            <div class="clear"></div>

                            <p class="green-bg">Not A member yet? Create new account <a href="<?php echo site_url().'/sign-up'; ?>">Click Here</a></p>

                        </div>

                    </div>



                    <div class="col-md-6 col-sm-6 l-BlueStyle">

                        <div class="box-inner1">

                            <h3>Login with Your Social Account</h3>

                            <?php //do_action( 'wordpress_social_login', array( 'mode' => 'login', 'caption' => '' ) ); ?>

                            <a href="javascript:void(0)" onclick="Login()"><img src="<?php bloginfo('template_url'); ?>/images/fb-button.png"></a>

                            <a href="javascript:GooglePluslogin();"><img src="<?php bloginfo('template_url'); ?>/images/g+Button.png"></a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript">

        jQuery(document).ready(function($){

            $('#wod_login').formValidation({

                fields: {

                    email: {

                        validators: {

                            notEmpty: {

                                message: 'Email Address is required and cannot be empty'

                            },

                            emailAddress: {

                                message: 'The value is not a valid email address'

                            }

                        }

                    },

                    password: {

                        validators: {

                            notEmpty: {

                                message: 'Password is required and cannot be empty'

                            }

                        }

                    }

                }

            }).on('success.form.fv', function(e) {

                // Prevent form submission

                var set_flag = true;

                e.preventDefault();

                // Get the form instance

                var $form = jQuery(e.target);



                // Get the FormValidation instance

                var bv = $form.data('formValidation');

                // Use Ajax to submit form data

                var frmdata = jQuery("#wod_login").serializeArray();

                frmdata.push({name:'action',value:'loginplus'});

                ajaxLoaderStart();

                if(set_flag){

                    jQuery.post(ajaxurl, frmdata, function(result) {

                        response = result;

                        //alert(response.message);

                        //SiteUrl = SiteUrl.replace(/\/$/, '');

                        if(response.success && response.class == 'alert-success'){

                            ajaxLoaderStop();

                            $("#lgnMessage").removeClass("alert-danger alert-success").addClass(response.class);

                            $('#lgnMessage').html(response.message).show();

                            if(response.redirect != ''){
                                window.location.href=response.redirect;
                            }else{
                                window.location.href=siteurl;
                            }
                        }

                        if(response.success && response.class == 'alert-danger'){
                            ajaxLoaderStop();
                            $("#lgnMessage").removeClass("alert-danger alert-success").addClass(response.class);
                            $('#lgnMessage').html(response.message).show();
                        }

                        $form
                            .formValidation('disableSubmitButtons', false) // Enable the submit buttons

                            .formValidation('resetForm', true);
                    }, 'json');

                }
            });
        });

    </script>

<?php get_footer(); ?>