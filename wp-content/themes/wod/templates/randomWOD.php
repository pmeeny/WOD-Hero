<?php
/*
 *  Template Name: Random WOD
 */
if (!is_user_logged_in()) {
    wp_redirect(site_url());
    exit();
}
$user_detail = wp_get_current_user();
if($user_detail->roles[0] == 'trainer'){
    wp_redirect(site_url().'/settings');
    exit();
}

get_header();

?>

=<body onload="rotate()">
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



   <p></p>
        <div id="quote">
        
        </div>
        <div class="submit-section">
            <button type="submit" onclick="rotate()">Generator a random WOD</button>
        </div>

<!--        <div>

            <table class="table table-condensed table-striped table-bordered table-hover no-margin overall_personal_best  ">
                <thead>

                    <th style="width:20%" class="exercise">Gym Name</th>

                </tr>
                </thead>
                <tbody>

                <tr>

                    <td class="trainer_name">Crossfit C2F</td>


                </tr>

                <tr>

                    <td class="trainer_name">Crossfit C2F</td>

                </tbody>
            </table>



        </div>
-->
     </div>  
</div>       </div>   </div>          


<script type="text/javascript">
quotes = [];
authors = [];
quotes[0] = "<tr>" +
    "<td>50 box jumps</td>" +
    "</tr><tr>" +
    "<td>50 kb swings</td>" +
    "</tr><tr>" +
    "<td>50 burpees</td>" +
    "</tr><tr>" +
    "<td>50 kb press</td>" +
    "</tr><tr>";
authors[0] = "Filthy 50s";

quotes[1] = "<tr>" +
    "<td>21 Thrusters</td>" +
    "</tr><tr>" +
    "<td>21 Pull-ups</td>" +
    "</tr><tr>" +
    "<td>15 Thrusters</td>" +
    "</tr><tr>" +
    "<td>15 Pull-ups</td>" +
    "</tr><tr>" +
    "<td>9 Thrusters</td>" +
    "</tr><tr>" +
    "<td>9 Pull-ups</td>" +
    "</tr><tr>";
authors[1] = "FRAN";

function rotate() {

    index = Math.floor(Math.random() * quotes.length);


    document.getElementById('quote').innerHTML =
'<table class="table table-condensed table-striped table-bordered table-hover no-margin overall_personal_best  ">'


       + "<thead><tr>" +
'<th style="width:20%" class="exercise">' +
authors[index] +
"</th></tr></thead>" +
"<tbody>" +

quotes[index]    +


"</tbody>" +
"</table>";






//document.write("<p>\n");
//document.write("<DT>" + "\"" + quotes[index] + "\"</DT>\n");
//document.write("<DD>" + "-- " + authors[index] + "</DT>\n");
//document.write("</p>\n");
}

</script>
</body>
                <?php get_footer(); ?>