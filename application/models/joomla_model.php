<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Joomla_model extends CI_Model {
	
	var $ciUserid = 0;
	var $jUserid = 0; 
		
	function __construct() {
		//select the joomla database
		$this->load->database();
		$this->joomla = $this->load->database('joomla', TRUE);
	}
	
	//Set the Joomla userid
	function setjUserid($jUserid) {
		
		if($jUserid == 0) { return false; }
		
		$this->jUserid = $jUserid; 
		
		return true;
	}
	
	//Set the CodeIgniter Userid
	function setciUserid($ciUserid) {
		
		if($ciUserid == 0) { return false; }
		
		$this->ciUserid = $ciUserid; 
		
		return true;
	}
	
	//Creates a Joomla user
	function create_joomla_user($userid) {
		
		//Set the CI userid as object variable
		$this->setciUserid($userid);
		
		//get users details
		$this->db->where('userid', $this->ciUserid);
		$query = $this->db->get('users');
		
		$result = $query->row();
		
		$email = $result->email;
		$username = $result->username;
		$name = $result->name;
		$password = $result->password;
		
		$email_exists_joomla = $this->check_email_joomla($email);
		
		
		if($email_exists_joomla) { return false; }
		
		//create jos_user entry
		$jUserid = $this->create_users_entry($name, $email, $username, $password);
		
		//set jUserid as object variable
		$this->setjUserid($jUserid);
		
		//create community_users entry
		$this->community_users_entry($username);
		
		//create usergroups_map entry
		$this->usergroups_map_entry();
		
		//create email config entry
		$this->email_config_entry();
		
		//create profilepoints entry
		$this->profilepoints_entry();
		
		//create achievements entry
		$this->achievements_entry();
		
		//if google connecting, create openid_users entry
		$this->openid_users_entry();
		
		//if facebook connecting, create openid_users entry
		$this->connectid_users_entry();
		
		return true;
		
	}
	
	//create jos_user entry & returns joomla userid
	function create_users_entry($name, $email, $username, $password) {
		
		$data = array(
			'username' => $username ,
			'name' => $name ,
			'email' => $email,
			'password' => $password,
			'usertype' => 2,
			'type' => 'user',
			'block' => 0,
			'sendEmail' => 0,
			'registerDate'=> date("Y-m-d H:i:s"),
			'lastVisitDate' => date("Y-m-d H:i:s"),
			'activation' => "",
			'params' => "{}",
			'cron' => 0
		);

		$this->joomla->insert('users', $data);
		
		$this->joomla->select('id');
		$this->joomla->where('email', $email);
		$query = $this->joomla->get('users');
		
		$result = $query->row();
		
		$userid = $result->id;
		
		return $userid;
		
	}
	
	function community_users_entry($username) {
		
		$alias = $this->jUserid.":".$username;
		
		$data = array(
			'userid' => $this->jUserid ,
			'confirmed' => 0 ,
			'params' => '{"notifyEmailSystem":0,"privacyProfileView":0,"privacyPhotoView":0,"privacyFriendsView":0,"privacyGroupsView":"","privacyVideoView":0,"notifyEmailMessage":0,"notifyEmailApps":0,"notifyWallComment":0}',
			'investmentScoreEnded' => 100,
			'investmentScoreCurrent' => 0,
			'investmentScoreTotal' => 100,
			'ranking' => 0,
			'title' => 'greenhorn',
			'progress'=> 20,
			'alias' => $alias
		);

		$this->joomla->insert('community_users', $data);
		
		return true;
		
	}
	
	function usergroups_map_entry() {
		
		$data = array(
			'user_id' => $this->jUserid,
			'group_id' => 2,
		);

		$this->joomla->insert('user_usergroup_map', $data);
		
		return true;
		
	}
	
	function email_config_entry() {
		
		$data = array(
			'userid' => $this->jUserid,
			'ConfirmationReminder' => 0,
			'EducationReminder' => 0,
			'QuizReminder' => 0,
			'InvestmentScore' => 0,
			'SpecialOffers' => 0,
			'WallReply' => 0,
			'NewFollower' => 0,
			'FriendRequest' => 0,
			'NewMessage' => 0
		);

		$this->joomla->insert('email_config', $data);
		
		return true;
		
	}
	
	function profilepoints_entry() {
		
		$data = array(
			'userid' => $this->jUserid,
			'broker' => '',
			'total' => 10
		);

		$this->joomla->insert('community_profilepoints', $data);
		
		return true;
		
	}
	
	function achievements_entry() {
		
		$data = array(
			'userid' => $this->jUserid
		);

		$this->joomla->insert('community_achievements', $data);
		
		return true;
		
	}
	
	function openid_users_entry() {
		
		$this->db->select('openid');
		$this->db->where('userid', $this->ciUserid);
		$query = $this->db->get('openid_users');
		
		//if user google connected, add to Joomla openid
		if($query->num_rows() >= 1) {
			
			$result = $query->row();
			
			$data = array(
				'userid' => $this->jUserid,
				'openid' => $result->openid,
				'type' => 'google'
			);

			$this->joomla->insert('community_openid_users', $data);
			
		}
				
		return true;
		
	}
	
	function connectid_users_entry() {
		
		$this->db->select('connectid');
		$this->db->where('userid', $this->ciUserid);
		$query = $this->db->get('fbconnect_users');
		
		//if user facebook connected, add to Joomla connectid
		if($query->num_rows() >= 1) {
			
			$result = $query->row();
			
			$data = array(
				'userid' => $this->jUserid,
				'connectid' => $result->connectid,
				'type' => 'facebook'
			);

			$this->joomla->insert('community_connect_users', $data);
			
		}
				
		return true;
		
	}
	
	//check if email already in joomla db
	function check_email_joomla($email) {
		
		$this->joomla->where('email', $email);
		$query = $this->joomla->count_all_results('users');
		
		if($query >= 1) {
			return true;
		}
		
		return false;
		
	}
	
	
}