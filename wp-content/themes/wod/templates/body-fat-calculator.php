<?php
/* Template Name: Body Fat % Calculator */

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
            <li><a href="<?php echo site_url() ?>/mydashboard/">Home</a></li>
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

                <iframe src=http://widgetscode.com/wc/fmw/all?skin=nb1 style='width:350px;height:450px;margin:0;'frameborder=0></iframe>

            </div>
        </div>       </div>   </div>

<?php get_footer(); ?>
