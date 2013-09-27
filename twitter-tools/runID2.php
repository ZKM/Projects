<?php
/* Load required lib files. */
session_start();
date_default_timezone_set('America/Chicago');
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

$dbname  = "master_tw";
$dbtable = "master_dt";

/* TEST CASE */
include('con.php');
$zeroSet = "SELECT `processed` FROM `" . $dbtable . "` ORDER BY `processed` ASC LIMIT 1";
//$zeroSet ="SELECT `Valid` FROM `".$dbtable."` ORDER BY `Valid` ASC LIMIT 1";
$zeroSetResult = mysql_query($zeroSet) or die('Error, query failed');

$zeroArray = (mysql_fetch_array($zeroSetResult));
//print_r($zeroArray);

if ($zeroArray['processed'][0] == 0) {
//if($zeroArray['Valid'][0] == 0){
    
    /* Get user access tokens out of the session. */
    $access_token = $_SESSION['access_token'];
    //$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, access_token, access_token_secret);
    $connection   = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $content      = $connection->get('account/rate_limit_status');
    $user         = $connection->get('account/verify_credentials');
    $screen_name  = $user->screen_name;
    
    print "Logged in: <strong>" . $screen_name . "</strong> <br>";
    print "Current API hits remaining: {$content->remaining_hits}\n<br>";
    
    $query = "SELECT `twitter_id`  FROM `" . $dbtable . "` WHERE `processed` !=1 LIMIT 0, 50";
    //$query = "SELECT `twitter_id`  FROM `".$dbtable."` WHERE `Valid` !=1 LIMIT 0, 500";
    
    $result = mysql_query($query);
    if (!$result) {
        $message = 'Invalid query: ' . mysql_error() . "\n";
        $message .= 'Whole query: ' . $query;
        die($message);
    }
    
    $screen_names = array();
    function trim_value(&$value)
    {
        $value = trim($value);
    }
    
    while ($row = mysql_fetch_assoc($result)) {
        $screen_names[] = $row['twitter_id'];
    }
    
    $screen_names = implode(',', $screen_names);
    $date         = date("Y/m/d, h:s A");
    
    $values = explode(",", $screen_names);
    
    //breakout the ids into blocks of 100
    foreach ($values as $value) {
        if ($count < 100) {
            $tmp_handles .= $value . ',';
            $count++;
            print 'adding new handle ' . $value . '<br>';
            
            $query2  = "UPDATE `" . $dbname . "`.`" . $dbtable . "` SET `processed` = '1' 
      WHERE `" . $dbTable . "`.`twitter_id` = '" . mysql_real_escape_string($value) . "'";
            $result2 = mysql_query($query2);
            
            
        } elseif ($count == 100) {
            $handles[]   = $tmp_handles;
            $count       = 0;
            $tmp_handles = '';
            print 'hit 100 resetting <br>';
        }
    }
    
    if (strlen($tmp_handles) != 0) {
        $handles[] = $tmp_handles;
        print 'setting remainder handles <br>';
    }
    
    
    
    /* LOOPING Through Twitter Names */
    foreach ($handles as $handle_string) {
        //$usercontent = $connection->get('users/lookup', array('screen_name' => $handle_string));
        $usercontent = $connection->get('users/lookup', array(
            'user_id' => $handle_string
        ));
        foreach ($usercontent as $user) {
            $twitter_id       = $user->id; //twitter ID
            $protected        = $user->protected; //public or private account
            $screen_name      = $user->screen_name; //screen name
            $name             = $user->name; //real name
            $description      = $user->description; //description
            $location         = $user->location; //location
            $url              = $user->url; //profile URL
            $followers_count  = $user->followers_count; //number of followers
            $friends_count    = $user->friends_count; //number of following
            $num_tweets       = $user->statuses_count; //number of updates
            $time_zone        = $user->time_zone; //time zone
            $account_creation = $user->created_at; //created twitter account
            $last_tweet_date  = $user->status->created_at; //last tweet date
            if ($protected == "true") {
                $protected = "Protected";
            } else {
                $protected = "Public";
            }
            $u = "UPDATE  `" . $dbname . "`.`" . $dbtable . "` SET 
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
        	
        	WHERE `" . $dbtable . "`.`twitter_id` = '" . mysql_real_escape_string($twitter_id) . "';";
            
            $r = mysql_query($u);
            
            if (!$r) {
                $message = 'Invalid query: ' . mysql_error() . "\n";
                $message .= 'Whole query: ' . $u;
                die($message);
            }
            echo "SUCCESS!" . "\n";
        }
    }
    mysql_close();
    
    
} else {
    print "nothing to do here<br>" . date("Y/m/d, h:s A") . "<br />";
    echo "<a href='http://twitter-tools.zgresources.com/download.php'>download to excel</a>";
    $to      = "zschneider@zocalogroup.com, ";
    $subject = 'Twitter Tools - Submitted';
    $message = 'Everything has been processed' . "\r\n" . 'Submitted: ' . date("Y/m/d, h:s A") . "http://twitter-tools.zgresources.com/download.php";
    $headers = 'From: no_reply@zgresources.com' . "\r\n" . 'Reply-To: no_reply@zgresources.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);
}