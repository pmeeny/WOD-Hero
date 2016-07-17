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
authors[0] = "FILTHY 50s(For Time)";

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
authors[1] = "FRAN(For time)";


quotes[2] = "<tr>" +
    "<td>100 Pull-ups</td>" +
    "</tr><tr>" +
    "<td>100 Push-ups</td>" +
    "</tr><tr>" +
    "<td>100 Sit-ups</td>" +
    "</tr><tr>" +
    "<td>100 Air Squats</td>" +
    "</tr><tr>"
authors[2] = "ANGIE(For time)";

quotes[3] = "<tr>" +
    "<td>Run 400 metres</td>" +
    "</tr><tr>" +
    "<td>30 Box jumps</td>" +
    "</tr><tr>" +
    "<td>30 Wall balls</td>" +
    "</tr><tr>" +
    "<td>100 Air Squats</td>" +
    "</tr><tr>"
authors[3] = "KELLY(5 rounds for time)";

quotes[4] = "<tr>" +
    "<td>100 Calorie row</td>" +
    "</tr><tr>" +
    "<td>75 Thrusters</td>" +
    "</tr><tr>" +
    "<td>50 Pull-ups</td>" +
    "</tr><tr>" +
    "<td>75 Wall balls</td>" +
    "</tr><tr>" +
    "<td>100 Calorie row</td>" +
    "</tr><tr>"
authors[4] = "HILDY(For time)";

quotes[5] = "<tr>" +
    "<td>Run 800 metres</td>" +
    "</tr><tr>" +
    "<td>50 back extensions</td>" +
    "</tr><tr>" +
    "<td>50 Sit-ups</td>" +
    "</tr><tr>"
authors[5] = "MICHAEL(3 rounds for time)";

quotes[6] = "<tr>" +
    "<td>Deadlift 5-5-3-3-3-1-1-1-1 reps</td>" +
    "</tr><tr>"
authors[6] = "DEADLIFTS";

quotes[7] = "<tr>" +
    "<td>21-18-15-12-9-6-3</td>" +
    "</tr><tr>" +
    "<td>Thrusters</td>" +
    "</tr><tr>" +
    "<td>Burpees</td>" +
    "</tr><tr>"
authors[7] = "WORKOUT 16.5(For time)";

quotes[8] = "<tr>" +
    "<td>Run 400 metres</td>" +
    "</tr><tr>" +
    "<td>21 kb swings</td>" +
    "</tr><tr>" +
    "<td>12 Pull-ups</td>" +
    "</tr><tr>"
authors[8] = "HELEN(3 rounds for time)";

quotes[9] = "<tr>" +
    "<td>Run 5km</td>" +
    "</tr><tr>"
authors[9] = "RUN 5KM(For time)";

quotes[10] = "<tr>" +
    "<td>Row 500 metres</td>" +
    "</tr><tr>" +
    "<td>21 GHD Sit-ups</td>" +
    "</tr><tr>" +
    "<td>25 hip extensions</td>" +
    "</tr><tr>"
authors[10] = "WOD(3 rounds for time)";

quotes[11] = "<tr>" +
    "<td>Front squat 3-3-3-3-3 reps</td>" +
    "</tr><tr>"
authors[11] = "WOD";

quotes[12] = "<tr>" +
    "<td>Run 800 metres</td>" +
    "</tr><tr>" +
    "<td>50 Wall balls</td>" +
    "</tr><tr>" +
    "<td>12 Pull-ups</td>" +
    "</tr><tr>"
authors[12] = "WOD(4 rounds for time)";

quotes[13] = "<tr>" +
    "<td>Row 1000 metres</td>" +
    "</tr><tr>" +
    "<td>42 KB Swings</td>" +
    "</tr><tr>" +
    "<td>24 Pull-ups</td>" +
    "</tr><tr>"
authors[13] = "WOD(3 rounds for time)";

quotes[14] = "<tr>" +
    "<td>5 Pull-ups</td>" +
    "</tr><tr>" +
    "<td>10 Push-ups</td>" +
    "</tr><tr>" +
    "<td>15 Air Squats</td>" +
    "</tr><tr>"
authors[14] = "CINDY(20 minute AMRAP)";


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