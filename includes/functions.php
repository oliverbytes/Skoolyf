<?php 

require_once("initialize.php");

function redirect_to($location = NULL)
{
	if($location != NULL)
	{
		header("Location: {$location}");
		exit();
	}
}

function __autoload($class_name)
{
	$class_name = strtolower($class_name);
	$path = INCLUDES_PATH.DS."{$class_name}.php";

	if(file_exists($path))
	{
		require_once($path);
	}
	else
	{
		die("The file {$path} could not be found.");	
	}
}

function include_public_layout($template="")
{
	include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function send_email($send_to, $subject, $body, $from_name, $from_email)
{
	if(!empty($send_to) && !empty($subject) && !empty($body))
	{
		// $mail = new PHPMailer();
		// $mail->IsSMTP();
		// $mail->SMTPDebug = 0;
		// $mail->SMTPAuth = true;
		// $mail->SMTPSecure = 'ssl';
		// $mail->Host     = "smtp.gmail.com";
		// $mail->Port 	= 465;
		// $mail->Priority = 1;
		// $mail->FromName = $from_name;
		// $mail->Username = EMAIL_ADDRESS;
		// $mail->Password = EMAIL_PASS;
		// $mail->From     = $from_email;
		// $mail->AddAddress($send_to); // for publishing
		// $mail->Subject  = $subject;
		// $mail->Body     = $body;
		// $mail->WordWrap = 50;

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = 1;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host     = "universe1.southbankdomains.com";
		$mail->Port 	= 465;
		$mail->Priority = 1;
		$mail->FromName = $from_name;
		$mail->Username = EMAIL_ADDRESS;
		$mail->Password = EMAIL_PASS;
		$mail->From     = $from_email;
		$mail->AddAddress($send_to); // for publishing
		$mail->Subject  = $subject;
		$mail->Body     = $body;
		$mail->WordWrap = 50;
		
		if(!$mail->Send()) 
		{
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mail->ErrorInfo;
		} 
		else 
		{
		  //echo 'Message has been sent.';
		}
	}
	else
	{
		echo "All email components are required.";
	}
}

function check_connection()
{
	$status = "";
	$conn = @fsockopen("www.itechroom.com", 80, $errno, $errstr, 30);
	if ($conn)
	{
		$status = "yes";
	}
	else
	{
		$status = "no";
	}
	
	return $status;
}

?>