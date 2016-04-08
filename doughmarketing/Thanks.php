<?php

$servername = "localhost";
$username = "paulmeeneghan";
$password = "39Nov2009";
$dbname = "dough_contact_form";

$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

$sql = "INSERT INTO ContactForm (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";

if ($connection->query($sql) === TRUE) {
} else {
    echo "Error: " . $sql . "<br>" . $connection->error;
}

$connection->close();
?>

<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="en">
<head>
  <!-- Mobirise Free Bootstrap Template, https://mobirise.com -->
  <title>Thanks</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Online marketing for small businesses">
  <meta name="keywords" content="Marketing,SEO">
  <meta name="author" content="Dough Marketing">   
  <meta name="generator" content="Mobirise v2.6.1, mobirise.com">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="assets/images/icon.png" type="image/x-icon">
  <meta name="description" content="">
  <meta property="og:image" content="http://www.doughmarketing.com/assets/images/landingPage.png" />
  <meta property="og:url" content="http://www.doughmarketing.com" />
  <meta property="og:description" content="Online marketing for small businesses" />
  <meta property="og:site_name" content="Dough Marketing" />
  <meta property="fb:app_id" content="1094040337293561" /> 
    
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:700,400&amp;subset=cyrillic,latin,greek,vietnamese">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/animate.css/animate.min.css">
  <link rel="stylesheet" href="assets/socicon/css/socicon.min.css">
  <link rel="stylesheet" href="assets/mobirise/css/style.css">
  <link rel="stylesheet" href="assets/mobirise-slider/style.css">
  <link rel="stylesheet" href="assets/mobirise-gallery/style.css">
  <link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">
  
  <script>

</script>

    
    <script type="text/javascript">(function(d,n){var s,a,p;s=document.createElement("script");s.type="text/javascript";s.async=true;s.src=(document.location.protocol==="https:"?"https:":"http:")+"//cdn.nudgespot.com"+"/nudgespot.js";a=document.getElementsByTagName("script");p=a[a.length-1];p.parentNode.insertBefore(s,p.nextSibling);window.nudgespot=n;n.init=function(t){function f(n,m){var a=m.split('.');2==a.length&&(n=n[a[0]],m=a[1]);n[m]=function(){n.push([m].concat(Array.prototype.slice.call(arguments,0)))}}n._version=0.1;n._globals=[t];n.people=n.people||[];n.params=n.params||[];m="track register unregister identify set_config people.delete people.create people.update people.create_property people.tag people.remove_Tag".split(" ");for(var i=0;i<m.length;i++)f(n,m[i])}})(document,window.nudgespot||[]);nudgespot.init("5d3d0ca949d3d9d85941a7bec9c37fae");</script>
    <script type="text/javascript">
  nudgespot.track("activity_name", {activity_properties_map});
</script>
  
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-72926754-1', 'auto');
  ga('send', 'pageview');

</script>
    
</head>
<body>
<!--
<section class="mbr-navbar mbr-navbar--freeze mbr-navbar--absolute mbr-navbar--transparent mbr-navbar--sticky mbr-navbar--auto-collapse" id="menu-74">
    <div class="mbr-navbar__section mbr-section">
        <div class="mbr-section__container container">
            <div class="mbr-navbar__container">
                <div class="mbr-navbar__column mbr-navbar__column--s mbr-navbar__brand">
                    <span class="mbr-navbar__brand-link mbr-brand mbr-brand--inline">
						<span class="mbr-brand__logo"><a href="#"><img class="mbr-navbar__brand-img mbr-brand__img" src="assets/images/discover-mobile-350x350-53.png" alt="Mobirise"></a></span>
                       <!-- <span class="mbr-brand__name"><a class="mbr-brand__name text-white" href="#">Dough Marketing</a></span>
                    </span>
					</span>
                </div>
                <div class="mbr-navbar__hamburger mbr-hamburger text-white"><span class="mbr-hamburger__line"></span></div>
               <div class="mbr-navbar__column mbr-navbar__menu">
                    <nav class="mbr-navbar__menu-box mbr-navbar__menu-box--inline-right">
                        <div class="mbr-navbar__column">
                            <ul class="mbr-navbar__items mbr-navbar__items--right mbr-buttons mbr-buttons--freeze mbr-buttons--right btn-decorator mbr-buttons--active">
                                <li class="mbr-navbar__item">
<a class="mbr-buttons__link btn text-white" href="#msg-box5-79">ABOUT US</a></li> <li class="mbr-navbar__item">
<a class="mbr-buttons__link btn text-white" href="#contacts2-90">CONTACT US</a></li><li class="mbr-navbar__item">
<!--<a class="mbr-buttons__link btn text-white" href="video-background.html">VIDEO BG</a></li> <li class="mbr-navbar__item">
<a class="mbr-buttons__link btn text-white" href="blog.html">BLOG</a></li></ul></div>
                        <div class="mbr-navbar__column"><ul class="mbr-navbar__items mbr-navbar__items--right mbr-buttons mbr-buttons--freeze mbr-buttons--right btn-inverse mbr-buttons--active"><li class="mbr-navbar__item"></li></ul></div>
                    </nav>
                </div> 
            </div>
        </div>
    </div>
</section>
-->

<!--
<section class="mbr-box mbr-section mbr-section--relative mbr-section--fixed-size mbr-section--full-height mbr-section--bg-adapted mbr-parallax-background mbr-after-navbar" id="header1-73" style="background-image: url(assets/images/dublin-1049403.jpg);">
-->
    
