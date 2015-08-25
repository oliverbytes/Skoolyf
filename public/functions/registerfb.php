<?php 

	require_once("../../includes/initialize.php");

	$config = array();
	$config['appId'] = APP_ID;
	$config['secret'] = APP_SECRET;
	$facebook = new Facebook($config);

	$fb_user = $facebook->api('/me','GET');

	if(User::get_by_oauthid($fb_user['id']) != null)
    {
      	header("location: ../../registration.php?fbtaken=Facebook Username: ".$fb_user['username']."<br/>Facebook ID: ".$fb_user['id']);
    }
    else
    {
		$username_exists  = User::username_exists($fb_user['username']);
		$email_exists     = false;

		if(isset($fb_user['email']) && $fb_user['email'] != "")
		{
			$email_exists = User::email_exists($fb_user['email']);
		}

		if($username_exists)
		{
			$message .= "Sorry, the username: <i><b>".$fb_user['username'].'</b></i> is already taken. Please choose a different one.<br />';
		}

		if($email_exists)
		{
			$message .= "Sorry, the email: <i><b>".$fb_user['email'].'</b></i> is already registered.';
		}

		if($message == "")
		{
			$generatePassword = generatePassword();

			$user = new User();
			$user->username = $fb_user['username'];
			$user->password = $generatePassword;
			$user->email    = $fb_user['email'];
			$user->name     = $fb_user['name'];
			$user->volume   = 4;
			$user->control  = 4;
			$user->language = 1;

			$user->lives    = 3;
			$user->coins    = 0;
			$user->bullets  = 10;
			$user->shields  = 2;
			$user->slowmos  = 0;
			$user->kills    = 0;
			$user->points   = 0;
			$user->top_score = 0;

			$user->level    = 1;
			$user->enabled  = 1;
			$user->admin    = 0;

			$user->oauth_uid = $fb_user['id'];
			$user->oauth_provider = "FACEBOOK";

			$user->create();
			$session->login($user);

			$logs = new Logs();
			$logs->user_id  = $user->id;
			$logs->platform = "WEB PORTAL";
			$logs->type     = "REGISTERED SUCCESSFULLY";
			$logs->create();

			$send_to    = "admin@kellyescape.com";
			$subject    = $user->username." - Registered";
			$body       = "Username: ".$user->username."\nPassword: ".$user->password."\nEmail: ".$user->email."\nDate and Time Registered: ".date('m/d/Y h:i:s a', time());
			$from_name  = $user->username;
			$from_email = "registrar@kellyescape.com";

			send_email($send_to, $subject, $body, $from_name, $from_email);

			if($user->email != "")
			{
			  $send_to    = $user->email;
			  $subject    = "Welcome ".$user->username." - Successfully Registered";
			  $body       = "Welcome ".$user->username." to the world of Kelly Escape. Help 'Kelly' Escape the world of darkness. Good luck and enjoy the amazing adventure!";
			  $body      .= "\n\nUser Account:\nUsername: ".$user->username."\nPassword: ".$user->password."\nEmail: ".$user->email."\nDate and Time Registered: ".date('m/d/Y h:i:s a', time());
			  $from_name  = $user->username;
			  $from_email = "admin@kellyescape.com";

			  send_email($send_to, $subject, $body, $from_name, $from_email);
			}

			header("location: ../../account.php?generatedPassword=".$generatedPassword);
		}
		else
		{
			header("location: ../../registration.php?fbregproblem=".$message);
		}
    }

    function generatePassword($length = 7) 
    {
	    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $count = mb_strlen($chars);

	    for ($i = 0, $result = ''; $i < $length; $i++) 
	    {
	        $index = rand(0, $count - 1);
	        $result .= mb_substr($chars, $index, 1);
	    }

	    return $result;
	}

?>