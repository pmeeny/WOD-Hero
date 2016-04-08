<?php get_header(); ?>

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
            <div class="row">
                <h2><?php if(get_post_meta( get_the_ID(), 'Title', true )){
                    echo get_post_meta( get_the_ID(), 'Title', true );
                        }else{ echo get_the_title(); } ?></h2>

                <?php $pageimg = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ),'full');

                if(isset($pageimg) && !empty($pageimg)){ ?>
                    <div class="about-pic"><img src="<?php echo $pageimg[0]; ?>"></div>
                <?php } ?>

                <?php the_content(); ?>
            </div>
        </div>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>