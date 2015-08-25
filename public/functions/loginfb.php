<?php 

  require_once("../../includes/initialize.php");

  $config = array();
  $config['appId'] = APP_ID;
  $config['secret'] = APP_SECRET;
  $facebook = new Facebook($config);

  $fb_user = $facebook->api('/me','GET');

  $user = User::get_by_oauthid($fb_user['id']);

  if($user != null)
  {
  	$session->login($user);

    $logs = new Logs();
    $logs->user_id  = $session->id;
    $logs->platform = "WEB PORTAL";
    $logs->type     = "FB LOGIN SUCCESS";
    $logs->create();

    header("location: ../../index.php");
  }
  else
  {
    header("location: ../../index.php?notregistered");
  }

?>