<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="index, follow" />
<meta name="keywords" content="Financial news, visually organized financial news, crowd-sourced news, the best financial news, finanical news aggregator, news on stocks, industry news, most popular finanical news, popular stock news" />
<meta name="rights" content="" />
<meta name="language" content="en-GB" />
<meta name="description" content="Find the best financial news online. See the days news visually organized by everyone's opinion. Create an account and start finding the news you love through people you respect." />
<meta name="google-site-verification" content="KU7IcBKivMaUgli3stwFbbtS6hx-xPM6wUnuv3F62VI" />
<!-- Facebook Meta Data -->
<?php echo $document->meta; ?>
<meta property="fb:app_id"      content="<?=$document->fb_app_id; ?>" />
<meta property="og:site_name" content="11secrets.com" />
<meta property="fb:admins" content="100002472157022" />
<!-- End Facebook Meta Data -->
<?php echo $document->title; ?>
<link href='http://fonts.googleapis.com/css?family=Rouge+Script' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Asap' rel='stylesheet' type='text/css'>
<link href="<?php echo base_url(); ?>resources/images/main/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
<link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/wookmark/reset.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/wookmark/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/main/main.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/main/top_bar.css" type="text/css">
<link rel="stylesheet" href='<?php echo base_url(); ?>resources/css/pages/article.css' type="text/css" media="screen, projection" /> 
<link rel="stylesheet" href='<?php echo base_url(); ?>resources/css/pages/reveal_permanent.css' type="text/css" media="screen, projection" /> 
<?php if($document->css != "") { ?>
<link rel="stylesheet" href='<?php echo base_url(); ?>resources/css/pages/<?=$document->css;?>' type="text/css" media="screen, projection" /> 
<?php } ?>
<script type="text/javascript">
//Default site variables
var BASEURL = '<?php echo base_url(); ?>'; var CURRENT_VIEW_USER = 0; var MY_USER = <?php echo $document->loggedin; ?>; var first = 1; var SOCIAL = 1;
</script>
<!-- Load Twitter Script -->
<script type="text/javascript" charset="utf-8">
  window.twttr = (function (d,s,id) {
    var t, js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return; js=d.createElement(s); js.id=id;
    js.src="//platform.twitter.com/widgets.js"; fjs.parentNode.insertBefore(js, fjs);
    return window.twttr || (t = { _e: [], ready: function(f){ t._e.push(f) } });
  }(document, "script", "twitter-wjs"));
</script>
<!-- End load twitter script -->
<script src="<?php echo base_url(); ?>resources/js/wookmark/jquery-1.7.1.min.js"></script>
<script src="<?php echo base_url(); ?>resources/js/wookmark/jquery.wookmark.js"></script>
<script src="<?php echo base_url(); ?>resources/js/wall/helpers.js"></script>
<script src="<?php echo base_url(); ?>resources/js/wall/popups.js"></script>
<script src="<?php echo base_url(); ?>resources/js/wall/lifts_comments.js"></script>
<script src="<?php echo base_url(); ?>resources/js/wall/styling.js"></script>
<script src="<?php echo base_url(); ?>resources/js/wall/collections.js"></script>
<script src="<?php echo base_url(); ?>resources/js/pages/article.js"></script>
<script src="<?php echo base_url(); ?>resources/js/wall/metrics.js"></script>
<script src="<?php echo base_url(); ?>resources/js/wall/social.js"></script>
<!-- Google Analytics start -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31573209-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- Google Analytics End -->
</head>

	
