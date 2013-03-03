<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wall extends CI_Controller {
	
	public function __construct()
	{
	            parent::__construct();
				$this->load->library('template');
				$this->load->model('user_model');
	            $this->userid = $this->user_model->is_logged_in();
	}

	public function index($error="",$popup="", $option="")
	{		
		
		//Set the userDetails, page Meta, Title & CSS
		$this->load->library('document');
		$document = New Document($this->userid);
		$document->setHeaderTags($option);
		$document->setCSS('wall.css');
		$document->setError($error);
		$document->setPopup($popup);
		$document->setOption($option);
			
		if($this->session->userdata('error') != "") {
			$this->load->helper('popup_helper');
			$document->setError(error_messages($this->session->userdata('error')));
			$document->setPopup("signup");
			$this->session->unset_userdata('error');
		}
		
		//get articles
		$this->load->model('article_model');
		
		//Redirect to SEO Friendly URL
		if($popup=="article" && is_numeric($option)) {
			$uri = uri_string();
			$url_title = $this->article_model->getArticleSlug($option);
			if($url_title && strpos($uri, $url_title) === false) {
				redirect("/wall/article/$option/".$url_title, 'location', 301);
			}
		}
		
		//The current default article tags. 1 is Top Stories, 2 is Staff Picks.
		//Comma separated list of numeric ID's with no spaces. 
		//For all Tag Id's, refer to sw_tags table in db.
		$default_article_tags = "1";
		
		$articles = $this->article_model->getArticles(20, 0, 0, $default_article_tags);
		
		//make articles into html
		$this->load->helper('html_helper');
		$articles_html = create_articles_html($articles, $document->userDetails->thumb, $this->userid, $this->user_model->isAdmin($this->userid));
		
		$tags = $this->article_model->getDefaultTags();
		
		$data = array('document' => $document,
					  'articles_html' => $articles_html,
					  'tags' => $tags,
					  'default_tags' => $default_article_tags);
						
		$partials = array('content'=>'pages/wall',
						  'head'=>'template/header',
						  'top_bar' => 'template/top_bar',
						  'footer'=>'template/footer'); 
		
		$this->template->load('template/main', $partials, $data);
	}
	
	//loads wall with a given article open (article perma-link)
	function article($newsid) {
		
		$this->index('', 'article', $newsid);
		
	}
	
	function login() {
		
		$this->index('', 'login');
		
	}
	
	
		
}

/* End of file welcome.php */
/* Location: ./application/controllers/wall.php */
