<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

//Load twitter PHP SDK
require 'twitter/tmhOAuth.php';
require 'twitter/tmhUtilities.php';

class Twitter {
	
	var $twitter = null; //twitter sdk object
	var $CI = null;
	
	public function __construct($user_tokens="2")
	{
		
			$this->CI =& get_instance();
				
			//Load all Twitter Configs
			$this->CI->load->config('twitter');
			
			/*
			* Add these to the request to send tweets from WealthLift
			* 'user_token'      => $this->CI->config->item('tw_user_token'),
			* 'user_secret'     => $this->CI->config->item('tw_user_secret')
			*/
			$params = array(
			  'consumer_key'    => $this->CI->config->item('tw_consumer_key'),
			  'consumer_secret' => $this->CI->config->item('tw_consumer_secret')
			);
			
			if($user_tokens != "2") {
				$params['user_token'] = $user_tokens['user_token'];
				$params['user_secret'] = $user_tokens['user_secret'];
			}
			
			$this->twitter = new tmhOAuth($params);
	}
	
	public function tweet($message) {
		
		$code = $this->twitter->request('POST', $this->twitter->url('1/statuses/update'), array(
		  'status' => $message
		));
	
		if ($code == 200) {
		  //tmhUtilities::pr(json_decode($this->twitter->response['response']));
			return true;
		} else {
		  //tmhUtilities::pr($this->twitter->response['response']);
			return false;
		}
		
	}
	
	function outputError() {
	  echo 'There was an error: ' . $this->twitter->response['response'] . PHP_EOL;
		return false;
	}

	function wipe() {
	  session_destroy();
	  header('Location: ' . tmhUtilities::php_self());
	}
	
	// Step 1: Request a temporary token
	function request_token() {
	  $code = $this->twitter->request(
	    'POST',
		$this->twitter->url('oauth/request_token', ''),
	    array(
	      'oauth_callback' => base_url()."twitter_connect/listener"
	    )
	  );

	  if ($code == 200) {
	    $_SESSION['oauth'] = $this->twitter->extract_params($this->twitter->response['response']);
	    return true;
	  } else {
	    outputError($this->twitter);
	  }
	}


	// Step 2: Direct the user to the authorize web page
	function authorize() {
	  $authurl = $this->twitter->url("oauth/authorize", '') .  "?oauth_token={$_SESSION['oauth']['oauth_token']}";
	  return $authurl;
	}



	
	// Step 3: This is the code that runs when Twitter redirects the user to the callback. Exchange the temporary token for a permanent access token
	function access_token() {
		
	  $this->twitter->config['user_token']  = $_SESSION['oauth']['oauth_token'];
	  $this->twitter->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

	  $code = $this->twitter->request(
	    'POST',
	    $this->twitter->url('oauth/access_token', ''),
	    array(
	      'oauth_verifier' => $_GET['oauth_verifier']
	    )
	  );
		
		
	  if ($code == 200) {
	    $_SESSION['access_token'] = $this->twitter->extract_params($this->twitter->response['response']);
	    unset($_SESSION['oauth']);
	    return true;
	  } else {
	    outputError($tmhOAuth);
	  }
	}


	// Step 4: Now the user has authenticated, do something with the permanent token and secret we received
	//returns user token and secret
	function verify_credentials() {
	  $this->twitter->config['user_token']  = $_SESSION['access_token']['oauth_token'];
	  $this->twitter->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];

	  $code = $this->twitter->request(
	    'GET',
	    $this->twitter->url('1/account/verify_credentials')
	  );

	  if ($code == 200) {
	    return array('user_token' => $this->twitter->config['user_token'], 'user_secret' => $this->twitter->config['user_secret']);
	  } else {
	    return false;
	  }
	}
		
	
}
