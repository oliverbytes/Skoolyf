<?php 

require_once("../initialize.php");

if(isset($_POST['comment']) && isset($_POST['itemid']) && isset($_POST['itemtype']))
{
	$comment 			= new Comment();
	$comment->comment 	= $_POST['comment'];
	$comment->userid 	= $session->user_id;
	$comment->itemid 	= $_POST['itemid'];
	$comment->itemtype 	= $_POST['itemtype'];
	$comment->create();

	// $notification 				= new Notification();
	// $notification->fromuserid 	= $session->user_id;
	// $notification->touserid 	= $touserid;
	// $notification->itemid 		= $friendship->id;
	// $notification->itemtype 	= "friend";
	// $notification->create();

	echo "success";
}
else
{
	echo "error";
}

?>