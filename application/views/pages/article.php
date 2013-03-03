<script type="text/javascript">
	var NEWSID = "<?=$option->newsid; ?>";
</script>

<div class="article_window">
	<div class="article_cont">
		<div class="article_header">
			<div class="article_title">
				<div class="title">
					<i><?=$option->title; ?></i>
				</div>
			</div>
			<div class="article_main">
				<div class="article_content">
					<div class="article_image"><img src="<?=$option->imageArray->img_local; ?>" /></div>
					<a href="javascript:void(0);" onclick="open_secretframe(<?=$option->newsid; ?>); return false;" ><div class="article_read_btn"></div></a>
				</div>
				<div class="article_share">
					<div class="article_external_share">
						<?php $encoded_title = htmlentities($option->title); ?>
						<div class="facebook_button">
						<div class="fb-like" data-href="<?=base_url(); ?>wall/article/<?=$option->newsid; ?>" data-send="true" data-layout="button_count" data-width="80" data-show-faces="false" data-action="like" data-font="arial"></div>
						</div>
						<div class="tweet_button">
							<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$option->bitly; ?>" data-text="<?=$encoded_title; ?>" data-/via="" data-hashtags="news">Tweet</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</div>
						<div class="googleplus_button">
							<g:plusone size="medium" href="http://www.11secrets.com/wall/article/<?=$option->newsid; ?>" callback="plusClick"></g:plusone>
						</div>
						<div class="permalink">
							<a href="javascript:void(0);" onclick="permalinkExpand();"><div class="link_button"></div></a>
							<div id="link-box">
								<div class="link_deco">
									<?=base_url(); ?>wall/article/<?=$option->newsid; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="article_internal_share">
						<a href="javascript:void(0);" onclick="lift(<?=$option->newsid; ?>); return false;" ><div id="articlelift<?=$option->newsid; ?>" class="<?php echo ($option->lifted) ? 'down_button' : 'relift_button'; ?>"></div></a>
						<a href="javascript:void(0);" onclick="pop_up('reveal', '', <?=$option->newsid; ?>); return false;" ><div class="reveal_article"></div></a>
					</div>
				</div>
			</div>
			<div class="article_lower">
				<div class="article_excerpt">
					<div class="excerpt_title">Excerpt</div>
					<div class="article_divider"></div>
					<div class="excerpt_text"><?php echo $option->content; ?>...</div>
					<div class="read_full_link"><a href="javascript:void(0);" onclick="open_secretframe(<?=$option->newsid; ?>); return false;" >Read full article »</a></div>
				</div>
				<div class="article_discussion">
					<div class="comment_title">Comments</div>
					<div class="article_divider" style="width:190px;"></div>
					<div class="comment" id="commentbox">
						<div class="user_thumb"><img src="<?=$avatar; ?>" width="30px" height="30px" /></div>
						<div class="user_comment">
							<div class="user_quote">
								<textarea class="comment_field" id="commentfieldpop<?=$option->newsid; ?>" maxlength="255" onfocus="if(this.value=='Add a comment...') { this.value='' }; fontSmaller(this);" onblur="if(this.value=='') { this.value = 'Add a comment...' } fontLarger(this);">Add a comment...</textarea>
							</div>
						</div>
					</div>
					<div class="comments">
						<div id="commentspop<?=$option->newsid; ?>">
							<?php echo $comments_html; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
