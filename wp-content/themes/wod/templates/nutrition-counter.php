<?php
/* Template Name: Nutrition Counter */

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
                    <script src="http://platform.fatsecret.com/js?key=d8a72963fafa40b593ca0d4475332a58&auto_template=false&theme=blank"></script>
                    <script>
                            fatsecret.setContainer('container');
                            fatsecret.setCanvas('foods.search');
                            fatsecret.setCanvasUrl('food.get', "<?php echo site_url(); ?>" + "/nutritional-information/");
                    </script>

                    <h4>Type a food e.g. Sweet Potato to get it's nutritional information</h4>

                    <div class="holder" >
                        <script>fatsecret.writeHolder("search");</script>
                        <script>fatsecret.writeHolder("result");</script>
                        <script>fatsecret.writeHolder("foodtitle");</script>
                        <script>fatsecret.writeHolder("servingdescription");</script>
                        <script>fatsecret.writeHolder("nutritionpanel");</script>
                        <script>fatsecret.writeHolder("servingselector");</script>
                        <div id="container">
                        </div>

                    </div>   </div></div></div></div>
<?php get_footer(); ?>