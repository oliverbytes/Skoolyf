<?php 

require_once("../initialize.php");

$accordion = "";

if(isset($_GET["batchid"]))
{
	$school = School::get_by_id($_GET["schoolid"]);
	$batch = Batch::get_by_id($_GET["batchid"]);

	$pages_folder = '../../public/schools/'.$school->id.'/yearbooks/'.$batch->id.'/pages/';
  	$pages = glob($pages_folder.'*html');
  	$page_filename = basename($pages[0]);

  	echo 'public/schools/'.$school->id.'/yearbooks/'.$batch->id.'/pages/'.$page_filename;
}
else
{
	echo "error batchid: ".$_GET["batchid"].", schoolid: ".$_GET["schoolid"];
}

?>