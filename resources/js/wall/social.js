function social_sharing() {
	
	if($('#topright-box').css("height") == "320px") {
		
		$('#topright-box').removeClass('toprightbox_social');
		$('.topright_container').html("<div class='topright_deco'></div>");
		$('#topContainer').addClass("middleLayer");
		
	}
	else {
		
		var post_url = BASEURL+'wall_ajax/social_clipup';
		$.post(post_url,
	      { ajax: '1' 
		   },

	      // when the Web server responds to the request
	      function(result) {
		
			$('#topContainer').removeClass('middleLayer');

			$('#topright-box').addClass('toprightbox_social');
			$('.topright_container').addClass('social_topright');
			$('.topright_deco').addClass('social_deco');

			$('.topright_deco').html(result);
			$('.popup_title').html('Social Settings');

			$('div.popup-left').css("height", "114px");
			$('div.popup-right').css("height", "114px");
			$('div.popup_title').css("left", "102px");
	      });

		return true;
		
	}
	
}

function toggle_social() {
	
	if(SOCIAL == 1) {
		SOCIAL = 0;
		$('#social_light_img').attr("src", BASEURL+'resources/images/main/red-sphere.png');
		$('#social_btn').html('Turn Social ON');
	}
	else {
		SOCIAL = 1;
		$('#social_light_img').attr("src", BASEURL+'resources/images/main/green-sphere.png');
		$('#social_btn').html('Turn Social OFF');
	}
	
	return true;
}


function post_article_fb(newsid){	
		
	if(SOCIAL == 0) { return false; }
	
	var post_url = BASEURL+'wall_ajax/post_article_fb';
	$.post(post_url,
      { newsid: newsid,
		ajax: '1' 
	   },

      // when the Web server responds to the request
      function(result) {
		//do nothing
      });

	return true;
};


function keep_article_fb(newsid){	
	
	if(SOCIAL == 0) { return false; }
	
	var post_url = BASEURL+'wall_ajax/keep_article_fb';
	$.post(post_url,
      { newsid: newsid,
		ajax: '1' 
	   },

      // when the Web server responds to the request
      function(result) {
		//do nothing
      });

	return true;
};

//deletes a FB post about a read or reveal action
function deleteActivity(actionid) {
	
	var post_url = BASEURL+'wall_ajax/delete_fb_action';
	$.post(post_url,
      { actionid: actionid,
		ajax: '1' 
	   },

      // when the Web server responds to the request
      function(result) {
		//remove the activity from the popup
		$('#action'+actionid).html('');
      });

	return true;
	
}

function open_secretframe(newsid) {
	
	var url = BASEURL+"article/read/"+newsid;
	
	window.open(url);
	
	post_article_fb(newsid);
	
	
}
