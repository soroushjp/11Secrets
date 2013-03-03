<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if ( ! function_exists('make_bitly_url'))
	{
		
		
		function make_bitly_url($url, $format = 'xml',$version = '3.0.0')
		{
			$login = "wealthliftemploy";
			$appkey = "R_519c30e4c77e2b958fd5315b6978b37c";
			
		  //create the URL
		  $bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;
  
		  //get the url
		  //could also use cURL here
		  $response = file_get_contents($bitly);
  
		  //parse depending on desired format
		  if(strtolower($format) == 'json')
		  {
		    $json = @json_decode($response,true);
		    return $json['results'][$url]['shortUrl'];
		  }
		  else //xml
		  {
		    $xml = simplexml_load_string($response);
		    return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
		  }
		}
		
	}

