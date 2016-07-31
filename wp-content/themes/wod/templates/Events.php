<?php
/* Template Name: Events */

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
        <h2><?php if (get_post_meta(get_the_ID(), 'Title', true)) {
                echo get_post_meta(get_the_ID(), 'Title', true);
            } else {
                echo get_the_title();
            } ?></h2>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <?php get_sidebar(); ?>
            </div>

            <div class="col-md-8 col-sm-8">

                <?php

                $args = array (
                    'post_type' => 'facebook_events',
                    'posts_per_page' => -1,
                    'orderby' => 'post_excerpt'
                );

                $fbe_query = new WP_Query( $args );
                if( $fbe_query->have_posts() ):
                    while ( $fbe_query->have_posts() ) : $fbe_query->the_post();

                        $event_title = get_the_title();
                        $event_desc =  get_the_content();
                        $event_image = get_fbe_image('cover');
                        $event_starts_month = get_fbe_date('event_starts','M');
                        $event_starts_day = get_fbe_date('event_starts','j');
                        $location = get_fbe_field('location');
                        $fb_event_uri = get_fbe_field('fb_event_uri');
                        $permalink = get_permalink();
                        $featured = get_post_meta($post->ID, 'feature_event', true);

                        ?>
                      <!--  <div class="fbe_col_title"><h3><?php //echo limitFBETxt( $event_title,50); ?></h3></div> -->
                        <img src="<?php echo get_fbe_image('cover'); ?>" alt="" />
                                       <!-- <div class="fbe_list_date">
                                            <div class="fbe_list_month"><?php echo $event_starts_month; ?></div>
                                            <div class="fbe_list_day"><?php echo $event_starts_day; ?></div>
                                        </div> -->
                                       <!-- <div class="fbe_col_location"><?php echo limitFBETxt($location,40); ?></div> -->



                        <table class="table table-condensed table-striped table-bordered table-hover no-margin overall_personal_best  ">


                            <thead><tr>
                                <th style="width:20%" class="exercise"><?php echo limitFBETxt( $event_title,70); ?>
                                    </th></tr></thead>
                            <tbody>
                            <tr>
                                <td>Date: <?php echo $event_starts_day; ?> <?php echo $event_starts_month;?></td>
                                </tr><tr>
                                <td>Location: <?php echo limitFBETxt($location,70); ?></td>
                                </tr><tr>
                                <td>Event Details: <?php echo $event_desc ?></td>
                                </tr><tr>

                                <td>Facebook Event Link: <a href="<?php echo $fb_event_uri;?>"> Click for the fb event </a>  </td>


                            </tr><tr>
                            </tbody>
                            </table>


            <?php

                    endwhile;
                endif;

                wp_reset_query();

                ?>

            </div>
        </div>       </div>   </div>

<?php get_footer(); ?>
