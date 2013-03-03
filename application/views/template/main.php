<?=$head;?>
<body>
	<!-- Beginning of FB Code -->
	<div id="fb-root"></div>
	    <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
	     <script type="text/javascript">
	       FB.init({
	         appId  : '<?=$document->fb_app_id; ?>',
	         status : true, // check login status
	         cookie : true, // enable cookies to allow the server to access the session
	         xfbml  : true  // parse XFBML
	       });

	     </script>
	<!-- End of FB Code	-->

<div id="mainContainer"> <!-- Open Main Container -->

<div class="container">
<header>
     <?=$top_bar;?>
</header>

<?=$content;?>
</div>

<?=$footer;?> 
</div>
<!-- Google Plus Code -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {parsetags: 'explicit'}
</script>
<!-- end Google plus code -->
<!-- start LinkedIn code -->
<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
<!-- end LinkedIn Code -->
<!-- Start Pinterest code -->
<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
<!-- end Pinterest code -->
</body>
</html>