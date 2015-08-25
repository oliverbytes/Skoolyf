<?php

require_once("../initialize.php");

$response = "";

$pageFileName 	= $_POST["whatpage"];
$content 		= $_POST["content"];

$handle = fopen("../../".$pageFileName, "w"); 

if(fwrite($handle, $content) == FALSE)
{ 
    $response = "error";
}
else
{ 
    $response = "success";
}

fclose($handle); 

$log = new Log($session->user_id, $clientip, "WEB", "UPDATED YEARBOOK PAGE: ".$pageFileName); $log->create();

echo $response;

?>