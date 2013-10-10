<?php
/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
//$content = $connection->get('account/rate_limit_status');
$user = $connection->get('account/verify_credentials');
$screen_name = $user->screen_name;

$dbname = "master_tw";
$dbtable = "master_dt";
				
print "Logged in: <strong>" . $screen_name . "</strong> <br>";
//print "Current API hits remaining: {$content->remaining_hits}\n<br>";

			// Data from FORM //
			if(isset($_POST['submit'])) {
				if(trim($_POST['frm_title']) == '') {
					$hasError = true;
				} else {
					$report_title = trim($_POST['frm_title']);
				}
			
				//Check to make sure that a valid email address is submitted
				if(trim($_POST['frm_email']) == '')  {
					$hasError = true;
				} else if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", trim($_POST['frm_email']))) {
					$hasError = true;
				} else {
					$report_email = trim($_POST['frm_email']);
				}
			
				$screen_name = $_POST['screen_name'];
				$screen_names = $_POST['screen_names'];
				$date = $_POST['date'];
			}
			

if ($screen_names == null) {
	//======== Twidal ========//
	// foreach ($usercontent as $user) {
	// 	$twitter_id = $user->id;
	// 	print "<strong>Twitter ID:</strong> " . $twitter_id . "<br>";
	// 	print "<strong>Title:</strong> " . $report_title ."<br>";	
	// 	print "<strong>Email:</strong> " . $report_email ."<br>";	
	// }

	//$method = "followers/ids/$twitter_id";
	$method = "followers/ids.json?cursor=-1&screen_name=" . $screen_name . "&count=10000";
	
	$cursor = -1;

	// Nissan
	// $cursor = 1445724334656947312;
	// $cursor = 1442351989753529513;
	// $cursor = 1438599641798563365;
	// $cursor = 1390571066095573256;

	// VMware
	// $cursor = 1357570478756687544;


	include('con.php');
	while ($cursor !=0) {
		$followers = $connection->get($method, array('cursor' => $cursor));
		echo "<pre>";
		print_r($followers);
		print_r("collecting..");
		echo "</pre>";
		//break;
		foreach ($followers as $element) {
		    if ( is_array($element)) {
		        foreach ( $element as $sub_element) {
		            echo $sub_element . ", ";

		            $twidal = "
					INSERT INTO `".$dbtable."` (`report_title`, `report_email`, `source`, `twitter_id`, `timestamp`) 
		            	VALUES (
		            		'". mysql_real_escape_string($report_title) ."', 
		            		'". mysql_real_escape_string($report_email) ."', 
		            		'". mysql_real_escape_string($screen_name) ."', 
		            		'". mysql_real_escape_string($sub_element) ."', 
		            		'". $date ."');";
	        	
		            $twidal_r = mysql_query($twidal);
					if (!$twidal_r) {
						$message_twi  = 'Invalid query: ' . mysql_error() . "\n";
						$message_twi .= 'Whole query: ' . $twidal;
					    die($message_twi);
					}

		        }
		    }
		}
		$cursor = $followers->next_cursor;
	}
} else {
	//======== Twanal ========//
	$values = explode(",", $screen_names);
	
	//breakout the ids into blocks of 100
	foreach ($values as $value) {
	    if ($count < 100) {
	        $tmp_handles .= $value . ',';
	        $count++;

	        include('con.php');
			$twanal = "INSERT INTO `".$dbtable."` (`report_title`, `report_email`, `source`, `processed`, `timestamp`) 
				VALUES (
					'". mysql_real_escape_string($report_title) ."', 
					'". mysql_real_escape_string($report_email) ."', 
					'". mysql_real_escape_string(trim($value)) ."',
					'1', 
					'". $date ."');";
            
            $twanal_r = mysql_query($twanal);
			if (!$twanal_r) {
				$message_twa  = 'Invalid query: ' . mysql_error() . "\n";
				$message_twa .= 'Whole query: ' . $twanal;
			    die($message_twa);
			}
			
	        print 'adding new handle ' . $value . '<br>';
	    } elseif ($count == 100) {
	        $handles[] = $tmp_handles;
	        $count = 0;
	        $tmp_handles = '';
	        print 'hit 100 resetting <br>';
	    }
	}
	
	if (strlen($tmp_handles) != 0) {
	    $handles[] = $tmp_handles;
	    print 'setting remainder handles <br>';
	}
	foreach ($handles as $handle_string) {
	
	    $usercontent = $connection->get('users/lookup', array('screen_name' => $handle_string));
	    foreach ($usercontent as $user) {
	        $twitter_id	 		= $user->id; 						//twitter ID
	        $protected	 		= $user->protected; 				//public or private account
	        $screen_name 		= $user->screen_name; 				//screen name
	        $name 				= $user->name; 						//real name
	        $description 		= $user->description; 				//description
	        $location 			= $user->location; 					//location
	        $url 				= $user->url; 						//profile URL
	        $followers_count 	= $user->followers_count;			//number of followers
	        $friends_count 		= $user->friends_count; 			//number of following
	        $num_tweets			= $user->statuses_count; 			//number of updates
	        $time_zone 			= $user->time_zone; 				//time zone
	        $account_creation 	= $user->created_at; 				//created twitter account
	        $last_tweet_date 	= $user->status->created_at; 		//last tweet date
	        $lang				= $user->status->lang;				//language
	
	        if ($protected == "true") {
	        	$protected = "Protected";
	        } else {
		        $protected = "Public";	        
	        }
	        
	        
	        
	        include('con.php');	        
	        $u = "UPDATE  `".$dbname."`.`".$dbtable."` SET 
	        	`Valid` = '1', 
	        	`twitter_id` = '" . mysql_real_escape_string($twitter_id) . "', 
	        	`account_type` = '" . mysql_real_escape_string($protected) . "', 
	        	`screen_name` = '" . mysql_real_escape_string($screen_name) . "', 
	        	`name` = '" . mysql_real_escape_string($name) . "', 
	        	`description` = '" . mysql_real_escape_string($description) . "', 
	        	`location` = '" . mysql_real_escape_string($location) . "', 
	        	`url` = '" . mysql_real_escape_string($url) . "', 
	        	`followers_count` = '" . mysql_real_escape_string($followers_count) . "', 
	        	`following_count` = '" . mysql_real_escape_string($friends_count) . "', 
	        	`num_tweets` = '" . mysql_real_escape_string($num_tweets) . "', 
	        	`time_zone` = '" . mysql_real_escape_string($time_zone) . "', 
	        	`creation_date` = '" . mysql_real_escape_string($account_creation) . "', 
	        	`last_tweet` = '" . mysql_real_escape_string($last_tweet_date) . "', 
	        	`timestamp` = '" . mysql_real_escape_string($date) . "' 
	        	
	        	WHERE `".$dbtable."`.`source` LIKE '" . mysql_real_escape_string($screen_name) . "';";
							
	        $r = mysql_query($u);

		if (!$r) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $u;
		    die($message);
		}

	        print "<ul>
	        		<li><strong>Title:</strong> " . $report_title . "</li>
	        		<li><strong>Submitted Email:</strong> " . $report_email . "</li>
	        		<li><strong>Twitter ID:</strong> " . $twitter_id . "</li>
	        		<li><strong>Account Type:</strong> " . $protected ."</li>
	        		<li><strong>Screen Name:</strong> " . $screen_name . "</li>
	        		<li><strong>Name:</strong> " . $name . "</li>
	        		<li><strong>Description:</strong> " . $description . "</li>
	        		<li><strong>Location:</strong> " . $location . "</li>
	        		<li><strong>URL:</strong> " . $url . "</li>
	        		<li><strong>Followers Count:</strong> " . $followers_count . "</li>
	        		<li><strong>Following Count:</strong> " . $friends_count . "</li>
	        		<li><strong>Number of Tweets:</strong> " . $num_tweets . "</li>
	        		<li><strong>Time Zone:</strong> " . $time_zone . "</li>
	        		<li><strong>Date Account was created:</strong> " . $account_creation . "</li>
	        		<li><strong>Last Day Tweeted:</strong> " . $last_tweet_date . "</li>
	        	</ul>";
	    }
	}
}


?>
