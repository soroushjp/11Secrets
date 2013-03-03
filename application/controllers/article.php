<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {
	
	public function __construct()
	{
	            parent::__construct();
				$this->load->library('template');
				$this->load->model('user_model');
	            $this->userid = $this->user_model->is_logged_in();
	}

	public function read($newsid)
	{		
		
		//Set the userDetails, page Meta, Title & CSS
		$this->load->library('document');
		$document = New Document($this->userid);
		$document->setHeaderTags($newsid);
		$document->setCSS('iframe.css');
			
		//get articles
		$this->load->model('article_model');
		$article = $this->article_model->getOneArticle($newsid);
		$link = $article->link;
		
		//Redirect to SEO Friendly URL
		$current_uri = uri_string();
		$correct_uri = $this->article_model->getArticleInternalLink($newsid);
		if($correct_uri && strpos($current_uri, $correct_uri) !== 0) {
			redirect($correct_uri, 'location', 301);
		}
		
		//Get next random stumble artice link
		$next_newsid = $this->article_model->getRandomRecentArticle($newsid);
		$next_link = base_url().$this->article_model->getArticleInternalLink($next_newsid);
		
		//For the moment, iframe will have no footer
		$empty_footer = "";
		
		$data = array('document' => $document,
					  'link' => $link,
					  'footer' => $empty_footer,
					  'next_link' => $next_link,
					  'article' => $article);
						
		$partials = array('content'=>'iframe/iframe',
						  'head'=>'template/header',
						  'top_bar' => 'iframe/top_bar'
						); 
		
		$this->template->load('template/main', $partials, $data);
	}

	
	
		
}

/* End of file welcome.php */
/* Location: ./application/controllers/wall.php */
