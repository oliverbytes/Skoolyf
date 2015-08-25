<?php

require_once("../initialize.php");

$message = "";

if(
	isset($_POST['sectionid']) && $_POST['sectionid']   != "" &&
    isset($_POST['batchselect'])   && $_POST['batchselect']   != "" &&
    isset($_POST['name'])   && $_POST['name']   != ""
    )
  {
	  $object = Section::get_by_id($_POST['sectionid']);

	  $batch = Batch::get_by_id($_POST['batchselect']);

	  $file = new File($_FILES['cover']);

      if($file->valid)
      {
        $object->picture  = $file->data;
      }
      else
      {
        $object->picture  = base64_decode($object->picture);
      }

	  if($object->name == $_POST['name'] && $object->batchid == $batch->id)
	  {
	    $object->comments  = $_POST['comments'];
	    $object->about  = $_POST['about'];
	    $object->advisermessage     = $_POST['advisermessage'];
        $object->comments  	= $_POST['comments'];
        $object->fbcomments = $_POST['fbcomments'];
        $object->enabled    = $_POST['enabled'];
	    $object->update();

	    $log = new Log($session->user_id, $clientip, "WEB", "UPDATED SECTION: ".$object->id); $log->create();
	    $message = "success";
	  }
	  else
	  {
	    if($batch != false && $batch != null && $batch != "")
	    {
	      if(!Section::section_exists($_POST['name'], $batch->id))
	      {
	      	$object->batchid   	= $batch->id;
	        $object->name      	= $_POST['name'];
	        $object->about     	= $_POST['about'];
	        $object->advisermessage     = $_POST['advisermessage'];
	        $object->comments  	= $_POST['comments'];
	        $object->fbcomments = $_POST['fbcomments'];
	        $object->enabled    = $_POST['enabled'];
	        $object->update();

	        $log = new Log($session->user_id, $clientip, "WEB", "UPDATED SECTION: ".$object->id); $log->create();
	        $message = "success";
	      }
	      else
	      {
	        $log = new Log($session->user_id, $clientip, "WEB", "UPDATE SECTION ALREADY EXISTS"); $log->create();
	        $message = "The Section: ".$_POST['name']." already exists.";
	      }
	    }
	    else
	    {
	      $message = "School: ".$_POST['schoolname']." doesn't exist anymore. Please refresh the page.";
	    }
	  }
  }
  else
  {
    $log = new Log($session->user_id, $clientip, "WEB", "UPDATE SECTION NOT FILLED"); $log->create();
    $message = "All fields are required.";
  }

echo $message;

?>