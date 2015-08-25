<?php 

  require_once("../../includes/initialize.php");

  if($session->is_logged_in())
  {
    $user = User::get_by_id($session->user_id);
    $user->oauth_uid = "";
    $user->oauth_provider = "";
    $user->update();

    $logs = new Logs();
    $logs->user_id  = $session->id;
    $logs->platform = "WEB PORTAL";
    $logs->type     = "FB DISCONNECTED";
    $logs->create();

    $session->logout();

    $fb_key_appid = 'fbs_'.APP_ID;
    setcookie($fb_key_appid, '', time()-3600);

    $fb_key_code = 'fbs_'.APP_ID.'_code';
    setcookie($fb_key_code, '', time()-3600);

    $fb_key_access_token = 'fbs_'.APP_ID.'_access_token';
    setcookie($fb_key_access_token, '', time()-3600);

    $fb_key_user_id = 'fbs_'.APP_ID.'_user_id';
    setcookie($fb_key_user_id, '', time()-3600);

    $fb_key_state = 'fbs_'.APP_ID.'_state';
    setcookie($fb_key_state, '', time()-3600);

    unset($_SESSION[$fb_key_appid]);
    unset($_SESSION[$fb_key_code]);
    unset($_SESSION[$fb_key_access_token]);
    unset($_SESSION[$fb_key_user_id]);
    unset($_SESSION[$fb_key_state]);
    
    session_destroy();

    $session = new Session();
    $session->login($user);

    header("location: ../../account.php?disconnected");
  }
  else
  {
  	header("location: ../../index.php");
  }

?>