<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$school 			= new School();
	$school->comments 	= $_POST['comments'];
	$school->pending 	= $_POST['pending'];
	$school->enabled 	= $_POST['enabled'];
	$school->name 		= $_POST['name'];
	$school->address 	= $_POST['address'];
	$school->email 		= $_POST['email'];
	$school->number 	= $_POST['number'];
	$school->about 		= $_POST['about'];
	$school->create();

	$schooluser           = new SchoolUser();
	$schooluser->pending  = 0;
	$schooluser->enabled  = 1;
    $schooluser->schoolid = $school->id;
    $schooluser->userid   = $session->user_id;
    $schooluser->level    = 1;
    $schooluser->create();

	$folder_path = "../../public/schools/";
    mkdir($folder_path.$school->id."/", 0700); // schoolid folder
    mkdir($folder_path.$school->id."/yearbooks/", 0700); // yearbook folder

	$log = new Log($session->user_id, $clientip, "WEB", "CREATED SCHOOL: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='edit')
{
	$school 			= School::get_by_id($_POST['id']);
	$school->comments 	= $_POST['comments'];
	$school->pending 	= $_POST['pending'];
	$school->enabled 	= $_POST['enabled'];
	$school->name 		= $_POST['name'];
	$school->email 		= $_POST['email'];
	$school->number 	= $_POST['number'];
	$school->about 		= $_POST['about'];
	$school->address 	= $_POST['address'];
	$school->update();

	$log = new Log($session->user_id, $clientip, "WEB", "UPDATED SCHOOL: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='del')
{
	$log = new Log($session->user_id, $clientip, "WEB", "DELETED SCHOOL: ".$_POST['id']); $log->create();

	$school = School::get_by_id($_POST['id']);

	$folder_path = "../../public/schools/".$school->id;

	if(file_exists($folder_path) && $folder_path != "../../public/schools/")
	{
		rrmdir($folder_path);
	}

	//===================SECTION=============================//

	SectionUser::delete_all_by_schoolid($school->id);
	Section::delete_all_by_schoolid($school->id);

	//===================BATCH=============================//

	BatchUser::delete_all_by_schoolid($school->id);
	Batch::delete_all_by_schoolid($school->id);

	//===================SCHOOL=============================//

	SchoolUser::delete_all_by_schoolid($school->id);

	$school->delete();
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