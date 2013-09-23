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
$content = $connection->get('account/rate_limit_status');
$user = $connection->get('account/verify_credentials');
$screen_name = $user->screen_name;

print "Logged in: <strong>" . $screen_name . "</strong> <br>";
print "Current API hits remaining: {$content->remaining_hits}\n<br>";

$screen_name = $_POST['screen_name'];
//$date = date("Y/m/d, h:s A");
$date = $_POST['date'];

$usercontent = $connection->get('users/lookup', array('screen_name' => $screen_name));
foreach ($usercontent as $user) {
	$twitter_id = $user->id;
}
print "<strong>Twitter ID:</strong> " . $twitter_id . "<br>";

$method = "followers/ids/$twitter_id";

$cursor = -1;

include('con.php');
while ($cursor !=0) {
	$followers = $connection->get($method, array('cursor' => $cursor));
	foreach ($followers as $element) {
	    if ( is_array($element)) {
	        foreach ( $element as $sub_element) {
	            echo $sub_element . ", ";
	           /* $query = "INSERT INTO `twollers`.`DoubleThink` (`source`, `twitter_id`, `timestamp`) 
	            	VALUES (
	            		'". mysql_real_escape_string($screen_name) ."', 
	            		'". mysql_real_escape_string($sub_element) ."', 
	            		'". $date ."')";
        	
        	$result = mysql_query($query);
	        if (!$result)
            print 'Error' . mysql_error();*/
	        }
	    }
	}
	$cursor = $followers->next_cursor;
}
?>