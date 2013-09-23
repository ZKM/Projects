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

$submission = $_POST['sub_id'];
$screen_names = $_POST['screen_name'];
$date = $_POST['date'];

$rate_limit = $connection->get('account/rate_limit_status');
$user = $connection->get('account/verify_credentials');

$user_id = $user->id;

print "Current API hits remaining: {$rate_limit->remaining_hits}\n<br>";

print "Current Submission: $submission<br>";

print "CURRENT USER ID: " . $user_id ."<br>";

$method = "followers/ids/$user_id";
$results = $connection->get($method);

print_r($results);
