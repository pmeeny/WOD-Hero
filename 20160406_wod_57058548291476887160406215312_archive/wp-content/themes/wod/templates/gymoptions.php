<?php
/* Template Name: Option Page */

if(!(is_user_logged_in() )) {
    wp_redirect(site_url().'/login');
    exit();
}
else
{
    wp_redirect(site_url().'/settings');
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
    <?php $user_detail = wp_get_current_user();
    $gym_name_last = get_user_meta( $user_detail->ID, 'gym_name', true );
    $insta_last = get_user_meta( $user_detail->ID, 'my_instagram', true );
    $fb_last = get_user_meta( $user_detail->ID, 'my_facebook', true );
    $tw_last = get_user_meta( $user_detail->ID, 'my_twitter', true );
    if($user_detail->roles[0] == 'trainer'){ ?>
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
                            <div class="reg_error"></div>
                            <div class="reg_success"></div>
                            <form id="trainer_form" method="post">
                                <table class="best1" width="100%" border="0" cellspacing="0" cellpadding="0">
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
                                    <tr>
                                        <td><i class="fa fa-instagram fa-fw"></i><label>INSTAGRAM</label></td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="insta_link" id="insta_link" value="<?php if(isset($insta_last) && $insta_last != ''){ echo $insta_last; } ?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-facebook-square fa-fw"></i><label>FACEBOOK</label></td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="fb_link" id="fb_link" value="<?php if(isset($fb_last) && $fb_last != ''){ echo $fb_last; } ?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-twitter-square fa-fw"></i><label>TWITTER</label></td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="tw_link" id="tw_link" value="<?php if(isset($tw_last) && $tw_last != ''){ echo $tw_last; } ?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php wp_nonce_field('trainercode','securitycode',false); ?>
                                            <input type="hidden" name="user_id" value="<?php echo $user_detail->ID; ?>">
                                        </td>
                                        <td><input type="submit" name="submit" value="Submit"></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }else{ ?>
        <div class="container">
            <div class="inner-content">
                <h2><?php if(get_post_meta( get_the_ID(), 'Title', true )){
                        echo get_post_meta( get_the_ID(), 'Title', true );
                    }else{ echo get_the_title(); } ?></h2>
                <div class="row">
                    <div class="col-md-4">
                        <?php get_sidebar(); ?>
                    </div>

                    <div class="col-md-8">
                        <div class="personal-best">
                            <div class="reg_error"></div>
                            <div class="reg_success"></div>
                            <?php
                            $trainer_last = get_user_meta( $user_detail->ID, 'my_trainer', true );
                            $gender_last = get_user_meta( $user_detail->ID, 'my_gender', true ); ?>
                            <form name="useroption_form" id="useroption_form" method="post">
                                <table class="best1" width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="38%"><label class="user_option">Your Gyms/Personal Trainer</label></td>
                                        <td width="62%">
                                            <div class="form-group">
                                                <select name="trainer_id" id="trainer_id" class="form-control">
                                                    <option value="">Select Gym</option>
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
                                   <!-- <tr>
                                        <td><label class="user_option">SEX</label></td>
                                        <td>
                                            <div class="form-group">
                                                <label><input type="radio" class="form-radio" name="gender" id="gender1" value="male" <?php /*if(isset($gender_last) && $gender_last == 'male'){ echo 'checked'; } */?>> Male </label>
                                                <label>
                                                <input type="radio" class="form-radio" name="gender" id="gender2" value="female" <?php /*if(isset($gender_last) && $gender_last == 'female'){ echo 'checked'; } */?>> Female </label>
                                            </div>
                                        </td>
                                    </tr>-->
                                    <tr>
                                        <td><?php wp_nonce_field('useroptioncode','securitycode',false); ?>
                                            <input type="hidden" name="user_id" value="<?php echo $user_detail->ID; ?>">
                                        </td>
                                        <td><input type="submit" name="submit" value="Submit"></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

<?php endwhile; ?>

<?php get_footer(); ?>