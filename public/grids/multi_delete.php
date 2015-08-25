<?php 

require_once("../../includes/initialize.php");

$what = $_POST['what'];
$ids = $_POST['ids'];

$response = "error";

global $session;

if(!$session->is_logged_in())
{
	die("not logged in");
}

if($what == "user")
{
	foreach ($ids as $id) 
	{
		SchoolUser::delete_all_by_userid($id);
		BatchUser::delete_all_by_userid($id);
		SectionUser::delete_all_by_userid($id);
		ClubUser::delete_all_by_userid($id);
		GroupUser::delete_all_by_userid($id);
		
		User::get_by_id($id)->delete();
	}

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE USERS"); $log->create();

	$response = "success";
}
else if($what == "school")
{
	foreach ($ids as $id) 
	{
		$school = School::get_by_id($id);

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

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE SCHOOLS"); $log->create();
	$response = "success";
}
else if($what == "schooluser")
{
	foreach ($ids as $id) 
	{
		SchoolUser::get_by_id($id)->delete();
	}

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE SCHOOLUSERS"); $log->create();
	$response = "success";
}
else if($what == "batch")
{
	foreach ($ids as $id) 
	{
		$batch = Batch::get_by_id($id);

		SectionUser::delete_all_by_schoolid($batch->schoolid);
		Section::delete_all_by_schoolid($batch->schoolid);
		BatchUser::delete_all_by_batchid($batch->id);

		$folder_path = "../../public/schools/".$batch->schoolid."/yearbooks/".$batch->id;

		rrmdir($folder_path);

		$batch->delete();
	}

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE BATCHS"); $log->create();

	$response = "success";
}
else if($what == "batchuser")
{
	foreach ($ids as $id) 
	{
		BatchUser::get_by_id($id)->delete();
	}

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE BATCHUSERS"); $log->create();
	$response = "success";
}
else if($what == "section")
{
	foreach ($ids as $id) 
	{
		Section::get_by_id($id)->delete();
	}

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE SECTIONS"); $log->create();
	$response = "success";
}
else if($what == "sectionuser")
{
	foreach ($ids as $id) 
	{
		SectionUser::get_by_id($id)->delete();
	}

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE SECTIONUSERS"); $log->create();
	$response = "success";
}
else if($what == "pending")
{
	foreach ($ids as $id) 
	{
		Pending::get_by_id($id)->delete();
	}

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE PENDINGS"); $log->create();
	$response = "success";
}
else if($what == "log")
{
	foreach ($ids as $id) 
	{
		Log::get_by_id($id)->delete();
	}

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE LOGS"); $log->create();
	$response = "success";
}
else if($what == "hit")
{
	foreach ($ids as $id) 
	{
		Hit::get_by_id($id)->delete();
	}

	$log = new Log($session->user_id, $clientip, "WEB", "DELETED MULTIPLE HITS"); $log->create();
	$response = "success";
}

echo $response;

function rrmdir($dir) 
{ 
  foreach(glob($dir . '/*') as $file) 
  { 
    if(is_dir($file)) rrmdir($file); else unlink($file); 
  } 

  rmdir($dir); 
}

?>