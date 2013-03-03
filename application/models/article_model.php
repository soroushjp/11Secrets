<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article_model extends CI_Model {
	
	function __construct()
	    {
	        parent::__construct();
			$this->load->model('user_model');
			$this->userid = $this->user_model->is_logged_in();
			$this->load->database();
	    }


	
	function getOneArticle($newsid) {
		
		//Select $number of articles ordered first by date and then by their votes
		$query = $this->db->query("SELECT TIME(published_date) as time, MONTHNAME(published_date) as month, DAY(published_date) as day, YEAR(published_date) as year, sourceName, votes, title, newsid, link FROM sw_news_metainfo WHERE newsid = $newsid");
		
		$article = $query->row();
		
		$article->imageArray = $this->getImgDetails($article->newsid);
			
		$article->content = $this->getContent($article->newsid);
						
		$article->comments = $this->getComments($article->newsid, true);
		
		$article->lifted = $this->getLiftedStatus($article->newsid, $this->userid);
		
		return $article;
		
	}
	
	function getArticles($number=20, $start=0, $userid=0, $tags="") {
		
		$SQL = $this->getSQLFiltersWHERE($userid, $tags);
		
		if(!$SQL) return false;
		
		$JOIN = $SQL[0];
		$WHERE = $SQL[1];

		$query_string = "SELECT sw_news_metainfo.newsid, MONTHNAME(sw_news_metainfo.published_date) as month, DAY(sw_news_metainfo.published_date) as day, YEAR(sw_news_metainfo.published_date) as year, sw_news_metainfo.sourceName, sw_news_metainfo.votes, sw_news_metainfo.title, sw_news_metainfo.link
						FROM sw_news_metainfo
						$JOIN
						$WHERE
						ORDER BY DATE(sw_news_metainfo.published_date) DESC, sw_news_metainfo.votes DESC, sw_news_metainfo.newsid DESC LIMIT $start, $number";
		
		$query = $this->db->query($query_string);	
		
		$articles = $query->result();
		
		foreach ($articles as &$article) {
				
			$article->imageArray = $this->getImgDetails($article->newsid);
			
			$article->commentCount = $this->getCommentCount($article->newsid);
		
			//$article->comments = $this->gComments($article->newsid, false);
		
			if($userid != 0 && $userid == $this->userid) $article->lifted = true;
			else {
				$article->lifted = $this->getLiftedStatus($article->newsid, $this->userid);
			}
		}	
		
		return $articles;
	}
	
	public function getArticleSlug($newsid) {
		
		if(!is_numeric($newsid) || $newsid == 0) return false;
		
		//First we look in database to see if a previous slug has been generated
		$query = $this->db->query("SELECT slug FROM sw_news_metainfo WHERE newsid = '$newsid'");
		$data = $query->row();
		if($data && $data->slug) return $data->slug;
		
		//Otherwise, we generate one and insert in in the db for all future use
		$this->load->helper('slug_helper');
		$slug = createSlug($this->getArticleTitle($newsid));
		
		if(!$slug) return false;
		$this->db->where('newsid', $newsid);
		$this->db->set('slug', $slug);
		$this->db->update('news_metainfo');
		
		return $slug;
	}
	
	//Select the article content
	function getContent($newsid) {
		
		if(empty($newsid)) { return false; }
		
		$this->db->select('content');
		$query = $this->db->get_where('news_content', array('newsid' => $newsid));
		$data = $query->row();
		
		return $data->content;
		
		return "";
	}
	
	//Select the article image
	function getImgDetails($newsid) {
		
		$this->db->select('img_local, height, width');
		$query = $this->db->get_where('news_images', array('newsid' => $newsid));
		$data = $query->row();
		
		return  $data;
		
	}
	
	//select all comments for a given article
	
	function getOneComment($commentid) {
		
		if(!is_numeric($commentid) || $commentid == 0) { return false; }
		
		$query = $this->db->query("SELECT commentid, comment, userid FROM sw_news_comments WHERE commentid = $commentid LIMIT 1");
		$data1 = $query->row_array();
		
		$query = $this->db->query("SELECT name, thumb, points FROM sw_users WHERE userid = " . $data1['userid'] . " LIMIT 1");
		$data2 = $query->row_array();
		
		$comment = (object) array_merge( $data1,  $data2);
		
		if($comment->thumb == "") $comment->thumb = $this->config->item('default_thumb');
		
		return $comment;		
	}
	
	function getComments($newsid, $popup) {
		
		$commentCount = $this->getCommentCount($newsid);
		$beginCount = $commentCount - 5;
		
		$from = ($commentCount > 5) ? $beginCount : 0;
		$to = ($commentCount > 5) ? $commentCount : 5;
		
		$limit = ($popup) ? "" : "LIMIT $from, $to";
		
		//get all comments for an article with user name, points and avatar
		$query = $this->db->query("SELECT sw_news_comments.commentid, sw_news_comments.comment, sw_news_comments.userid, sw_users.name, sw_users.thumb, sw_users.points FROM sw_news_comments, sw_users WHERE sw_news_comments.userid = sw_users.userid AND sw_news_comments.newsid = '$newsid' ORDER BY date ASC $limit");
		$data = $query->result();
								
		foreach($data as $row) {
			if($row->thumb == "") {
				$row->thumb = $this->config->item('default_thumb');
			}
		}
		
		return  $data;
		
	}
	
	//Gets number of comments in 
	function getCommentCount($newsid) {
		
		$this->db->where('newsid', $newsid);
		$query = $this->db->get('news_comments');
		
		return $query->num_rows();
		
	}
	
	function getCommentOwner($commentid) {
	
		if(!is_numeric($commentid) || $commentid == 0) { return false; }
		
		$this->db->select('userid');
		$query = $this->db->get_where('news_comments', array('commentid' => $commentid));
		
		if($query->num_rows() == 1) {
			$comment = $query->row();
			return $comment->userid;
		}
		
		return false;
	}
	
	//Find out if this article has been lifted
	function getLiftedStatus($newsid, $userid) {
		
		if($userid == 0) { return false; }
		
		$this->db->select('newsid');
		$query = $this->db->get_where('lifts', array('newsid' => $newsid, 'userid' => $userid));
		
		if($query->num_rows() >= 1) {
			return true;
		}
		
		return false;
		
	}
	
	//add a comment to an article
	function addComment($newsid, $userid, $comment) {
		
		$data = array(
		   'newsid' => $newsid ,
		   'userid' => $userid ,
			'comment' => $comment,
			'date' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('news_comments', $data);
		
		$this->last_insert_commentid = $this->db->insert_id();
		
		//initialize Joomla Model
		$CI =& get_instance();
		
		//add user point
		$CI->user_model->addUserPoint($userid, 10);
		
		return true;
		
	}
	
	function getLastCommentID() {
		
		return $this->last_insert_commentid;
	}
	
	function deleteComment($commentid) {
	
		if(!is_numeric($commentid) || $commentid == 0) { return false; }
		
		$this->db->delete('news_comments', array('commentid' => $commentid)); 
		
		if($this->db->affected_rows() == 1) return true;
		
		return false;
	}
	
	//Add lift point to article
	function addArticleLift($newsid, $userid)
	{
		//Check if user already lifted article
		$this->db->select('newsid');
		$this->db->where('newsid', $newsid);
		$this->db->where('userid', $userid);
		$query = $this->db->get('lifts');
		
		if($query->num_rows() == 1) {
			//already re-lifted
			$this->removeArticleLift($newsid, $userid);
			return false;
		}
		
		//else, user hasnt yet lifted, lift article
		
		//Add article to users collection
		$this->addToCollection($newsid, $userid);
		
		//increment the articles vote count
		$this->addArticleVote($newsid);
		
		//initialize Joomla Model
		$CI =& get_instance();
		
		//add user point
		$CI->user_model->addUserPoint($userid, 5);
		
		return true;
	}
	
	//Remove rlift point to article
	function removeArticleLift($newsid, $userid)
	{
		
		//Check if user already lifted article
		$this->db->select('newsid');
		$this->db->where('newsid', $newsid);
		$this->db->where('userid', $userid);
		$query = $this->db->get('lifts');
		
		if($query->num_rows() == 0) {
			//hasn't lifted yet
			return false;
		}
				
		//Remove article to users collection
		$this->removeFromCollection($newsid, $userid);
		
		//decrease the articles vote count
		$this->removeArticleVote($newsid);
		
		//initialize Joomla Model
		$CI =& get_instance();
		
		//remove user point
		$CI->user_model->removeUserPoint($userid, 5);
		
		return true;
	}
	
	//Add article to users collection
	function addToCollection($newsid, $userid) {
		
		$data = array(
		   'newsid' => $newsid ,
		   'userid' => $userid ,
		);

		$this->db->insert('lifts', $data);
		
		return true;
		
	}
	
	//Remove article to users collection
	function removeFromCollection($newsid, $userid) {
		
		$this->db->delete('lifts', array('userid' => $userid, 'newsid' => $newsid)); 
		
		return true;
		
	}
	
	//increment the articles vote count
	function addArticleVote($newsid) {
		
		$this->db->where('newsid', $newsid);
		$this->db->set('votes', 'votes+1', FALSE);
		$this->db->update('news_metainfo');
		
		return true;
	}
	
	//decrease the articles vote count
	function removeArticleVote($newsid) {
		
		$this->db->where('newsid', $newsid);
		$this->db->set('votes', 'votes-1', FALSE);
		$this->db->update('news_metainfo');
		
		return true;
	}
	
	//get article data needed
	function getArticleMeta($newsid) {
		
		if($newsid == 0) { return false; }
		
		$this->db->select('title');
		$this->db->where('newsid', $newsid);
		$query = $this->db->get('news_metainfo');
		
		$result = $query->row();
		
		$title = $result->title;
		
		$imgDetails = $this->getImgDetails($newsid);
		$img = $imgDetails->img_local;
		
		$url = base_url()."wall/article/".$newsid;
		
		return array('title' => $title, 'img' => $img, 'url' => $url, 'type' => 'article');
		
	}
	
	//returns article details needed for todays queued tweets
	function get_tweet_articles($year, $month, $day, $limit) {
		
		$limit = "LIMIT 0, $limit";
		
		$query = $this->db->query("SELECT newsid FROM sw_news_metainfo WHERE YEAR(published_date) = '$year' AND MONTH(published_date) = '$month' AND DAY(published_date) = '$day' $limit");
		
		$newsids = $query->result();
		
		$articles = array();
		
		foreach($newsids as $row) {
			
			$article = $this->getArticleMeta($row->newsid);
			
			$articles[] = array('title' => $article['title'], 'url' => $article['url'], 'newsid' => $row->newsid);
			
		}
		
		return $articles;
		
	}
	
	//Grabs all tags currently in database, with keys representing their TagID
	public function getAllTags() {
		
		$this->db->select('tagid, tag')->order_by("level", "tagid"); 
		$query = $this->db->get('tags');
		
		$tags = $query->result();
		
		return $tags;
				
	}
	
	public function getDefaultTags() {
		//The current default tags: tags from level 1 and up (ie. Top Stories, Staff, Picks and major categories)
		return $this->getTagsByLevel(1, true);
	}
	
	public function getTagsByLevel($level="", $get_parents=false) {
		
		if($level==="") return $this->getAllTags();
		if(!is_numeric($level) || $level < 0) return false;
		
		$operator = ($get_parents===true) ? " <=" : "";
		
		$this->db->select('tagid, tag')->where("level$operator", $level)->order_by('level', 'asc')->order_by('tagid', 'asc');
		$query = $this->db->get('tags');
		
		$tags = $query->result();
		
		return $tags;
				
	}
	
	//Gets tags' text by a comma separated list of tag IDs, with return array with elements tagid=>tag_text
	public function getTagsByID($tagids) {
		
		$query = $this->db->query("SELECT tagid, tag FROM sw_tags WHERE tagid IN ($tagids) ORDER BY level, tagid");
		$tags = $query->result();
		
		return $tags;
				
	}
	
	public function getRemainingTags($userid=0, $tags="") {
		
		
		//TRY ONE:
		// if($userid==0 && $tags=="") return $this->getDefaultTags();
		// 
		// $WHERE = $this->getFiltersWHERE($userid, $tags);
		// 
		// $query = $this->db->query("SELECT tagid, tag FROM sw_tags WHERE tagid IN (SELECT DISTINCT tagid FROM sw_news_tags $WHERE) AND tagid NOT IN ($tags) ORDER BY level, tagid");
		// 
		// $tags = $query->result();
		// 
		// return $tags;
		
		// TRY TWO:
		// if($tags=="") return $this->getDefaultTags();
		// 
		// if($tags != "") {		
		// 	$this->load->helper('tags_helper');
		// 	$proc_tags = processTagCSV($tags);
		// 
		// 	if($proc_tags) {
		// 		$TAG_FILTER = true;
		// 		$tag_count = getTagCount($proc_tags);
		// 	}
		// }
		// 
		// $USER_FILTER = ($userid != 0) ? "AND newsid IN (SELECT newsid FROM sw_lifts WHERE userid = $userid)" : "";
		// 
		// $query = $this->db->query("SELECT sw_tags.tagid,sw_tags.tag FROM sw_tags INNER JOIN (SELECT DISTINCT sw_news_tags.tagid FROM sw_news_tags INNER JOIN (SELECT newsid FROM sw_news_tags WHERE tagid IN ($tags) $USER_FILTER GROUP BY newsid HAVING count(newsid) >= $tag_count) tagged_news ON sw_news_tags.newsid = tagged_news.newsid WHERE sw_news_tags.tagid NOT IN ($tags)) tags ON sw_tags.tagid = tags.tagid ORDER BY level, tagid") ;
		// 
		// $tags = $query->result();
		// 
		// return $tags;
		
					// if($tags=="") return $this->getDefaultTags();
					// 
					// if($tags != "") {		
					// 	$this->load->helper('tags_helper');
					// 	$proc_tags = processTagCSV($tags);
					// 
					// 	if($proc_tags) {
					// 		$TAG_FILTER = true;
					// 		$tag_count = getTagCount($proc_tags);
					// 	}
					// }
					// 
					// $JOIN  = "";
					// $WHERE = "";
					// $i = 1;
					// $tag_array = str_getcsv($proc_tags);
					// 
					// foreach($tag_array as $tag) {
					// 	if ($i == 1) {
					// 		$WHERE = "WHERE n$i.tagid = $tag";
					// 	} else {
					// 		$WHERE .= " AND  n$i.tagid = $tag";
					// 	}
					// 	
					// 	$JOIN  .= " \nJOIN sw_news_tags AS n$i USING (newsid)";
					// 	
					// 	$i++;
					// }
					
		if($tags=="" && $userid==0) return $this->getDefaultTags();
				
		$SQL = $this->getSQLFiltersWHERE($userid, $tags);

		if(!$SQL) return false;

		$JOIN = $SQL[0];
		$WHERE = $SQL[1];
		$proc_tags = $SQL[2];
		
		$WHERE_remove_duplicates = ($proc_tags) ? " AND sw_tags.tagid NOT IN ($proc_tags)" : "";
		
		$query_string = "SELECT DISTINCT sw_tags.tagid, sw_tags.tag
						FROM sw_tags
						JOIN sw_news_tags AS n0 USING (tagid)
						$JOIN
						$WHERE
						$WHERE_remove_duplicates
						ORDER BY sw_tags.level, sw_tags.tagid";
		
		$query = $this->db->query($query_string) ;
		
		$tags = $query->result();

		return $tags;
			
	}
	
	private function getSQLFiltersWHERE($userid=0, $tags="") {
		
			$USER_FILTER = false;
			$TAG_FILTER  = false;
			$JOIN  = "";
			$WHERE = "";
			$proc_tags = 0;
			$cond_count = 1;
		
			if($userid != 0 && is_numeric($userid)) $USER_FILTER = true;
		
			if($tags != "") {		
				$this->load->helper('tags_helper');
				$proc_tags = processTagCSV($tags);

				if($proc_tags) {
					$TAG_FILTER = true;
				}
			}
			
			if($TAG_FILTER) {
			
				$tag_array = str_getcsv($proc_tags);

				foreach($tag_array as $tag) {

					$JOIN  .= " JOIN sw_news_tags AS n$cond_count USING (newsid)";

					if ($cond_count == 1) {
						$WHERE = "WHERE n$cond_count.tagid = $tag";
					} else {
						$WHERE .= " AND  n$cond_count.tagid = $tag";
					}

					$cond_count++;
				}
			}
			
			if($USER_FILTER) {
				$JOIN .= " JOIN sw_lifts AS lifts USING (newsid)";
				
				if ($cond_count == 1) {
					$WHERE = "WHERE lifts.userid = $userid";
				} else {
					$WHERE .= " AND lifts.userid = $userid";
				}
				
				$cond_count++;
			}
			
			return array($JOIN, $WHERE, $proc_tags);
	}
	
	//passed an array of objects with attribute newsid, return objects with title attrib. appended to objects
	public function getTitles($objects) {
		
		$object_array = array();
		
		foreach($objects as $object) {
			$query = $this->db->query("SELECT title, link FROM sw_news_metainfo WHERE newsid = '$object->newsid'");
			$data = $query->row();
			$object->title = $data->title;
			$object->link = $data->link;
			$object_array[] = $object;
 		}

		return $object_array;
		
	}
	
	public function getArticleLink($newsid) {
		
		$query = $this->db->query("SELECT link FROM sw_news_metainfo WHERE newsid = '$newsid'");
		$data = $query->row();
		
		return $data->link;
		
	}
	
	public function getArticleInternalLink($newsid) {

		$url_title = $this->article_model->getArticleSlug($newsid);
		if(!$url_title) return false;
		
		$uri = "article/read/$newsid/" . $url_title;
		
		return $uri;
		
	}
	
	public function getArticleTitle($newsid) {
		
		$query = $this->db->query("SELECT title FROM sw_news_metainfo WHERE newsid = '$newsid'");
		$data = $query->row();
		
		return $data->title;
		
	}
	
	public function getRandomRecentArticle($current_newsid) {
		//This is used for stumbling across websites. We give them a random news item out of the last 300.
		
		//We get newsid of the 300th of the latest articles
		$query = $this->db->query("SELECT newsid FROM sw_news_metainfo ORDER BY newsid DESC LIMIT 300, 1");
		$data = $query->row();
		$start_newsid = $data->newsid;
		
		$query = $this->db->query("SELECT newsid FROM sw_news_metainfo WHERE newsid > $start_newsid ORDER BY rand() LIMIT 1");
		$data = $query->row();
		
		if($query->num_rows == 1) return $data->newsid;
		
		return false;
		
	}
	
	
	
}