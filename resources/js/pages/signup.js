function submit_signup_form(){
	
	var name = $('#name').val();
	var email = $('#email').val();
	var password = $('#password').val();
	var post_url = BASEURL+"user/create_member";
	

	$.post(post_url,
      { name: name,
		email: email,
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
				var error = "<div class='error'>"+result+"</div>";
				$('.error').html(error);
			}
			
		});

}