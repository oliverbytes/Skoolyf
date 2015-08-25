<?php

require_once("../initialize.php");

$message = "";

if(
	isset($_POST['schoolid']) &&  $_POST['schoolid'] != "" && 
    isset($_POST['name']) &&  $_POST['name'] != "" && 
    isset($_POST['address']) && $_POST['address'] != ""
    )
  {
  	$object = School::get_by_id($_POST['schoolid']);

    $name_exists = false;

    if($_POST['name'] != $object->name)
    {
      $name_exists = School::name_exists($_POST['name']);
    }

    if($name_exists)
    {
      $log = new Log($session->user_id, $clientip, "WEB", "UPDATE SCHOOL ALREADY EXISTS"); $log->create();
      
      $message .= "Sorry, the School Name: <i><b>".$_POST['name'].'</b></i> is already taken.';
    }

    $file = new File($_FILES['logo']);

    if($file->valid)
    {
      $object->logo  = $file->data;
    }
    else
    {
      $object->logo  = base64_decode($object->logo);
    }

    $file = new File($_FILES['picture']);

    if($file->valid)
    {
      $object->picture  = $file->data;
    }
    else
    {
      $object->picture  = base64_decode($object->picture);
    }

    if($message == "")
    {
      $object->name         = $_POST['name'];
      $object->about        = $_POST['about'];
      $object->email        = $_POST['email'];
      $object->number       = $_POST['number'];
      $object->address      = $_POST['address'];
      $object->comments     = $_POST['comments'];
      $object->fbcomments   = $_POST['fbcomments'];
      $object->history      = $_POST['history'];
      $object->visionmission= $_POST['visionmission'];
      $object->corevalues   = $_POST['corevalues'];
      $object->update();

      $log = new Log($session->user_id, $clientip, "WEB", "UPDATED SCHOOL: ".$object->id); $log->create();
      $message = "success";
    }
  }
  else
  {
    $log = new Log($session->user_id, $clientip, "WEB", "UPDATE SCHOOL NOT FILLED"); $log->create();
    $message = "All fields are required.";
  }

  echo $message;

?>