<?php

require_once("../initialize.php");

$batch 		= Batch::get_by_id($_GET['batchid']);
$pagenumber = intval($_GET['pagenumber']) - 1;

$pages_folder = '../../public/schools/'.$batch->schoolid.'/yearbooks/'.$batch->id.'/pages/';
$pages = glob($pages_folder.'*html');

if(count($pages) > 0)
{
	echo str_replace("../../","", $pages[$pagenumber]);
}
else
{
	echo "no pages";
}

?>