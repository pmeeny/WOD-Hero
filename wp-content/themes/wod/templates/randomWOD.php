<?php
/* Template Name: Random WOD */

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
<body onload="rotate()"></body>
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


        <input type=button value="Random WOD Generator" onclick="rotate()">
   <p></p>
        <div id="quote">
        
        </div>

     </div>  
</div>       </div>   </div>          


<script type="text/javascript">
quotes = [];
authors = [];
quotes[0] = "50 box jumps<br>50 kb swings<br>50 burpees<br>50 kb press<br>";
    
authors[0] = "<h3>Filthy 50s<h3>";

function rotate(){

index = Math.floor(Math.random() * quotes.length);

    
    document.getElementById('quote').innerHTML=quotes[index] + "<p><p>"  + "<b>" + authors[index] + "</b>";

//document.write("<p>\n");
//document.write("<DT>" + "\"" + quotes[index] + "\"</DT>\n");
//document.write("<DD>" + "-- " + authors[index] + "</DT>\n");
//document.write("</p>\n");
}

</script> 

                <?php get_footer(); ?>