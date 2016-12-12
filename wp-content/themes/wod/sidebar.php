<div class="sideLink">
    <?php $user_detail = wp_get_current_user(); ?>
    <ul>
        <?php  if($user_detail->roles[0] == 'trainer'){ ?>
            <li><a href="<?php echo site_url().'/settings'; ?>"><span><i class="fa fa-cog"></i></span>Settings</a></li>
           <!-- <li><a href="<?php /*echo site_url().'/options'; */?>"><span><i class="fa fa-cog"></i></span>Options</a></li>-->
            <li><a href="<?php echo wp_logout_url( home_url() ); ?>"> <span> <i class="fa fa-sign-out"></i> </span>Logout</a></li>
        <?php }else{ ?>
            <li><a href="<?php echo site_url().'/mydashboard'; ?>"><span><i class="fa fa-bar-chart"></i></span> Dashboard</a></li>
            <li><a href="<?php

                $user_detail = wp_get_current_user();
                $trainer_last = get_user_meta( $user_detail->ID, 'my_trainer', true );

                echo site_url().'/overall-personal-best/?gym_name='.$trainer_last ; ?>"> <span><i class="fa fa-users"></i></span> Overall Personal Bests</a></li>
            <li><a href="<?php echo site_url().'/personal-best/'; ?>"> <span><i class="fa fa-user"></i></span> My Personal Bests</a></li>
            <li><a href="<?php echo site_url().'/social-media/'; ?>"> <span> <i class="fa fa-share-alt"></i> </span> My gyms social media</a></li>
            <li><a href="<?php echo site_url().'/add-workoutpb/'; ?>"> <span><i class="fa fa-plus-square"></i></span> Add a WOD</a></li>
            <li><a href="<?php echo site_url().'/add-pb/'; ?>"> <span><i class="fa fa-plus-square"></i></span> Add a Personal Best</a></li>
            <li><a href="<?php echo site_url().'/gym-finder/'; ?>"> <span><i class="fa fa-users"></i></span> Gym Finder</a></li>
            <li><a href="<?php echo site_url().'/random-wod/'; ?>"> <span><i class="fa fa-user"></i></span> Random WOD Generator</a></li>
            <li><a href="<?php echo site_url().'/events/'; ?>"> <span><i class="fa fa-users"></i></span> Crossfit Events</a></li>
            <li><a href="<?php echo site_url().'/nutrition/'; ?>"> <span><i class="fa fa-bar-chart"></i></span> Nutritional Information</a></li>
                <li><a href="<?php echo site_url().'/body-fat-calculator/'; ?>"> <span><i class="fa fa-user"></i></span> Body fat % Calculator</a></li>
        <?php } ?>
    </ul>
</div>