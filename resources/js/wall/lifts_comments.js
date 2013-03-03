function lift(newsid) {
	var post_url = BASEURL+'wall_ajax/lift';
	$.post(post_url,
      { newsid:newsid,
		ajax: '1' 
	   },

      // when the Web server responds to the request
      function(result) {
     	if(result == 'worked') {
			//increment points and relift count
			var current_lift = $('#'+newsid).html();
			$('#'+newsid).html(parseInt(current_lift) + 1);
			$('#lift'+newsid).attr("class", "lifted");
			$('#articlelift'+newsid).removeClass("class", "relift_button");
			$('#articlelift'+newsid).attr("class", "down_button");
			//increase users points
			increasePoints(5);
			incrementDiv('stat_relifts', 1);
			keep_article_fb(newsid);
		}
		else if(result == 'login') {
			//redirect to login popup
			var reminder = 'lift|'+newsid;
			setCookie('reminder',reminder,1, '/', '.11secrets.com');
			pop_up('login', 'relift');
		}
		else {
			//remove lift, points and color
			var current_relift = $('#'+newsid).html();
			$('#'+newsid).html(parseInt(current_relift) - 1);
			$('#lift'+newsid).attr("class", "lift");
			$('#articlelift'+newsid).removeClass("class", "down_button");
			$('#articlelift'+newsid).attr("class", "relift_button");
			//increase users points
			decreasePoints(5);
			decreaseDiv('stat_relifts', 1);
		}	
	});
}

function comment(newsid, comment) {
	var post_url = BASEURL+'wall_ajax/comment';
	$.post(post_url,
      { newsid:newsid,
		comment:comment,
		ajax: '1' 
	   },

      // when the Web server responds to the request
      function(result) {
		if(result == 'login') {
			//redirect to login popup
			var encoded_comment = urlencode(comment);
			var reminder = 'comment|'+newsid+'|'+encoded_comment;
			setCookie('reminder',reminder,1, '/', '.11secrets.com');
			pop_up('login', 'comment');
		} else if (result == 'failed') {
			return false;
		} else {
			// 
			// //show the comment being posted
			// var name = $('#user_name').html();
			// var points = $('#points').html();
			// var user_thumb = $('#user_icon').html();
			// var comment_html = "<div class='comment'><div class='user_thumb'>"+user_thumb+"</div><div class='user_comment'><div class='user_quote'><span class='user_name'>"+name+points+"</span> "+comment+"</div></div></div>";
			// //check if already 5 comments
			
			comment_html = result;
			
			$('#comments'+newsid).append(comment_html);
			
			$('#commentfield'+newsid).val('Add a comment...').blur();
			
			//do it popup as well
			$('#commentspop'+newsid).prepend(comment_html);
			
			$('#commentfieldpop'+newsid).val('Add a comment...').blur();
			call_wookmark();
			
			//increase their points by 10
			var pointAdd = 10;
			increasePoints(pointAdd);	
			incrementDiv('stat_comments', 1);
		}

	});
}

function setCommentPermissions() {
	$("div.user_comment").hover(
		function(){$(this).children("a.com_closer").css('display', 'inline');},
		function(){$(this).children("a.com_closer").css('display', 'none');}
	);
}

function is_int(value){ 
  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
      return true;
  } else { 
      return false;
  } 
}

function removeComment(comment_div_id) {

	commentid = comment_div_id.replace("commentuq_", "");
	
	if(is_int(commentid) == false) return false;
	
	var post_url = BASEURL+'wall_ajax/remove_comment';
	
	$.post(post_url,
      { commentid:commentid,
		ajax: '1' 
	   },
	
	// when the Web server responds to the request
	function(result) {
		
		if(result > 0) {
			$("div#"+comment_div_id).remove();
			decreasePoints(result);
			decreaseDiv('stat_comments', 1);
			call_wookmark();
		}	
	});
	
	return true;
	
}

//increase a users point count
function increasePoints(number) {
	var current_points = $('#points').html();
	if (current_points == null) return false;
	
	var points_alone = current_points.replace(/\(*\)/,'');
	points_alone = points_alone.replace(/\(\)*/,'');
	var new_points = parseInt(points_alone) + number;
	$('#points').html('('+new_points+')');
	
	return true;
}

//decrease a users point count
function decreasePoints(number) {
	var current_points = $('#points').html();
	if (current_points == null) return false;
	
	var points_alone = current_points.replace(/\(*\)/,'');
	points_alone = points_alone.replace(/\(\)*/,'');
	var new_points = parseInt(points_alone) - number;
	$('#points').html('('+new_points+')');
}

//increase a users point count
function incrementDiv(divId, number) {
	var current_points = $('#'+divId).html();
	var new_points = parseInt(current_points) + number;
	$('#'+divId).html(new_points);
}

//increase a users point count
function decreaseDiv(divId, number) {
	var current_points = $('#'+divId).html();
	var new_points = parseInt(current_points) - number;
	$('#'+divId).html(new_points);
}

