<?php $userDetails = $document->userDetails; ?>
<div id="topContainer" class="middleLayer">

<div class="pageHeader">
	<div class="buttons" id="login_signup_buttons">	
		<?php if($document->loggedin == false) { ?>	
		<a href="javascript:void(0);" onclick="clipup('signup', '', ''); return false;" class="signup"><div class="signup_button"></div></a>
		
		<a href="javascript:void(0);" onclick="clipup('login', '', ''); return false;"><div class="login_button"></div></a>
		<?php } else
		//If user is logged in
		{ ?>
							<a href="<?php echo base_url(); ?>user/logout"><div class="logout_button"></div></a>
		<?php } ?>
		<div id="topright-box">
			<div class="popup-top">
				<div class="popup_title"></div>
			</div>
			<div class="popup-left"></div>
			<div class="popup-right"></div>
			<div class="topright_container"><div class="topright_deco"></div></div>
			<div class="popup-bottom"></div>
		</div>
	</div>
	
	<div class="logo">
		<a href="<?php echo base_url(); ?>">
			<img src="<?php echo base_url(); ?>resources/images/main/11secrets-logo-c.png" width="190" />
		</a>
	</div>
	<div id="loading_symbol">
	</div>
	
	<a href="<?=base_url(); ?>" class="back_waller"><div id="back_wall" class="displayed"></div></a>
	<div class="message_bar">
		<a href="javascript:void(0)" onclick="pop_up('reveal', '', <?=$article->newsid; ?>); return false;" class="reveal_link"><div id="reveal_btn"></div></a>
		<?php $lifted = ($article->lifted) ? "lifted" : "lift"; ?>
		<a href="javascript:void(0)" onclick="lift(<?=$article->newsid; ?>); return false;" class="keep_link"><div id="lift<?=$article->newsid; ?>" class="<?=$lifted; ?>"></div></a>
		<div class="original_source">Trouble Viewing? <a href="<?=$article->link; ?>" style="text-decoration:underline;">Go To Original Source</a></div>
	</div>
	<a href="<?=$next_link ?>" class="next_article"><div id="next_article_btn"></div></a>
</div>

</div>

<?php if($document->popup != "") { ?>
<script type="text/javascript">

$(document).ready(new function() {
	var type = "<?php echo $document->popup; ?>";
	var error = "<?php echo $document->error; ?>";
	var option = "<?php echo $document->option; ?>";
	pop_up(type, error, option);
});

</script>
<?php } ?>
