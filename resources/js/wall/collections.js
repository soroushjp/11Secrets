function get_collection(number, collection_owner, tags, append) {
	
	var post_url = BASEURL+'wall_ajax/get_articles';
	var counter = 0;
	
	number = (typeof number !== 'undefined' && number != '') ? number : 0;
	collection_owner = (typeof collection_owner !== 'undefined' && collection_owner != '') ? collection_owner : 0;
	tags = (typeof tags !== 'undefined' && tags != 0) ? tags : '';
		
	newLoad = isNewUser(collection_owner);
	
	counter = getCounter(newLoad);
	
	if(typeof append === 'undefined') {
		if (counter>0) append = 1;
		else append = 0;
	} else if(append != 1) {
		append = 0;
		counter = 0;
	}
	
	$.post(post_url,
      { ajax: '1',
		number: number,
		start: counter,
		userid: collection_owner,
		tags: tags,
		async: false
	  },

      // when the Web server responds to the request
      function(result) {
	
		var ajaxreturn = eval(result);
		var header = ajaxreturn[0];
		var articles = ajaxreturn[1];
		
			if(append==0) {	        	
				$('ul#tiles').html(articles);
				
				if(MY_USER != 0 && collection_owner == MY_USER) {
					pressMyNewsButton();
				}
				
				if(collection_owner != 0) $("div#back_wall").css('display', 'inline');
				else $("div#back_wall").css('display', 'none');

				$('div.main_message').text(header);
				onResizeEnd();
				
				hideTagsOn = 0;
				$(window).scrollTop(0);
				resetScrollStart(0);
				
			} else {
				$('ul#tiles').append(articles);
			}
			
			//Set current view variables				
			CURRENT_VIEW_USER = collection_owner;
			CURRENT_VIEW_TAGS = tags;
			
			if(newLoad) updateTags(tags);
	        call_wookmark();
		});

	return 1;
	
}

function getCounter(newLoad) {
	
	if(newLoad) {
		counter = 0;		
		//Remove any pre-existing popups
		closePopups();
	} else {
		counter =  $("ul#tiles li").length;
	}
	
	return counter;
}

function isNewUser(collection_owner) {
	
	//CHECK IF REQUESTED VIEW IS IDENTICAL TO CURRENT VIEW, AND SET newLoad VALUE ACCORDINGLY
	var newLoad = (CURRENT_VIEW_USER != collection_owner) ? 1 : 0 ;
	
	return newLoad;
}

function pressMyNewsButton() {
	$('.collection_button').css("background-position-y", "-68px");
	$("a.mynews").attr("onclick", "unpressMyNewsButton(); get_collection(); return false;");
}

function unpressMyNewsButton() {
	$('.collection_button').css("background-position-y", "0px");
	$("a.mynews").attr("onclick", "get_collection(0, "+MY_USER+", '', 0); return false;");
}

var scrollBuffer = 800;
var success = 1;
var currentDocHeight = 0;
var scrollStart = 0;
var hideTagsOn = 1;

$(document).ready(function() {
	resetScrollStart(2000);
	
	$(window).scroll(function () {
		getContent();
		//hideTagsOnScroll();
	});
	
	addLoadingHandler();
});

