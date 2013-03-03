<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metrics_model extends CI_Model {
	
	function __construct()
	    {
	        parent::__construct();
			$this->load->database();
	    }
	
	function log_article_share($userid, $newsid, $type) {
		
		$tables = array('facebook' => 'article_fbrecommends', 'twitter' => 'article_tweets', 'google' => 'article_gplus', 'linkedin' => 'article_inshares', 'pinterest' => 'article_pinterestpins');
				
		$this->db->select('shareid');
		$this->db->where('userid', $userid);
		$this->db->where('newsid', $newsid);
		$query = $this->db->get($tables[$type]);
		
		if($query->num_rows() == 0 || $userid == 0) {
			
			$data = array(
				'newsid' => $newsid,
				'userid' => $userid
			);
			
			$this->db->insert($tables[$type], $data);
			
		}
		
		return true;
		
	}
	
	//store that a user read a given article
	public function addArticleRead($userid, $newsid) {
		
		$this->db->where('userid', $userid);
		$this->db->where('newsid', $newsid);
		$query = $this->db->get('news_read');
		
		if($query->num_rows() == 0) {
			
			$data = array('userid' => $userid, 'newsid' => $newsid);
			$this->db->insert('news_read', $data);
			
		}
		
		return true;
				
	}
	
	//store that a user clicked the reveal button for a given article
	public function addRevealOpen($userid, $newsid) {
		
		$this->db->where('userid', $userid);
		$this->db->where('newsid', $newsid);
		$query = $this->db->get('news_reveal_open');
		
		if($query->num_rows() == 0) {
			
			$data = array('userid' => $userid, 'newsid' => $newsid);
			$this->db->insert('news_reveal_open', $data);
			
		}
		
		return true;
				
	}
	
	
	
}