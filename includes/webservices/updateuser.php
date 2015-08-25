<?php 

require_once("../initialize.php");

$message = "";

if( 
	$_POST["userid"]  	!= "" && 
	$_POST["username"]  != "" && 
	$_POST["password"]  != "" && 
	$_POST["email"]     != ""
  )
  {
  	$object             = User::get_by_id($_POST['userid']);
	$object->username   = $_POST["username"];
	$object->email   	= $_POST["email"];
	$object->password   = $_POST["password"];
	$object->firstname  = $_POST["firstname"];
	$object->middlename = $_POST["middlename"];
	$object->lastname   = $_POST["lastname"];
	$object->gender   	= $_POST["gender"];
	$object->address    = $_POST["address"];
	$object->moto       = $_POST["moto"];
	$object->birthdate  = $_POST["birthdate"];
	$object->number     = $_POST["number"];
	$object->comments   = $_POST["comments"];
	$object->fbcomments = $_POST["fbcomments"];
	$object->enabled    = $_POST['enabled'];

	$file = new File($_FILES['picture']);

	if($file->valid)
	{
		$object->picture  = $file->data;
	}
	else
	{
		$object->picture  = base64_decode($object->picture);
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
	
	if($_POST["username"] != $object->username)
	{
	  if(!User::username_exists($_POST["username"]))
	  {
	    $object->update();

	    $log = new Log($session->user_id, $clientip, "WEB", "UPDATED USER: ".$object->id); $log->create();
	    $message = "success";
	  }
	  else
	  {
	    $log = new Log($session->user_id, $clientip, "WEB", "UPDATE USER ALREADY TAKEN"); $log->create();
	    $message = "Username:".$_POST["username"]." already taken.";
	  }
	}
	else
	{
	  $object->update();

	  $log = new Log($session->user_id, $clientip, "WEB", "UPDATED USER: ".$object->id); $log->create();
	  $message = "success";
	}
}
else
{
	$log = new Log($session->user_id, $clientip, "WEB", "UPDATE USER NOT FILLED"); $log->create();

	$message = "All fields are required.";
}

echo $message;

?>