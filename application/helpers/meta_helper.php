<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('create_facebook_meta'))
{
    function create_facebook_meta($meta="") {
	
		if($meta == "") {
			$meta = array(
				'title' => "Find the best news online, chosen by readers",
				'type' => 'website',
				'url' => 'http://www.11secrets.com/',
				'img' => 'http://www.11secrets.com/aimg-foftbyg-1333094828-X.jpeg'
				);
		}
		
		$meta = (object) $meta;
		
		$meta_html = "<meta property='og:title' content='$meta->title' /><meta property='og:type' content='$meta->type' /><meta property='og:url' content='$meta->url' /><meta property='og:image' content='$meta->img' />";
		
		return $meta_html;
		
	}
	
	
}
