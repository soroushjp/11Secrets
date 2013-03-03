<div id="tags_container">
	<div id="tags_area">
		<div id="tags_box">
			<?php foreach($tags as $tag) echo " <a id=\"tag_$tag->tagid\" href=\"javascript:void(0);\" onclick=\"add_tag($tag->tagid); return false\" class=\"tag_link\">$tag->tag</a> " ; ?>
		</div>
		<script type="text/javascript">
		var DEFAULT_TAG_HTML = $("div#tags_box").html(); var CURRENT_VIEW_TAGS = '<?= $default_tags; ?>';
		</script>
	</div>
	<!--
	<div class="tags_area_toggle">
		<a href="javascript:void(0)" onclick="toggleTags();" class="tags_toggle_btn">Hide topic tags</a>
	</div>
	-->
</div>
	<div id="main" role="main" class="main_section">
  		<ul id="tiles">
		    <!-- These are our grid blocks -->
		
			<?=$articles_html; ?>
						
		    <!-- End of grid blocks -->
		  </ul>
	</div>
  <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      	
		<?php 
		if($document->loggedin == false) { ?>
		<?php } ?>
      	call_wookmark();
      
    });
	function call_wookmark() {
		// Prepare layout options.
	      var options = {
	        autoResize: true, // This will auto-update the layout when the browser window is resized.
	        container: $('#main'), // Optional, used for some extra CSS styling
	        offset: 50, // Optional, the distance between grid items
	        itemWidth: 260 // Optional, the width of a grid item
	      };
	      // Get a reference to your grid items.
	      var handler = $('#tiles li');
	      // Call the layout function.
	      handler.wookmark(options);
			//Reset comment removal functions for users (SJPOUR)
	      setCommentPermissions();
	}	
  </script>
