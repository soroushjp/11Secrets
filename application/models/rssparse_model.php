<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rssparse_model extends CI_Model {
	
	protected $FeedID=0;
	protected $Source="";
	protected $SourceName="";
	protected $Title="";
	protected $PubDate="";
	protected $ArticleLink="";
	protected $Content="";
	protected $ImgLink="";
	protected $ImgLocal="";
	protected $ImgLocalPath="";
	protected $ImgFind="";
	protected $ImgHeight=0;
	protected $ImgWidth=0;
	protected $GUID="";
	protected $DCIdentifier="";
	protected $HasImage=0;
	protected $tags=array();
	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }

	public function setFeedID($feedid=-1){
		if(!is_numeric($feedid) || $feedid<="0" || $feedid=="") return false;		
		$this->FeedID = $feedid;
		return true;
	}
	
	public function setSource($source=""){
		if($source=="") return false;		
		$this->Source = $source;
		return true;
	}
	
	public function setSourceName($source_name=""){
		if($source_name=="") return false;		
		$this->SourceName = $source_name;
		return true;
	}
	
	public function setTitle($title=""){
		if($title=="") return false;		
		$this->Title = $title;
		return true;
	}
	
	public function setPubDate($pubDate=""){
		if($pubDate=="") return false;		
		$this->PubDate = $pubDate;
		return true;
	}
	
	public function setArticleLink($link=""){
		if($link=="") return false;		
		$this->ArticleLink = $link;
		return true;
	}
	
	public function setImgLink($link=""){
		if($link=="") return false;		
		$this->ImgLink = $link;
		return true;
	}
	
	public function setImgLocal($link=""){
		if($link=="") return false;		
		$this->ImgLocal = $link;
		return true;
	}
	
	public function setImgLocalPath($path=""){
		if($path=="") return false;		
		$this->ImgLocalPath = $path;
		return true;
	}
	
	public function setImgFind($info=""){
		if($info=="") return false;		
		$this->ImgFind = $info;
		return true;
	}
	
	public function setImgHeight($height=0){
		if(!is_numeric($height) || $height<="0" || $height=="") return false;		
		$this->ImgHeight = $height;
		return true;
	}
	
	public function setImgWidth($width=0){
		if(!is_numeric($width) || $width<="0" || $width=="") return false;		
		$this->ImgWidth = $width;
		return true;
	}
	
	public function setGUID($guid=""){
		if($guid=="") return false;		
		$this->GUID = $guid;
		return true;
	}
	
	public function setContent($content=""){	
		$this->Content = $content;
		return true;
	}
	
	public function setDCIdentifier($dc_identifier=""){
		if($dc_identifier=="") return false;		
		$this->DCIdentifier = $dc_identifier;
		return true;
	}
	
	public function setHasImage($has_image=0){
		if($has_image != 1 && $has_image != 0) return false;		
		$this->HasImage = $has_image;
		return true;
	}
	
	public function setTagIDs($tags){
		
		if(empty($tags)) return true;
		
		foreach($tags as $tag){
			if($tag==null || !is_numeric($tag)) return false;
		}
		
		$this->tags = $tags;
		return true;
	}
	
	public function saveArticle() {
		
		// $random_category = rand(1,9);
		// 
		// if($random_category >= 1 && $random_category <= 6) $randomizer = rand(5,35);
		// if($random_category == 7) $randomizer = rand(35,400);
		// if($random_category >= 8 && $random_category <= 9) $randomizer = rand(400,500);
		
		$sw_news_metainfo = array('userid' => 1,
								  'title' => $this->Title,
								  'published_date' => $this->pubDateToMySql($this->PubDate),
								  'source' => $this->Source,
								  'sourceName' => $this->SourceName,
								  'dc_identifier' => $this->DCIdentifier,
								  'link' => $this->ArticleLink,
								  'guid' => $this->GUID,
								  'has_image' => $this->HasImage,
								  'votes' => 0
								);
								
		if(!$this->db->insert('sw_news_metainfo', $sw_news_metainfo)) return false;
		$newsid = $this->db->insert_id();						
										
		$sw_news_content = array('newsid' => $newsid,
								 'content' => $this->Content
								);
		$sw_news_images = array('newsid' => $newsid,
								'img_link' => $this->ImgLink,
								'localized' => 1,
								'img_local' => $this->ImgLocal,
								'img_local_path' => $this->ImgLocalPath,
								'location' => 'tmp',
								'height' => $this->ImgHeight,
								'width' => $this->ImgWidth,
								'img_find' => $this->ImgFind
								);
		
		if(!$this->db->insert('sw_news_content', $sw_news_content)) return false;
		if(!$this->db->insert('sw_news_images', $sw_news_images)) return false;
		
		if(!$this->saveArticleTags($newsid)) return false;
		
		return true;
	}
	
	public function saveArticleTags($newsid) {
		
		if(empty($this->tags)) return false;
		
		$total_affected = 0;
		
		foreach($this->tags as $tagid) {
			
			$sql = "INSERT IGNORE INTO sw_news_tags (newsid, tagid) 
			        VALUES ('$newsid', '$tagid')";

			$success = $this->db->query($sql);
			
			if(!$success) return false;
			
			$total_affected += $this->db->affected_rows();
		}
		
		return $total_affected;
	}
	
	public function rotateFeed() {
		
		$this->db->select('next_feedid')->from('news_feeds_counter')->where('index', 1)->limit(1);
		
		$query = $this->db->get();
		
		if ($query->num_rows() <= 0) return false;
		
		$row = $query->row_array();
		$current_feedid = $row['next_feedid'];
		
		$this->db->select('feedid')->from('news_feeds')->where('feedid >', $current_feedid)->order_by("feedid", "asc")->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() <= 0) {
			//We're at the end of the feeds and need to start from top
			$this->db->select('feedid')->from('news_feeds')->order_by("feedid", "asc")->limit(1);
			$query = $this->db->get();
			$next_row = $query->row_array();
			$next_feedid = $next_row['feedid'];
		} else {
			$next_row = $query->row_array();
			$next_feedid = $next_row['feedid'];
		}
		
		$success = $this->db->where('index', 1)->update('news_feeds_counter', array('next_feedid'=>$next_feedid));
		
		if(!$success) return false;
			