function resetScrollStart(delay) {
	delay = (typeof delay !== 'undefined') ? delay : 1000;
	hideTagsOn = 0;
	setTimeout("resetScroll()", delay);
}
function resetScroll() {
	scrollStart = $(window).scrollTop();
	hideTagsOn = 1;
}
function hideTagsOnScroll() {
	
	if(hideTagsOn == 0) return false;
	
	scrollAbsDistance = Math.abs(scrollStart - $(window).scrollTop());
	
	if(scrollAbsDistance > 500) {
		toggleTags("hide");
	}
}
function getContent() {
	   if ($(document).height() != currentDocHeight && $(document).height() - $(window).scrollTop() - $(window).height() < scrollBuffer && success == 1)
		{
			currentDocHeight = $(document).height();
			success = 0;		
			success = get_collection(20, CURRENT_VIEW_USER, CURRENT_VIEW_TAGS, 1);
		}
}
function addLoadingHandler() {
	$("div#loading_symbol").ajaxStart(function() {
		$(this).css("display", "inline");
	});
	$("div#loading_symbol").ajaxStop(function() {
		$(this).css("display", "none");
	});
}
function add_tag(tag) {
	if(success==0) return false;
	success=0;
	
	if (first == 1 || CURRENT_VIEW_TAGS == '') {
		var tags = tag;
		first = 0;
	} else var tags = CURRENT_VIEW_TAGS+","+tag;
	
	//Get tagged articles
	success = get_collection(20, CURRENT_VIEW_USER, tags, 0);
	
	var tag_link = $("a#tag_"+tag);
	
	tag_link.attr("onclick","remove_tag("+tag+"); return false;");
	tag_link.addClass("clicked_tag");
	
	updateTags(tags);
	
	CURRENT_VIEW_TAGS = tags;
	return success;
}

var tag_html = "";

function updateTags(current_tags) {
	
	markTagsBusy();
	
 	if (typeof current_tags === 'undefined') return false;
	
	if(current_tags == "" && CURRENT_VIEW_USER == 0) {
		$("div#tags_box").html(DEFAULT_TAG_HTML);
		return 1;
	}
	
	var post_url = BASEURL+'wall_ajax/get_available_tags';
	var tags_box = $("div#tags_box");
	
	$.post(post_url,
      { ajax: '1',
		tags: current_tags,
		userid: CURRENT_VIEW_USER
	  },

      // when the Web server responds to the request
      function(result) {
		
		tags_box.html(result);
	  });
	
	return 1;
}

function remove_tag(tag) {
	
	if(success==0) return false;
	success=0;
	
	if (CURRENT_VIEW_TAGS == '') return false;
	
	if(CURRENT_VIEW_TAGS == tag) var tags = '';
	else {
		var tags = CURRENT_VIEW_TAGS;
		
		var re1 = new RegExp(tag+",", "g");
		var re2 = new RegExp(","+tag+"$", "g");
		tags = tags.replace(re1, "");
		tags = tags.replace(re2, "");
	}
	
	success = get_collection(20, CURRENT_VIEW_USER, tags, 0);
	
	tag_link = $("a#tag_"+tag);
	
	tag_link.attr("onclick","add_tag("+tag+"); return false;");
	tag_link.removeClass("clicked_tag");
	
	updateTags(tags);
	
	CURRENT_VIEW_TAGS = tags;
	return success;
}

function clear_tags() {
	
	if(success==0) return false;
	success=0;
	
	if (CURRENT_VIEW_TAGS == '') return false;
	
	var tags = '';
	
	success = get_collection(20, CURRENT_VIEW_USER, tags, 0);
	
	CURRENT_VIEW_TAGS = tags;
	return success;
}

function markTagsBusy() {
	all_tags = $("a.tag_link");
	
	all_tags.addClass("tag_link_busy");
	all_tags.unbind('click');
	all_tags.attr('onclick', 'javascript:void(0);');
}

function toggleTags(specific_action) {
	
	specific_action = (typeof specific_action !== 'undefined') ? specific_action : 'none';
	
	var tags = $("div#tags_area");
	btn_a = $("a.tags_toggle_btn");
	// var min_margin = 20;
	// min_topspace = min_margin + $("div#topContainer").outerHeight();
	// max_topspace = min_topspace + tags.outerHeight();
	
	if(tags.is(":visible") && specific_action != "show") {
		tags.slideUp('300');
		setTimeout('btn_a.text("Show topic tags")', 500);
		//setTimeout('$("div.main_section").css("margin-top", min_topspace+"px")', 300);
	} else if(specific_action != "hide") {
		tags.slideDown('300');
		setTimeout('btn_a.text("Hide topic tags")', 500);
		//$("div.main_section").css("margin-top", max_topspace+"px");
		
		//scrollCheck is set to prevent tags menu from disappearing with only a slight scroll once it's been opened by the user
		resetScrollStart(0);
	}
}


