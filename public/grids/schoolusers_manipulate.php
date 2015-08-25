<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$user 				= new SchoolUser();
	$user->pending 		= $_POST['pending'];
	$user->enabled 		= $_POST['enabled'];
	$user->schoolid 	= $_POST['schoolid'];
	$user->userid 		= $_POST['userid'];
	$user->level 		= $_POST['level'];
	$user->create();

	$log = new Log($session->user_id, $clientip, "WEB", "CREATED SCHOOLUSER: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='edit')
{
	$user 				= SchoolUser::get_by_id($_POST['id']);
	$user->pending 		= $_POST['pending'];
	$user->enabled 		= $_POST['enabled'];
	$user->schoolid 	= $_POST['schoolid'];
	$user->userid 		= $_POST['userid'];
	$user->level 		= $_POST['level'];
	$user->update();

	$log = new Log($session->user_id, $clientip, "WEB", "UPDATED SCHOOLUSER: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='del')
{
	$log = new Log($session->user_id, $clientip, "WEB", "DELETED SCHOOLUSER: ".$_POST['id']); $log->create();
	SchoolUser::get_by_id($_POST['id'])->delete();
}

?>