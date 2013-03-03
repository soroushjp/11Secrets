<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Handle_openid {
	
	protected $identity = "";
	protected $email = "";
	protected $name = "";
	
	
	public function setIdentity($identity) {
		if($identity == "") return false;
		
		$id_pieces = explode("?id=", $identity);
		
		$identity = $id_pieces[1];
		
		$this->identity = $identity;
		
		return true;
	}
	
	public function setEmail($attributes) {
					
		if($attributes['contact/email'] == "") return false;
		
		$this->email = $attributes['contact/email'];
		
		return true;
	}
	
	public function setName($attributes) {
		if($attributes['namePerson/first'] == "" || $attributes['namePerson/last'] == "") return false;
		
		$fullname = $attributes['namePerson/first']." ".$attributes['namePerson/last'];
		
		$this->name = $fullname;
		
		return true;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function getName() {
		return $this->name;
	}
	
	//Get userid if user already connected, otherwise return false
	public function getUserid() {
		
		$CI =& get_instance();
		
		$identity = $this->identity;
		
		$CI->db->where('openid', $identity);
		$query = $CI->db->get('openid_users');
		
		if($query->num_rows == 0) { return false; }
		
		$row = $query->row_array(); 
		if(!$row) return false;
		
		return $row['userid'];
		
	}
	
	
	// returns userid or false
	public function createUser() {
		
		//make a randomly generated password
		$password = md5(rand());
		
		$CI =& get_instance();
				
		$CI->load->library('userLib');
		$userLib = new UserLib();
		
		
		//creates both user and joomla user
		$userid = $userLib->create_member($this->name, $this->email, $password);
		
		$userLib->store_openid($userid, $this->identity);
		
		return $userid;
		
	}
	
	
}