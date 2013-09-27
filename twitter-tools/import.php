<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Twitter Tools v0.1</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link rel="icon" href="favicon.ico" />
		<link type="text/css" rel="stylesheet" href="../css/style.css" />
	</head>
	<body>
	<?php
	if(isset($_POST['submit']))
	{
	    $client_name = $_POST['client'];
	    $screen_names = $_POST['screen_names'];
	    $date = $_POST['date'];

	    $values = explode(",", $screen_names);
	    
	    //breakout the ids into blocks of 100
		foreach ($values as $value) {
		    if ($count < 100) {
		        $tmp_handles .= $value . ',';
		        $count++;
		        print 'inserting new screen name ' . $value . '<br>';
				include('con.php');
			    $query = "INSERT INTO `master_tw`.`DoubleThink` (`client_name`, `Twitter Profile`, `timestamp`) 
				            	VALUES (
				            		'".$client_name."', 
				            		'".mysql_real_escape_string($value)."', 
				            		'".$date."')";
		        	
				$result = mysql_query($query);
				if (!$result)
				print 'Error' . mysql_error();
		    } elseif ($count == 100) {
		        $handles[] = $tmp_handles;
		        $count = 0;
		        $tmp_handles = '';
		        print 'hit 100 resetting <br>';
		    }
		}
	}
?>
		<form  action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
			<h1>Insert screen names in DB</h1>
			<input type="text" name="client" placeholder="Client Name"><br>
			<textarea name="screen_names" placeholder="Insert comma separted screen names"></textarea>
			<input type="hidden" name="date" value="<?php print (date("Y/m/d, h:s A")) ?>"> <br />
			<input type="submit" name="submit" value="Submit"><br>
		</form>
	</body>
</html>
