<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Twitter Tools v0.2</title>
	<link rel="icon" href="favicon.ico" />
	<link type="text/css" rel="stylesheet" href="../blueprint/screen.css" media="screen, projection" />
	<link type="text/css" rel="stylesheet" href="../blueprint/print.css" media="print" />
	<!--[if lt IE 8]><link rel="stylesheet" href="../blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
	<link type="text/css" rel="stylesheet" href="../css/style.css" media="screen" />

	<script src="./js/jquery-1.4.2.min.js" type="text/javascript"></script>
	<script src="./js/jquery.validate.pack.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#tt_data").validate();
	});
	</script>
	</head>
	<body>
		<div class="status">
			<?php 
			if (isset($menu)) {
				print $menu;
			}
			if (isset($status_text)) {
				print '<h3>'.$status_text.'</h3>';
			}	
			print($content);		
			?>
		</div>
		<?php
			if (isset($twitterTools)) {
				print $twitterTools;
			}
		?>
	</body>
</html>
