<?php $userDetails = $document->userDetails; ?>
<div id="topContainer" class="middleLayer">

<div class="pageHeader">
	<div class="buttons" id="login_signup_buttons">	
		<?php if($document->loggedin == false) { ?>	
			<!-- switch to for Beta: pop_up('beta', 'nothing', ''); return false; -->
		<a href="javascript:void(0);" onclick="clipup('signup', 'nothing', ''); return false;" class="signup"><div class="signup_button"></div></a>
		
		<a href="javascript:void(0);" onclick="clipup('login', '', ''); return false;"><div class="login_button"></div></a>
		<?php } else { ?>
			<?php $avatar = $userDetails->thumb; ?>
			<div class="user_profile">
				<a href="javascript:void(0)" onclick="social_sharing();" >
					<div class="social_text">Social: </div>
					<div class="social_light">
						<img id="social_light_img" src="<? echo base_url(); ?>resources/images/main/green-sphere.png" width="14" height="15" />
					</div>
				</a>
				<a href="javascript:void(0)" onclick="pop_up('uploadavatar', '', '');">
					<div id="user_icon"><img src="<?=$avatar; ?>" height="30" width="30" /></div>
				</a>
				<a href="javascript:void(0);" onclick="get_collection(0, <?=$userDetails->userid; ?>, '', 0); return false;" class="mynews"><div id="user_name"><?=$userDetails->name; ?></div>
				<div id="points">(<?=$userDetails->points; ?>)</div>
				<a href="<?php echo base_url(); ?>user/logout"><div class="logout_button"></div></a>
				<a href="javascript:void(0);" onclick="get_collection(0, <?=$userDetails->userid; ?>, '', 0); return false;" class="mynews"><div class="collection_button"></div></a>
			</div>
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
			<img src="<?php echo base_url(); ?>resources/images/main/11secrets-logo-c.png" width="190"/>
		</a>
	</div>
	<div id="loading_symbol">
	</div>
	
	<a href="javascript:void(0);" onclick="get_collection();unpressMyNewsButton(); return false;" class="back_waller"><div id="back_wall"></div></a>
	<div class="message_bar">
		<div class="main_message"><?php if($document->loggedin == false) { ?> Discover all the best news and gossip!  <?php } ?></div>
	</div>

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
