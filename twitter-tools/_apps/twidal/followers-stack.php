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

$submission = $_POST['sub_id'];
$screen_names = $_POST['screen_name'];
$date = $_POST['date'];

print "Current Submission: $submission<br>";

$followers = $connection->get('followers/ids', array('cursor' => -1));
$followerIds = array();

var_dump($followerIds);

foreach ($followers->ids as $i => $id) {
    $followerIds[] = $id;
    if ($i == 99) break; // Deal with only the latest 100 followers.
}