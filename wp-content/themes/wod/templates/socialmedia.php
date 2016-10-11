<?php
/* Template Name: My gym's social media feed */
if(!(is_user_logged_in() )) {
    wp_redirect(site_url().'/login');
    exit();
}
$user_detail = wp_get_current_user();
if($user_detail->roles[0] == 'trainer'){
    wp_redirect(site_url().'/options');
    exit();
}
get_header(); ?>
    <link href="https://vjs.zencdn.net/4.2/video-js.css" rel="stylesheet">
    <script src="https://vjs.zencdn.net/4.2/video.js"></script>
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
            <h2><?php if(get_post_meta( get_the_ID(), 'Title', true )){
                    echo get_post_meta( get_the_ID(), 'Title', true );
                }else{ echo get_the_title(); } ?></h2>
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <?php get_sidebar(); ?>
                </div>
                <div class="col-md-8 col-sm-8">
                    <div class="personal-best" id="myTabs">
                        <?php
                        $user_detail = wp_get_current_user();
                        $my_trainer = get_user_meta( $user_detail->ID, 'my_trainer', true );
                        //$insta_last = get_user_meta( $my_trainer, 'my_instagram', true );
                        $insta_last="";
                        $fb_last = get_user_meta( $my_trainer, 'my_facebook', true );
                        $tw_last = get_user_meta( $my_trainer, 'my_twitter', true ); ?>
                        <?php
                        if($insta_last == '' && $fb_last == '' && $tw_last == '')
                        {
                            echo '<p style="line-height: 15px;" class="alert alert-warning text-center">No Social Media.</p>';
                        }
                        else
                        { ?>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tabStyle" role="tablist">
                            <?php if(!empty($insta_last)){ ?>
                                <li role="presentation" class="active"><a href="#insta" aria-controls="insta" role="tab" data-toggle="tab"><i class="fa fa-instagram fa-fw"></i>Instagram</a></li>
                            <?php }
                            if(!empty($fb_last)){ ?>
                            <li role="presentation"><a href="#facebook" aria-controls="facebook" role="tab" data-toggle="tab"><i class="fa fa-facebook-square fa-fw"></i>Facebook</a></li>
                            <?php }
                            if(!empty($tw_last)){ ?>
                            <li role="presentation"><a href="#twitter" aria-controls="twitter" role="tab" data-toggle="tab"><i class="fa fa-twitter-square fa-fw"></i>Twitter</a></li>
                            <?php } ?>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <?php
                            if(!empty($insta_last))
                            {
                                $url_end = str_replace('https://www.instagram.com','',$insta_last);
                                $username = explode('/',$url_end);
                                $filtered = array_filter($username, 'filter_callback');
                                $insta_user_id = !empty($filtered[1]) ? getInstaID($filtered[1]) : '00000';
                                $insta_feeds = !empty($insta_user_id) ? getInstagramFeeds($insta_user_id) :'';
                                ?>
                                <div role="tabpanel" class="tab-pane active" id="insta" style="min-height:800px;">
                                    <div id="col-md-12 instafeed">
                                        <?php if(!empty($insta_feeds)):

                                            if(!empty($insta_feeds->data))
                                            foreach($insta_feeds->data as $media):

                                                $content = '<div class="col-md-3 col-sm-4"><div class="social-box">';
                                                // output media
                                                if ($media->type === 'video') {
                                                    // video
                                                    $poster = $media->images->low_resolution->url;
                                                    $source = $media->videos->standard_resolution->url;
                                                    $content .= "<video class=\"media video-js vjs-default-skin\" width=\"250\" height=\"250\" poster=\"{$poster}\"
                           data-setup='{\"controls\":true, \"preload\": \"auto\"}'>
                             <source src=\"{$source}\" type=\"video/mp4\" />
                           </video>";
                                                } else {
                                                    // image
                                                    $image = $media->images->low_resolution->url;
                                                    $content .= "<a target='_blank' href='".$media->link."'><img class=\"media\" src=\"{$image}\"/></a> ";
                                                }
                                                // create meta section
                                                $avatar = $media->user->profile_picture;
                                                $username = $media->user->username;
                                                $comment = $media->caption->text;
                                                $comt = str_replace("#",'',$comment);
                                                $content .= "<div class=\"content\">
                           <div class=\"avatar\" style=\"background-image: url({$avatar})\"></div>
                           <p>{$username}</p>
                           <div class=\"comment\">{$comt}</div>
                         </div>";
                                                // output media
                                                echo $content . '</div></div>';

                                         endforeach;
                                        endif;
                                    ?>
                                    </div>
                                </div>
                            <?php
                            }
                            if(!empty($fb_last)){
                                $user_end = str_replace('https://www.facebook.com','',$fb_last);
                                $usernamefb = explode('/',$user_end);
                                ?>
                                <div role="tabpanel" class="tab-pane" id="facebook" style="min-height:800px;">

                                    <?php $profile_id = $usernamefb[1]; ?>
                                    <div id="fb-root"></div>
                                    <script>(function(d, s, id) {
                                            var js, fjs = d.getElementsByTagName(s)[0];
                                            if (d.getElementById(id)) return;
                                            js = d.createElement(s); js.id = id;
                                            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=944251092321091";
                                            fjs.parentNode.insertBefore(js, fjs);
                                        }(document, 'script', 'facebook-jssdk'));</script>
                                    <div class='col-md-offset-2 facebook_feeds'>
                                        <div class="fb-page" data-href="https://www.facebook.com/<?php echo $profile_id; ?>/"  data-height="800"  data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-width="750" data-hide-cover="false" data-show-facepile="true"></div>
                                    </div>
                                </div>
                            <?php }
                            if(!empty($tw_last)){

                                $url_end = str_replace('https://twitter.com','',$tw_last);
                                $username = explode('/',$url_end);
                                $filtered = array_filter($username, 'filter_callback');

                                //Twitter OAuth Settings, enter your settings here:
                                $CONSUMER_KEY = 'vvpmKjn1bcceB7fr8CGPDDSjF';
                                $CONSUMER_SECRET = 'sKVs8UPEpSUyVO7UkJn96YMLfaYNz6FRhsD3pddA0sMeU1vPvA';
                                $ACCESS_TOKEN = '2799583039-6zenxxExfSA9R59QGb1c8QMcMpS3YZkJ0GDZFkW';
                                $ACCESS_TOKEN_SECRET = 'p65oAn04gTFB9w0zZ5vNsLMXqdcpsWRPSkEJFu4S3aHpa';

                                Codebird::setConsumerKey($CONSUMER_KEY, $CONSUMER_SECRET);
                                $cb = Codebird::getInstance();
                                $cb->setToken($ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);

                                //$q = $_POST['q'];
                                $count = 20;
                                $api = 'statuses_userTimeline';

                                $params = array(
                                    'screen_name' => $filtered[1],
                                    //'q' => $q,
                                    'count' => $count
                                );

                                $data = $cb->$api($params);

                                ?>
                                <div role="tabpanel" class="tab-pane" id="twitter" style="min-height:800px;">

                                    <?php

                                        if(isset($data->errors) && !empty($data->errors)){

                                            echo '<p class="alert alert-danger text-center"> No feeds found.. </p>';
                                        }
                                        else{

                                            if(isset($data) && !empty($data)) { $i = 1;
                                                foreach ($data as $tfeed) {
                                                    if($i <= $count){
                                                        $name = $tfeed->user->name;
                                                        $imgurl = $tfeed->entities->media[0]->media_url;
                                                        $profileimgurl = $tfeed->user->profile_image_url;
                                                        $screen_name = $tfeed->user->screen_name;
                                                        $text = $tfeed->text;
                                                        $head = explode('http://', $text);
                                                        $headback = explode('/', $head[1]);
                                                        $imglnk = "http://" . $head[1];
                                                        $headname = $head[0];
                                                        $imghead = $headback[1];
                                                        $date = date('d M y', strtotime($tfeed->created_at));
                                                        ?>
                                                        <div class="twitter_feeds">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                    <img src="<?php echo $profileimgurl; ?>"/>
                                                                </div>
                                                                <div class="col-md-11">
                                                                    <div class="postDeta">
                                                                        <a target="_blank" href="<?php echo $tw_last; ?>"><?php echo $name; ?></a>@<?php echo  $screen_name; ?>
                                                                        <span><?php echo $date; ?></span>
                                                                    </div>
                                                                    <div class="twitt-hadding">
                                                                        <h1> <?php echo $headname; ?> </h1> <a target="_blank" href="<?php echo $imglnk; ?>"><?php echo 'pic.twitter.com/'.$imghead; ?></a>
                                                                    </div>
                                                                    <div class="post-pic">
                                                                        <img src="<?php echo $imgurl; ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }$i++;
                                                }
                                            }else{
                                                echo "No feeds found.";
                                            }
                                        }
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    $('#myTabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show');
    });

    jQuery(window).load(function(){
        $('#myTabs a').first().trigger("click");
    });
</script>
<?php get_footer(); ?>