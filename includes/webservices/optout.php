<?php

require_once("../initialize.php");

$response = "";

$loggeduser = User::get_by_id($session->user_id);

if(isset($_GET['schoolid']) || isset($_GET['batchid']) || isset($_GET['sectionid']))
{
	if(isset($_GET['schoolid']))
	{
		$school = School::get_by_id($_GET['schoolid']);

		if(SchoolUser::userExists($loggeduser->id, $school->id))
		{
			$object = SchoolUser::getUser($loggeduser->id, $school->id);
			$object->delete();
			$response = "success";

			$notification 				= new Notification();
			$notification->fromuserid 	= $loggeduser->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "message";
			$notification->title 		= "Opted Out";
			
			$admins = SchoolUser::getAdmins($school->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}
		}
		else
		{
			$response = "Error";
		}
	}
	else if(isset($_GET['batchid']))
	{
		$batch = Batch::get_by_id($_GET['batchid']);

		if(BatchUser::userExists($loggeduser->id, $_GET['batchid']))
		{
			$object = BatchUser::getUser($loggeduser->id, $_GET['batchid']);
            $object->delete();
			$response = "success";

			$notification 				= new Notification();
			$notification->fromuserid 	= $loggeduser->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "message";
			$notification->title 		= "Opted Out";
			
			$admins = BatchUser::getAdmins($batch->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}
		}
		else
		{
			$response = "Error";
		}
	}
	else if(isset($_GET['sectionid']))
	{
		$section = Section::get_by_id($_GET['sectionid']);

		if(SectionUser::userExists($loggeduser->id, $_GET['sectionid']))
		{
			$object = SectionUser::getUser($loggeduser->id, $_GET['sectionid']);
			$object->delete();
			$response = "success";

			$notification 				= new Notification();
			$notification->fromuserid 	= $loggeduser->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "message";
			$notification->title 		= "Opted Out";
			
			$admins = SectionUser::getAdmins($section->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}
		}
		else
		{
			$response = "Error";
		}
	}
	else if(isset($_GET['clubid']))
	{
		$club = Club::get_by_id($_GET['clubid']);

		if(ClubUser::userExists($loggeduser->id, $_GET['clubid']))
		{
			$object = ClubUser::getUser($loggeduser->id, $_GET['clubid']);
			$object->delete();
			$response = "success";

			$notification 				= new Notification();
			$notification->fromuserid 	= $loggeduser->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "message";
			$notification->title 		= "Opted Out";
			
			$admins = ClubUser::getAdmins($club->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}
		}
		else
		{
			$response = "Error";
		}
	}
	else if(isset($_GET['groupid']))
	{
		$group = Group::get_by_id($_GET['groupid']);

		if(GroupUser::userExists($loggeduser->id, $_GET['groupid']))
		{
			$object = GroupUser::getUser($loggeduser->id, $_GET['groupid']);
			$object->delete();
			$response = "success";

			$notification 				= new Notification();
			$notification->fromuserid 	= $loggeduser->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "message";
			$notification->title 		= "Opted Out";
			
			$admins = GroupUser::getAdmins($group->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}
		}
		else
		{
			$response = "Error";
		}
	}

	$log = new Log($loggeduser->id, $clientip, "WEB", "JOINED"); $log->create();
}
else
{
	$response = "error";
}

echo $response;

?>