<div class="article_window" style="margin-left: 0px !important; padding-top: 31px !important;">

	<div class="article_envelop">
		<div class="article_img_peek">
			<img src="<?=$option->imageArray->img_local; ?>" height="110px;" />
		</div>
		<div class="article_news_title"><?=$option->title; ?></div>
		<div class="invite_tag">
			<div class="invite_text">Send by:</div>
			<div class="invite_line"></div>
			<div class="social_button_pink">
				<a href="javascript:void(0);" onclick="share_prompt(); return false;" >
					<div class="pink_button" id="pink_fb"><img src="/resources/images/buttons/pink_fb.png" width="60" height="60" /></div>
				</a>
				<?php 
				$encoded_title = htmlentities($option->title); 
				$article_url = base_url()."wall/article/".$option->newsid;
				$encoded_url = htmlentities($article_url); 
				$encoded_img = htmlentities($option->imageArray->img_local); 
				?>
				<div class="pink_button" id="custom-tweet-button">
					<a class="tweet_popup" href="https://twitter.com/share?text=<?=$encoded_title; ?>&url=<?=$option->bitly; ?>" data-url="" >
						<img src="/resources/images/buttons/pink_twitter.png" width="60" height="60" />
					</a>
				</div>
				<a class="pin_popup" href="http://pinterest.com/pin/create/button/?url=<?=$encoded_url; ?>&media=<?=$encoded_img; ?>&description=<?=$encoded_title; ?>" count-layout="none"><div class="pink_button" id="pink_pinterest"><img src="/resources/images/buttons/pink_pinterest.png" width="60" height="60" /></div></a>
				<div class="pink_button" id="pink_email">
					<a href="mailto:yourbestie@mail.com&?subject=Interesting%20Article:%20<?=$encoded_title; ?>&body=Check%20out%20this%20article%20I%20found%20on%2011Secrets.com:%20<?=$encoded_url; ?>">
						<img src="/resources/images/buttons/pink_email.png" width="60" height="60" /></div>
					</a>
			</div>
		</div>
	</div>

</div>

<?php $stripped_title = str_replace("'", "", $option->title); ?>
<script type="text/javascript">

	function share_prompt()
	{


	    FB.ui(
	       {
	         method: 'feed',
	         name: "<?=$stripped_title; ?>",
	         link: "<?=base_url(); ?>wall/article/<?=$option->newsid; ?>",
	         picture: "<?=$option->imageArray->img_local; ?>",
	         caption: '',
	         description: '11Secrets.com | Discover all the best news and gossip!'
	       },
	       function(response) {
	         if (response && response.post_id) {
				store_metric('facebook');
	         } else {
	           //do nothing
	         }
	       }
	     );
	 }
	
	$('.tweet_popup').click(function(event) {
	    var width  = 575,
	        height = 400,
	        left   = ($(window).width()  - width)  / 2,
	        top    = ($(window).height() - height) / 2,
	        url    = this.href,
	        opts   = 'status=1' +
	                 ',width='  + width  +
	                 ',height=' + height +
	                 ',top='    + top    +
	                 ',left='   + left;

	    window.open(url, 'twitte', opts);
		store_metric('twitter');

	    return false;
	  });
	
	$('.pin_popup').click(function(event) {
	    var width  = 575,
	        height = 400,
	        left   = ($(window).width()  - width)  / 2,
	        top    = ($(window).height() - height) / 2,
	        url    = this.href,
	        opts   = 'status=1' +
	                 ',width='  + width  +
	                 ',height=' + height +
	                 ',top='    + top    +
	                 ',left='   + left;

	    window.open(url, 'twitte', opts);
		store_metric('pinterest')

	    return false;
	  });
	

</script>