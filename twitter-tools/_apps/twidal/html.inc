<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Twidal .01</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link rel="icon" href="favicon.ico" />
		<style type="text/css">
			input, textarea {width: 200px;}
			form {width:200px; margin: 50px auto; text-align:center;}
			img {border-width: 0; display: block;}
			img:hover {opacity: .7;}
			a {color: #FF0000; text-decoration: none;}
			a:hover {text-decoration: underline;}
			em {font-size: 80%; text-transform: lowercase; font-style: normal;}
			
			.redButton {
				-moz-box-shadow:inset 0px 1px 0px 0px #f29c93;
				-webkit-box-shadow:inset 0px 1px 0px 0px #f29c93;
				box-shadow:inset 0px 1px 0px 0px #f29c93;
				background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fe1a00), color-stop(1, #ce0100) );
				background:-moz-linear-gradient( center top, #fe1a00 5%, #ce0100 100% );
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fe1a00', endColorstr='#ce0100');
				background-color:#fe1a00;
				-moz-border-radius:6px;
				-webkit-border-radius:6px;
				border-radius:6px;
				border:1px solid #d83526;
				display:inline-block;
				color:#ffffff;
				font-family:arial;
				font-size:15px;
				font-weight:bold;
				padding:6px 24px;
				text-decoration:none;
				text-shadow:1px 1px 0px #b23e35;
			}
				.redButton:hover {text-decoration: none; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ce0100), color-stop(1, #fe1a00) ); background:-moz-linear-gradient( center top, #ce0100 5%, #fe1a00 100% ); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ce0100', endColorstr='#fe1a00'); background-color:#ce0100;}
				.redButton:active {position:relative; top:1px;}
				
		</style>
	</head>
	<body>
	<?php 
		if (isset($menu)) {
			echo $menu;
		}
		if (isset($status_text)) {
			echo '<h3>'.$status_text.'</h3>';
		}
		

		print($content);
		
		//Submit ID Counter
		include('con.php'); 
		$sub_id_query = mysql_query('SELECT submission_id FROM user_search ORDER BY submission_id DESC LIMIT 1');
		mysql_close($con);

		if (!$sub_id_query) {
			//die('Could not query:' . mysql_error());
			$submit_id = 1;
		}
		else {
			$submit_id = mysql_result($sub_id_query, 0);
			$submit_id++;
		}
		
		print "Current Submission: $submit_id <br>";		
		?>

		<!-- FORM that collects information from an Array of Screen Names 
		<form action="user_search.php" name="twanal_form" method="POST"> -->
			<!--<label for="user_search">User Search: <br>
			<textarea style="width: 200px; height: 300px;" name="screen_name"></textarea></label>-->
			<input type="hidden" name="date" value="<?php print(date("Y/m/d, h:s A")); ?>">
			<input type="hidden" name="sub_id" value="<?php print $submit_id; ?>">
<!--			<a class="redButton" href="javascript:document.twanal_form.submit();">GSD!</a> 
		</form> -->

	</body>
</html>
