<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Document {
	
	
	var $CI = null;
	
	//Header Vars
	var $meta_obj = "";
	var $meta = "";
	var $title = "";
	var $css = "";
	
	//User Vars
	var $loggedin = 0;
	var $userDetails = "";
	
	//fb Vars
	var $fb_app_id = "";
	
	//Index Vars
	var $error = "";
	var $popup = "";
	var $option = "";
		
	public function __construct($userid=0)
	{
			$this->CI =& get_instance();
			$this->setUserDetails($userid);
	}
	
	public function setHeaderTags($newsid="") {
		
		$this->setMeta($newsid);
		$this->setTitle();
		$this->setFBAppId();
		
		return true;
	}
	
	public function setUserDetails($userid) {
		
		$this->loggedin = $userid;
		
		//get users name, points
		$this->CI->load->model('user_model');
		$this->userDetails = $this->CI->user_model->getDetails($userid);
		
		return true;
		
	}
	
	public function setFBAppId() {
		
		$this->fb_app_id = $this->CI->config->item('fb_appid');
		
		return true;
		
	}
	
	public function setMeta($newsid="") {
		
		$this->CI->load->model('article_model');
		$meta = (is_numeric($newsid) == true) ? $this->CI->article_model->getArticleMeta($newsid) : "";
		
		if($meta == "") {
			$meta = array(
				'title' => "11Secrets.com | Discover all the best news and gossip!",
				'type' => 'website',
				'url' => 'http://www.11secrets.com/',
				'img' => 'http://media8.11secrets.com/aimg-ahurjja-1335451003-X.jpeg'
				);
			
			$append = "";
		} else {
			$meta['title'] .= " | 11Secrets.com";
		}
		
		$meta = (object) $meta;
		
		$meta_title = str_replace("'", "", $meta->title);
		$meta_html = "
		<meta property='og:title' content='$meta_title' />
		<meta property='og:type' content='$meta->type' />
		<meta property='og:url' content='$meta->url' />
		<meta property='og:image' content='$meta->img' />";
		
		$this->meta_obj = $meta;
		$this->meta = $meta_html;
		
		return true;
		
	}
	
	public function setTitle() {
				
		$this->title = "<title>".$this->meta_obj->title."</title>";
				
		return true;
		
	}
	
	public function setCSS($css_file) {
		
		$this->css = $css_file;
		
	}
	
	public function setError($error) {
		
		$this->error = $error;
		
		return true;
	}
	
	public function setOption($option) {
		
		$this->option = $option;
		
		return true;
	}
	
	public function setPopup($popup) {
		
		$this->popup = $popup;
		
		return true;
	}
	
	public function getMeta() {
		
		return $this->meta;
		
	}
	
	public function getTitle() {
		
		return $this->title;
	}
	
}
