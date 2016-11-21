<?php
/* Template Name: Social sign in */

if(!isset($_SESSION['SocialLogin']) && empty($_SESSION['SocialLogin'])){
    wp_redirect(site_url()); die;
}
get_header(); ?>
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
                <div class="col-md-10 col-md-offset-2">
                    <div class="col-md-10 col-sm-6 grayStyle">
                        <div class="box-inner">
                            <div class="alert" id="regMessage" style="display: none;"></div>
                            <form class="" method="post" id="social_register">
                                <div class="form-group">
                                    <label>User Type</label>
                                    <select class="form-control" name="user_type" id="user_type">
                                        <option value="">Select User Type</option>
                                        <option value="normal_user">Gym User</option>
                                        <option value="trainer">Gym Owner</option>
                                    </select></div>
                                <div class="form-group user_gender_for_normal_user normal-hide">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Select User Gender</option>
                                        <option  <?php if(strtolower($_SESSION['SocialLogin']['gender']) == 'male'){ echo 'selected'; } ; ?> value="male">Male</option>
                                        <option <?php if(strtolower($_SESSION['SocialLogin']['gender']) == 'female'){ echo 'selected'; } ; ?> value="female">Female</option>
                                    </select>
                                </div>
								<?php if(!empty($_SESSION['SocialLogin']['name'])): ?>
									<div class="form-group">
										<input type="text" name="name" id="name" class="form-control" placeholder="Your Full Name" value="<?php echo $_SESSION['SocialLogin']['name']; ?>">
									</div>
								<?php else: ?>
									<div class="form-group">
										<input type="text" name="name" id="name" class="form-control" placeholder="Your Full Name">
									</div>
								<?php endif; ?>

                                <?php if(!empty($_SESSION['SocialLogin']['email'])): ?>
                                    <div class="form-group">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Address" value="<?php echo $_SESSION['SocialLogin']['email']; ?>" readonly="">
                                    </div>
                                <?php else: ?>
                                    <div class="form-group">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Address">
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password">
                                </div>
                                <input type="hidden" name="socialId" value="<?php echo $_SESSION['SocialLogin']['socialId']; ?>">
                                <input type="hidden" name="screen_name" value="<?php echo $_SESSION['SocialLogin']['screen_name']; ?>">
                                <input type="hidden" name="social_type" value="<?php echo $_SESSION['SocialLogin']['social_type']; ?>">
                                <input type="hidden" name="socialImage" value="<?php echo $_SESSION['SocialLogin']['socialImage']; ?>">
                                <?php wp_nonce_field('registercode','securitycode',false); ?>
                               <div class="ftext"> <input type="submit" name="submit" value="Signup"> </div>
                            </form>
                            <div class="clear"></div>
                            <p class="green-bg">Already A Member? <a href="<?php echo site_url().'/login'; ?>">Click Here</a> to Login here</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
	$(document).ready(function(){
		var quoteSource=[
		{
			quote: "Start by doing what's necessary; then do what's possible; and suddenly you are doing the impossible.",
			name:"Francis of Assisi"
	    },
	    {
	    	quote:"Believe you can and you're halfway there.",
	    	name:"Theodore Roosevelt"
	    },
	    {
	    	quote:"It does not matter how slowly you go as long as you do not stop.",
	    	name:"Confucius"
	    },
	    {
	    	quote:"Our greatest weakness lies in giving up. The most certain way to succeed is always to try just one more time.",
	    	name:"Thomas A. Edison"
	    },
	    {
	    	quote:"The will to win, the desire to succeed, the urge to reach your full potential... these are the keys that will unlock the door to personal excellence.",
	    	name:"Confucius"
	    },
	    {
	    	quote:"Don't watch the clock; do what it does. Keep going.",
	    	name:"Sam Levenson"
	    },
	    {
	    	quote:"A creative man is motivated by the desire to achieve, not by the desire to beat others.",
	    	name:"Ayn Rand"
	    },
	    {
	    	quote:"A creative man is motivated by the desire to achieve, not by the desire to beat others.",
	    	name:"Ayn Rand"
	    },
	    {
	    	quote:"Expect problems and eat them for breakfast.",
	    	name:"Alfred A. Montapert"
	    },
	    {
	    	quote:"Start where you are. Use what you have. Do what you can.",
	    	name:"Arthur Ashe"
	    },
	    {
	    	quote:"Ever tried. Ever failed. No matter. Try Again. Fail again. Fail better.",
	    	name:"Samuel Beckett"
	    },
	    {
	    	quote:"Be yourself; everyone else is already taken.",
	    	name:"Oscar Wilde"
	    },
	    {
	    	quote:"Two things are infinite: the universe and human stupidity; and I'm not sure about the universe.",
	    	name:"Albert Einstein"
	    },
	    {
	    	quote:"Always remember that you are absolutely unique. Just like everyone else.",
	    	name:"Margaret Mead"
	    },
	    {
	    	quote:"Do not take life too seriously. You will never get out of it alive.",
	    	name:"Elbert Hubbard"
	    },
	    {
	    	quote:"People who think they know everything are a great annoyance to those of us who do.",
	    	name:"Isaac Asimov"
	    },
	    {
	    	quote:"Procrastination is the art of keeping up with yesterday.",
	    	name:"Don Marquis"
	    },
	    {
	    	quote:"Get your facts first, then you can distort them as you please.",
	    	name:"Mark Twain"
	    },
	    {
	    	quote:"A day without sunshine is like, you know, night.",
	    	name:"Steve Martin"
	    },
	    {
	    	quote:"My grandmother started walking five miles a day when she was sixty. She's ninety-seven now, and we don't know where the hell she is.",
	    	name:"Ellen DeGeneres"
	    },
	    {
	    	quote:"Don't sweat the petty things and don't pet the sweaty things.",
	    	name:"George Carlin"
	    },
	    {
	    	quote:"Always do whatever's next.",
	    	name:"George Carlin"
	    },
	    {
	    	quote:"Atheism is a non-prophet organization.",
	    	name:"George Carlin"
	    },
	    {
	    	quote:"Hapiness is not something ready made. It comes from your own actions.",
	    	name:"Dalai Lama"
	    }

	];
		

		$('#quoteButton').click(function(evt){
			//define the containers of the info we target
			var quote = $('#quoteContainer p').text();
			var quoteGenius = $('#quoteGenius').text();
			//prevent browser's default action
			evt.preventDefault();
			//getting a new random number to attach to a quote and setting a limit
			var sourceLength = quoteSource.length;
			var randomNumber= Math.floor(Math.random()*sourceLength);
			//set a new quote
			for(i=0;i<=sourceLength;i+=1){
			var newQuoteText = quoteSource[randomNumber].quote;
			var newQuoteGenius = quoteSource[randomNumber].name;
			//console.log(newQuoteText,newQuoteGenius);
      var timeAnimation = 500;
      var quoteContainer = $('#quoteContainer');
      //fade out animation with callback
      quoteContainer.fadeOut(timeAnimation, function(){
        quoteContainer.html('');
				quoteContainer.append('<p>'+newQuoteText+'</p>'+'<p id="quoteGenius">'+'-								'+newQuoteGenius+'</p>');
        
        //fadein animation.
        quoteContainer.fadeIn(timeAnimation);
      });  
			
			break;
		};//end for loop
	
	});//end quoteButton function
		
		
});//end document ready
    </script>
<?php get_footer(); ?>