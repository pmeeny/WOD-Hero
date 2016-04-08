<?php
$andven_option=get_option('andven_options');
$social_settings= $andven_option['social_settings'];
if(isset($social_settings['facebook_url'])){
    $fb = $social_settings['facebook_url'];
}
if(isset($social_settings['insta_url'])){
    $insta = $social_settings['insta_url'];
}
if(isset($social_settings['twitter_url'])){
    $tw = $social_settings['twitter_url'];
}
if(isset($social_settings['youtube_url'])){
    $youTube = $social_settings['youtube_url'];
}
?>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-2"> <div class="f-logo"><img src="<?php bloginfo('template_url'); ?>/images/footer-logo.png"></div></div>
            <div class="col-sm-6">
                <ul>
                    <?php
                    $defaults = array(
                        'theme_location'  => '',
                        'menu'            => 'footer menu',
                        'container'       => false,
                        'echo'            => true,
                        'items_wrap'      => '%3$s',
                    );
                    wp_nav_menu( $defaults );
                    ?>
                </ul>
                <p>Copyright &copy; <?php echo date('Y'); ?> WOD Hero, All right reserved.</p>
                <a href="<?php echo site_url('privacy-policy'); ?>">Privacy Policy </a>   <a href="<?php echo site_url('terms-and-conditions'); ?>">Terms And Conditions</a> </div>
            <div class="col-sm-4">
                <ul class="f-social">
                    <?php
                    if(!empty($insta))
                    {
                        ?>
                        <li><a href="<?php echo $insta; ?>" target="_blank"><i class="fa fa-instagram fa-2"></i></a></li>
                        <?php

                    }
                    ?>
                    <?php
                    if(!empty($fb))
                    {
                        ?>
                        <li><a href="<?php echo $fb; ?>" target="_blank"> <i class="fa fa-facebook fa-2"></i> </a></li>
                       <?php

                    }
                    ?>
                    <?php
                    if(!empty($tw))
                    {
                        ?>
                        <li><a href="<?php echo $tw; ?>" target="_blank"><i class="fa fa-twitter fa-2"></i></a></li>
                        <?php
                    }
                    ?>
                    <?php
                    if(!empty($youTube))
                    {
                        ?>
                        <li><a href="<?php echo $youTube; ?>" target="_blank"><i class="fa fa-youtube fa-2"></i></a></li>
                       <?php

                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>