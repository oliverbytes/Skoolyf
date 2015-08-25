<?php

require_once("../initialize.php");

  if(
      isset($_POST['username']) && 
      isset($_POST['password']) && 
      $_POST['username'] !="" && 
      $_POST['password'] !=""
    )
  {
    $user = User::login($_POST['username'], $_POST['password']);

    if($user)
    {
      if($user->enabled == 1)
      {
        $session->login($user);
        $log = new Log($user->id, $clientip, "WEB", "LOGIN SUCCESS"); $log->create();
        $message = "success";
      }
      else
      {
        $log = new Log(0, $clientip, "WEB", "LOGIN DISABLED"); $log->create();
        $message = "Sorry that you can\'t login right now. <br />Your account has been disabled by the admin for some reason.";
      }
    }
    else
    {
      $log = new Log(0, $clientip, "WEB", "LOGIN INVALID"); $log->create();
      $message = "Wrong username or password.";
    }
  }
  else
  {
    $log = new Log(0, $clientip, "WEB", "LOGIN NOT FILLED"); $log->create();
    $message = "Please enter username and password.";
  }

  echo $message;

?>