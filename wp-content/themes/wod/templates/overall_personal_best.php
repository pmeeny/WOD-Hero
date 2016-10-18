<?php
/*
 *  Template Name: Overall Personal Best
 */
if (!is_user_logged_in()) {
    wp_redirect(site_url());
    exit();
}
$user_detail = wp_get_current_user();
if($user_detail->roles[0] == 'trainer'){
    wp_redirect(site_url().'/settings');
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
            <h2><?php if (get_post_meta(get_the_ID(), 'Title', true)) {
    echo get_post_meta(get_the_ID(), 'Title', true);
} else {
    echo get_the_title();
} ?></h2>
            <div class="row">

                <form id="myForm" name="myForm" method="post" action="<?php echo site_url('overall-personal-best'); ?>">


                <div class="col-md-4 col-sm-4">
                    <?php get_sidebar(); ?>
                    <div class="sideFilter">

                    <?php
                        if(isset($_REQUEST['paging']))
                        {
                            ?>
                            <input name="paging" type="hidden" value="<?php echo $_REQUEST['paging']; ?>" />
                            <?php
                        }
                    ?>
                        <div class="title">Filter your results</div>
                           <div class="gender-section">
                               <div class="sub-heading">Gender</div>
                                 <ul>
                                     <li><input type="radio"  required <?php if(isset($_REQUEST['gender']) && $_REQUEST['gender'] == 'male'){ echo "checked"; } ?> name="gender" id="Male" value="male"><label for="Male">Male</label></li>
                                     <li><input type="radio" <?php if(isset($_REQUEST['gender']) &&  $_REQUEST['gender'] == 'female'){ echo "checked"; } ?> name="gender" id="Female" value="female"><label for="Female">Female</label></li>
                                 </ul>
                            </div>

                        <div class="gym-section">
                            <div class="sub-heading">GYM</div>
                                <div class="form-group">
                                    <select name="gym_name" id="gym_name" class="form-control field_required">
                                        <option value="">Select your GYM</option>
                                        <?php $args=array('role'=>'trainer', 'orderby' => 'user_nicename', 'order' => 'ASC');
                                        $trainers = get_users($args);
                                        foreach($trainers as $trainer){
                                            $fname = get_user_meta( $trainer->ID, 'first_name', true );
                                            $lname = get_user_meta( $trainer->ID, 'last_name', true ); ?>
                                            <option value="<?php echo $trainer->ID; ?>" <?php if(isset($trainer_last) && $trainer_last == $trainer->ID){ echo 'selected="selected"'; } ?>><?php echo $fname." ".$lname;
                                                ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="exercise-section">
                               <div class="sub-heading">Exercise</div>
                               <ul>
                               <?php
                                $args = array('post_type' =>'workout', 'posts_per_page' => -1, 'post_status' =>'publish');
                                $exercise_query = new WP_Query( $args );
                                // The Loop
                                if ( $exercise_query->have_posts() ) :
                                    global $post;
                                    $p=0;
                                    while ( $exercise_query->have_posts() ) :
                                        $exercise_query->the_post();
                                        ?>
                                        
                                        <?php
                                        //$localhost=$_SERVER["HTTP_HOST"];
                                        if($post->post_title == "Back Squat" || $post->post_title == "Bench Press"|| $post->post_title == "Clean & Jerk"|| $post->post_title == "Clean & Press"|| $post->post_title == "Deadlift"
                                          || $post->post_title == "Front Squat" || $post->post_title == "Hang Clean" || $post->post_title == "Hang Snatch" ||
                                        $post->post_title == "Power Clean" || $post->post_title == "Power Snatch" || $post->post_title == "Push Press" || $post->post_title == "Strict Press"

                                        ){ ?>
                                        
                                        <li><input type="checkbox" id="<?php echo $post->ID; ?>_Exercise"
                                                <?php if(!empty($_REQUEST['exercise']) && in_array($post->ID, $_REQUEST['exercise'])){ echo "checked"; } ?>
                                                   name="exercise[]" value="<?php echo $post->ID; ?>"><label for="<?php echo $post->ID; ?>_Exercise"> <?php the_title(); ?></label></li><?php
                                        
                                        }
                                        $p++;
                                    endwhile;
                                endif;
                                // Reset Post Data
                                wp_reset_postdata();
                                ?>
                               </ul>
                            </div>
                            <div class="submit-section">
                               <button type="submit" id="submitBtn">Filter</button>
                            </div>

                    </div>
                </div>
                <div class="col-md-8 col-sm-8">
                <?php
                    global $wpdb;
                    $conditionArray = $inner_query = '';
                    if(isset($_REQUEST['gender']) && !empty($_REQUEST['gender'])){
                        $conditionArray[] = " m.meta_key = 'gender'";
                        $conditionArray[] = " m.meta_value LIKE '".$_REQUEST['gender']."'";

                        $inner_query = "INNER JOIN {$wpdb->prefix}usermeta m ON m.user_id = u.ID";
                    }
                    if(isset($_REQUEST['exercise']) && !empty($_REQUEST['exercise'])){
                        $exercise = implode(',',$_REQUEST['exercise']);
                        $conditionArray[] = " wd.wk_name IN (".$exercise.")";
                    }
                  $sql_trainer="";
                    if(isset($_REQUEST['gym_name']) && !empty($_REQUEST['gym_name'])){

                        $trainer_id = $_REQUEST['gym_name'];
                        $fname = get_user_meta( $trainer_id, 'first_name', true );
                        $lname = get_user_meta( $trainer_id, 'last_name', true );
                        $trainer_id_value="'".$fname." ".$lname."'";
                        $sql_trainer="AND wd.gym_name =".$trainer_id_value;
                    }

                    $conditionArray[] = " wd.over_all_publish = '1'";
                    if($conditionArray)
                    {
                        $condition .= ' WHERE ';
                        $condition .= implode(' AND ',$conditionArray);
                    }

                   // error_log($total_sql_query);

                    $total_sql_query = "SELECT wd.*,u.ID as IdUser
                                          FROM {$wpdb->prefix}users u
                                          ".$inner_query."
                                          LEFT  JOIN {$wpdb->prefix}add_workout wd ON wd.user_id = u.ID
                                          ".$condition.$sql_trainer."
                                          ORDER BY wd.pbweight DESC";

                //error_log($total_sql_query);
                /*
                 * error_log($total_sql_query);
                 *[15-Jul-2016 21:49:20 UTC] SELECT wd.*,u.ID as IdUser
                                          FROM wp_users u
                                          INNER JOIN wp_usermeta m ON m.user_id = u.ID
                                          LEFT  JOIN wp_add_workout wd ON wd.user_id = u.ID
                                           WHERE  m.meta_key = 'gender' AND  m.meta_value LIKE 'male' AND  wd.wk_name IN (380) AND  wd.over_all_publish = '1'
                                          ORDER BY wd.id DESC

                 *
                 *
                 */

                $list_items = $wpdb->get_results($total_sql_query);

                    $p = new pagination;
                    $p->Items(count($list_items));
                    $default_posts_per_page = get_option( 'posts_per_page' );
                    if($default_posts_per_page < 10)
                    {
                        $default_posts_per_page = 10;
                    }
                    $p->limit($default_posts_per_page);
                    $page_link = get_permalink(get_the_ID());
                    $str = '';
                    if(!empty($_GET))
                    {
                        $str = '?';
                        foreach($_GET as $key=>$val)
                        {
                            if($key != 'paging')
                            {
                                if(is_array($val))
                                {
                                    foreach($val as $sub_key=>$sub_val)
                                    {
                                        $str .=$key.'%5B%5D='.$sub_val;
                                        $str .="&";
                                    }
                                }
                                else
                                {
                                    $str .=$key.'='.$val;
                                    $str .="&";
                                }
                            }

                           //
                        }


                    }

                        $p->target($page_link.urlencode_deep($str));
                        $p->target($page_link.$str);




                    $p->currentPage($_GET[$p->paging]);
                    $p->calculate(); // Calculates what to show
                    $p->parameterName('paging');
                    $p->adjacents(1);

                        if(!isset($_GET['paging'])) {
                            $p->page = 1;
                        } else {
                            $p->page = $_GET['paging'];
                        }
                        //Query for limit paging
                        $limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;

                        $sql_query = "SELECT wd.*,u.ID as IdUser
                                          FROM {$wpdb->prefix}users u
                                          ".$inner_query."
                                          LEFT  JOIN {$wpdb->prefix}add_workout wd ON wd.user_id = u.ID
                                          ".$condition.$sql_trainer."
                                          ORDER BY wd.pbweight DESC ".$limit;

                        $overall_personal_best=  $wpdb->get_results($sql_query);
                 ?>
                    <div class="table-responsive">
  <table class="table table-condensed table-striped table-bordered table-hover no-margin overall_personal_best  ">
    <thead>
      <tr>
        <th style="width:20%">Athlete</th>
        <th style="width:20%" class="exercise">Gym</th>
        <th style="width:15%" class="exercise">Exercise</th>
        <th style="width:15%" class="exercise">Weight</th>
        <th style="width:15%" class="exercise">Date</th>
      </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($overall_personal_best))
        {
            foreach($overall_personal_best as $key=>$obj)
            {

                $trainee_info = get_userdata($obj->user_id);
                $trainer_id = get_user_meta( $obj->user_id, 'my_trainer', true );
                $gym_name_id = get_user_meta( $trainer_id, 'gym_name', true );
                $gym_name = get_the_title($gym_name_id);

                ?>

                <tr>
                    <td>
                        <span class="trainee_name"><?php echo $trainee_info->first_name, ' ' ,$trainee_info->last_name; ?></span>
                    </td>
                    <td class="trainer_name"><?php echo $gym_name; ?></td>
                    <td class="exercise">
                        <span class="exercise-title"><?php echo get_the_title($obj->wk_name); ?></span>
                    </td>
                    <td class="exercise"><?php

                        $exercise = array();

                        $exercise[] = !empty($obj->box_jump) ? json_decode($obj->box_jump) : '';
                        $exercise[] = !empty($obj->distance) ? json_decode($obj->distance) : '';
                        $exercise[] = !empty($obj->weight) ? json_decode($obj->weight) : '';


                        if(!empty($obj->times))
                        {
                            $times = json_decode($obj->times);
                            $time_slot  =  explode(':',$times->text);
                            $times->text= time_formatted_pad($time_slot[0]).':'.time_formatted_pad($time_slot[1]).':'.time_formatted_pad($time_slot[2]);
                            $exercise[] = !empty($obj->times) ?  $times: '';

                        }

                        $i= 0;
                        foreach($exercise as $e_key=>$e_val)
                        {
                           if(!empty($e_val))
                           {

                               if($i !=0 || ($i !=0 && $i < count($exercise)))
                               {
                                   echo  " ,  ";
                               }

                               echo $e_val->text,' ', stripslashes( $e_val->unit );

                               $i++;
                           }
                        }
                        ?></td>
                    <td>
                        <span class="trainee_date"><?php echo date('d M Y', strtotime($obj->complete_date)); ?></span>
                    </td>

                </tr>
            <?php

            }
        }
        else
        {
            ?>
            <tr>
                <td style="width:100%" colspan="5">
                    <span class="denger">No Record found.</span>
                </td>
            </tr>
            <?php
        }
          ?>

    </tbody>
  </table>

<?php
if(!empty($overall_personal_best)) {
    $p->show();
}?>




</div>



                   </form>

                <div class="clear"></div>
            </div>


                </div>

            </div>
    </div>
<?php get_footer(); ?>