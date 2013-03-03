//Article comments text resizing on entry code (Fabio Berger)
function fontSmaller(element) {
	element.style.fontSize = "10px";
	element.style.color = "#4c4c4c";
}	
function fontLarger(element) {
	element.style.fontSize = "14px";
	element.style.color = "#AAA";
}

//Top bar width consistency code (SJPour)
$(document).ready(function() {
	onResizeEnd();
});

//Show buttons on Article hover
function showButtons(newsid) {
	var liftId = '#lift'+newsid;
	$(liftId).show();
	
	
	var revealId = '#reveal'+newsid;
	$(revealId).show();
	
}

//Hide buttons on Article blur
function hideButtons(newsid) {
	var liftId = '#lift'+newsid;
	if($(liftId).hasClass('lift')) {
		$(liftId).hide();
	}
	
	var revealId = '#reveal'+newsid;
	$(revealId).hide();
	
}

// //Top bar dropping message and logo depending on window width space availability (SJPOUR)
// $(document).ready(function() {
// 	$(window).resize(function() {
// 		$("div.pageHeader").width($(window).width());
// 
// 		//checkMessageSpace();
// 		checkLogoSpace();
// 
// 	});
// });

$(document).ready(function() {
	var rtime = new Date(1, 1, 2000, 12,00,00);
	var timeout = false;
	var delta = 200;
	$(window).resize(function() {	
	    rtime = new Date();
	    if (timeout === false) {
	        timeout = true;
	        setTimeout(resizeend, delta);
	    }
	});

	function resizeend() {
	    if (new Date() - rtime < delta) {
	        setTimeout(resizeend, delta);
	    } else {
	        timeout = false;
	        
			onResizeEnd();
	    }               
	}
});

function readjustTopMargin() {
	var tags = $("div#tags_area");
	var main = $("div.main_section");
	var min_margin = 20;
	min_topspace = min_margin + $("div#topContainer").outerHeight();
	max_topspace = min_topspace + tags.outerHeight();
	
	main.css("margin-top", max_topspace+"px");
}

function onResizeEnd() {
	$("div.pageHeader").width($(window).width());
	checkMessageSpace();
	checkLogoSpace();
	checkFooterSpace();
	readjustTopMargin();
}

function checkMessageSpace() {
	
	var HeaderWidth = getHeaderWidth();
	
	if(!$("div.logo").is(":visible")) return false;
		
	var hasSpace = ($(window).width() - HeaderWidth > 0) ? true : false;
	var hasMessageSpace = ($(window).width() - HeaderWidth - $("div.message_bar").outerWidth() > 0) ? true : false;
	
	if($("div.message_bar").is(":visible") && !hasSpace) {
		$("div.message_bar").hide();
		return "hidden";
	}
	
	if (!$("div.message_bar").is(":visible") && hasMessageSpace) {
		$("div.message_bar").show();
		realignMessageBar();
		return "shown";
	}
	
	if($("div.message_bar").is(":visible")) {
		realignMessageBar();
		return "shown";
	}
}

function checkLogoSpace() {
	
	if($("div.message_bar").is(":visible")) return false;
	
	var HeaderWidth = getHeaderWidth();
		
	var hasSpace = ($(window).width() - HeaderWidth > 0) ? true : false;
	var hasLogoSpace = ($(window).width() - HeaderWidth - $("div.logo").outerWidth() > 0) ? true : false;
	
	if (!$("div.logo").is(":visible") && hasLogoSpace) {
		$("div.logo").show();
		return "shown";
	}
	
	if($("div.logo").is(":visible") && !hasSpace) {
		$("div.logo").hide();
		return "hidden";
	}
		
}

function getHeaderWidth() {
	var neededSpace = 0;
	
	$("div.pageHeader").children().each( function(){ 
		if($(this).is(":visible") && $(this).attr("id") != "loading_symbol") {
			neededSpace += $(this).outerWidth();
		} 
	});
	
	return neededSpace;
}

function checkFooterSpace() {
	
	if($("div.footer").outerWidth() > $(window).width()) {
		$("div.footer").css('width', $(window).width());
		$("div.footer").css('overflow', 'hidden');
	}
	
	return true;
}

function realignMessageBar() {
	if (!$("div.message_bar").is(":visible") || $.trim($("div.message_bar").text()) == "") return false;
	var HeaderWidth = getHeaderWidth();
	var left_margin = ($("div.pageHeader").width() - HeaderWidth)/2;
	$("div.message_bar").css('left', left_margin);
}