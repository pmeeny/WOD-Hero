<?php get_header(); ?>

<div class="clear"></div>

       <div class="container">
        <div class="inner-content">
            <h2>Activate Account</h2>
            <div class="row">
                <div class="about-us">
                    <?php
                    if(isset($_REQUEST['key']) && !empty($_REQUEST['key'])){
                        $user_id = syonencryptor('decrypt',$_REQUEST['key']);
                        $requested_key = $_REQUEST['key'];
                        if($user_id){
                            $code = get_user_meta( $user_id, 'has_to_be_activated', true );
                            if ($code == $requested_key) {
                                delete_user_meta( $user_id, 'has_to_be_activated' );
                                $secret =  get_user_meta($user_id, 'meta_secret',true);
                                //asthmacare_welcome_user_notification($user_id,$secret);
                                $user_info = get_userdata($user_id);
                                echo "<p class='alert alert-success'>Your account is now active!.</p>"; ?>

                                <div id="signup-welcome">
                                    <p><span class="h3"><?php _e('Email address:'); ?></span> <?php echo $user_info->user_email ?></p>
                                    <p><span class="h3"><?php _e('Password:'); ?></span> <?php echo $secret; ?></p>
                                </div>

                                <?php
                                printf( __('<a href="%1$s">View your site</a> or <a href="%2$s">Log in</a>'), site_url(), get_permalink(15));
                            }
                            else{
                                echo "<p class='alert alert-danger'>Activation link has been expired!.</p>";
                            }
                        }
                        else{
                            echo "<p class='alert alert-danger'>Activation Key Doesn't matach with the requested key.</p>";
                        }
                    }
                    else{
                        echo "<p class='alert alert-danger'>Activation Key Required.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        </div>



    <script type="text/javascript">
        var key_input = document.getElementById('key');
        key_input && key_input.focus();
    </script>

<?php get_footer(); die;?>