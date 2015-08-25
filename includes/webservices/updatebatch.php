<?php

require_once("../initialize.php");

$message = "";

if(
    //isset($_POST['schoolselect']) && $_POST['schoolselect'] != "" &&
    isset($_POST['batchid']) && $_POST['batchid']   != "" &&
    isset($_POST['fromyear']) && $_POST['fromyear']   != "" &&
    isset($_POST['about']) && $_POST['about']   != ""
    )
  {
    if(strtotime(date("Y-m-d")) < strtotime($_POST['pubdate']))
    {
      //$school = School::get_by_id($_POST['schoolselect']);
    	$object = Batch::get_by_id($_POST['batchid']);
      $school = School::get_by_id(CSNTRID);

      if($school != false && $school != null && $school != "")
      {
        if(!Batch::batch_exists($_POST['fromyear'], $school->id) || $object->fromyear == $_POST['fromyear'])
        {
            $file = new File($_FILES['cover']);

            if($file->valid)
            {
              $object->picture  = $file->data;
            }
            else
            {
              $object->picture  = base64_decode($object->picture);
            }

            $object->fromyear   = $_POST['fromyear'];
            $object->about      = $_POST['about'];
            $object->comments   = $_POST['comments'];
            $object->fbcomments = $_POST['fbcomments'];
            $object->enabled    = $_POST['enabled'];

            if(isset($_POST['pubdate']))
            {
              $object->pubdate    = $_POST['pubdate'];
            }
            
            //$object->schoolid  = $school->id;
            $object->update();

            $log = new Log($session->user_id, $clientip, "WEB", "UPDATED BATCH: ".$object->id); $log->create();
            $message = "success";
        }
        else
        {
          $log = new Log($session->user_id, $clientip, "WEB", "UPDATE BATCH ALREADY EXISTS"); $log->create();
          $message = "The batch of ".$object->get_batchyear()." from ".$school->name." already exists.";
        }
      }
      else
      {
        //$message = "School: ".$_POST['schoolname']." doesn't exist anymore. Please refresh the page.";
      	$message = "School doesnt exist";
      }
    }
    else
    {
      $message = "Invalid Deadline Date.";
    }
  }
  else
  {
    $log = new Log($session->user_id, $clientip, "WEB", "UPDATE BATCH NOT FILLED"); $log->create();
    $message = "All fields are required.";
  }

  echo $message;

?>