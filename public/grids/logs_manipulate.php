<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$log 			= new Log(0, "", "", "");
	$log->user_id 	= $_POST['userid'];
	$log->ip 		= $_POST['ip'];
	$log->platform 	= $_POST['platform'];
	$log->action 	= $_POST['action'];
	$log->create();

	$log = new Log($session->user_id, $clientip, "WEB", "CREATED LOG: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='edit')
{
	$log 			= Log::get_by_id($_POST['id']);
	$log->user_id 	= $_POST['userid'];
	$log->ip 		= $_POST['ip'];
	$log->platform 	= $_POST['platform'];
	$log->action 	= $_POST['action'];
	$log->update();

	$log = new Log($session->user_id, $clientip, "WEB", "UPDATED LOG: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='del')
{
	$log = new Log($session->user_id, $clientip, "WEB", "DELETED LOG: ".$_POST['id']); $log->create();
	Log::get_by_id($_POST['id'])->delete();
}

?>