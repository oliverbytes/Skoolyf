<?php

require_once("../initialize.php");

$batch 			= Batch::get_by_id($_GET['batchid']);
$page 	= $_POST["page"];

$file_path = "../../public/schools/".$batch->schoolid."/yearbooks/".$batch->id."/pages/";

if(file_exists($file_path.$page))
{
	unlink($file_path.$page);
	echo "success";
}
else
{
	echo $file_path.$page." : does not exist";
}

?>