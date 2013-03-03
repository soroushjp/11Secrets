<link rel="STYLESHEET" type="text/css" href="<?= base_url(); ?>resources/css/pages/uploadavatar.css">

<h1>Upload a Profile Photo</h1>

<h3><?php echo $error;?></h3>

<div id="upload_box">
	
	<?php if($success == 1 ) { ?>
		
		<div id="profile_image">
			<img src="<?=$avatar?>" height="160" width="160" />
		</div>
		<div id="upload_info">
			<span class="upload_message">Congrats! Upload Successful</span>

		</div>
		
	<?php } else { ?>
	
	<div id="profile_image">
		<img src="<?=$avatar?>" height="160" width="160" />
	</div>
	<div id="upload_info">
		<span class="upload_message">Upload your photo here below:</span>
		
		<?php echo form_open_multipart('upload_avatar/do_upload');?>
		
		<p class="file_list"><input class="file_input" type="file" name="userfile" size="20" /></p>
		
		<p><input class="input_submit" type="submit" value="Upload photo" /></p>
		
	</div>
	
	<?php } ?>
	
</div>