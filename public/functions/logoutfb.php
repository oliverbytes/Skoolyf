<?php 

	require_once("../../includes/initialize.php");

	if(!$session->is_logged_in())
	{
		header("location: ../../index.php");
	}
	else
	{
		$config = array();
		$config['appId'] = APP_ID;
		$config['secret'] = APP_SECRET;
		$facebook = new Facebook($config);

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

	  	header("location: ../../index.php");
	}

?>