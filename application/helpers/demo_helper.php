<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if ( ! function_exists('personal_collection_demo'))
	{
	    function personal_collection_demo() {
		
				$img_array['img_local'] = "http://www.11secrets.com/resources/images/main/demo_article.png";
				$img_array['height'] = "133";
				$img_array['width'] = "200";
				
				$img_obj = (object) $img_array;
				
				$article['newsid'] = 1;
				$article['title'] = "You haven't kept any articles yet. Click the Keep button on any article to add it to your collection.";
				$article['year'] = date('Y');
				$article['month'] = date('M');
				$article['day'] = date('d');
				$article['sourceName'] = "11Secrets Team";
				$article['link'] = base_url();
				$article['votes'] = 938;
				$article['imageArray'] = $img_obj;
				$article['comments'] = array();
				$article['commentCount'] = 0;
				$article['lifted'] = false;
				
				$article_obj = (object) $article;
				
				$articles = array($article_obj);
				
				return $articles;

			}
			
	}
	
	if ( ! function_exists('other_collection_demo'))
	{
	    function other_collection_demo() {
		
				$img_array['img_local'] = "http://www.11secrets.com/resources/images/main/demo_article_other.png";
				$img_array['height'] = "133";
				$img_array['width'] = "200";
				
				$img_obj = (object) $img_array;
				
				$article['newsid'] = 1;
				$article['title'] = "This user hasn't kept any articles yet. Check back soon.";
				$article['year'] = date('Y');
				$article['month'] = date('M');
				$article['day'] = date('d');
				$article['sourceName'] = "11Secrets Team";
				$article['link'] = base_url();
				$article['votes'] = 0;
				$article['imageArray'] = $img_obj;
				$article['comments'] = array();
				$article['commentCount'] = 0;
				$article['lifted'] = false;
				
				$article_obj = (object) $article;
				
				$articles = array($article_obj);
				
				return $articles;

			}
			
	}
	
	if ( ! function_exists('no_articles_with_tag_demo'))
	{
	    function no_articles_with_tag_demo() {
		
				$img_array['img_local'] = "http://www.11secrets.com/resources/images/main/demo_article_other.png";
				$img_array['height'] = "133";
				$img_array['width'] = "200";
				
				$img_obj = (object) $img_array;
				
				$article['newsid'] = 1;
				$article['title'] = "There are no articles with the selected tags. Try selecting fewer.";
				$article['year'] = date('Y');
				$article['month'] = date('M');
				$article['day'] = date('d');
				$article['sourceName'] = "11Secrets Team";
				$article['link'] = base_url();
				$article['votes'] = 0;
				$article['imageArray'] = $img_obj;
				$article['comments'] = array();
				$article['commentCount'] = 0;
				$article['lifted'] = false;
				
				$article_obj = (object) $article;
				
				$articles = array($article_obj);
				
				return $articles;

			}
			
	}
