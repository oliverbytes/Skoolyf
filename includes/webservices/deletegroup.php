<?php 

require_once("../initialize.php");

$id = $_GET['id'];

$object 	= Group::get_by_id($id);

GroupUser::delete_all_by_sectionid($object->id);

$object->delete();

$log = new Log($session->user_id, $clientip, "WEB", "DELETED CLUB: ".$object->id); $log->create();

echo "success";

?>