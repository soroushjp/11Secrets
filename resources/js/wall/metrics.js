var NEWSID = 0;

function store_metric(type) {
	var post_url = BASEURL+'wall_ajax/store_metric';
	$.post(post_url,
      { newsid:NEWSID,
		type: type,
		ajax: '1' 
	   },

      // when the Web server responds to the request
      function(result) {
		
	});
}

//callback interface for G+ button
function plusClick(data){
    if(data.state=="on"){
        // +1
		store_metric('google');
		
    }else if(data.state=="off"){
        // -1 (user took their +1 Away)
    }
}

//callback interface for LinkedIn share button
function LinkedInShare() {
	store_metric('linkedin');
}


$(document).ready(function() {
	
	/* Adding the Facebook Recommend Listener */
	FB.Event.subscribe('edge.create', function(href, widget) {
		store_metric('facebook');
	   });
	
	
	/* Add the Twittter Tweet Button Listener */

	twttr.events.bind('tweet', function(event) {
		store_metric('twitter');
	});
	
	
	
});

