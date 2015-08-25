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
		$object 			= Friend::get_by_id($itemid);
		$object->pending 	= 0;
		$object->update();

		$notification->title 		= "message";
		$notification->itemid 		= $itemid;
		$notification->itemtype 	= "friend";
	}
	else if($itemtype == "schooluser")
	{
		$object           = SchoolUser::get_by_id($itemid);
		$object->pending  = 0;
		$object->update();

		$notification->title 		= "message";
		$notification->itemid 		= $itemid;
		$notification->itemtype 	= "schooluser";
	}
	else if($itemtype == "batchuser")
	{
		$object           = BatchUser::get_by_id($itemid);
		$object->pending  = 0;
		$object->update();

		$notification->title 		= "message";
		$notification->itemid 		= $itemid;
		$notification->itemtype 	= "batchuser";
	}
	else if($itemtype == "sectionuser")
	{
		$object           = SectionUser::get_by_id($itemid);
		$object->pending  = 0;
		$object->update();

		$notification->title 		= "message";
		$notification->itemid 		= $itemid;
		$notification->itemtype 	= "sectionuser";
	}
	else if($itemtype == "clubuser")
	{
		$object           = ClubUser::get_by_id($itemid);
		$object->pending  = 0;
		$object->update();

		$notification->title 		= "message";
		$notification->itemid 		= $itemid;
		$notification->itemtype 	= "clubuser";
	}
	else if($itemtype == "groupuser")
	{
		$object           = GroupUser::get_by_id($itemid);
		$object->pending  = 0;
		$object->update();

		$notification->title 		= "message";
		$notification->itemid 		= $itemid;
		$notification->itemtype 	= "groupuser";
	}

	$notification->fromuserid 	= $session->user_id;
	$notification->touserid 	= $touserid;
	$notification->create();

	$lastnotification = Notification::get_by_id($notificationid);
	$lastnotification->pending = 0;
	$lastnotification->update();

	echo "success";
}
else
{
	echo "error";
}

?>