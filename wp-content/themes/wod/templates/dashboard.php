<?php
/* Template Name: Dashboard */

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
                <div class="col-md-4 col-sm-4">
                    <?php get_sidebar(); ?>
                </div>

                <div class="col-md-8 col-sm-8" id="overallCalendar">
                    <div id="workout_calendar"></div>

                    <div class="workout_calendar_help">
                        <ul>
                            <li><img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-best.png"/> My
                                Personal best</li>
                            <li><img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-cardio.png"/>Cardio </li>
                            <li><img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-strength.png"/>
                                Strength </li>
                            <li><img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-running.png"/> Running/Rowing
                            </li>
                         <!--   <li><img src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-strength.png"/> WOD  -->
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--<div class="modal fade" id="workoutDetailModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"></div> -->

<div id="workout"></div>

<script type="text/javascript">
    var drawFlag = false;
    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };

    jQuery(document).ready(function($){

        var loggedUserId = "<?php echo toPublicId(get_current_user_id());?>";
        var calendar = $('#workout_calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            defaultDate: "<?php echo date("Y-m-d"); ?>",
            editable: false,
            selectable: true,
            selectHelper: true,
            eventOverlap: false,
            events: ajaxurl+'?action=getWorkoutData&userId='+loggedUserId,
            select: function(start,end,allDay) {

                if(end._d.getTime() != start._d.getTime()){
                    calendar.fullCalendar('unselect') ;
                }

                var start_date = new Date(start._d);
                var end_date = new Date(end._d);

                var date = new Date();
                var start_d = start_date.getDate();
                var start_m = start_date.getMonth()+1;
                var start_y = start_date.getFullYear();
                start_date_format = start_y+'-'+start_m+'-'+start_d;

                $('#workout_calendar #symptom_date').val(start_date_format);

                var view_type = $('#workout_calendar').fullCalendar('getView');
                console.log(view_type.name);


                if(view_type.name=='month')
                {
                    var end_d = end_date.getDate()-parseInt(1);
                }
                else
                {
                    var end_d = end_date.getDate();
                }

                if(end_d==0)
                {
                    end_date.setDate(end_date.getDate() - 1);
                    var end_d = end_date.getDate();
                }

                var end_m = end_date.getMonth()+1;
                var end_y = end_date.getFullYear();
                end_date_format = end_y+'-'+end_m+'-'+end_d;

                var check = start._d;//moment(start._d).format('YYYY-MM-DD');
                var today = new Date();//moment(new Date()).format('YYYY-MM-DD');

                $('.fc-helper').remove();
                $('#symptom_Data').hide().html('');
                $('#symptoms_modal').modal('show');

            },
            eventClick: function(calEvent, jsEvent, view) {
                $('#edit_symptom_Data').hide().html('');

                getSingleWorkoutDetail(calEvent.start_date);

            },
            eventRender: function(event, element) {
                var html = "";
                html = '<div class="workout_data"><span class="fc-title">'+event.title+'</span>'+event.images_data+'</div>';
                element.html(html);
            }
        });

    });

</script>
<?php get_footer(); ?>