<?php 

  require_once("../../includes/initialize.php");

  $config = array();
  $config['appId'] = APP_ID;
  $config['secret'] = APP_SECRET;
  $facebook = new Facebook($config);

  $fb_user = $facebook->api('/me','GET');

  if($session->is_logged_in())
  {
    $current_user = User::get_by_id($session->user_id);
    $another_user = User::get_by_oauthid($fb_user['id']);

    if($another_user == false)
    {
      $current_user->oauth_uid = $fb_user['id'];
      $current_user->oauth_provider = "FACEBOOK";
      $current_user->update();

      $logs = new Logs();
      $logs->user_id  = $session->id;
      $logs->platform = "WEB PORTAL";
      $logs->type     = "FB CONNECTED";
      $logs->create();

      header("location: ../../account.php?another_user: ".$another_user);
    }
    else if($current_user->username == $another_user->username)
    {
      $current_user->oauth_uid = $fb_user['id'];
      $current_user->oauth_provider = "FACEBOOK";
      $current_user->update();

      header("location: ../../account.php?current: ".$current_user->oauth_uid.", another: ".$another_user->oauth_uid);
    }
    else
    {
      header("location: ../../account.php?fbtaken=Facebook Username: ".$fb_user['username']."<br/>Facebook ID: ".$fb_user['id']);
    }
  }
  else
  {
  	header("location: ../../index.php");
  }

?>