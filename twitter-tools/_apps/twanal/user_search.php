<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Twanal 1.0</title>
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
		</style>	
	</head>
	<body>
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

$screen_names = $_POST['screen_name'];
$date = $_POST['date'];

$values = explode(",", $screen_names);

//breakout the ids into blocks of 100
foreach ($values as $value) {
    if ($count < 100) {
        $tmp_handles .= $value . ',';
        $count++;
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

        if ($protected == "true") {
        	$protected = "Protected";
        } else {
	        $protected = "Public";	        
        }

        print "<ul>
        		<li><strong>Submission ID:</strong> " . $submission . "</li>
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
?>
	</body>
</html>