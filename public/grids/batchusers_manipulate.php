<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$batchuser 				= new BatchUser();
	$batchuser->pending 	= $_POST['pending'];
	$batchuser->enabled 	= $_POST['enabled'];
	$batchuser->schoolid 	= $_POST['schoolid'];
	$batchuser->batchid 	= $_POST['batchid'];
	$batchuser->userid 		= $_POST['userid'];
	$batchuser->level 		= $_POST['level'];
	$batchuser->create();

	$log = new Log($session->user_id, $clientip, "WEB", "CREATED BATCHUSER: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='edit')
{
	$batchuser 				= BatchUser::get_by_id($_POST['id']);
	$batchuser->pending 	= $_POST['pending'];
	$batchuser->enabled 	= $_POST['enabled'];
	$batchuser->schoolid 	= $_POST['schoolid'];
	$batchuser->batchid 	= $_POST['batchid'];
	$batchuser->userid 		= $_POST['userid'];
	$batchuser->level 		= $_POST['level'];
	$batchuser->update();

	$log = new Log($session->user_id, $clientip, "WEB", "UPDATED BATCHUSER: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='del')
{
	$log = new Log($session->user_id, $clientip, "WEB", "DELETED BATCHUSER: ".$_POST['id']); $log->create();

	$batchuser = BatchUser::get_by_id($_POST['id'])->delete();
}

?>