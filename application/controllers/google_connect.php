<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Google_connect extends CI_Controller {
	
	public function index() {
				
		try {
			$host_array = array(base_url());
			$this->load->library('Openid', $host_array);
			$this->openid->realm = "http://*.11secrets.com";
			$this->openid->required = array('namePerson/first', 'namePerson/last', 'contact/email');
		    if(!$this->openid->mode) {
		            $this->openid->identity = 'https://www.google.com/accounts/o8/id';
		            redirect($this->openid->authUrl());
		
		    } elseif($this->openid->mode == 'cancel') {
		        //You have canceled authentication!
				$this->session->set_userdata('error', 'cancelled');
				redirect('/');
		    } else {
				if($this->openid->validate()) {
					$validated = $this->_validateUser($this->openid->identity, $this->openid->getAttributes());
					if(is_numeric($validated)) {
						$restURL = "";
						if($validated != 0) {
							$restURL = "wall/article/".$validated;
						}
						$redirectURL = base_url().$restURL;
						header("Location: ".$redirectURL); 
					}
					else { 
						$this->session->set_userdata('error', $validated);
						redirect('/');
						}
				}
				else {
					//logging in user failed
					$this->session->set_userdata('error', 'failed');
					redirect('/');
				}
		    }
		} catch(ErrorException $e) {
		    echo $e->getMessage();
		}
		
		
	}
	
	public function _validateuser($identity, $attributes) {
		
		$this->load->library('userLib');
		$this->load->library('Handle_openid');
		
		$userLib = new UserLib();
		
		$this->handle_openid->setIdentity($identity);
		$this->handle_openid->setEmail($attributes);
		$this->handle_openid->setName($attributes);
		
		$userid = $this->handle_openid->getUserid();
		if($userid == false) {
			$email_used = $userLib->check_email($this->handle_openid->getEmail());
			if($email_used) { return "already"; }
			
			$userid = $this->handle_openid->createUser();
			if($userid != false) {
				//login here
				return $userLib->loginAndCheck($userid);
			}
		}
		else {
			//login user here
			return $userLib->loginAndCheck($userid);
		}
		
	}
	
	
}
