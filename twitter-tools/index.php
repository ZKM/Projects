<?php
session_start();
if (!isset($_SESSION['username'])) {
header('Location: login.php');
}
else {
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}

/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$rate_limit = $connection->get('account/rate_limit_status');
$user = $connection->get('account/verify_credentials');
$screen_name = $user->screen_name;

$content = "Logged in: <strong>" . $screen_name . "</strong> <em><a href='./clearsessions.php'>Clear Session</a></em>"; 
$twitterTools = "
	<div class='container'>
		<div class='forms'>
			<form id='tt_data' name='tt_data' action='./tt_data.php' method='POST'> 
				<div class='req frm_data'>
					<label for='frm_title'>Client Name:</label> <br>
					<input id='frm_title' class='required' type='text' name='frm_title' placeholder='i.e. Team Zelo Report'>
				</div>
				<div class='req frm_data'>
					<label for='frm_email'>Enter Your Email:</label> <br>
					<input id='frm_email' class='required' type='text' name='frm_email' placeholder='i.e. yourname@zocalogroup.com'>
				</div>
				<div id='twanal' class='frm_data'>
					<label for='sn_info'>Enter Comma Separated Screennames:</label> <br>
					<textarea id='sn_info' name='screen_names' placeholder='i.e. zocalogroup, nissan, zkm, ketchum, womma'></textarea>
					<input type='hidden' name='date' value='" . date('Y/m/d, h:s A') . "'>
				</div>
				<div id='twollers' class='frm_data'> 
					<label for='follower_search'>Follower Search:</label> <br>
					<input id='follower_search' type='text' name='screen_name' placeholder='i.e. zocalogroup'>
					<input type='hidden' name='date' value='" . date('Y/m/d, h:s A') . "'>
				</div>
				<input class='redButton' type='submit' name='submit' value='GSD' />
			</form> 
		</div>
	</div>";
		
if ($hasError = false){
	//print "no errors";
	header('Location: ./tt_data.php');
}

/* Include HTML to display on the page */
include('html.inc');

}