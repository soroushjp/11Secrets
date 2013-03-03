<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if ( ! function_exists('popup_html'))
	{
	    function popup_html($type, $error="", $option="") {
		
				$css = $type.'.css';
				$url = 'pages/'.$type;
				$data = array('page_css' => $css);
				$data['error'] = $error;
				$data['option'] = $option;
				
				return array('url' => $url, 'data' => $data);

			}
			
	}
	
	if ( ! function_exists('error_messages'))
	{
			
		function error_messages($error) {
			
			if($error == "already") {
				return "There is already an account with this email. Please use the associated login details.";
			}	
			if($error == "cancelled") {
				return "You have cancelled the Google Connect.";
			}
			if($error == "failed") {
				return "We were unable to log you in. Please try again.";
			}
			if($error == "relift") {
				return "Please login or <a href='javascript:void(0);' onclick='pop_up(\"signup\", \"\", \"\"); return false;'>sign up</a> to keep an article";
			}
			if($error == "comment") {
				return "Please login or <a href='javascript:void(0);' onclick='pop_up(\"signup\", \"\", \"\"); return false;'>sign up</a> to comment on an article";
			}
			if($error == "nothing") {
				return "";
			}
			if($error == "") {
				return "<a href=\"javascript:void(0)\" onclick=\"pop_up('signup', '', ''); return false;\">Create a free account</a> in less than 10 seconds now";
			}
			
			return $error;
			
		}
		
	}
