<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wall_ajax extends CI_Controller {
	
	public function __construct()
	{
	            parent::__construct();
				$this->load->model('user_model');
	            $this->userid = $this->user_model->is_logged_in();
	}
	
	//Retrieves HTML for a pop-up
	function pop_up() {
		
		$this->load->helper('popup_helper');
		
		$type = $this->input->post('type');
		$error = $this->input->post('error');
		$option = $this->input->post('option');
		
		$error = ($error == "none") ? "" : $error;
		
		$this->load->model('article_model');
		$this->load->model('metrics_model');
		$this->load->helper('html_helper');
		$this->load->helper('bitly_helper');
				
		if($type == 'article') {
			
			if($this->userid != 0) { $this->metrics_model->addArticleRead($this->userid, $option); }
				
			$article = $this->article_model->getOneArticle($option);
			
			$article->bitly = make_bitly_url("http://www.11secrets.com/wall/article/".$article->newsid);
			
			$return = popup_html('article', $error, $article);
			
			$return['data']['comments_html']  = "";
			
			foreach($article->comments as $comment) {
				$comment_html = create_comment_html($comment, $this->userid, $this->user_model->isAdmin($this->userid), false);
				$return['data']['comments_html'] .= $comment_html;
			}
			
			$return['data']['avatar'] = $this->user_model->getAvatar($this->userid);
						
			echo $this->load->view($return['url'], $return['data'], true);
						
			return true;
		}
		
		if($type == 'signup') {
			$real_error = error_messages($error);
			$return = popup_html('signup', $real_error, $option);
			echo $this->load->view($return['url'], $return['data'], true);
			
			return true;
		}
		
		if($type == 'login') {
			
			$real_error = error_messages($error);
			$return = popup_html('login', $real_error, $option);
			echo $this->load->view($return['url'], $return['data'], true);
			
			return true;
		}
		
		if($type == 'beta') {
			
			$real_error = error_messages($error);
			$return = popup_html('beta', $real_error, $option);
			echo $this->load->view($return['url'], $return['data'], true);
			
			return true;
		}
		if($type == 'uploadavatar') {
			
			$this->load->helper(array('form', 'url'));
			
			$return = popup_html('uploadavatar', $error, $option);
			
			$return['data']['success'] = 0;
			$return['data']['error'] = "";
			$return['data']['avatar'] = $this->user_model->getAvatar($this->userid);
			
			echo $this->load->view($return['url'], $return['data'], true);
			
			return true;
		}
		
		if($type == 'reveal') {
			
					
			if($this->userid != 0) { $this->metrics_model->addRevealOpen($this->userid, $option); }
			
			$article = $this->article_model->getOneArticle($option);
			
			$article->bitly = make_bitly_url("http://www.11secrets.com/wall/article/".$article->newsid);
					
			$return = popup_html('reveal', $error, $article);
			
			echo $this->load->view($return['url'], $return['data'], true);

			return true;
			
		}
		
		return false;
	}
	
	
	function lift() {
		
		$newsid = $this->input->post('newsid');
		
		//if user is not signed up
		if($this->userid == 0) {
			echo "login";
			return false;
		}
		
		//else re-lift the article
		$this->load->model('article_model');
		
		//Add re-lift point, vote, collection
		$relifted = $this->article_model->addArticleLift($newsid, $this->userid);
		
		if(!$relifted) {
			echo "removed";
			return false;
		}
		
		 echo "worked";	
	}
	
	function comment() {
		
		$newsid = $this->input->post('newsid');
		$comment = $this->input->post('comment');
				
		//if user is not signed up
		if($this->userid == 0) {
			echo "login";
			return false;
		}
	
		//user is signed up, lets process the comment
		$this->load->model('article_model');
		
		//Add comment to article
		$comment_posted = $this->article_model->addComment($newsid, $this->userid, $comment);
		
		if(!$comment_posted) {
			echo "failed";
			return false;
		}
		
		$commentid = $this->article_model->getLastCommentID();
		$comment = $this->article_model->getOneComment($commentid);
		
		$this->load->helper('html_helper');
		
		echo create_comment_html($comment, $this->userid);
		
	}
	
	function remove_comment() {
		
		//Switch this with SQL statement to retrieve points for comments if this is ever implemented in DB
		$comment_points = 10;
		
		if($this->userid == 0) {echo 0; return false;}
		if(!isset($_POST['commentid'])) {echo 0; return false;}
		
		$commentid = $_POST['commentid'];
		
		if(!is_numeric($commentid)) {echo 0; return false;}
		
		$this->load->model('article_model');
		
		$comment_owner = $this->article_model->getCommentOwner($commentid);
		
		if(!$comment_owner) {echo 0; return false;}
		
		//Check if logged in user is either the comment owner (or an admin, to be built) (SJPOUR)
		if($this->userid != $comment_owner && !$this->user_model->isAdmin($this->userid))  {echo 0; return false;}
		
		$success = $this->article_model->deleteComment($commentid);
	
		//Remove user's points (TO BE FIXED)
		$this->user_model->removeUserPoint($comment_owner, $comment_points);
		
		if(!$success) {echo 0; return false;}
			
		echo $comment_points; return true;
		
	}
	
	
	function get_articles() {
		
		$default_number = 20;
				
		$number = isset($_POST['number']) ? $this->input->post('number') : $default_number;
		$start = isset($_POST['start']) ? $this->input->post('start') : 20;
		$userid = isset($_POST['userid']) ? $this->input->post('userid') : 0;
		$tags = isset($_POST['tags']) ? $this->input->post('tags') : '';
		
		$number = ($number == 0) ? $default_number : $number;
		
		$this->load->model('article_model');
		$articles = $this->article_model->getArticles($number, $start, $userid, $tags);
		
		$demo = false;
		if(count($articles) == 0 && $start == 0) {
			$demo = true;
			$this->load->helper('demo_helper');
			
			if($userid != 0) $articles = ($userid == $this->userid) ? personal_collection_demo() : other_collection_demo();
			else $articles = no_articles_with_tag_demo();
		}
		
		$thumb = $this->user_model->getThumb($this->userid);
		
		$this->load->helper('html_helper');
		$articles_html = create_articles_html($articles, $thumb, $this->userid, $this->user_model->isAdmin($this->userid), $demo);
		
		//Code for determining appropriate page header for current articles. If this grows more complex, should be moved out into it's own function
		if($userid==0 && $this->userid == 0) {
			$header = "Discover all the best news and gossip!";
		} elseif ($userid==0 && $this->userid != 0) {
			$header = "";
		} elseif ($userid==$this->userid) {
			$header = "My Article Collection";
		} else {
			$myuser = $this->user_model->getDetails($userid);
			$header = "$myuser->name's Article Collection";
		}
		
		$ajax_return = array($header, $articles_html);
		
		echo json_encode($ajax_return);
	}	
	
	function store_metric() {
		
		$this->load->model('metrics_model');
		
		$type = $this->input->post('type');
		$newsid = $this->input->post('newsid');
		
		$this->metrics_model->log_article_share($this->userid, $newsid, $type);
		
		return true;
		
	}
	
	function get_available_tags() {
		
		$userid = isset($_POST['userid']) ? $this->input->post('userid') : 0;
		$tags = isset($_POST['tags']) ? $this->input->post('tags') : '';
		
		$this->load->model('article_model');
		
		$tags_html = "";
		$proc_tags = "";
		$selected_tags = array();
		
		if($tags != "") {
			$this->load->helper('tags_helper');
			$proc_tags = processTagCSV($tags);
	
			$proc_tags_array = str_getcsv($proc_tags);
	
			$selected_tags = $this->article_model->getTagsByID($proc_tags);
		}
		
		$new_tags = $this->article_model->getRemainingTags($userid,$proc_tags);
	
		foreach($selected_tags as $tag) {
	
			$tag_action = "remove_tag";
			$append_class= " clicked_tag";
		
			$selected_tag_html = "<a id=\"tag_$tag->tagid\" href=\"javascript:void(0);\" onclick=\"$tag_action($tag->tagid); return false\" class=\"tag_link$append_class\">$tag->tag</a>";
		
			$tags_html .= $selected_tag_html;
	
		 }
		
		foreach($new_tags as $tag) {

			$tag_action = "add_tag";
			$append_class = "";

			$new_tag_html = "<a id=\"tag_$tag->tagid\" href=\"javascript:void(0);\" onclick=\"$tag_action($tag->tagid); return false\" class=\"tag_link$append_class\">$tag->tag</a>";

			$tags_html .= $new_tag_html;

		 }
		
		echo $tags_html;
	}
	
	public function social_clipup() {
		
		//get recent activity
		$this->load->model('facebook_model');
		$social_actions = $this->facebook_model->getFBActions($this->userid, 3);
		
		//add article title & link to recent activity objs
		$this->load->model('article_model');
		$completeActionList = $this->article_model->getTitles($social_actions);
		
		//Put it into html using helper
		$this->load->helper('html_helper');
		$activity_html = create_socialclip_html($completeActionList);
		
		//output it below social on/off & format it
		$complete_html = $activity_html;
		
		echo $complete_html;
		
	}
	
	public function post_article_fb() {
		
		if($this->user_model->isFacebookConnected($this->userid) == false) { return 'failed'; }
		
		$newsid = $this->input->post('newsid');
		
		$this->load->library('facebook');
		$facebook = new Facebook();
				
		$article = base_url()."wall/article/".$newsid;
				
		$fbArticleId = $facebook->postArticle($article);
						
		$this->load->model('facebook_model');
		$this->facebook_model->storeFBAction('read', $newsid, $this->userid, $fbArticleId['id']);
								
		if($fbArticleId == false) {
			return false;
		}
		else {
			return true;
		}
				
	}
	
	public function keep_article_fb() {
		
		if($this->user_model->isFacebookConnected($this->userid) == false) { return 'failed'; }
		
		$newsid = $this->input->post('newsid');
		
		$this->load->library('facebook');
		$facebook = new Facebook();
				
		$article = base_url()."wall/article/".$newsid;
				
		$fbArticleId = $facebook->keepArticle($article);
		
		$this->load->model('facebook_model');
		$this->facebook_model->storeFBAction('keep', $newsid, $this->userid, $fbArticleId['id']);
								
		if($fbArticleId == false) {
			return false;
		}
		else {
			return true;
		}
				
	}
	
	public function delete_fb_action() {
		
		$actionid = $this->input->post('actionid');
		
		$this->load->model('facebook_model');
		$fbid = $this->facebook_model->getFBId($actionid);
		
		$this->load->library('facebook');
		$facebook = new Facebook();
								
		$fbArticleId = $facebook->deleteAction($fbid);
		
		$this->facebook_model->delete_fb_action($actionid);
		
		return true;
		
	}
	
}
