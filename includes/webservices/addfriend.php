<?php 

require_once("../initialize.php");

if(isset($_GET['touserid']))
{
	$touserid = $_GET['touserid'];

	$friendship 			= new Friend();
	$friendship->userid 	= $session->user_id;
	$friendship->touserid 	= $touserid;
	$friendship->create();

	$notification 				= new Notification();
	$notification->fromuserid 	= $session->user_id;
	$notification->touserid 	= $touserid;
	$notification->itemid 		= $friendship->id;
	$notification->itemtype 	= "friend";
	$notification->create();

	echo "success";
}
else
{
	echo "error";
}

?>