//pop up function for login, signup and article viewing

function pop_up(type, error, option){	
	
	//$('html').css( 'overflow', 'hidden' );
	
	$("body").css("overflow", "hidden");
	
	if(type == 'article' || type == 'reveal') {
		divid = 'articlePopup';
	}
	else if(type == "login" || type == "signup") {
		//divid = 'registerPopup';
		divid = 'betaPopup';
		
	} else if(type=="uploadavatar") {
		divid = 'uploadavatar';
	} else if(type == 'beta') {
		divid = 'betaPopup';
	}
	
	var post_url = BASEURL+'wall_ajax/pop_up';
	$.post(post_url,
      { type: type,
		error:error,
		option:option,
		ajax: '1'
	   },

      // when the Web server responds to the request
      function(result) {
		
		//Remove any previously open popups before showing another one
		if($('#video_overlay').is(':visible')) {
			$('#video_overlay').remove();
			$('#page_overlay').remove();
		}
	
     	$('<div id="video_overlay"><div class="'+divid+'">'+result+'</div></div>').appendTo('body').append('<div class="close"></div>').fadeIn('fast');
		
		//Append the page transparent overlay
		$('body').append('<div id="page_overlay"></div>');
		
		if(type == 'beta' || type == 'login' || type == 'signup') {
			$('body').append('<div id="beta_overlay"></div>');
			$('.close').hide();
		}

		/* Grab viewport height middle position */ 
		var vph = $(window).height() / 2;

		/* Grab viewport width middle position */ 
		var vpw = $(window).width() / 2;

		/* Grab overlay height middle position */ 
		var vbh = $('#video_overlay').height() /2;

		/* Grab overlay width middle position */ 
		var vbw = $('#video_overlay').width() /2;

		/* Find overlay height middle on screen */ 
		var hoffsetval = vph - vbh - 15 + 'px';

		/* Find overlay width middle on screen */ 
		var woffsetval = vpw - vbw - 15 + 'px';

		/* Assign top offset to overlay and make visible */ 
		$('#video_overlay').css({'top' : hoffsetval, 'left' : woffsetval,  'visibility': 'visible'});
		
		if(type == 'article') {
			
			//Make forms submit on enter press
			$("#commentfieldpop"+option).bind('keypress', function(e) {
			        if(e.keyCode==13){
			            comment(option, $("#commentfieldpop"+option).val());
			        }
			});
			
			setCommentPermissions();
		}
		
		/* this call makes any FB HTML5 render after ajax call */
		FB.XFBML.parse();
		
		/* this call makes any linkedin button re-render after ajax call */
		//IN.parse();
		
		/* this call makes any google plus button re-render after ajax call */
		gapi.plusone.go();
		
		/* this call re-renders twitter tweet buttons after ajax call */
		$.ajax({ url: 'http://platform.twitter.com/widgets.js', dataType: 'script', cache:true});
		
      }
    );

	return true;
};

//functionalty for closing popups
$('#video_overlay .close').live('click', function(){
	closePopups();
})

$('#page_overlay').live('click', function(){
	closePopups();	
})

$('.close').click(function(){
	closePopups();
})

$('#page_overlay').click(function(){
	closePopups();
})

function closePopups() {
	if($('#video_overlay').is(':visible')){
    	$('#video_overlay').fadeOut('fast', function(){
    		$('#video_overlay').remove();
    	});
		$('#page_overlay').fadeOut('fast', function(){
			$(this).remove();
		});		
    }

	$('body').css( 'overflow', 'auto' );	
	
	var NEWSID = null;
}	


function clipup(type, error, option) {

	if($('#topright-box').css("height") == "520px") {
		$('#topright-box').css("height", "0px");
		$('.topright_container').html("<div class='topright_deco'></div>");
		login_signup_buttons(type, 'close');
	}
	else {
		
		login_signup_buttons(type, 'open');
		
		var post_url = BASEURL+'wall_ajax/pop_up';
		$.post(post_url,
	      { type: type,
			error:error,
			option:option,
			ajax: '1' 
		   },

	      // when the Web server responds to the request
	      function(result) {
			
				$('#topright-box').css("height", "520px");
				$('.topright_deco').html(result);
				
				if(type == "signup") {
					$('.popup_title').html('Sign up');
					$('#topright-box').css("right", "5px");
					$('.signup_message').html('');
					$('.signup_message').css("padding-bottom", "6px");
				}
				else  {
					$('.popup_title').html('Login');
					$('#topright-box').css("right", "98px");
					$('.login_message').html('');
					$('.login_message').css("margin-bottom", "6px");
				}
	
			}
			);
	
	}
	
}

function login_signup_buttons(type, action) {
	
	var antitype = (type == 'login') ? 'signup' : 'login';
	var antiaction = (action == 'open') ? 'close' : 'open';
	var antiDiv = "."+antitype+"_button";
	var divName = "."+type+"_button";
	
	if(action == 'close') {
		$(divName).css("background-position-y", "0px");
		$(antiDiv).css("background-position-y", "0px");
		$('#topContainer').addClass("middleLayer");
	}
	else {
		
		$(divName).css("background-position-y", "-68px");
		$(antiDiv).css("background-position-y", "0px");
		$('#topContainer').removeClass('middleLayer');
		
	}
	
}