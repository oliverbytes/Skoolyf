<?php

require_once("../initialize.php");

$response = "";

if((
	isset($_GET['schoolid']) || 
	isset($_GET['batchid']) || 
	isset($_GET['sectionid'])) && 
	isset($_GET['userid']))
{
	$user = User::get_by_id($_GET['userid']);

	if(isset($_GET['schoolid']))
	{
		if(!SchoolUser::userExists($user->id, $_GET['schoolid']))
		{
			$school = School::get_by_id($_GET['schoolid']);

			$object           = new SchoolUser();
			$object->schoolid = $school->id;
			$object->userid   = $user->id;
			$object->level    = 0;
			$object->role     = "student";
			$object->enabled  = 1;
			$object->pending  = 1;
			$object->create();

			$notification 				= new Notification();
			$notification->fromuserid 	= $session->user_id;
			$notification->touserid 	= $user->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "schooluser";
			$notification->title 		= "Invites you";
			$notification->create();

			$response = "success";
		}
		else
		{
			$theuser = SchoolUser::getUser($user->id, $_GET['schoolid']);

			if($theuser->pending == 0)
			{
				$response = "This user is already a member.";
			}
			else
			{
				$response = "This user is already pending.";
			}
		}
	}
	else if(isset($_GET['batchid']))
	{
		if(!BatchUser::userExists($user->id, $_GET['batchid']))
		{
			$batch 	= Batch::get_by_id($_GET['batchid']);
			$school = School::get_by_id($batch->schoolid);

			$object           = new BatchUser();
            $object->schoolid = $school->id;
            $object->batchid  = $batch->id;
            $object->userid   = $user->id;
            $object->level    = 0;
            $object->role     = "student";
            $object->enabled  = 1;
            $object->pending  = 1;
            $object->create();

            $notification 				= new Notification();
			$notification->fromuserid 	= $session->user_id;
			$notification->touserid 	= $user->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "batchuser";
			$notification->title 		= "Invites you";
			$notification->create();

			$response = "success";
		}
		else
		{
			$theuser = BatchUser::getUser($user->id, $_GET['batchid']);

			if($theuser->pending == 0)
			{
				$response = "This user is already a member.";
			}
			else
			{
				$response = "This user is already pending.";
			}
		}
	}
	else if(isset($_GET['sectionid']))
	{
		if(!SectionUser::userExists($user->id, $_GET['sectionid']))
		{
			$section 	= Section::get_by_id($_GET['sectionid']);
			$batch 		= Batch::get_by_id($section->batchid);
			$school 	= School::get_by_id($batch->schoolid);

			$object           = new SectionUser();
			$object->userid   = $user->id;
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
			$notification->touserid 	= $user->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "sectionuser";
			$notification->title 		= "Invites you";
			$notification->create();

			$response = "success";
		}
		else
		{
			$theuser = SectionUser::getUser($user->id, $_GET['sectionid']);

			if($theuser->pending == 0)
			{
				$response = "This user is already a member.";
			}
			else
			{
				$response = "This user is already pending.";
			}
		}
	}
	else if(isset($_GET['clubid']))
	{
		if(!ClubUser::userExists($user->id, $_GET['clubid']))
		{
			$club 	= Club::get_by_id($_GET['clubid']);

			$object           = new ClubUser();
			$object->userid   = $user->id;
			$object->clubid   = $club->id;
			$object->level    = 0;
			$object->role     = "student";
			$object->enabled  = 1;
			$object->pending  = 1;
			$object->create();

			$notification 				= new Notification();
			$notification->fromuserid 	= $session->user_id;
			$notification->touserid 	= $user->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "clubuser";
			$notification->title 		= "Invites you";
			$notification->create();

			$response = "success";
		}
		else
		{
			$theuser = ClubUser::getUser($user->id, $_GET['clubid']);

			if($theuser->pending == 0)
			{
				$response = "This user is already a member.";
			}
			else
			{
				$response = "This user is already pending.";
			}
		}
	}
	else if(isset($_GET['groupid']))
	{
		if(!GroupUser::userExists($user->id, $_GET['groupid']))
		{
			$group 	= Group::get_by_id($_GET['groupid']);

			$object           = new GroupUser();
			$object->userid   = $user->id;
			$object->groupid   = $group->id;
			$object->level    = 0;
			$object->role     = "student";
			$object->enabled  = 1;
			$object->pending  = 1;
			$object->create();

			$notification 				= new Notification();
			$notification->fromuserid 	= $session->user_id;
			$notification->touserid 	= $user->id;
			$notification->itemid 		= $object->id;
			$notification->itemtype 	= "groupuser";
			$notification->title 		= "Invites you";
			$notification->create();

			$response = "success";
		}
		else
		{
			$theuser = GroupUser::getUser($user->id, $_GET['groupid']);

			if($theuser->pending == 0)
			{
				$response = "This user is already a member.";
			}
			else
			{
				$response = "This user is already pending.";
			}
		}
	}

	$log = new Log($session->user_id, $clientip, "WEB", "INVITED: ".$user->id); $log->create();
}
else
{
	$response = "error";
}

echo $response;

?>