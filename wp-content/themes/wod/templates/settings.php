<?php
/* Template Name: Settings */
if(!(is_user_logged_in() )) {
    wp_redirect(site_url().'/login');
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
                <div class="col-md-4 col-sm-4">
                    <?php get_sidebar(); ?>
                </div>
                <div class="col-md-8 col-sm-8">
                    <div class="personal-best">
                        <?php
                        if(!empty($_REQUEST['error']))
                        {
                            ?>
                            <div class="alert alert-danger"><?php echo urldecode($_REQUEST['error']); ?></div>
                        <?php
                        }
                        if(!empty($_REQUEST['msg']))
                        {
                         ?>
                            <div class="alert alert-success"><?php echo urldecode($_REQUEST['msg']); ?></div>
                            <?php
                        }
                        ?>

                        <form name="profilePicUpload" id="profilePicUpload" action="" method="post" enctype="multipart/form-data" style="display: none;">
                            <input type="file" name="userPic" id="userPic">
                            <input type="hidden" name="action" value="uploadUserPic">
                            <input type="submit" value="Update User Profile" id="updateUserPic" style="display: none;">
                        </form>

                        <form action="<?php echo admin_url('admin-ajax.php'); ?>" id="profile_setting_form" method="post">
                            <input type="hidden" name="action" value="wod_hero" />
                            <?php
                                     $current_user_detail = wp_get_current_user();
                                     $wod_hero_user_info = get_user_meta( $current_user_detail->ID, 'wod_hero_user_info', true );
                            ?>
                            <table class="best1" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="23%"><label>YOUR NAME*</label></td>
                                    <td width="77%">
                                        <div class="form-group">
                                            <input type="text" class="form-control field_required" name="display_name" id="display_name" value="<?php echo !empty($current_user->display_name) ? $current_user->display_name :''; ?>">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="23%"><label>UPLOAD YOUR PHOTO*</label></td>
                                    <td width="77%">
                                        <div class="form-group">
                                            <div class="photo">
                                                <?php $profile_pic_id = get_user_meta(get_current_user_id(), 'profile_picture', true); ?>
                                                <div id="userprofilePic">
                                                    <div style="margin-bottom: 7px;"> <?php if(!empty($profile_pic_id)): echo wp_get_attachment_image($profile_pic_id, array('150', '150')); ?></div>
                                                    <a data-upload_id="<?php echo $profile_pic_id; ?>" class="action_delete btn btn-success btn-sm" onclick="deleteUserPic(this)" href="javascript:void(0);">Delete</a>
                                                    <a href="javascript:void(0)" id="editProfilePic" class="btn btn-success btn-sm">Edit</a>
                                                    <?php else: ?>
                                                        <div id="upload">upload Photo</div>
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               <?php $user_detail = wp_get_current_user();
    $gym_name_last = get_user_meta( $user_detail->ID, 'gym_name', true );
    $insta_last = get_user_meta( $user_detail->ID, 'my_instagram', true );
    $fb_last = get_user_meta( $user_detail->ID, 'my_facebook', true );
    $tw_last = get_user_meta( $user_detail->ID, 'my_twitter', true );

    //pre($user_detail);
    if($user_detail->roles[0] == 'trainer'){ ?>





                                    <tr>
                                        <td width="23%"><i class="fa fa-thumbs-up fa-fw"></i><label>GYM NAME</label></td>
                                        <td width="77%">
                                            <div class="form-group">
                                                <select name="gym_name" id="gym_name" class="form-control">
                                                    <option value="">Select Gym</option>
                                                    <?php $args=array(
                                                        'posts_per_page' => -1,
                                                        'post_type' => 'gym',
                                                        'post_status' => 'publish',
                                                        'caller_get_posts'=> 1,
                                                        'order'=> 'desc');

                                                    $my_query = null;
                                                    $my_query = new WP_Query($args);
                                                    if( $my_query->have_posts() ) {
                                                        while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                                            <option value="<?php the_ID(); ?>" <?php if(isset($gym_name_last) && $gym_name_last == get_the_ID()){ echo 'selected="selected"'; } ?>><?php the_title(); ?></option>
                                                        <?php endwhile;
                                                    } ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                        <td><i class="fa fa-facebook-square fa-fw"></i><label>FACEBOOK</label></td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control check_real_val" name="fb_link" id="fb_link" value="<?php if(isset($fb_last) && $fb_last != ''){ echo $fb_last; } ?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-twitter-square fa-fw"></i><label>TWITTER</label></td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control check_real_val" name="tw_link" id="tw_link" value="<?php if(isset($tw_last) && $tw_last != ''){ echo $tw_last; } ?>">
                                            </div>
                                        </td>
                                    </tr>
    <?php }else{ ?>


        <?php
        $gender = get_user_meta( get_current_user_id(), 'gender', true );
        ?>
        <tr>
            <td><label>SEX</label></td>
            <td>
                <div class="form-group">
                    <label><input type="radio" class="form-radio" name="gender" id="gender1" value="male" <?php if(isset($gender) && $gender == 'male'){ echo 'checked'; } ?>> Male </label>
                    <label>
                        <input type="radio" class="form-radio" name="gender" id="gender2" value="female" <?php if(isset($gender) && $gender == 'female'){ echo 'checked'; } ?>> Female </label>
                </div>
            </td>
        </tr>


                       <?php
                            $trainer_last = get_user_meta( $user_detail->ID, 'my_trainer', true );


                            $gender_last = get_user_meta( $user_detail->ID, 'my_gender', true ); ?>

                                    <tr>
                                        <td width="38%"><label>YOUR GYM*</label></td>
                                        <td width="62%">
                                            <div class="form-group">
                                                <select name="trainer_id" id="trainer_id" class="form-control field_required">
                                                    <option value="">Select your GYM</option>
                                                    <?php $args=array('role'=>'trainer', 'orderby' => 'user_nicename', 'order' => 'ASC');
                                                    $trainers = get_users($args);
                                                    foreach($trainers as $trainer){
                                                        $fname = get_user_meta( $trainer->ID, 'first_name', true );
                                                        $lname = get_user_meta( $trainer->ID, 'last_name', true ); ?>
                                                        <option value="<?php echo $trainer->ID; ?>" <?php if(isset($trainer_last) && $trainer_last == $trainer->ID){ echo 'selected="selected"'; } ?>><?php echo $fname." ".$lname; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
    <?php } ?>


                    <tr>
                                    <td><?php wp_nonce_field('wod_hero_user_info_code','wod_hero_user_info_security_code'); ?>
                                    </td>
                                    <td><input type="submit" class="submit_btn" name="submit" value="Submit"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
    <script type="text/javascript">
        jQuery(document).ready(function(){
/*---------------------------------------------------------------------------------------
/* Add Form Validation
/*----------------------------------------------------------------------------------------*/
    jQuery("body").delegate('.submit_btn', 'click', function(e){
        var has_error = false;
        var index = 0;


        if(jQuery("#userprofilePic").find('img').length > 0)
        {
            jQuery("#userprofilePic").removeClass('has_error');
             has_error = false;


        }
        else
        {

            jQuery("#userprofilePic").addClass('has_error');
             has_error = true;
             jQuery("#userprofilePic").focus();

        }



        jQuery('.field_required').removeClass('has_error');
        jQuery(this).parents('form').find('.field_required').each(function(){

            if(jQuery(this).is(':visible'))
            {

                var isval =jQuery(this).val().trim();

                if(!isval)
                {
                    has_error = true;
                    jQuery(this).addClass('has_error');
                    if(index == 0)
                    {
                        jQuery(this).focus();
                        index++;
                    }


                }
                else
                {

                    jQuery(this).removeClass('has_error');
                    if(jQuery(this).attr('name') == 'email'  || jQuery(this).attr('name') == 'username')
                    {
                        var username_email = jQuery(this).val();
                        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                        var quotes_regex = /['|"]/g;

                        if((!regex.test(username_email) && jQuery(this).attr('name') == 'email') || quotes_regex.test(username_email))
                        {
                            jQuery(this).addClass('has_error');
                            if(index == 0)
                            {
                                has_error = true;
                                jQuery(this).focus();
                                index++;
                            }
                        }

                    }



                }
            }
        });

        jQuery(this).parents('form').find('.check_real_val').each(function(){

            if(jQuery(this).is(':visible'))
            {

                var isval =jQuery(this).val().trim();

                if(isval !='')
                {
                   switch(jQuery(this).attr('name'))
                   {
                        case 'insta_link':

                                        var insta_link = /instagram.com/g;
                                        if(!insta_link.test(isval))
                                        {
                                                has_error = true;
                                                jQuery(this).addClass('has_error');
                                                if(index == 0)
                                                {
                                                    jQuery(this).focus();
                                                    index++;
                                                }

                                        }
                                         else
                                {
                                    jQuery(this).removeClass('has_error');
                                }
                                        break;


                        case 'fb_link':
                                        var fb_link = /facebook.com/g;
                                        if(!fb_link.test(isval))
                                        {
                                                has_error = true;
                                                jQuery(this).addClass('has_error');
                                                if(index == 0)
                                                {
                                                    jQuery(this).focus();
                                                    index++;
                                                }

                                        }
                                         else
                                {
                                    jQuery(this).removeClass('has_error');
                                }
                                        break;

                        case 'tw_link':

                                        var tw_link = /twitter.com/g;
                                        if(!tw_link.test(isval))
                                        {
                                                has_error = true;
                                                jQuery(this).addClass('has_error');
                                                if(index == 0)
                                                {
                                                    jQuery(this).focus();
                                                    index++;
                                                }

                                        }
                                         else
                                {
                                    jQuery(this).removeClass('has_error');
                                }
                                        break;



                   }
                }

            }
        });




        if(has_error)
        {
            return false;
        }
        else
        {
        return true;

        }
    });


jQuery("body").delegate('#userprofilePic', 'click', function($){

     if(jQuery("#userprofilePic").find('img').length > 0)
        {
            jQuery("#userprofilePic").removeClass('has_error');
             has_error = false;


        }
        else
        {

            jQuery("#userprofilePic").addClass('has_error');
             has_error = true;
             jQuery("#userprofilePic").focus();

        }

});

    //add validation
    jQuery("body").delegate('.field_required', 'keyup , change', function($){
        var isval =jQuery(this).val().trim();
        // alert(isval);
        if(!isval)
        {
            jQuery(this).addClass('has_error');
        }
        else
        {



            jQuery(this).removeClass('has_error');
        }
    });

        //add validation
    jQuery("body").delegate('.check_real_val', 'keyup , change', function($){
        var isval =jQuery(this).val().trim();
        if(isval !='')
        {
           switch(jQuery(this).attr('name'))
           {
                case 'insta_link':

                                var insta_link = /instagram.com/g;
                                if(!insta_link.test(isval))
                                {
                                    jQuery(this).addClass('has_error');

                                }
                                else
                                {
                                    jQuery(this).removeClass('has_error');
                                }
                                break;


                case 'fb_link':
                                var fb_link = /facebook.com/g;
                                if(!fb_link.test(isval))
                                {

                                        jQuery(this).addClass('has_error');


                                }
                                 else
                                {
                                    jQuery(this).removeClass('has_error');
                                }
                                break;

                case 'tw_link':

                                var tw_link = /twitter.com/g;
                                if(!tw_link.test(isval))
                                {

                                        jQuery(this).addClass('has_error');


                                }
                                 else
                                {
                                    jQuery(this).removeClass('has_error');
                                }
                                break;



           }
        }
        else
        {
            jQuery(this).removeClass('has_error');

        }
    });








        });

    </script>
<?php get_footer(); ?>