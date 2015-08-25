<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$section = new Section();
	$section->name 			= $_POST['name'];
	$section->comments 		= $_POST['comments'];
	$section->about 		= $_POST['about'];
	$section->pending 		= $_POST['pending'];
	$section->enabled 		= $_POST['enabled'];
	$section->sectionname 	= $_POST['sectionname'];
	$section->schoolid 		= $_POST['schoolid'];
	$section->batchid 		= $_POST['batchid'];
	$section->create();

	$sectionuser           = new SectionUser();
	$sectionuser->pending  = 0;
	$sectionuser->enabled  = 1;
	$sectionuser->schoolid = $section->schoolid;
	$sectionuser->sectionid= $section->id;
	$sectionuser->userid   = $session->user_id;
	$sectionuser->level    = 1;
	$sectionuser->create();

	$log = new Log($session->user_id, $clientip, "WEB", "CREATED SECTION: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='edit')
{
	$section 				= Section::get_by_id($_POST['id']);
	$section->name 			= $_POST['name'];
	$section->comments 		= $_POST['comments'];
	$section->about 		= $_POST['about'];
	$section->pending 		= $_POST['pending'];
	$section->enabled 		= $_POST['enabled'];
	$section->sectionname 	= $_POST['sectionname'];
	$section->schoolid 		= $_POST['schoolid'];
	$section->update();

	$log = new Log($session->user_id, $clientip, "WEB", "UPDATED SECTION: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='del')
{
	$section 	= Section::get_by_id($_POST['id']);
	$school 	= School::get_by_id($section->schoolid);
	$batch 		= Batch::get_by_id($section->batchid);

	SectionUser::delete_all_by_sectionid($section->id);

	$section->delete();
	$log = new Log($session->user_id, $clientip, "WEB", "DELETED SECTION: ".$_POST['id']); $log->create();
}

function rrmdir($dir) 
{ 
  foreach(glob($dir . '/*') as $file) 
  { 
    if(is_dir($file)) rrmdir($file); else unlink($file); 
  } 

  rmdir($dir); 
}

?>