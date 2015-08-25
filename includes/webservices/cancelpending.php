<?php

require_once("../initialize.php");

$response = "";

if(isset($_GET['schoolid']) || isset($_GET['batchid']) || isset($_GET['sectionid']))
{
	if(isset($_GET['schoolid']))
	{
		if(SchoolUser::userExists($session->user_id, $_GET['schoolid']))
		{
			$object = SchoolUser::getUser($session->user_id, $_GET['schoolid']);
			$object->pending == 0;
			$object->update();
			$response = "success";
		}
		else
		{
			$response = "Error";
		}
	}
	else if(isset($_GET['batchid']))
	{
		if(BatchUser::userExists($session->user_id, $_GET['batchid']))
		{
			$object = BatchUser::getUser($session->user_id, $_GET['batchid']);
            $object->update();
			$response = "success";
		}
		else
		{
			$response = "Error";
		}
	}
	else if(isset($_GET['sectionid']))
	{
		if(SectionUser::userExists($session->user_id, $_GET['sectionid']))
		{
			$object = SectionUser::getUser($session->user_id, $_GET['sectionid']);
			$object->update();
			$response = "success";
		}
		else
		{
			$response = "Error";
		}
	}
	else if(isset($_GET['clubid']))
	{
		if(ClubUser::userExists($session->user_id, $_GET['clubid']))
		{
			$object = ClubUser::getUser($session->user_id, $_GET['clubid']);
			$object->update();
			$response = "success";
		}
		else
		{
			$response = "Error";
		}
	}
	else if(isset($_GET['groupid']))
	{
		if(GroupUser::userExists($session->user_id, $_GET['groupid']))
		{
			$object = GroupUser::getUser($session->user_id, $_GET['groupid']);
			$object->update();
			$response = "success";
		}
		else
		{
			$response = "Error";
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