<section class="mbr-box mbr-section mbr-section--relative mbr-section--fixed-size mbr-section--full-height mbr-section--bg-adapted mbr-parallax-background mbr-after-navbar" id="header1-73" style="background-image: url(assets/images/student-849822.jpg);">
    <div class="mbr-box__magnet mbr-box__magnet--sm-padding mbr-box__magnet--center-left">
        <div class="mbr-overlay" style="opacity: 0.6; background-color: rgb(76, 105, 114);"></div>
        <div class="mbr-box__container mbr-section__container container">
            <div class="mbr-box mbr-box--stretched"><div class="mbr-box__magnet mbr-box__magnet--center-left">
                <div class="row"><div class=" col-sm-6">
                    <div class="mbr-hero animated fadeInUp">
                        <h1 class="mbr-hero__text">Thanks!!!</h1>
                        <p class="mbr-hero__subtext">
                  We will be in contact soon     
                            <br></p>
                    </div>
                    <!--<div class="mbr-buttons btn-inverse mbr-buttons--left"><a class="mbr-buttons__btn btn btn-lg btn-default animated fadeInUp delay" href="#features1-85">LEARN MORE</a></div>-->
                </div></div>
            </div></div>
        </div>
        <div class="mbr-arrow mbr-arrow--floating text-center">
            <div class="mbr-section__container container">
                <a class="mbr-arrow__link" href="#features1-75"><i class="glyphicon glyphicon-menu-down"></i></a>
            </div>
        </div>
    </div>
</section>


<section class="mbr-section mbr-section--relative mbr-section--fixed-size" id="social-buttons2-84" style="background-color: rgb(240, 240, 240);">
    

    <div class="mbr-section__container container">
        <div class="mbr-header mbr-header--inline row">
            <div class="col-sm-4">
                <h3 class="mbr-header__text">FOLLOW US</h3>
            </div>
            <div class="mbr-social-icons mbr-social-icons--style-1 col-sm-8">
<a class="mbr-social-icons__icon socicon-bg-twitter" title="Twitter" target="_blank" href="https://twitter.com/doughmarketing1"><i class="socicon socicon-twitter"></i></a> 
                <a class="mbr-social-icons__icon socicon-bg-facebook" title="Facebook" target="_blank" href="https://www.facebook.com/DoughMarketing"><i class="socicon socicon-facebook"></i></a>
                 <a class="mbr-social-icons__icon socicon-bg-linkedin" title="LinkedIn" target="_blank" href="https://www.linkedin.com/company/dough-marketing"><i class="socicon socicon-linkedin"></i></a>              
    </div>
</section>


<section class="mbr-section mbr-section--relative mbr-section--fixed-size" id="contacts2-90" style="background-color: rgb(60, 60, 60);">
    
    <div class="mbr-section__container container">
        <div class="mbr-contacts mbr-contacts--wysiwyg row">
            <div class="col-sm-6">
                <figure class="mbr-figure mbr-figure--wysiwyg mbr-figure--full-width mbr-figure--no-bg">
                    <div class="mbr-figure__map mbr-figure__map--short mbr-google-map">
                        <p class="mbr-google-map__marker" data-coordinates="53.2736945,-6.2579447"></p>
                    </div>
                </figure>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-5 col-sm-offset-1">
                        <p class="mbr-contacts__text"><strong>ADDRESS</strong><br>
Dough Marketing<br>
Ballinteer<br>
Dublin, Ireland<br><br>
<strong>CONTACT US</strong><br>
Email: hello@doughmarketing.com<br>
Phone: +353 (87) 99532367</p>
                    </div>
                <!--    <div class="col-sm-6"><p class="mbr-contacts__text"><strong>ABOUT US</strong></p><ul class="mbr-contacts__list"><li><a href="#" class="text-gray">Bootstrap one page template</a><a class="mbr-contacts__link text-gray" href="#"></a></li><li><a href="#" class="text-gray">Bootstrap basic template</a><a class="mbr-contacts__link text-gray" href="#"></a></li><li><a href="#" class="text-gray">Bootstrap gallery template</a></li><li><a href="#" class="text-gray">Bootstrap responsive template</a></li><li><br></li></ul></div>
                </div>  -->
            </div>
        </div>
    </div>
</section>

<footer class="mbr-section mbr-section--relative mbr-section--fixed-size" id="footer1-91" style="background-color: rgb(68, 68, 68);">
    
    <div class="mbr-section__container container">
        <div class="mbr-footer mbr-footer--wysiwyg row">
            <div class="col-sm-12">
                <p class="mbr-footer__copyright"></p><p>Copyright (c) 2016 Dough Marketing.</p><p></p>
            </div>
        </div>
    </div>
</footer>

  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script src="assets/jquery/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js"></script>
  <script src="assets/smooth-scroll/SmoothScroll.js"></script>
  <script src="assets/jarallax/jarallax.js"></script>
  <script src="assets/bootstrap-carousel-swipe/bootstrap-carousel-swipe.js"></script>
  <script src="assets/masonry/masonry.pkgd.min.js"></script>
  <script src="assets/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/social-likes/social-likes.js"></script>
  <script src="assets/mobirise/js/script.js"></script>
  <script src="assets/mobirise-gallery/script.js"></script>
  
 <script type="text/javascript">
  nudgespot.identify("<?php echo $_POST["email"]; ?>", {first_name: "<?php echo $_POST["name"]; ?>", last_name: ""});
</script>
    <script type="text/javascript">
  nudgespot.track("activity_name", {activity_properties_map});
</script>
</body>
</html>