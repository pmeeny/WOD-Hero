<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body>

<div class="main">

    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-4"> <div class="logo"> <a href="<?php echo site_url(); ?>"><img src="<?php bloginfo('template_url'); ?>/images/logo.png"></a> </div>    </div>
                
                <div class="col-md-6 col-sm-8">  
                <div class="header-menu">                
                 <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button> 
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        
                            <ul class="mainmenu list-inline nav navbar" id="nav navbar-nav">
                            <?php echo wod_main_menu();
                             ?>
                            <?php if(is_user_logged_in() ) {
                                $user_detail = wp_get_current_user();
                                $user = get_user_meta($user_detail->ID,'first_name',true); ?>
                                <li class="blue-Btn"><a href="<?php echo site_url().'/mydashboard'; ?>"><?php echo substr($user, 0, 11);  ?></a></li>
                                <li class="gren-Btn"><a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a></li>
                            <?php }else{ ?>
                             <!--   <li class="gren-Btn"><a href="<//?php echo site_url().'/login'; ?>">Login</a></li>
                                <li class="blue-Btn"><a href="<//?php echo site_url().'/sign-up'; ?>">Signup</a></li> -->
                            <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>