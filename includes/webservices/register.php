<?php

require_once("../initialize.php");

$message = "";


if(
  isset($_POST['username']) && 
  isset($_POST['password']) && 
  $_POST['username'] != "" && 
  $_POST['password'] != ""
  )
{
  $username_exists  = User::username_exists($_POST['username']);
  $email_exists     = false;

  if(isset($_POST['email']) && $_POST['email'] != "")
  {
    $email_exists = User::email_exists($_POST['email']);
  }

  if($username_exists)
  {
    $message .= "Sorry, the username: <i><b>".$_POST['username'].'</b></i> is already taken. Please choose a different one.<br />';
  }

  if($email_exists)
  {
    $message .= "Sorry, the email: <i><b>".$_POST['email'].'</b></i> is already registered.';
  }

  if($message == "")
  {
    $user = new User();
    $user->username   = $_POST['username'];
    $user->password   = $_POST['password'];
    $user->email      = $_POST['email'];
    $user->create();

    $session->login($user);

    $log = new Log($user->id, $clientip, "WEB", "REGISTERED"); $log->create();

    $message = "success";
  }
}
else
{
  $message = "Please enter a username and a password.";
  $log = new Log(0, $clientip, "WEB", "REGISTER NOT FILLED"); $log->create();
}

echo $message;

?>