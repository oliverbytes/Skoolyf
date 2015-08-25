<?php 

require_once("../initialize.php");

$school = School::get_by_id($_GET['id']);

$folder_path = "../../public/schools/".$school->id;

if(file_exists($folder_path))
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

$log = new Log($session->user_id, $clientip, "WEB", "DELETED SCHOOL: ".$school->id); $log->create();
echo "success";

function rrmdir($dir) 
{ 
  foreach(glob($dir . '/*') as $file) 
  { 
    if(is_dir($file)) rrmdir($file); else unlink($file); 
  } 

  rmdir($dir); 
}

?>