<div class="sideLink">
    <?php $user_detail = wp_get_current_user(); ?>
    <ul>
        <?php  if($user_detail->roles[0] == 'trainer'){ ?>
            <li><a href="<?php echo site_url().'/settings'; ?>"><span><i class="fa fa-cog"></i></span>Settings</a></li>
           <!-- <li><a href="<?php /*echo site_url().'/options'; */?>"><span><i class="fa fa-cog"></i></span>Options</a></li>-->
            <li><a href="<?php echo wp_logout_url( home_url() ); ?>"> <span> <i class="fa fa-sign-out"></i> </span>Logout</a></li>
        <?php }else{ ?>
            <li><a href="<?php echo site_url().'/mydashboard'; ?>"><span><i class="fa fa-bar-chart"></i></span> Dashboard</a></li>
            <li><a href="<?php echo site_url().'/overall-personal-best/'; ?>"> <span><i class="fa fa-users"></i></span> Overall Personal Best</a></li>
            <li><a href="<?php echo site_url().'/personal-best/'; ?>"> <span><i class="fa fa-user"></i></span> Personal Bests</a></li>
            <li><a href="<?php echo site_url().'/social-media/'; ?>"> <span> <i class="fa fa-share-alt"></i> </span> Social Media</a></li>
            <li><a href="<?php echo site_url().'/add-workoutpb/'; ?>"> <span><i class="fa fa-plus-square"></i></span> Add Workout/PB</a></li>
            <li><a href="<?php echo site_url().'/gym-finder/'; ?>"> <span><i class="fa fa-user"></i></span> Gym Finder</a></li>
            <li><a href="<?php echo site_url().'/random-wod/'; ?>"> <span><i class="fa fa-user"></i></span> Random WOD</a></li>
            <li><a href="<?php echo site_url().'/youtube-channel/'; ?>"> <span><i class="fa fa-user"></i></span> Youtube Channel</a></li>
        <?php } ?>
    </ul>
</div>