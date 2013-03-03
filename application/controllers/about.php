<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends CI_Controller {
	
	public function __construct()
	{
	            parent::__construct();
				$this->load->library('template');
				$this->load->model('user_model');
	            $this->userid = $this->user_model->is_logged_in();
	}
	
	public function index($page="privacy", $css="privacy.css")
	{			
		
		//Set the userDetails, page Meta, Title & CSS
		$this->load->library('document');
		$document = New Document($this->userid);
		$document->setHeaderTags();
		$document->setCSS($css);
						
		$data = array('document' => $document);
						
		$partials = array('content'=>"pages/$page",
						  'head'=>'template/header',
						  'top_bar' => 'template/top_bar',
						  'footer'=>'template/footer'); 
		
		$this->template->load('template/main', $partials, $data);
	}
	
	public function privacy() {
		
		$this->index('privacy', 'privacy.css');
		
	}
	
	public function terms() {
		
		$this->index('terms', 'privacy.css');
		
	}

		
}

/* End of file welcome.php */
/* Location: ./application/controllers/wall.php */
