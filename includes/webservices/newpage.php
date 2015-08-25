<?php

require_once("../initialize.php");

$batch 			= Batch::get_by_id($_GET['batchid']);
$newpagename 	= "0.".$_POST["newpagename"];

$file_path = "../../public/schools/".$batch->schoolid."/yearbooks/".$batch->id."/pages/";

if(!file_exists($file_path.$newpagename))
{
	$handle = fopen($file_path.$newpagename, 'w') or die("can't open file");
	fclose($handle);
	echo "success";
}
else
{
	echo $file_path.$newpagename." : already exists";
}

?>