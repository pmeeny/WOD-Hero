<?php
/* Template Name: Personal Best */
if(!(is_user_logged_in() )) {
    wp_redirect(site_url().'/login');
    exit();
}
$user_detail = wp_get_current_user();
if($user_detail->roles[0] == 'trainer'){
    wp_redirect(site_url().'/settings');
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
                <div class="col-md-4">
                    <?php get_sidebar(); ?>
                </div>

                <div class="col-md-8">
                    <?php
                         $lastPerBest = getLastPersonalBest();
                         if(empty($lastPerBest))
                         {
                             echo '<p style="line-height: 15px;" class="alert alert-warning text-center">No personal best found.</p>';
                         }
                         else
                         {
                             ?>
                             <div class="personal-best">

                                 <h3>My last personal best</h3>

                                 <?php
                                 global $wpdb,$current_user;
                                 get_currentuserinfo();
                                 $current_user_workouts = $wpdb->get_results($wpdb->prepare("SELECT * FROM  ".$wpdb->prefix."add_workout  WHERE 1=%d AND publish=%d AND user_id = %d GROUP BY wk_name",array(1, 1,$current_user->ID)));
                                 $first_post_title ='';
                                 ?>
                                 <div class="form-group">

                                 <form class="form-group" id="wk_name1" role="form" method="post">

                                     <select class="form-control plot_again_map" name="wk_name2" id="wk_name2">

                                     <!-- onchange="this.form.submit() -->
                                         <?php
                                         if(!empty($current_user_workouts))
                                         {
                                             foreach($current_user_workouts as $key=>$val)
                                             {
                                                 $post_title = get_the_title($val->wk_name);
                                                 if($key ==0)
                                                 {
                                                     $first_post_title = $post_title;
                                                     $first_post_id = $val->wk_name;
                                                 }
                                                 ?>
                                                <option id="dataname1" value="<?php echo $val->wk_name; ?>" data-name="<?php echo $post_title; ?>"><?php echo $post_title; ?></option>
                                                 <?php
                                             }
                                         } ?>
                                        </select>


                                     <script type="text/javascript">


                                         $(document).ready(function(){
                                             var id = $("#wk_name2").val();
                                             // var wname = $("#wk_name").attr('data-name');
                                             ajaxLoaderStart();
                                             jQuery.ajax({
                                                 type	: "POST",
                                                 cache	: false,
                                                 url     : ajaxurl,
                                                 dataType : 'text',
                                                 data: {
                                                     'action' : 'get_personal_best_for_graph1',
                                                     'wid':id
                                                 },
                                                 success: function(data) {
                                                     ajaxLoaderStop();

                                                     if(data)
                                                     {
                                                         $('#personal_graph1').html(data);
                                                     }
                                                     else
                                                     {
                                                         $('#personal_graph1').html('No Result Found');
                                                     }


                                                 }
                                             });


                                             $(".plot_again_map").change(function() {
                                                 var id = $("#wk_name2").val();
                                                 // var wname = $("#wk_name").attr('data-name');
                                                 ajaxLoaderStart();
                                                 jQuery.ajax({
                                                     type	: "POST",
                                                     cache	: false,
                                                     url     : ajaxurl,
                                                     dataType : 'text',
                                                     data: {
                                                         'action' : 'get_personal_best_for_graph1',
                                                         'wid':id
                                                     },
                                                     success: function(data) {
                                                         ajaxLoaderStop();

                                                         if(data)
                                                         {
                                                             $('#personal_graph1').html(data);
                                                         }
                                                         else
                                                         {
                                                             $('#personal_graph1').html('No Result Found');
                                                         }


                                                     }
                                                 });
                                             });
                                         });
                                     </script>



                            <!--         <noscript><input type="submit" value="Submit"></noscript>  -->





                                </form>
                                     <div class="clear"></div>
                                     <div class="personal_graph1">
                                         <div class="highcharts-container" id="personal_graph1"></div>
                                     </div>


                                 </div>
                                 <div class="clear"></div>
                                 <h3>My personal bests</h3>
                                 <span class="sort_graph">
                                        <form name="graph_sort" id="graph_sort" method="post">
                                            <?php
                                            global $wpdb,$current_user;
                                            get_currentuserinfo();
                                            $current_user_workouts = $wpdb->get_results($wpdb->prepare("SELECT * FROM  ".$wpdb->prefix."add_workout  WHERE 1=%d AND publish=%d AND user_id = %d GROUP BY wk_name",array(1, 1,$current_user->ID)));
                                            $first_post_title ='';
                                            ?>
                                            <div class="form-group">
                                                <select class="form-control plot_again_map" name="wk_name" id="wk_name">
                                                    <?php
                                                    if(!empty($current_user_workouts))
                                                    {
                                                        foreach($current_user_workouts as $key=>$val)
                                                        {
                                                            $post_title = get_the_title($val->wk_name);
                                                            if($key ==0)
                                                            {
                                                                $first_post_title = $post_title;
                                                                $first_post_id = $val->wk_name;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $val->wk_name; ?>" data-name="<?php echo $post_title; ?>"><?php echo $post_title; ?></option>
                                                        <?php
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control plot_again_map" name="graph_plot_by" id="graph_plot_by">
                                                            <option value="">Graph Plot by</option>
                                                            <option selected value="weekly">Weekly</option>
                                                            <option value="monthly">Monthly</option>
                                                </select>
                                            </div>
                                        </form>
                                    </span>
                                    <div class="clear"></div>
                                 <div class="personal_graph">
                                     <div class="highcharts-container" id="best_personal_graph"></div>
                                 </div>

                             </div>
                             <?php
                         }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
global $wpdb,$current_user;
get_currentuserinfo();
$current_workouts_details = $wpdb->get_results($wpdb->prepare("SELECT * FROM  ".$wpdb->prefix."add_workout  WHERE 1=%d AND publish=%d AND  user_id = %d AND  wk_name=%d AND complete_date >=
DATE_ADD(CURDATE(), INTERVAL -7 DAY)    ",array(1,1,$current_user->ID,$current_user_workouts[0]->wk_name)));
$total_val = array();
//pre($current_workouts_details);
$total_val = array('Monday'=>array(0), 'Tuesday'=>array(0), 'Wednesday'=>array(0), 'Thursday'=>array(0), 'Friday'=>array(0), 'Saturday'=>array(0),'Sunday'=>array(0));
$total_time_val = array('Monday'=>array(0), 'Tuesday'=>array(0), 'Wednesday'=>array(0), 'Thursday'=>array(0), 'Friday'=>array(0), 'Saturday'=>array(0),'Sunday'=>array(0));
$total_distance_val = array('Monday'=>array(0), 'Tuesday'=>array(0), 'Wednesday'=>array(0), 'Thursday'=>array(0), 'Friday'=>array(0), 'Saturday'=>array(0),'Sunday'=>array(0));
$time = false;
$weight = false;
$distance = false;
foreach( $current_workouts_details as $key=>$val )
{

    $total_box_jump = json_decode( $val->box_jump );
    $total_distance = json_decode( $val->distance );
    $total_weight = json_decode( $val->weight );
    $total_times = json_decode( $val->times );
    $new_key = date('l',strtotime($val->complete_date));
    if(!empty($total_times->unit))
    {
        //convert in minutes
        $time = true;
        switch($total_times->unit)
        {
            case 'seconds':
                $total_time_val[trim($new_key)][$key] = floatval( ($total_times->text)/60 );
                break;

            case 'hour':
                $total_time_val[trim($new_key)][$key] = floatval( ($total_times->text)*60 );
                break;

            default:
                $total_time_val[trim($new_key)][$key] = floatval( ($total_times->text) );
                break;

        }
    }

    if(!empty($total_weight->unit))
    {
        $weight = true;
        switch($total_weight->unit)
        {
            case 'lb':
                $total_val[trim($new_key)][$key] =  floatval( $total_weight->text * 0.453592);
                break;

            default:
                $total_val[trim($new_key)][$key] =  $total_weight->text;
                break;

        }
    }



    if(!empty($total_distance->unit))
    {
        $distance = true;
        switch($total_distance->unit)
        {
             default:
                 $total_distance_val[trim($new_key)][$key] =  $total_distance->text;
                break;

        }
    }

 }

?>

<script type="text/javascript">

    $(document).ready(function(){

        // Defining the chart

          var chart_opt  =  '<?php $default_graph_obj = get_personal_best_for_graph_json_option($first_post_id, 'weekly');
echo $default_graph_obj; ?>';

        if(chart_opt !='0')
        {
            var  options =  jQuery.parseJSON(chart_opt);
            $('#best_personal_graph').highcharts(options);
        }
        else
        {
            $('#best_personal_graph').html('No Result Found!');
        }


        $(".plot_again_map").change(function() {
            var id = $("#wk_name").val();
            var wname = $("#wk_name").attr('data-name');
            var graph_plot_by = jQuery('#graph_plot_by').val();
            ajaxLoaderStart();
            jQuery.ajax({
                type	: "POST",
                cache	: false,
                url     : ajaxurl,
                dataType : 'json',
                data: {
                    'action' : 'get_personal_best_for_graph',
                    'wid':id,
                    'graph_plot_by':graph_plot_by,
                },
                success: function(data) {
                    ajaxLoaderStop();

                   if(data)
                   {
                       $('#best_personal_graph').html('');
                       $('#best_personal_graph').highcharts(data);
                   }
                    else
                   {
                       $('#best_personal_graph').html('No Result Found');
                   }


                }
            });
        });
    });

</script>
<?php get_footer(); ?>