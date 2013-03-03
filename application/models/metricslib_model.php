<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metricslib_model extends CI_Model {
	
	function __construct()
	    {
	        parent::__construct();
			$this->load->database();
	    }
	
	//for a given user, get number times of specfic stat
	function getNumber($table, $userid) {
		
		$this->db->where('userid', $userid);
		$query = $this->db->get($table);	
		
		return $query->num_rows();
		
	}
	
	//for a given user, get number times read article & posted to FB
	function getFBActionNumber($action, $userid=0) {
		
		if($userid != 0) {
			$this->db->where('userid', $userid);
		}
		
		$this->db->where('actiontype', $action);
		$query = $this->db->get('fb_actions');	
		
		return $query->num_rows();
		
	}
	
	function getTotalNumber($table) {
		
		return $this->db->count_all($table);
		
	}
	
	//returns an array of male & female numbers
	function getGenderNumbers() {
		
		$total = $this->db->count_all('fbconnect_userdata');
		
		$this->db->select('userid');
		$this->db->where('sex', 'female');
		$query = $this->db->get('fbconnect_userdata');
		
		$no_female = $query->num_rows();
		$no_male = $total - $no_female;
		
		return array('male' => $no_male, 'female' => $no_female);
		
	}
	
	function getGender($userid) {
		
		$this->db->select('sex');
		$this->db->where('userid', $userid);
		$query = $this->db->get('fbconnect_userdata');
		
		if($query->num_rows() == 0) {
			return false;
		}
		
		$result = $query->row();
		
		return $result->sex;
	}
	
	function getAllFBUserAges() {
		
		$current_year = date('Y');
		$this->db->select("($current_year - YEAR(birthday)) as age", FALSE);
		$query = $this->db->get("fbconnect_userdata");
		
		return $query->result();
		
	}
	
	function getFBUserAge($userid) {
		
		$current_year = date('Y');
		$this->db->select("($current_year - YEAR(birthday)) as age", FALSE);
		$this->db->where('userid', $userid);
		$query = $this->db->get("fbconnect_userdata");
		
		if($query->num_rows() == 0) { return 0; }
		
		$result = $query->row();
		
		return $result->age;
		
	}
	
	//returns a list of userid's of users who satisfy the constructed sql
	//$variables is an array of columns and their correct values
	//$type is whether to use "=" or "LIKE" between WHERE clauses
	function getUserList($sql_string) {
		
		$query = $this->db->query("SELECT userid FROM sw_source $sql_string");
				
		return $query->result();
		
	}
	
	function getMonthList() {
		
		$query = $this->db->query("SELECT DISTINCT MONTH(registerdate) as month FROM sw_source ORDER BY month ASC", FALSE);
		
		return $query->result();
		
	}
	
	//given a month, return the number of active users logged in that month
	function getMonthActiveUsers($month) {
		
		$query = $this->db->query("SELECT DISTINCT userid FROM `sw_login_log` WHERE MONTH(timestamp) = '$month'");
		
		return $query->num_rows();
				
	}
	
	
}