<link rel="stylesheet" href='<?php echo base_url(); ?>resources/css/pages/<?=$page_css;?>' type="text/css" media="screen, projection" /> 

<script src="<?php echo base_url(); ?>resources/js/pages/login.js"></script>

<?php $this->load->helper('form'); ?>

<div id="login_window">
	

	<div class="login_message">Login</div>
	
	<div class="facebook_connect"><a href="<?=base_url(); ?>facebook_connect/login_redirect"><img src="<?php echo base_url(); ?>resources/images/buttons/facebookconnect2.png" width="200px" height="35px"></a></div>

	<div class="google_connect"><a href="<?=base_url(); ?>google_connect"><img src="<?php echo base_url(); ?>resources/images/buttons/googleconnect.png" width="200px" height="35px"></a></div>
	
	<!--
	<div class="form_or">OR</div>
	
	<div class="email_signup">
    	<div class="form_field">		
			<input id="email" type="text" name="email" maxlength="120" onfocus="if(this.value=='Email/Username') { this.value='' };" onblur="if(this.value=='') { this.value = 'Email/Username' }"  value="<?php echo set_value('email', 'Email/Username'); ?>">
		</div>

		<div class="form_field">
				<input id="password" name="password" maxlength="100" onfocus="if(this.value=='Password') { this.value='' }; this.type = 'password'" onblur="if(this.value=='') { this.value = 'Password' } if(this.value == 'Password') { this.type = 'text' }"  value="<?php echo set_value('password', 'Password'); ?>">
		</div>
	
		<div class="submit_button">
			<a href="#" class="awesome blue large" onclick="submit_login_form('<?=base_url(); ?>');" >Log in</a>
		</div>
	-->
	
	<div class="error"><?php echo $error; ?></div>
	<!-- <div class="login_link">Don't have an account? <a href="javascript:void(0);" onclick="pop_up('beta', 'nothing', ''); return false;">Get Access</div> -->
	
	</div>
	

</div><!-- end login_form-->

<script type="text/javascript">
//Make forms submit on enter press

$('#password').bind('keypress', function(e) {
        if(e.keyCode==13){
            submit_login_form();
        }
});

</script>