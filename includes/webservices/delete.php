<?php 

require_once("../initialize.php");

if(isset($_GET['notificationid']) && $_GET['notificationid'] != '')
{
	$notificationid = $_GET['notificationid'];

	$lastnotification = Notification::get_by_id($notificationid);
	$lastnotification->delete();

	echo "success";
}
else
{
	echo "error";
}

?>