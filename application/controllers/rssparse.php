<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class RSSParse extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('rssparse_model');
		$this->load->library('imagelibrary');
		$this->load->library('image_lib');
		
		$this->imagelibrary = new Imagelibrary();
	}
	
	public function index() {
		
		echo "\n\n--------RSS ARTICLE PARSE AND STORE BEGIN-------\n\n";
		
		set_time_limit (0);
		
		$TMP_URL = base_url() . "tmp/";
		
		$articles = array();
		$data['articles'] = $articles;
		$n = 0;
		$d = 0;
		$f = 0;
		
		$local_dir = "/var/www/tmp/";
		
		$standard_width = 200;
		$max_width = 504;
		
		$feeds_array = $this->rssparse_model->getFeeds(0,0);
		
		$feed_count = count($feeds_array);
		
		echo "Retrieved $feed_count RSS feeds. \n\n"; flush();	

		foreach($feeds_array as $feed) {
			
			$feed_tags = $this->rssparse_model->getFeedTagIDs($feed['feedid']);
			
			echo "\n\tParsing a feed ..."; flush();
			
			$image_articles =  $this->parseFeedArticles($feed['rss_url'], $feed['source'], $feed['og_check'], $feed['standard_width'], $max_width);
			
			echo "Parsed feed. \n\n"; flush();
			
			if(!$image_articles) continue;
			
			$a=0;
			
			foreach($image_articles as $key => &$article) {
				
				$duplicate_newsid = $this->rssparse_model->getDuplicates($article['guid'], $article['link'], $article['title']);
				
				if($duplicate_newsid != 0 ) {
					
					$d++;
					echo "\n\t\tDUPLICATE: Article already in DB: newsid #".$duplicate_newsid." \n\t\tLink: ". $article['link'] . " \n"; flush();
					
					//Append these tags to existing duplicate article for better tagging
					$this->rssparse_model->setTagIDs($feed_tags);
					$success = $this->rssparse_model->saveArticleTags($duplicate_newsid);
					if($success>0) echo "\t\tSuccessfully appended $success new tags to newsid #$duplicate_newsid \n"; flush();
					
					continue;
				}
				
				if($duplicate_newsid === false) {
					echo "ERROR: Couldn't get number of duplicates from DB"; flush();
					return false;
				}
				
				echo "\n\t\tStoring remote article image locally ... "; flush();
				
				$local_img = $this->imagelibrary->storeRemoteImage($article['img_link'], $local_dir, true);	
				if(!$local_img) continue;
				
				echo "Stored remote article image locally. \n\n"; flush();
				
				echo "\t\tRetrieving image information and resizing ... "; flush();
				
				$local_link = str_replace($local_dir, '', $local_img);
				$local_link = $TMP_URL . $local_link;
				
				if($img_size = getimagesize($local_img)) {
					
					if($img_size[0] < $standard_width) {
						unset($image_articles[$key]);
						unlink($local_img);
						echo "Image width less than $standard_width, article skipped. \n\t\tLink: ". $article['link'] . " \n\n"; flush();
						continue;
					} else $article['has_image'] = 1;
					
				} else {
					echo "ERROR: Failed to get image information. \n\t\tImage: $local_img\n\n";
					continue;
				}
				
				$a++;
				
				$height = floor($standard_width * ($img_size[1]/$img_size[0]));
				
				if($img_size[0] > $standard_width) {
					
					//INSTALL AND SWITCH TO IMAGEMAGICK FOR PERFROMANCE BOOST ON PRODUCTION SERVER
					$config['image_library'] = 'gd2';
					$config['source_image']	= $local_img;
					$config['maintain_ratio'] = TRUE;
					$config['width'] = $standard_width;
					$config['height'] = $height;
					$config['master_dim'] = 'width';

					$this->image_lib->initialize($config);  
					$this->image_lib->resize();
				}
				
				echo "Image resized. \n\n"; flush();
				
				echo "\t\tArticle #$a: Storing article in DB ..."; flush();
				
				$this->rssparse_model->setFeedID($feed['feedid']);
				$this->rssparse_model->setSourceName($feed['sourceName']);
						
				if(!$this->rssparse_model->setTitle($article['title'])) continue;
				if(!$this->rssparse_model->setPubDate($article['pubDate'])) continue;
				if(!$this->rssparse_model->setContent($article['description'])) continue;
				if(!$this->rssparse_model->setArticleLink(trim($article['link']))) continue;
				
				if(!$this->rssparse_model->setSource($article['source'])) continue;
				if(!$this->rssparse_model->setImgLink($article['img_link'])) continue;
				if(!$this->rssparse_model->setImgFind($article['img_find'])) continue;
				
				if(!$this->rssparse_model->setImgLocal($local_link)) continue;
				if(!$this->rssparse_model->setImgLocalPath($local_img)) continue;
				if(!$this->rssparse_model->setImgWidth($standard_width)) continue;
				if(!$this->rssparse_model->setImgHeight($height)) continue;
				
				if(!$this->rssparse_model->setGUID(trim($article['guid']))) continue;
				if(!$this->rssparse_model->setDCIdentifier(trim($article['dc_identifier']))) continue;
				
				if(!$this->rssparse_model->setHasImage($article['has_image'])) continue;
				
				if(!$this->rssparse_model->setTagIDs($feed_tags)) continue;
				
				$saved = $this->rssparse_model->saveArticle();
				
				if($saved === true) {
					$n++;
					echo "SUCCESS: Stored article in DB. \n\t\tLink: ". $article['link'] . " \n\n"; flush();
					continue;
				}
				
				if($saved === false) {
					$f++;
					echo "ERROR: Failed to save article in DB. \n\t\tLink: ". $article['link'] . " \n\n"; flush();
					continue;
				}
					
			}
			
		}

		echo "Successfully stored $n articles. $d duplicates found, not stored again in DB. $f failed to save."; flush();
		
		echo "\n\n--------RSS ARTICLE PARSE AND STORE COMPLETE-------\n\n";
		return true;

	}	
	
	private function parseFeedArticles($rss_url="", $type="", $og_check=false, $standard_width, $max_width) {
		
		if($rss_url=="") return false;
		
		$this->load->helper('url');
		$this->load->library('simpledom/simple_html_dom.php');
		
		$rss_url_enc = urlencode($rss_url);
		
 		$json_url =  base_url() . "fulltext/makefulltextfeed.php?url=" . $rss_url_enc . "&max=30&format=json" ;
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $json_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, "fulltexter:fullytextmyridez65");
		
		$json_articles = curl_exec($ch);
		
		curl_close($ch);
				
		$json_data = json_decode($json_articles, true);
		unset($json_articles);
		
		$articles = $json_data['rss']['channel']['item'];
		unset($json_data);
		
		if(!$articles) { echo "Error: No articles for feed: $rss_url \n\n"; return false; }
		
		foreach($articles as $key => &$article) {
			
			$article['source'] = $type;
			
			//Strip whitespace from HTML
			$article['description'] = preg_replace('~>\s+<~', '><', $article['description']);
			
			if(!$this->passTextFilters($article['description'])) {
				unset($articles[$key]);
				continue;
			}
			
			$article['description'] = $this->cleanArticle($article['description'], $type);
			
			$bodyImage = $this->parseBodyImages($article['description'], $standard_width, $max_width);
			
			//First check for usable body images. If found, we have our image.
			if($bodyImage) {
				
				//Store resized width <img> tags
				$article['description'] = $bodyImage[1];
			
				foreach ($bodyImage[0] as $body_image) {
					if($this->imagelibrary->checkURLIsImage($body_image) && $this->passImgFilters($body_image)) {
						$article['img_link'] = $body_image;
						$article['img_find'] = "body_image";
						continue;
					}
				}
				
				if(isset($article['img_link'])) continue;
				
			}
				
			//Next option, we check for usable Open Graph images. If found, we have our image.
			if($og_check) {
				
				$og_image = $this->getOGImage($article['link']);
				
				if($og_image) {
					$og_image = $this->parseOGImage($og_image, $type, $standard_width);
				
					if($this->imagelibrary->checkURLIsImage($og_image) && $this->passImgFilters($og_image)) {
						$article['img_link'] = $og_image;
						$article['img_find'] = "og_image";
						continue;
					}
				}	
			}
			
			//No usable images found. We don't use this article and remove it from our return array.
			unset($articles[$key]);
			
		}
		
		return $articles;
	}
	
	private function getOGImage($articleurl="") {
		
		if($articleurl=="") return false;
		
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_URL, $articleurl);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		$article_html = curl_exec($ch);
	
		curl_close($ch);
		
		
		$article_dom = str_get_html($article_html);
		unset($article_html);
		
		if(!$article_dom) return false;
		
		$og_img = $article_dom->find('meta[property=og:image]', 0);
		
		if(!$og_img) return false;
		
		if(isset($og_img->content)) return $og_img->content;

		return false;
		
	}
	
	private function parseOGImage($url, $type, $standard_width=200) {	
		if($url == "" || $type == "") return false;
		
		if($type=="wsj") {
				$parsed_url = str_replace("_A_", "_E_", $url);
				return $parsed_url;
		} 
		
		if($type=="reuters") {
			$parsed_url = preg_replace("/&w=.*&/", "&w=$standard_width&", $url);
			return $parsed_url;
		} 
		
		if($type=="google") {
				$parsed_url = str_replace("&size=s2", "", $url);
				return $parsed_url;
		}
		
		return $url;
				
	}
	
	private function parseBodyImages($body="", $standard_width=200, $max_width) {
		
		//Finds images in article to use for article photo. Also shrinks down images that are too big for use in QuickRead.
		
		if($body=="")return false;
		
		$body = htmlspecialchars_decode($body);
		
		$html = str_get_html($body);
		
		$imgs = $html->find('img');
		
		if(!$imgs) return false;
		
	
		foreach($imgs as $key => $img) { 
		
			if(isset($img->width)) $img_w_ok = ($img->width >= $standard_width || substr($img->width, -1) == "%") ? true : false ;
			else $img_w_ok = true;
			if(isset($img->height)) $img_h_ok = ($img->height >= ($standard_width/4) || substr($img->height, -1) == "%") ? true : false ;
			else $img_h_ok = true;

			if(isset($img->src) && $img_w_ok && $img_h_ok) {
				
				//Shrink down images that are too wide for QuickRead window
				if(isset($img->width) && floatval($img->width) > $max_width) {
					$imgs[$key]->width = $max_width;
					
					if(isset($img->height)) $imgs[$key]->height = "";
				} elseif (isset($img->width) && $img->width == "100%") {
					
					$imgs[$key]->width = $max_width;
					
					if(isset($img->height)) $imgs[$key]->height = "";
					
				}	
				
				//Set img's as image URLs
				$img_links[] = $img->src;
			}
	
		}
		
		if(!isset($img_links)) return false;
		
		//Add break after first image to prevent strange text wrapping in QuickRead
		$imgs[0]->outertext = $imgs[0]->outertext . "<br/>";
		
		$parsedImages[0] = $img_links;
		$parsedImages[1] = $html->save();
		
		return $parsedImages;
		
	}
	

	

	
	private function passImgFilters($img_link) {
		
		$filters = array("http://media.cnbc.com/i/CNBC/CNBC_Images/backgrounds/realtime_icon.gif",
						"http://s.wsj.net/img/WSJ_profile_lg.gif",
						"http://si.wsj.net/img/WSJ_profile_lg.gif",
						"http://i.cdn.turner.com/money/images/bug.gif",
						"http://media.cnbc.com/i/CNBC/CNBC_Images/header/CNBC_Logo_PS.gif",
						"http://media.cnbc.com/i/CNBC/Sections/News_And_Analysis/_News/_CNBC_EXPLAINS/_IMAGES/CNBC_explains_icon1.gif",
						"http://www.reuters.com/resources_v2/images/reuters_fb_share.jpg");
						
		if(in_array($img_link, $filters)) return false;
		
		return true;
		
	}
	
	private function passTextFilters($text) {
		
		$filters = array("jQuery",
						"<script",
						"&lt;script",
						"javascript");
						
		foreach($filters as $filter) {
			if(strpos($text, $filter)>0) return false;
		}
		
		return true;
		
	}
	
	private function cleanArticle($article, $source="") {

		$article = strip_tags($article, '<br><br/><p><a><img><table><tr><td><th><tbody><cite><h1><h2><h3><b><strong>');
		
		$article_dom = str_get_html($article);
		
		$a_tags = $article_dom->find('a');
		
		foreach ($a_tags as $key => $a_tag) {
			$a_tags[$key]->target = "_blank";
			$a_tags[$key]->rel = "nofollow";
		}
		
		//Additional cleaning for CNBC news feeds
		
		if($source=="cnbc") {
			
			$a_loads = $article_dom->find('a.black_no_change');
			
			foreach($a_loads as $key => $a_load) {
				$a_loads[$key]->outertext = '';
			}
			
			$tables = $article_dom->find('table');
			
			foreach($tables as $key => $table) {
				
				if(isset($tables[$key]->style)) {
					
					if(substr(trim($tables[$key]->style), -1) == ";") $tables[$key]->style = $tables[$key]->style . "margin:10px";
					else $tables[$key]->style = $tables[$key]->style . "; margin:10px;border: solid thin #CCC;";
					
				} else {
					
					$tables[$key]->style = "margin:10px; border: solid thin #CCC;";
					
				}
				
				$tables[$key]->cellpadding = "10";
				
			}
			
			$article = $article_dom->save();
			
			//Removes "Explain This" messages on stock terms for CNBC articles
			$article = preg_replace("/<a\s+[\"'\/\-:;&\?s\w#.=]*><[\"'\/:;&?\s\w#.=]*\[cnbc explains\][\"\s\/]*>\s*<\/a>/", "", $article);
			
			return $article;
		}
		
		$article = $article_dom->save();
		
		if(strpos($article, "[unable to retrieve full-text content]") !== FALSE ) {
			
			$article = str_replace("[unable to retrieve full-text content]", "", $article);
			
			$article .= "<br/><br/> (The full text of this article was not able to be retrieved. Please click <b>See Original Article</b> above to go the original source and see the full article.)";
			
		}
		
		return $article;
	}
	
	
}