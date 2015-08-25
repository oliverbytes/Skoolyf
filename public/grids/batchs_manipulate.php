<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$batch 				= new Batch();
	$batch->comments 	= $_POST['comments'];
	$batch->about 		= $_POST['about'];
	$batch->pending 	= $_POST['pending'];
	$batch->enabled 	= $_POST['enabled'];
	$batch->fromyear 	= $_POST['fromyear'];
	$batch->schoolid 	= $_POST['schoolid'];
	$batch->create();

	$batchuser           = new BatchUser();
	$batchuser->pending  = 0;
	$batchuser->enabled  = 1;
	$batchuser->schoolid = $batch->schoolid;
	$batchuser->batchid  = $batch->id;
	$batchuser->userid   = $session->user_id;
	$batchuser->level    = 1;
	$batchuser->create();

    $folder_path = "../../public/schools/".$batch->schoolid."/yearbooks/".$batch->id."/";

    mkdir($folder_path, 0700);
	mkdir($folder_path."pages", 0700);
	mkdir($folder_path."files", 0700);

	copy("../../public/index.php", $folder_path."/pages/index.php");
	copy("../../public/page1.html", $folder_path."/pages/page1.html");

	$log = new Log($session->user_id, $clientip, "WEB", "CREATED BATCH: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='edit')
{
	$batch 				= Batch::get_by_id($_POST['id']);
	$batch->comments 	= $_POST['comments'];
	$batch->about 		= $_POST['about'];
	$batch->pending 	= $_POST['pending'];
	$batch->enabled 	= $_POST['enabled'];
	$batch->fromyear 	= $_POST['fromyear'];
	$batch->schoolid 	= $_POST['schoolid'];
	$batch->update();

	$log = new Log($session->user_id, $clientip, "WEB", "UPDATED BATCH: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='del')
{
	$log = new Log($session->user_id, $clientip, "WEB", "DELETED BATCH: ".$_POST['id']); $log->create();

	$batch = Batch::get_by_id($_POST['id']);

	SectionUser::delete_all_by_schoolid($batch->schoolid);
	Section::delete_all_by_schoolid($batch->schoolid);

	BatchUser::delete_all_by_batchid($batch->id);

	$folder_path = "../../public/schools/".$batch->schoolid."/yearbooks/".$batch->id;

	rrmdir($folder_path);

	$batch->delete();
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