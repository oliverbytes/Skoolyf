<?php

require_once("../initialize.php");

$batch 			= Batch::get_by_id($_GET['batchid']);
$oldpagename 	= $_POST["oldpagename"];
$oldpagenumber 	= substr($oldpagename, 0, 2);
$newpagename 	= $_POST["newpagename"];

$file_path = "../../public/schools/".$batch->schoolid."/yearbooks/".$batch->id."/pages/";

if(file_exists($file_path.$oldpagename))
{
	rename($file_path.$oldpagename, $file_path.$oldpagenumber.$newpagename.".html");
	echo "success";
}
else
{
	echo $file_path.$oldpagename." : does not exist";
}

?>