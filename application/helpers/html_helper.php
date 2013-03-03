<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('create_articles_html'))
{
    function create_articles_html($articles, $avatar='', $loggedin_userid=0, $isAdmin=0, $demo=false) {
	
			$final_html = "";
			$base_url = base_url();
			
			
			foreach($articles as $article) {
				
				$image = $article->imageArray;
				$img_width = $image->width;
				$img_height = $image->height;
				$img_url = $image->img_local;
				
				$lifted = ($article->lifted) ? "lifted" : "lift";
				$revealed = 'reveal'; //eventually check if user already shared this article
				$lift_id = "lift".$article->newsid;
				$reveal_id = "reveal".$article->newsid;
				
				//if not a demo article, define all JS
				$popup_js = (!$demo) ? "pop_up(\"article\", \"\", $article->newsid); return false;" : "javascript:void(0);";
				$lift_js = (!$demo) ? "lift($article->newsid); return false;" : "javascript:void(0);";
				$reveal_js = (!$demo) ? "pop_up(\"reveal\", \"\", $article->newsid); return false;" : "javascript:void(0);";
				$comment_js = (!$demo) ? "$('#commentfield$article->newsid').bind('keypress', function(e) { if(e.keyCode==13){ comment('$article->newsid', $('#commentfield$article->newsid').val()); } });" : "javascript:void(0);";
				
				$comments = "";
				
				$first_piece = "
			<li>
				<div class='article_box' onmouseover='showButtons($article->newsid);' onmouseout='hideButtons($article->newsid);'>
					<div class='article_img'>
						<a href='javascript:void(0)' onclick='$lift_js'>
							<div id='$lift_id' class='$lifted'>
								<!-- <div class='lift_score' id='$article->newsid'>$article->votes</div> -->
							</div>
						</a>
						<a href='javascript:void(0)' onclick='$reveal_js'>
							<div id='$reveal_id' class='$revealed'>
								<!-- <div class='lift_score' id='$article->newsid'>$article->votes</div> -->
							</div>
						</a>
						<!-- old js: pop_up(\"article\", \"\", $article->newsid); return false; -->
						<a href='javascript:void(0)' onclick='open_secretframe($article->newsid); return false;'><img src='$img_url' width='$img_width' height='$img_height'></a>
					</div>
					<div class='article_text'>
						<a href='javascript:void(0)' onclick='open_secretframe($article->newsid); return false;'>
							<div class='article_title'>$article->title</div>
						</a>
						<div class='article_details'>
							<div class='article_source'>$article->sourceName</div>
							<div class='article_age'>$article->month $article->day, $article->year</div>
						</div>
					</div>
				</div>
					<div class='comments'>
						<div class='comments_only'  id='comments$article->newsid'>";
						
						/*
						$comments = "";
						
						foreach($article->comments as $comment) {
							
							$comment_html = create_comment_html($comment, $loggedin_userid, $isAdmin, $demo);
							
							if ($comment_html) $comments .= $comment_html;
						
						 }
						*/
						
						$more_comments = ($article->commentCount > 0) ? "<div id='more_comments'><a href='javascript:void();' onclick='$popup_js' >See all comments ($article->commentCount) »</a></div>" : "";
						
						$last_piece = "
						</div>
						$more_comments
						<div class='comment' id='commentbox'>
							<div class='user_thumb'><img src='$avatar' width='30' height='30' /></div>
							<div class='user_comment'>
								<div class='user_quote'>
									<textarea class='comment_field' id='commentfield$article->newsid' maxlength='255' onfocus='if(this.value==\"Add a comment...\") { this.value=\"\" }; fontSmaller(this);' onblur='if(this.value==\"\") { this.value = \"Add a comment...\" } fontLarger(this);'>Add a comment...</textarea>
									<input type='hidden' value='article_id'>
								</div>
							
							</div>
						</div>
					</div>
				</li>
				<script type='text/javascript'>			
				$comment_js
				</script>
				";
				
				$final_html .= $first_piece.$last_piece;
				
			}
			
			return $final_html;

	}
	
	function create_comment_html($comment = array(), $loggedin_userid=0, $isAdmin=0, $demo=false) {
		
		$collection_js = "get_collection(0, $comment->userid);";
		
		if(empty($comment)) return false;
		
		if ($loggedin_userid == $comment->userid || $isAdmin) {
			$commentRemoveBtn = "<a href='javascript:void(0);' onclick='removeComment(\"commentuq_$comment->commentid\")' class='com_closer' style='display:none'>
									<div class='commentRemoveBtn'></div>
								</a>";
			$hoverclose = " hoverclose";
		} else {
			$commentRemoveBtn = "";
			$hoverclose = "";
		}
			
		$comment_html = "<div class='comment $hoverclose' id='commentuq_$comment->commentid'>
						<a href='javascript:void(0);' onclick=\"$collection_js return false;\">
							<div class='user_thumb'>
								<img src='$comment->thumb' width='30' height='30' />
							</div>
						</a>
						<div class='user_comment'>
							$commentRemoveBtn
							<div class='user_quote'>
								<a href='javascript:void(0);' onclick=\"$collection_js return false;\">
									<span class='user_name'>$comment->name ($comment->points)</span>
								</a>
							$comment->comment
							</div>
						</div>
					</div>";
					
		return $comment_html;
	}
		
}

if ( ! function_exists('create_socialclip_html'))
{
    function create_socialclip_html($actions) {
	
			$final_html = "
			<div class='social_off'><a id='social_btn' href='javascript:void(0);' onclick='toggle_social();'>Turn Social OFF</a></div>
			<div class='social_list'>Recent Activity</div>
			<div class='social_actions'>";
			$base_url = base_url();
			
			foreach($actions as $action) {
				$short_month = substr($action->month, 0, 3);
				$real_title = (strlen($action->title) > 28) ? substr($action->title, 0, 28).'...' : $action->title;
				$final_html .= "<div class='social_action' id='action$action->actionid'>
									<div class='social_title'>$real_title</div>
									<div class='social_date'>$short_month $action->day</div>
									<a href='javascript:void(0)' onclick='deleteActivity($action->actionid); return false;' ><div class='social_delete'></div></a>
								</div>";
			}
			
			return $final_html;

	}

		
}

