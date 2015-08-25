<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_hitged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$hit 			= new Hit();
	$hit->user_id 	= $_POST['user_id'];
	$hit->platform 	= $_POST['platform'];
	$hit->date 		= $_POST['date'];
	$hit->type 		= $_POST['type'];
	$hit->create();

	$log = new Log($session->user_id, $clientip, "WEB", "CREATED HIT: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='edit')
{
	$hit 			= Hit::get_by_id($_POST['id']);
	$hit->user_id 	= $_POST['user_id'];
	$hit->platform 	= $_POST['platform'];
	$hit->date 		= $_POST['date'];
	$hit->type 		= $_POST['type'];
	$hit->update();

	$log = new Log($session->user_id, $clientip, "WEB", "UPDATED HIT: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='del')
{
	$log = new Log($session->user_id, $clientip, "WEB", "DELETED HIT: ".$_POST['id']); $log->create();

	Hit::get_by_id($_POST['id'])->delete();
}

?>