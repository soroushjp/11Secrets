<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('processTagCSV'))
{
	
	function processTagCSV($tags) {
		
		if($tags == "") return false;
		
		//Remove whitespace from tag string, shouldn't be in a comma separated list of Tag ID's
		$tags = trim($tags);
		
		$tags_array = str_getcsv($tags);
		if(!$tags_array) return false;
		
		//Remove duplicate tags
		$tags_array = array_unique($tags_array);
		//Reset array keys to consecutive numerical keys
		$tags_array = array_values($tags_array);

		//Validates list of tags to make sure it contains only numberical values. Strings together to produce processed tag CSV for use in DB
		
		foreach($tags_array as $key => $tag) {
			if(!is_numeric($tag)) return false;
			
			if($key == 0) $processed_tags = $tag;
			else $processed_tags .= ",$tag";
		}
	
		return $processed_tags;
	}
	
	function getTagCount($tags) {
		
		//Only validated tags from post-processTagCSV should be passed to this function
		
		if($tags == "") return false;
		
		$tags_array = str_getcsv($tags);
	
		return count($tags_array);
	}

}