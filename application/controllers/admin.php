<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	public function __construct()
	{
	            parent::__construct();
				$this->load->model('user_model');
	            $this->userid = $this->user_model->is_logged_in();
				if($this->userid != 2 && $this->userid != 4 && $this->userid != 5) { exit("access denied.");  }
	}
	
	public function panel() {
		
		$this->load->library('metrics');
		$metrics = New Metrics();
		
		//Set the userDetails, page Meta, Title & CSS
		$this->load->library('document');
		$document = New Document($this->userid);
		$document->setHeaderTags('');
		$document->setCSS('control_panel.css');
		
		//get and set timestamp interval
		$interval = $this->process_dates();
						
		$metrics->setStartDate($interval['start_date']);
		$metrics->setEndDate($interval['end_date']);
				
		//set campaign search type
		$search_type = $this->input->post('search_type');		
		$metrics->setSearchType($search_type);
		
		//insert campaign variables into an array
		$campaign_variables = $this->campaign_variables();
		
		$metrics->setUserList($campaign_variables);	
					
		//now set all the stats using the data
		$metrics->setStats();
		
		//output an array of the stats
		$stats = $metrics->getStats();
				
		/* Output the data */
		$this->load->library('template');
			
		$data = array('document' => $document, 'stats' => $stats, 'traits' => $campaign_variables, 'search_type' => $search_type, 'date_array' => $interval['date_array']);
						
		$partials = array('content'=>'pages/control_panel',
						  'head'=>'template/header',
						  'top_bar' => 'template/top_bar',
						  'footer'=>'template/footer'); 
		
		$this->template->load('template/main', $partials, $data);
	}
	
	//turns a start and end date into a unix timestamps
	public function process_dates() {
		
		$start_day = $this->input->post('start_day');
		$start_month = $this->input->post('start_month');
		$start_year = $this->input->post('start_year');
		
		$end_day = $this->input->post('end_day');
		$end_month = $this->input->post('end_month');
		$end_year = $this->input->post('end_year');
		
		$start_date = $start_year."-".$start_month."-".$start_day;
		
		$error = "";
		
		if($start_day == "" || $start_month == "" || $start_year == "") {
			$start_date = "2011-01-01";
		}
		
		$end_date = $end_year."-".$end_month."-".$end_day;
		
		if($end_day == "" || $end_month == "" || $end_year == "") {
			$end_date = date("Y-m-d", (time()+86400));
		}
		
		$date_array = array('start_year' => $start_year, 'start_month' => $start_month, 'start_day' => $start_day, 'end_year' => $end_year, 'end_month' => $end_month, 'end_day' => $end_day);
						
		return array('start_date' => $start_date, 'end_date' => $end_date, 'date_array' => $date_array);
		
	}
	
	//takes input post campaign variables and inserts them in an array
	public function campaign_variables() {
		
		$campaign = $this->input->post('utm_campaign');
		$source = $this->input->post('utm_source');
		$medium = $this->input->post('utm_medium');
		$content = $this->input->post('utm_content');
		$term = $this->input->post('utm_term');
		$country = $this->input->post('country');
		
		return array('utm_campaign' => $campaign, 'utm_source' => $source, 'utm_medium' => $medium, 'utm_content' => $content, 'utm_term' => $term, 'country' => $country);
		
	}
	
	
}