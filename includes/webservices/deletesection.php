<?php 

require_once("../initialize.php");

$id = $_GET['id'];

$section 	= Section::get_by_id($id);
$batch  	= Batch::get_by_id($section->batchid);

SectionUser::delete_all_by_sectionid($section->id);

$section->delete();

$log = new Log($session->user_id, $clientip, "WEB", "DELETED SECTION: ".$section->id); $log->create();

echo "success";

?>