<?php get_header(); ?>



    <div class="clear"></div>

    <div class="bridcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url(); ?>">Home</a></li>
                <li class="active"><a>404</a></li>
            </ol>
        </div>
    </div>

    <div class="container">
        <div class="inner-content">
            <div class="row">
                <h2>Page Not Found!</h2>
                <div class="row text-center">
                <div class="img-404 ">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/notfoundpage.png" class="img-responsive">
                </div> </div>



            </div>
        </div>
    </div>



<?php get_footer(); ?>