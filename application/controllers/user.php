<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	
	function validate_credentials()
	{		
		
		$this->load->library('userLib');
		$userLib = new UserLib();
		
		
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		
		$userid = $userLib->validate($email, $password);
		
		if($userid != 0) // if the user's credentials validated...
		{
			echo $userLib->loginAndCheck($userid);
		}
		else // incorrect username or password
		{
			echo "Log in details are incorrect. Please try again.";
		}
		
		
	}	
	
	function create_member()
	{
		$this->load->helper('popup_helper');
		$this->load->library('form_validation');
		$this->load->library('userLib');
		$userLib = new UserLib();
		
		// field name, error message, validation rules
		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[30]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[120]|callback_email_check');
		
		if($this->form_validation->run() == FALSE)
		{
			echo validation_errors('<span>');
		}
		
		else
		{	
			$name = $this->input->post('name');
			$password = $this->input->post('password');
			$email = $this->input->post('email');
			
			if($query = $userLib->create_member($name, $email, $password))
			{	
				$this->validate_credentials();
			}
			else
			{
				echo "unable to create account, please refresh and try again.";
			}
		}
		
	}
	
	public function email_check($str)
		{
			
			$this->load->library('userLib');
			$userLib = new UserLib();
			
			$email_used = $userLib->check_email($str);
			
			if ($email_used)
			{
				$this->form_validation->set_message('email_check', 'The %s is already associated with an account. Please use another email');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		
	public function beta_check() {
				
		$code = $this->input->post('code');
		
		$this->load->model('user_model');
		$code_exists = $this->user_model->check_beta_code($code);
		
		if($code_exists) {
			//increment signup count for the code
			$this->user_model->incrementBetaCode($code);
			
			echo "worked";
		}
		else {
			echo "Beta Invite Code Not Found";
		}
		
		
	}
	
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/', 'refresh');
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/wall.php */
