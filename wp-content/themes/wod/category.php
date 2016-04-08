<?php
/**
 * The template for displaying Category pages
 * Used to display archive-type pages for posts in a category.
 *
 */

get_header(); ?>
<div class="clear"></div>

<?php if ( have_posts() ) :
    $category = get_the_category(); //print_r($category);
    if($category) {
        $cat_name =  $category[0]->cat_name;
    } ?>
    <div class="bridcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url(); ?>">Home</a></li>
                <li><a><?php echo $cat_name; ?></a></li>
            </ol>
        </div>
    </div>

    <?php /* Start the Loop */ ?>

    <div class="container">
        <div class="inner-content">
            <div class="row">
                <?php while ( have_posts() ) : the_post(); ?>

                    <div class="postList">
                        <div class="postimage">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <?php if(get_the_post_thumbnail($post->ID) != '' ){
                                    the_post_thumbnail('thumbnail',array('class' => 'img-responsive'));
                                }else{
                                    echo '<a href="'; the_permalink(); echo '" class="thumbnail-wrapper">';
                                    echo '<img class="img-responsive" src="';
                                    echo catch_first_image();
                                    echo '" alt="" />';
                                    echo '</a>';
                                } ?>
                            </a>
                        </div>
                        <div class="postCont">
                            <h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                            <?php the_excerpt(); ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

<?php else : ?>
    <p class="post-none">No Category post were found.</p>
<?php endif; ?>
</div>

<?php get_footer(); ?>
