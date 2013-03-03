<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twitter_model extends CI_Model {
	
	function __construct()
	    {
	        parent::__construct();
			$this->load->database();
	    }
	
	//check if the given user has already Facebook Connected
	function insert_twitter_credentials($userid, $user_tokens, $userDetails)
	{
		
		$this->db->select('user_token');
		$query = $this->db->get_where('twconnect_users', array('userid' => $userid));
		
		if($query->num_rows() != 0) { return "This stock wall user is already twitter connected"; } 
		
		$data = array(
			'user_token' => $user_tokens['user_token'],
			'user_secret' => $user_tokens['user_secret'],
			'userid' => $userid
		);
		
		$this->db->insert('twconnect_users', $data);
		
		$this->db->query("INSERT INTO twitter_network.tw_twitter_users SELECT *, '$userDetails->name', '$userDetails->email', '' FROM stockwall.sw_twconnect_users WHERE userid = '$userid'");		
		
		return "new twitter connect user created!";
			
	}
	
	public function get_user_tokens($userid) {
		
		$this->db->select('user_token, user_secret');
		$this->db->where('userid', $userid);
		$query = $this->db->get('twconnect_users');
		
		$result = (array) $query->row();
		
		return $result;
		
	}
	
	
}