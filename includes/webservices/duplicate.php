<?php

require_once("../initialize.php");

$response = "";

$pageFileName 	= $_POST["whatpage"];
$duplicatedname = $_POST["duplicatedname"];

$batch 			= Batch::get_by_id($_GET['batchid']);
$file_path 		= "../../public/schools/".$batch->schoolid."/yearbooks/".$batch->id."/pages/";

if(!file_exists($file_path."0.".$duplicatedname))
{ 
    copy($file_path.$pageFileName, $file_path."0.".$duplicatedname);
    $response = "success";
    $log = new Log($session->user_id, $clientip, "WEB", "DUPLICATED YEARBOOK PAGE: ".$pageFileName); $log->create();
}
else
{ 
    $response = "error: ".$file_path.$pageFileName." already exists";
    $log = new Log($session->user_id, $clientip, "WEB", "DUPLICATE ERROR YEARBOOK PAGE: ".$pageFileName); $log->create();
}

echo $response;

?>