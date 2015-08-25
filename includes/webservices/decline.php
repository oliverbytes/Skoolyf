<?php 

require_once("../initialize.php");

if(
	isset($_GET['itemid']) && $_GET['itemid'] != '' &&
	isset($_GET['itemtype']) && $_GET['itemtype'] != '' &&
	isset($_GET['touserid']) && $_GET['touserid'] != '' &&
	isset($_GET['notificationid']) && $_GET['notificationid'] != ''
	)
{
	$itemtype 			= $_GET['itemtype'];
	$itemid 			= $_GET['itemid'];
	$touserid 			= $_GET['touserid'];
	$notificationid 	= $_GET['notificationid'];

	$notification = new Notification();

	if($itemtype == "friend")
	{
		$object = Friend::get_by_id($itemid);
		$object->delete();
	}
	else if($itemtype == "schooluser")
	{
		$object = SchoolUser::get_by_id($itemid);
		$object->delete();
	}
	else if($itemtype == "batchuser")
	{
		$object = BatchUser::get_by_id($itemid);
		$object->delete();
	}
	else if($itemtype == "sectionuser")
	{
		$object = SectionUser::get_by_id($itemid);
		$object->delete();
	}
	else if($itemtype == "clubuser")
	{
		$object = ClubUser::get_by_id($itemid);
		$object->delete();
	}
	else if($itemtype == "groupuser")
	{
		$object = GroupUser::get_by_id($itemid);
		$object->delete();
	}

	$lastnotification = Notification::get_by_id($notificationid);
	$lastnotification->delete();

	echo "success";
}
else
{
	echo "error";
}

?>