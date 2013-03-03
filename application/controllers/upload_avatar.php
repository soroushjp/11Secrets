<?php

class Upload_avatar extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->userid = $this->user_model->is_logged_in();		
		$this->load->helper('url');
		$this->load->helper('string');
		$this->load->library('imagelibrary');
		
		if (isset($_SERVER['HTTP_REFERER']))
		 {
		 	$this->session->set_userdata('previous_page', $_SERVER['HTTP_REFERER']);
		 }
		 else
		 {
		 	$this->session->set_userdata('previous_page', base_url());
		 }
	}
	
	function do_upload()
	
	{
		$tmp_avatar_path = "resources/images/user_thumb/";
		$thumb_postfix = "_thumb";
		$image_ext = ".jpg";
		
		if($this->userid == 0 || !is_numeric($this->userid)) return false;
		
		$imagelibrary = new Imagelibrary();
		
		$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . "/" . $tmp_avatar_path;
		
		$config['file_name'] = "user" . $this->userid . "-" . random_string("alnum", 10);
		$thumb_filename = $config['file_name'] . $thumb_postfix;
		
		$config['file_name'] .= $image_ext;
		$thumb_filename .= $image_ext;
		
		$config['overwrite'] = TRUE;
		$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
		$config['max_size']	= '4096';
		$config['max_width']  = '0';
		$config['max_height']  = '0';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			// For testing only, can be removed in production (SJPOUR)
			// echo "Fail whale: " . $this->upload->display_errors() . "<br/>\n";
			// echo "File path: " . $config['upload_path'] . "<br/>\n"; 
			
			redirect($this->session->userdata('previous_page'));
			return false;
		}
		
		$image_path = $config['upload_path'] . $config['file_name'];
		$thumb_path = $config['upload_path'] . $thumb_filename;
		
		if(!$imagelibrary->createAvatar($image_path, $image_path, "jpg")) {redirect($this->session->userdata('previous_page')); return false;}
		if(!$imagelibrary->createThumb($image_path, $thumb_path, "jpg")) {redirect($this->session->userdata('previous_page')); return false;}
		
		$image_url = base_url() . $tmp_avatar_path . $config['file_name'];
		$thumb_url = base_url() . $tmp_avatar_path . $thumb_filename;
		
		if(!$this->user_model->setAvatar($this->userid, $image_url)) {redirect($this->session->userdata('previous_page')); return false;}
		if(!$this->user_model->setThumb($this->userid, $thumb_url)) {redirect($this->session->userdata('previous_page')); return false;}
		
		// For testing only, can be removed in production (SJPOUR)	
		// echo "Great successss" . "<br/>\n";
		// echo "File path: " . $image_path . "<br/>\n"; 
		// echo "Thumb File path: " . $thumb_path . "<br/>\n"; 
		// echo "New URL: " . $image_url . "<br/>\n"; 
		// echo "New Thumb URL: " . $thumb_url . "<br/>\n"; 
			
		redirect($this->session->userdata('previous_page'));
		
		return true;

	}
	

}
?>