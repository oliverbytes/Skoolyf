<?php

require_once("../initialize.php");

$message = "";

if(
	isset($_POST['groupid']) &&  $_POST['groupid'] != "" && 
    isset($_POST['name']) &&  $_POST['name'] != ""
    )
  {
  	$object = Group::get_by_id($_POST['groupid']);

    $file = new File($_FILES['logo']);

    if($file->valid)
    {
      $object->logo  = $file->data;
    }
    else
    {
      $object->logo  = base64_decode($object->logo);
    }

    $file = new File($_FILES['cover']);

    if($file->valid)
    {
      $object->cover  = $file->data;
    }
    else
    {
      $object->cover  = base64_decode($object->cover);
    }

    if($message == "")
    {
      if(isset($_POST['schoolselect']) && $_POST['schoolselect'] != "NOTHING")
      {
        $school = School::get_by_id($_POST['schoolselect']);
      }

      $object->schoolid   = $school->id;
      $object->name       = $_POST['name'];
      $object->about      = $_POST['about'];
      $object->enabled    = $_POST['enabled'];
      $object->comments   = $_POST['comments'];
      $object->fbcomments = $_POST['fbcomments'];
      $object->update();

      $log = new Log($session->user_id, $clientip, "WEB", "UPDATED GROUP: ".$object->id); $log->create();
      $message = "success";
    }
  }
  else
  {
    $log = new Log($session->user_id, $clientip, "WEB", "UPDATE GROUP NOT FILLED"); $log->create();
    $message = "All fields are required.";
  }

  echo $message;

?>