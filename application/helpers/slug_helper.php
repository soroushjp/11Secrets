<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('processTagCSV'))
{
	function createSlug($title="") {
		if($title=="") return false;
		
		$slug = $title;
		
		$slug = preg_replace("/[^\w\s]+/", "", $slug);
		$slug = preg_replace("/\s+/", "_", $slug);
		$slug = strtolower($slug);
		$slug = substr($slug, 0, 120);
		
		return $slug;
	}
}