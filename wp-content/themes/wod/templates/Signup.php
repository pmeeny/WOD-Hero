<?php
/* Template Name: Sign Up */

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
                <div class="col-md-10 col-md-offset-1">
                    <div class="col-md-6 col-sm-6 grayStyle">
                        <div class="box-inner">
                            <h3>Create A New Account here</h3>
                            <div class="alert" id="regMessage" style="display: none;"></div>

                            <form class="" method="post" id="wod_register">
                                <div class="row">
                                <div class="form-group">
                                	<div class="col-sm-3 col-xs-4"><label>User Type</label></div>
                                    <div class="col-sm-9  col-xs-8"><select class="form-control" name="user_type" id="user_type">
                                        <option value="">Select User Type</option>
                                        <option value="normal_user">Gym User</option>
                                        <option disabled value="trainer">Gym Owner</option>
                                    </select></div>
                                </div>
                              </div>
                                <div class="user_gender_for_normal_user normal-hide">
                                    <div class="row ">
                                        <div class="form-group">
                                            <div class="col-sm-3 col-xs-4"><label>Gender</label></div>
                                            <div class="col-sm-9  col-xs-8"><select class="form-control field_required" name="gender" id="gender">
                                                    <option value="">Select User Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="gym_id_val form-group normal-hide">
                                    <div class="row ">
                                    <div class="col-sm-3 col-xs-4"><label>Gym</label></div>
                                    <div class="col-sm-9 col-xs-8"><select name="trainer_id" id="trainer_id" class="form-control field_required">
                                        <option value="">Select your GYM</option>
                                        <?php $args=array('role'=>'trainer', 'orderby' => 'user_nicename', 'order' => 'ASC');
                                        $trainers = get_users($args);
                                        foreach($trainers as $trainer){
                                            $fname = get_user_meta( $trainer->ID, 'first_name', true );
                                            $lname = get_user_meta( $trainer->ID, 'last_name', true ); ?>
                                            <option value="<?php echo $trainer->ID; ?>" <?php if(isset($trainer_last) && $trainer_last == $trainer->ID){ echo 'selected="selected"'; } ?>><?php echo $fname." ".$lname; ?></option>
                                        <?php } ?>
                                    </select></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Your Full Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email Address">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password">
                                </div>

                                <input type="hidden" name="redirect_to" value="<?php echo (isset($_REQUEST['redirect_to'])) ? urldecode($_REQUEST['redirect_to']) : site_url().'/my-profile';?>">
                                <?php wp_nonce_field('registercode','securitycode',false); ?>
                               <div class="ftext"> <input type="submit" name="submit" value="Signup"> </div>
                            </form>
                            <div class="clear"></div>
                            <p class="green-bg">Already a member? <a href="<?php echo site_url().'/login'; ?>">Click Here</a> to login
                            <br>Gym Owners, to get your gym listed <a href="<?php echo site_url().'/contact-us'; ?>">Contact Us</a>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="signup-inner">
                            <h3>Affiliates</h3>
                      <!--   <a href="javascript:void(0)" onclick="Login()"><img src="<?php //bloginfo('template_url'); ?>/images/fb-button.png"></a>

                            <a href="javascript:GooglePluslogin();"><img src="<?php //bloginfo('template_url'); ?>/images/g+Button.png"></a> -->

                            <img src="<?php bloginfo('template_url'); ?>/images/chapter2fitness.jpg" style="width:200px;height:200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endwhile; ?>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#wod_register').formValidation({
                fields: {
                    trainer_id: {
                        validators: {
                            notEmpty: {
                                message: "Don't forget to select your gym"
                            }
                        }
                    },
                    gender: {
                        validators: {
                            notEmpty: {
                                message: "Don't forget to set your gender"
                            }
                        }
                    },
                    user_type: {
                        validators: {
                            notEmpty: {
                                message: 'User Type is required and cannot be empty'
                            }
                        }
                    },

                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Name is required and cannot be empty'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email Address is required and cannot be empty'
                            },
                            emailAddress: {
                                message: 'The value is not a valid email address, make sure it contains no spaces'
                            },

                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Password is required and cannot be empty'
                            },
                            stringLength: {
                                min: 6,
                                max: 20,
                                message: 'The Password must be more than 6 and less than 20 characters long'
                            }
                        }
                    },
                    cpassword: {
                        validators: {
                            notEmpty: {
                                message: 'Confirm Password is required and cannot be empty'
                            },
                            identical: {
                                field: 'password',
                                message: 'The password and its confirm are not same'
                            }
                        }
                    }
                },  rules: {
                    field: {
                        required: true
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
                var frmdata = jQuery("#wod_register").serializeArray();
                frmdata.push({name:'action',value:'registerplus'});
                ajaxLoaderStart();
                if(set_flag){
                    jQuery.post(ajaxurl, frmdata, function(result) {
                        response = result;
                        //alert(response.message);
                        //SiteUrl = SiteUrl.replace(/\/$/, '');
                        if(response.success && response.class == 'alert-success'){
                            ajaxLoaderStop();
                            $("#regMessage").removeClass("alert-danger alert-success").addClass(response.class);
                            $('#regMessage').html(response.message).show();

                        }
                        if(response.success && response.class == 'alert-danger'){
                            $("#regMessage").removeClass("alert-danger alert-success").addClass(response.class);
                            $('#regMessage').html(response.message).show();
                            ajaxLoaderStop();
                        }
                        $form
                            .formValidation('disableSubmitButtons', false) // Enable the submit buttons
                            .formValidation('resetForm', true);
                    }, 'json');
                }

                    ajaxLoaderStop();

            });
        });
    </script>
<?php get_footer(); ?>