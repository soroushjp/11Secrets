function submit_login_form(){
	
	var email = $('#email').val();
	var password = $('#password').val();
	var post_url = BASEURL+"user/validate_credentials";

	$.post(post_url,
      { email: email,
		password: password,
		ajax: '1' 
	   },

      // when the Web server responds to the request
      function(result) {
			if(!isNaN(result)) {
				$('#video_overlay .close').click();
				var restURL = "";
				if(result != 0) {
					restURL = "wall/article/"+result;
				}
				window.location = BASEURL+restURL;
			}
			else {
				$('.error').html(result);
			}
			
		});

}
