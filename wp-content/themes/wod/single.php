<?php /*
* The template for displaying all pages.
*/

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();
    $category = get_the_category(); //print_r($category);
    if($category) {
        $cat_slug =  $category[0]->category_nicename;
        $cat_name =  $category[0]->cat_name;
    }
    ?>
    <div class="clear"></div>

    <div class="bridcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url(); ?>">Home</a></li>
                <li><a href="<?php echo site_url().'/category/'.$cat_slug; ?>"><?php echo $cat_name; ?></a></li>
                <li class="active"><a><?php the_title(); ?></a></li>
            </ol>
        </div>
    </div>

    <div class="container">
        <div class="inner-content">
            <div class="row">

                <div class="about-pic"><?php if(get_the_post_thumbnail($post->ID) != '' ){
                    the_post_thumbnail('full',array('class' => 'img-responsive'));
                }else{
                    echo '<a href="'; the_permalink(); echo '" class="thumbnail-wrapper">';
                    echo '<img class="img-responsive" src="';
                    echo catch_first_image();
                    echo '" alt="" />';
                    echo '</a>';
                } ?></div>
                <?php the_content();?>

                <?php edit_post_link(__('<strong>Edit</strong>'));?>

                <?php //comments_template(); // Get comments.php template ?>
            </div>
        </div>
    </div>

<?php endwhile; else: ?>
  <p>
    <?php _e('Sorry, no posts matched your criteria.'); ?>
  </p>
<?php endif; ?>
<?php get_footer(); ?>
