function submit_beta_form(){
	
	var code = $('#code').val();
	var post_url = BASEURL+"user/beta_check";
	

	$.post(post_url,
      { code: code,
		ajax: '1' 
	   },

      // when the Web server responds to the request
      function(result) {
			if(result == 'worked') {
				pop_up('signup', 'nothing', '');
			}
			else {
				var error = "<div class='error'>"+result+"</div>";
				$('.error').html(error);
			}
			
		});

}