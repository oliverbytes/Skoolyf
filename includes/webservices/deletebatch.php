<?php 

require_once("../initialize.php");

$batchid = $_GET['id'];

$batch = Batch::get_by_id($batchid);
$batch->delete();

SectionUser::delete_all_by_batchid($batch->id);
BatchUser::delete_all_by_batchid($batch->id);

$folder_path = "../../public/schools/".$batch->schoolid."/yearbooks/".$batch->id;

rrmdir($folder_path);

$log = new Log($session->user_id, $clientip, "WEB", "DELETED BATCH: ".$batch->id); $log->create();

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