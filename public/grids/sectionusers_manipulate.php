<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$sectionuser = new SectionUser();
	$sectionuser->pending 		= $_POST['pending'];
	$sectionuser->enabled 		= $_POST['enabled'];
	$sectionuser->userid 		= $_POST['userid'];
	$sectionuser->schoolid 		= $_POST['schoolid'];
	$sectionuser->batchid 		= $_POST['batchid'];
	$sectionuser->sectionuserid = $_POST['sectionuserid'];
	$sectionuser->level 		= $_POST['level'];
	$sectionuser->create();

	$log = new Log($session->user_id, $clientip, "WEB", "CREATED SECTIONUSER: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='edit')
{
	$sectionuser = SectionUser::get_by_id($_POST['id']);
	$sectionuser->pending 		= $_POST['pending'];
	$sectionuser->enabled 		= $_POST['enabled'];
	$sectionuser->userid 		= $_POST['userid'];
	$sectionuser->schoolid 		= $_POST['schoolid'];
	$sectionuser->batchid 		= $_POST['batchid'];
	$sectionuser->sectionuserid = $_POST['sectionuserid'];
	$sectionuser->level 		= $_POST['level'];
	$sectionuser->update();

	$log = new Log($session->user_id, $clientip, "WEB", "UPDATED SECTIONUSER: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='del')
{
	$log = new Log($session->user_id, $clientip, "WEB", "DELETED SECTIONUSER: ".$_POST['id']); $log->create();
	
	SectionUser::get_by_id($_POST['id'])->delete();
}

?>