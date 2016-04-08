<?php
/* Template Name: Social sign in */

if(!isset($_SESSION['SocialLogin']) && empty($_SESSION['SocialLogin'])){
    wp_redirect(site_url()); die;
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
                <div class="col-md-10 col-md-offset-2">
                    <div class="col-md-10 col-sm-6 grayStyle">
                        <div class="box-inner">
                            <div class="alert" id="regMessage" style="display: none;"></div>
                            <form class="" method="post" id="social_register">
                                <div class="form-group">
                                    <label>User Type</label>
                                    <select class="form-control" name="user_type" id="user_type">
                                        <option value="">Select User Type</option>
                                        <option value="normal_user">Normal User</option>
                                        <option value="trainer">Trainer</option>
                                    </select></div>
                                <div class="form-group user_gender_for_normal_user normal-hide">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Select User Gender</option>
                                        <option  <?php if(strtolower($_SESSION['SocialLogin']['gender']) == 'male'){ echo 'selected'; } ; ?> value="male">Male</option>
                                        <option <?php if(strtolower($_SESSION['SocialLogin']['gender']) == 'female'){ echo 'selected'; } ; ?> value="female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Your Name" value="<?php echo $_SESSION['SocialLogin']['name']; ?>">
                                </div>
                                <?php if(!empty($_SESSION['SocialLogin']['email'])): ?>
                                    <div class="form-group">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Address" value="<?php echo $_SESSION['SocialLogin']['email']; ?>" readonly="">
                                    </div>
                                <?php else: ?>
                                    <div class="form-group">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Address">
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password">
                                </div>
                                <input type="hidden" name="socialId" value="<?php echo $_SESSION['SocialLogin']['socialId']; ?>">
                                <input type="hidden" name="screen_name" value="<?php echo $_SESSION['SocialLogin']['screen_name']; ?>">
                                <input type="hidden" name="social_type" value="<?php echo $_SESSION['SocialLogin']['social_type']; ?>">
                                <input type="hidden" name="socialImage" value="<?php echo $_SESSION['SocialLogin']['socialImage']; ?>">
                                <?php wp_nonce_field('registercode','securitycode',false); ?>
                               <div class="ftext"> <input type="submit" name="submit" value="Signup"> </div>
                            </form>
                            <div class="clear"></div>
                            <p class="green-bg">Already A Member? <a href="<?php echo site_url().'/login'; ?>">Click Here</a> to Login here</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#social_register').formValidation({
                fields: {
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
                                message: 'The value is not a valid email address'
                            }
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
                var frmdata = jQuery("#social_register").serializeArray();
                frmdata.push({name:'action',value:'socialRegister'});
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