//		SELECT id FROM table WHERE id > 123 ORDER BY id ASC LIMIT 1	
			
		return $current_feedid;
	}
	
	public function getFeed($feedid=0) {
		
		if(!is_numeric($feedid) || $feedid==0) return false;
			
		$this->db->select('feedid, source, sourceName, og_check, rss_url, standard_width')->from('sw_news_feeds')->where('feedid', $feedid)->limit(1);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
		
	}
	
	public function getFeeds($num=0, $start=0) {
		
		if(!is_numeric($num) || !is_numeric($start)) return false;
			
		$this->db->select('feedid, source, sourceName, og_check, rss_url, standard_width')->from('sw_news_feeds')->where('active', 1)->order_by('feedid', 'ASC');
		
		if($num > 0) {
			$this->db->limit($num, $start);
		}
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
		
	}
	
	public function getFeedTagIDs($feedid) {
		
		if(!is_numeric($feedid)) return false;
			
		$this->db->distinct()->select('tagid')->from('sw_news_feeds_tags')->where('feedid', $feedid);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			$tag_ids = array();
			foreach ($query->result() as $row) {
				$tag_ids[] = $row->tagid;
			}
			return $tag_ids;
		}
		
		return false;
		
	}
	
	private function pubDateToMySql($str) {
	    return date('Y-m-d H:i:s', strtotime($str));
	}
	
	public function getDuplicates($GUID="", $link="", $title="", $img_link="") {
		
		$count = 0;
		
		if (!($GUID == "" && $title == "" && $link == "")) {
			//Check article GUID, title or link are not duplicates
			$this->db->select('newsid');
			if($GUID != "") $this->db->or_like('guid', $GUID);
			if($title != "") $this->db->or_like('title', $title);
			if($link != "") $this->db->or_like('link', $link);
			$this->db->limit(1);
		
			$query = $this->db->get('sw_news_metainfo');
		
			$count = $query->num_rows();
		
			if($count > 0) {
				$result = $query->row();		
				$dup_newsid = $result->newsid;	
			}

			if($count === 1) return $dup_newsid;	
		}
		
		if ($img_link != "") {
			//Check image url is not duplicate
			$this->db->select('newsid');
			$this->db->like('img_link', $img_link);
			$this->db->limit(1);

			$query = $this->db->get('sw_news_images');

			$count = $query->num_rows();

			if($count > 0) {
				$result = $query->row();		
				$dup_newsid = $result->newsid;
			}
		
			if($count === 1) return $dup_newsid;
		}
		
		if($count === 0 ) return 0;
		return false;
		
	}
	
}

?>