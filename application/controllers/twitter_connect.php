<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twitter_connect extends CI_Controller {
		
	public function __construct()
	{
		parent::__construct();
		//load in the twitter library
		$this->load->library('twitter');
		
	}
	
	//first step in connected a users account with twitter
	public function authenticate_user() {
		
		$twitter = new Twitter();
		
		session_start();
		
		$return = $twitter->request_token();
		
		if($return == true) {
			$authurl = $twitter->authorize();
			//direct user to twitter
			header("Location: {$authurl}");
		}
		
		echo true;
		
	}
	
	//once twitter returns the authenticated user, this function is called
	//makes sure user is authenticated and then stores a users details
	public function listener() {
		
		session_start();
		
		$twitter = new Twitter();
		
		$return = $twitter->access_token();
		
		if($return == false) { return false; }
		
		$user_tokens = $twitter->verify_credentials();
		
		if($user_tokens == false) { echo "verify credentials fail!"; }	
				
		$this->load->model('user_model');
        $userid = $this->user_model->is_logged_in();

		$userDetails = $this->user_model->getTwitterInsertDetails($userid);
		
		$this->load->model('twitter_model');
		$return = $this->twitter_model->insert_twitter_credentials($userid, $user_tokens, $userDetails);
				
		
		echo $return;
		
	}
	
	//sends a tweet to a given user
	public function send_tweet($sender_userid, $message) {
				
		$this->load->model('twitter_model');
		
		$user_tokens = $this->twitter_model->get_user_tokens($sender_userid);
		
		$twitter = new Twitter($user_tokens);
		
		$return = $twitter->tweet($message);
		
		return $return;
		
	}
		

	
}