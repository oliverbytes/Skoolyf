<?php

require_once("../initialize.php");

$response = "";

$batch 			= Batch::get_by_id($_GET['batchid']);
$file_path = "../../public/schools/".$batch->schoolid."/yearbooks/".$batch->id."/pages/";

$pages = json_decode($_POST['pages']);

$index = 0;

foreach ($pages as $page) 
{
	$index++;

	$oldpagename = $page->pageFileName;

	$page->pageFileName = substr($page->pageFileName, 2);
	$newpagename = $index.".".$page->pageFileName;

	rename($file_path.$oldpagename, $file_path.$newpagename);
}

echo "success";

?>