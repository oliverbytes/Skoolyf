<?php 

  require_once("includes/initialize.php");

  $batch = Batch::get_by_id($_GET['id']);

  $folder_path = "public/schools/".$batch->schoolid."/yearbooks/".$batch->id."/pages/";

  header("location: ".$folder_path."index.php");

?>