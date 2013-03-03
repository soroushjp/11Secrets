<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Metrics {
	
	
	var $CI = null;
	var $userList = "all";
	var $start_timestamp = 1; //start unix timestamp
	var $end_timestamp = null; //end unix timestamp
	var $search_type = "=";
	
	var $stats = array(); //contains all stats
	
	public function __construct()
	{
			$this->end_timestamp = time();
			$this->CI =& get_instance();
			$this->CI->load->model('metricslib_model');
	}
	
	public function setStartDate($date) {
						
		$this->start_timestamp = strtotime($date);
					
		return true;
		
	}
	
	public function setEndDate($date) {
					
		$this->end_timestamp = strtotime($date);
					
		return true;
		
	}
	
	public function setSearchType($search_type) {
		
		$this->search_type = $search_type;				
	
		return true;
		
	}
	
	public function setUserList($campaign_variables) {
		
		//Given campaign data and dates, build the mysql statement
		$this->CI->load->helper('sql_helper');
		$sql_string = build_where_sql($campaign_variables, $this->search_type);
		
		$final_sql = $this->append_time_where($sql_string);
		
		$total_user_number = $this->CI->metricslib_model->getTotalNumber('users');
		
		//Use the above sql statement to get a list of UserIds
		$userList = $this->CI->metricslib_model->getUserList($final_sql);
		
		if(count($userList) != $total_user_number) {
			$this->userList = $userList;
		}
				
		return true;
			
	}
	
	public function getStats() {
		
		return $this->stats;
		
	}
	
	public function setStats() {
				
		//# stats
		$this->setNumberUsers();		
		$this->setNumber('no_logins', 'login_log');
		$this->setNumber('no_lifts', 'lifts');
		$this->setNumber('no_comments', 'news_comments');
		$this->setNumber('no_read', 'news_read');
		
		//Social sharing stats
		$this->setNumber('no_fb_recommends', 'article_fbrecommends');
		$this->setNumber('no_tweets', 'article_tweets');
		$this->setNumber('no_google_pluses', 'article_gplus');
		$this->setNumber('no_pinterestpins', 'article_pinterestpins');
		
		$this->setFBActionStats('no_fbaction_read', 'read');
		$this->setFBActionStats('no_fbaction_reveal', 'keep');
		
		//user type stats
		$this->setNumber('no_goog_users', 'openid_users');
		$this->setNumber('no_fb_users', 'fbconnect_users');
				
		//demographic stats
		$this->setGenderStats();
		$this->setAverageAge();
		
		//Avg. Stats
		$this->setAvgStat('no_logins', 'avg_logins');
		$this->setAvgStat('no_lifts', 'avg_lifts');
		$this->setAvgStat('no_comments', 'avg_comments');
		
		//Add Monthly active users
		$this->getMonthlyActiveUsers();
		
		
		return true;
		
	}
	
	public function getMonthlyActiveUsers() {
		
		$monthList = $this->CI->metricslib_model->getMonthList();
		
		$monthlyActive = array();
		foreach($monthList as $row) {
			
			$monthlyActive[$row->month] = $this->CI->metricslib_model->getMonthActiveUsers($row->month);
			
		}
		
		//format the array for output into the google graph JS
		$js_formatted_stat = $this->google_graph_format($monthlyActive);
		
		$this->stats['js_active_users'] = $js_formatted_stat;
		
		return true;
		
	}
	
	public function setNumberUsers() {
		
		if($this->userList == "all") {
			$this->stats['no_users'] = $this->CI->metricslib_model->getTotalNumber('users');
			return true;
		}
		
		$this->stats['no_users'] = count($this->userList);
		return true;
	}
	
	//gets a number of a given statistic
	//$statistic is the output stat's name & $table is the stats table
	public function setNumber($statistic, $table) {
				
		if($this->userList == "all") {
			$this->stats[$statistic] = $this->CI->metricslib_model->getTotalNumber($table);
			return true;
		}
		
		$number = 0;
		foreach($this->userList as $user) {
			$users_number = $this->CI->metricslib_model->getNumber($table, $user->userid);
			$number += $users_number;
			
		}
		
		$this->stats[$statistic] = $number;		
		return true;
	}
	
	public function setFBActionStats($statistic, $action) {
		
		if($this->userList == "all") {
			$this->stats[$statistic] = $this->CI->metricslib_model->getFBActionNumber($action);
			return true;
		}
		
		$number = 0;
		foreach($this->userList as $user) {
			$users_number = $this->CI->metricslib_model->getFBActionNumber($action, $user->userid);
			$number += $users_number;
		}
		
		$this->stats[$statistic] = $number;		
		return true;
		
	}
	
	public function setGenderStats() {
		
		if($this->userList == "all") {
			$gender_data = $this->CI->metricslib_model->getGenderNumbers();
		}
		else {
			$gender_data = array('male' => 0, 'female' => 0);
			
			foreach($this->userList as $user) {
				$gender = $this->CI->metricslib_model->getGender($user->userid);
				if($gender == false) { continue; }
				$gender_data[$gender] += 1;
			}
			
		}
		
		$this->stats['no_male'] = $gender_data['male'];
		$this->stats['no_female'] = $gender_data['female'];
		
		return true;
		
	}
	
	public function setAverageAge() {
		
		if($this->stats['no_fb_users'] == 0) { $this->stats['avg_age'] = 0; return true; }
		
		$current_year = date("Y", time());
		$ages = array();
		
		if($this->userList == "all") {
			$ages_arr  = $this->CI->metricslib_model->getAllFBUserAges();
			foreach($ages_arr as $user) {
				$ages[]  = $user->age;				
			}
		}
		else {
			foreach($this->userList as $user) {
				$ages[]  = $this->CI->metricslib_model->getFBUserAge($user->userid);				
			}
		}
		
		$age_sum = array_sum($ages);
		$no_users = $this->stats['no_fb_users'];
		
		$this->stats['avg_age'] = $age_sum/$no_users;
		
		return $age_sum;
				
	}
	
	public function setAvgStat($statistic, $avg_name) {
		
		if($this->stats['no_users'] == 0) { 
			$this->stats[$avg_name] =  0;
			return true;
		}
		
		$this->stats[$avg_name] =  $this->stats[$statistic] / $this->stats['no_users'];
		
		return true;
		
	}
	
	function append_time_where($sql_string) {
		
			return $sql_string." UNIX_TIMESTAMP(registerdate) > ".$this->start_timestamp." AND UNIX_TIMESTAMP(registerdate) < ".$this->end_timestamp;
						
	}
	
	//given an array of months and numbers, format for google graph js output
	function google_graph_format($inputData) {
		
		$js_data = "";
		//$cumulative = 0;
		foreach($inputData as $key => $value) {
			//$cumulative += $value;
			$month_name = date( 'F', mktime(0, 0, 0, $key) );
			$js_data .= " [ '$month_name', $value ],";
		}
		
		return $js_data;
		
	}
	
	
	
}