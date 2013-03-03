<link rel="stylesheet" href='<?php echo base_url(); ?>resources/css/pages/<?=$page_css;?>' type="text/css" media="screen, projection" /> 

<script src="<?php echo base_url(); ?>resources/js/pages/beta.js"></script>

<?php $this->load->helper('form'); ?>

<div class="beta_window">
	
	<div class="beta_message"><div class="beta_title">Enter your Invitation Code</div>
	<img src="/resources/images/main/ticket.png" width="257" height="159">
	</div>
				
		<div class="beta_signup">
			<div class="form_field">
				<input id="code" type="text" name="code" maxlength="95" onfocus="if(this.value=='Invitation Code') { this.value='' };" onblur="if(this.value=='') { this.value = 'Invitation Code' }"  value="<?php echo set_value('code', 'Invitation Code'); ?>">
			</div>
		
			<div class="submit_button">
				<a href="javascript:void(0);" onclick="submit_beta_form();"><div class="exclusive_button"></div></a>
			</div>
		
		
		<div class="error"><?php echo validation_errors('<span>'); ?><?php echo $error; ?></div>
		<div class="login_link">Already a member? <a href="javascript:void(0);" onclick="pop_up('login', 'nothing', ''); return false;">Login</div>
		</div>
			
</div>




<script type="text/javascript">

//Make forms submit on enter press

$('#code').bind('keypress', function(e) {
        if(e.keyCode==13){
            submit_beta_form();
        }
});



</script>
