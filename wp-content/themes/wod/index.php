<?php get_header('home'); ?>

    <div class="slider"> <div class="container">
       <h1>BETA VERSION LIVE</h1>
        </div> </div>

    <div class="container home-page-container">
        <div class="row">
            <div class="col-md-12">
                <div class="home-aboutText">
                    <?php $page_data = get_page(40); ?>
                    <h2><?php echo $page_data->post_title; ?></h2>
                    <?php echo apply_filters('the_content', $page_data->post_content); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <?php $args = array( 'posts_per_page' => 3, 'category_name'=>'blog' );
            $myposts = new WP_Query( $args );
            if ( $myposts->have_posts() ) :
                while ( $myposts->have_posts() ) : $myposts->the_post(); ?>
                    <div class="col-md-4 col-sm-4">
                        <div class="home-post">
                            <?php if(get_the_post_thumbnail($post->ID) != '' ){
                                the_post_thumbnail('full',array('class' => 'img-responsive'));
                            }
                            else{
                                echo '<a href="'; the_permalink(); echo '" class="thumbnail-wrapper">';
                                echo '<img class="img-responsive" src="';
                                echo catch_first_image();
                                echo '" alt="" />';
                                echo '</a>';
                            } ?>
                            <div class="postText">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <?php echo apply_filters('the_content', substr(get_the_content(), 0, 200) ); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

<?php get_footer(); ?>