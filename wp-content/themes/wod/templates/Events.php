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
                    'order' => 'ASC',
                );

                $fbe_query = new WP_Query( $args );
                if( $fbe_query->have_posts() ):
                    while ( $fbe_query->have_posts() ) : $fbe_query->the_post();

                        $event_title = get_the_title();
                        $event_desc =  get_the_content();
                        $event_image = get_fbe_image('cover');

                        ?>
                      <!--  <img src="<?php //echo get_fbe_image('cover'); ?>" alt="" /> -->
                        <h1><?php echo $event_title; ?></h1>
                        <p><?php echo $event_desc; ?></p>
                        <?php

                    endwhile;
                endif;

                wp_reset_query();

                ?>

            </div>
        </div>       </div>   </div>

<?php get_footer(); ?>
