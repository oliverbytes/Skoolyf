<?php

require_once("../initialize.php");

$response = "";

if(
	isset($_GET['schoolid']) || 
	isset($_GET['batchid']) || 
	isset($_GET['sectionid']) || 
	isset($_GET['clubid']) || 
	isset($_GET['groupid'])
	)
{
	if(isset($_GET['schoolid']))
	{
		if(!SchoolUser::userExists($session->user_id, $_GET['schoolid']))
		{
			$school = School::get_by_id($_GET['schoolid']);

			$object           = new SchoolUser();
			$object->schoolid = $school->id;
			$object->userid   = $session->user_id;
			$object->level    = 0;
			$object->role     = "student";
			$object->enabled  = 1;
			$object->pending  = 1;
			$object->create();

			$notification 				= new Notification();
			$notification->fromuserid 	= $session->user_id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "schooluser";
			$notification->title 		= "Requests";
			
			$admins = SchoolUser::getAdmins($school->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}

			$response = "success";
		}
		else
		{
			$response = "Already Joined.";
		}
	}
	else if(isset($_GET['batchid']))
	{
		if(!BatchUser::userExists($session->user_id, $_GET['batchid']))
		{
			$batch 	= Batch::get_by_id($_GET['batchid']);
			$school = School::get_by_id($batch->schoolid);

			$object           = new BatchUser();
            $object->schoolid = $school->id;
            $object->batchid  = $batch->id;
            $object->userid   = $session->user_id;
            $object->level    = 0;
            $object->role     = "student";
            $object->enabled  = 1;
            $object->pending  = 1;
            $object->create();

            $notification 				= new Notification();
			$notification->fromuserid 	= $session->user_id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "batchuser";
			$notification->title 		= "Requests";
			
			$admins = BatchUser::getAdmins($batch->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}

			$response = "success";
		}
		else
		{
			$response = "Already Joined.";
		}
	}
	else if(isset($_GET['sectionid']))
	{
		if(!SectionUser::userExists($session->user_id, $_GET['sectionid']))
		{
			$section 	= Section::get_by_id($_GET['sectionid']);
			$batch 		= Batch::get_by_id($section->batchid);
			$school 	= School::get_by_id($batch->schoolid);

			$object           = new SectionUser();
			$object->userid   = $session->user_id;
			$object->schoolid = $school->id;
			$object->batchid  = $batch->id;
			$object->sectionid= $section->id;
			$object->level    = 0;
			$object->role     = "student";
			$object->enabled  = 1;
			$object->pending  = 1;
			$object->create();

			$notification 				= new Notification();
			$notification->fromuserid 	= $session->user_id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "sectionuser";
			$notification->title 		= "Requests";
			
			$admins = SectionUser::getAdmins($section->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}

			$response = "success";
		}
		else
		{
			$response = "Already Joined.";
		}
	}
	else if(isset($_GET['clubid']))
	{
		if(!ClubUser::userExists($session->user_id, $_GET['clubid']))
		{
			$club = Club::get_by_id($_GET['clubid']);

			$object           = new ClubUser();
			$object->userid   = $session->user_id;
			$object->clubid   = $club->id;
			$object->level    = 0;
			$object->role     = "student";
			$object->enabled  = 1;
			$object->pending  = 1;
			$object->create();

			$notification 				= new Notification();
			$notification->fromuserid 	= $session->user_id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "clubuser";
			$notification->title 		= "Requests";
			
			$admins = ClubUser::getAdmins($club->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}

			$response = "success";
		}
		else
		{
			$response = "Already Joined.";
		}
	}
	else if(isset($_GET['groupid']))
	{
		if(!GroupUser::userExists($session->user_id, $_GET['groupid']))
		{
			$group = Group::get_by_id($_GET['groupid']);

			$object           = new GroupUser();
			$object->userid   = $session->user_id;
			$object->groupid  = $group->id;
			$object->level    = 0;
			$object->role     = "student";
			$object->enabled  = 1;
			$object->pending  = 1;
			$object->create();

			$notification 				= new Notification();
			$notification->fromuserid 	= $session->user_id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "groupuser";
			$notification->title 		= "Requests";
			
			$admins = GroupUser::getAdmins($group->id);

			foreach ($admins as $admin)
			{
				$notification->touserid 	= $admin->userid;
				$notification->create();
			}

			$response = "success";
		}
		else
		{
			$response = "Already Joined.";
		}
	}

	$log = new Log($session->user_id, $clientip, "WEB", "JOINED"); $log->create();
}
else
{
	$response = "error";
}

echo $response;

?>