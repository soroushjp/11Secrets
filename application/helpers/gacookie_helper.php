<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('parseGA_utmz'))
{
	function parseGA_utmz() {
		
			$empty_utm = array("source" => "(none)", "campaign" => "(none)", "medium" => "(none)", "content" => "(none)", "term" => "(none)");
		
			if (isset($_COOKIE['__utmz']) && $_COOKIE['__utmz'] != "") {
				$utmz_cookie = $_COOKIE['__utmz'];
				$utmz_cookie = urldecode($utmz_cookie);
			} else {
				return $empty_utm;
			}

			$utmcsr = $utmccn = $utmcmd = $utmcct = $utmctr = "(none)";

			$utmz_cookie_parts = explode("|", $utmz_cookie);

			foreach ($utmz_cookie_parts as $utmz_info ) {

				$utmz_info_exploded = explode("=", $utmz_info);
				$utmz_info_name = substr($utmz_info_exploded[0], -6);

				switch($utmz_info_name) {
					case "utmcsr":
						$utmcsr = $utmz_info_exploded[1];
						break;
					case "utmccn":
						$utmccn = $utmz_info_exploded[1];
						break;
					case "utmcmd":
						$utmcmd = $utmz_info_exploded[1];
						break;
					case "utmcct":
						$utmcct = $utmz_info_exploded[1];
						break;
					case "utmctr":
							$utmctr = $utmz_info_exploded[1];
							break;
				}
			}

			$utm_variables = array("source" => $utmcsr, "campaign" => $utmccn, "medium" => $utmcmd, "content" => $utmcct, "term" => $utmctr);

			return $utm_variables;

		}
}	