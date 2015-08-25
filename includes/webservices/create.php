<?php

$message = "";

require_once("../initialize.php");

if($_POST['what'] == "school")
{
	if(
		isset($_POST['name']) && 
		isset($_POST['address']) && 
		$_POST['name'] != "" && 
		$_POST['address'] != ""
	)
	{
		$name_exists = School::name_exists($_POST['name']);

		if($name_exists)
		{
			$message .= "Sorry, the School Name: <i><b>".$_POST['name'].'</b></i> is already taken.';
		}

		if($message == "")
		{

			$object             = new School();
			$object->name       = $_POST['name'];
			$object->about       = $_POST['about'];
			$object->address    = $_POST['address'];
			$object->comments     = $_POST['comments'];
      		$object->fbcomments   = $_POST['fbcomments'];
			$object->history      = $_POST['history'];
		    $object->visionmission= $_POST['visionmission'];
		    $object->corevalues   = $_POST['corevalues'];

			$file = new File($_FILES['logo']);

			if($file->valid)
			{
				$object->logo  = $file->data;
			}

			$file = new File($_FILES['cover']);

			if($file->valid)
			{
				$object->picture  = $file->data;
			}

			$object->create();

			$schooluser           = new SchoolUser();
			$schooluser->schoolid = $object->id;
			$schooluser->userid   = $session->user_id;
			$schooluser->level    = 1;
			$schooluser->role     = "admin";
			$schooluser->enabled  = 1;
			$schooluser->pending  = 0;
			$schooluser->create();

			$folder_path = "../../public/schools/";
			mkdir($folder_path.$object->id."/", 0700); // schoolid folder
			mkdir($folder_path.$object->id."/yearbooks/", 0700); // yearbook folder

			$log = new Log($session->user_id, $clientip, "WEB", "CREATED SCHOOL: ".$object->id); $log->create();
			$message = "success";
		}
	}
	else
	{
		$log = new Log($session->user_id, $clientip, "WEB", "CREATE SCHOOL NOT FILLED"); $log->create();
		$message = "All fields are required.";
	}
}
else if($_POST['what'] == "batch")
{
	if(
		//isset($_POST['schoolselect'])   && $_POST['schoolselect']   != "" &&
		isset($_POST['fromyear'])   && $_POST['fromyear']   != "" &&
		isset($_POST['about'])   && $_POST['about']   != ""
	)
	{

		if(strtotime(date("Y-m-d")) < strtotime($_POST['pubdate']))
		{

			//$school = School::get_by_id($_POST['schoolselect']);
			$school = School::get_by_id(CSNTRID);

			if($school != false && $school != null && $school != "")
			{
				if(!Batch::batch_exists($_POST['fromyear'], $school->id))
				{
					$object            = new Batch();
					$object->fromyear  = $_POST['fromyear'];
					$object->about     = $_POST['about'];
					$object->comments  = $_POST['comments'];
					$object->fbcomments  = $_POST['fbcomments'];
					$object->schoolid  = $school->id;
					$object->enabled   = 1;
					$object->pending   = 0;
					$object->pubdate  	= $_POST['pubdate'];
					$object->published  = 0;

					$file = new File($_FILES['cover']);

					if($file->valid)
					{
						$object->picture  = $file->data;
					}

					$object->create();

					$folder_path = "../../public/schools/".$school->id."/yearbooks/".$object->id."/";
					mkdir($folder_path, 0700);
					mkdir($folder_path."pages", 0700);
					mkdir($folder_path."files", 0700);

					copy("../../public/0.students.php", $folder_path."/pages/0.students.php");
					copy("../../public/0.page1.html", $folder_path."/pages/0.page1.html");

					copy("../../public/11.Vision_and-Mission-Sample.html", $folder_path."/pages/11.Vision_and-Mission-Sample.html");
					copy("../../public/12.7.The_Achievers-Sample.html", $folder_path."/pages/12.7.The_Achievers-Sample.html");
					copy("../../public/22.Core_Values-Sample.html", $folder_path."/pages/22.Core_Values-Sample.html");
					copy("../../public/33.Message_of_the_City_Mayor-Sample.html", $folder_path."/pages/33.Message_of_the_City_Mayor-Sample.html");
					copy("../../public/44.3.History-Sample.html", $folder_path."/pages/44.3.History-Sample.html");
					copy("../../public/55.5.Message_of_the_Director-Sample.html", $folder_path."/pages/55.5.Message_of_the_Director-Sample.html");

					$batchuser           = new BatchUser();
					$batchuser->schoolid = $school->id;
					$batchuser->batchid  = $object->id;
					$batchuser->userid   = $session->user_id;
					$batchuser->level    = 1;
					$batchuser->role     = "admin";
					$batchuser->enabled  = 1;
					$batchuser->pending  = 0;
					$batchuser->create();

					$log = new Log($session->user_id, $clientip, "WEB", "CREATED BATCH: ".$object->id); $log->create();
					$message = "success";
				}
				else
				{
					$log = new Log($session->user_id, $clientip, "WEB", "CREATE BATCH ALREADY EXISTS"); $log->create();
					$message = "The batch of ".$_POST['fromyear']." - ".($_POST['fromyear'] + 1)." from ".$school->name." already exists.";
				}
			}
			else
			{
				$message = "School: ".$_POST['schoolname']." doesn't exist anymore. Please refresh the page.";
			}
		}
		else
		{
			$message = "Invalid Deadline Date.";
		}
	}
	else
	{
		$log = new Log($session->user_id, $clientip, "WEB", "CREATE BATCH NOT FILLED"); $log->create();
		$message = "All fields are required.";
	}
}
else if($_POST['what'] == "section")
{
	if(
		isset($_POST['batchselect'])   && $_POST['batchselect']   != "" &&
		isset($_POST['name'])   && $_POST['name']   != "" &&
		isset($_POST['about'])   && $_POST['about']   != ""
	)
	{
		$batch = Batch::get_by_id($_POST['batchselect']);
		$school = School::get_by_id($batch->schoolid);

		if($batch != false && $batch != null && $batch != "")
		{
			if(!Section::section_exists($_POST['name'], $batch->id))
			{
				$object            = new Section();
				$object->name      = $_POST['name'];
				$object->comments  = $_POST['comments'];
				$object->fbcomments  = $_POST['fbcomments'];
				$object->about     = $_POST['about'];
				$object->advisermessage     = $_POST['advisermessage'];
				$object->schoolid  = $school->id;
				$object->batchid   = $batch->id;
				$object->pending   = 0;
				$object->enabled   = 1;

				$file = new File($_FILES['cover']);

				if($file->valid)
				{
					$object->picture  = $file->data;
				}

				$object->create();

				$sectionuser           = new SectionUser();
				$sectionuser->userid   = $session->user_id;
				$sectionuser->schoolid = $school->id;
				$sectionuser->batchid  = $batch->id;
				$sectionuser->sectionid= $object->id;
				$sectionuser->level    = 1;
				$sectionuser->role    	= "admin";
				$sectionuser->enabled  = 1;
				$sectionuser->pending  = 0;
				$sectionuser->create();

				$log = new Log($session->user_id, $clientip, "WEB", "CREATED SECTION: ".$object->id); $log->create();
				$message = "success";
			}
			else
			{
				$message = "School: ".$_POST['schoolname']." doesn't exist anymore. Please refresh the page.";
			}
		}
	}
	else
	{
		$log = new Log($session->user_id, $clientip, "WEB", "CREATE SECTION NOT FILLED"); $log->create();
		$message = "All fields are required.";
	}
}
else if($_POST['what'] == "club")
{
	if(isset($_POST['name']) && $_POST['name'] != "")
	{
		if(isset($_POST['schoolselect']) && $_POST['schoolselect'] != "NOTHING")
		{
			$school = School::get_by_id($_POST['schoolselect']);
		}

		$object             = new Club();
		$object->schoolid  	= $school->id;
		$object->name       = $_POST['name'];
		$object->about      = $_POST['about'];
		$object->fbcomments = $_POST['fbcomments'];
		$object->comments  	= $_POST['comments'];

		$file = new File($_FILES['logo']);

		if($file->valid)
		{
			$object->logo  = $file->data;
		}

		$file = new File($_FILES['cover']);

		if($file->valid)
		{
			$object->cover  = $file->data;
		}

		$object->create();

		$clubuser           = new ClubUser();
		$clubuser->clubid 	= $object->id;
		$clubuser->userid   = $session->user_id;
		$clubuser->level    = 1;
		$clubuser->role    	= "admin";
		$clubuser->enabled  = 1;
		$clubuser->pending  = 0;
		$clubuser->create();

		$log = new Log($session->user_id, $clientip, "WEB", "CREATED CLUB: ".$object->id); $log->create();
		$message = "success";
	}
	else
	{
		$log = new Log($session->user_id, $clientip, "WEB", "CREATE CLUB NOT FILLED"); $log->create();
		$message = "All fields are required.";
	}
}
else if($_POST['what'] == "group")
{
	if(isset($_POST['name']) && $_POST['name'] != "")
	{
		if(isset($_POST['schoolselect']) && $_POST['schoolselect'] != "NOTHING")
		{
			$school = School::get_by_id($_POST['schoolselect']);
		}

		$object             = new Group();
		$object->schoolid  	= $school->id;
		$object->name       = $_POST['name'];
		$object->about      = $_POST['about'];
		$object->fbcomments = $_POST['fbcomments'];
		$object->comments  	= $_POST['comments'];

		$file = new File($_FILES['logo']);

		if($file->valid)
		{
			$object->logo  = $file->data;
		}

		$file = new File($_FILES['cover']);

		if($file->valid)
		{
			$object->cover  = $file->data;
		}

		$object->create();

		$groupuser           = new GroupUser();
		$groupuser->groupid  = $object->id;
		$groupuser->userid   = $session->user_id;
		$groupuser->level    = 1;
		$groupuser->role     = "admin";
		$groupuser->enabled  = 1;
		$groupuser->pending  = 0;
		$groupuser->create();

		$log = new Log($session->user_id, $clientip, "WEB", "CREATED GROUP: ".$object->id); $log->create();
		$message = "success";
	}
	else
	{
		$log = new Log($session->user_id, $clientip, "WEB", "CREATE GROUP NOT FILLED"); $log->create();
		$message = "All fields are required.";
	}
}
else if($_POST['what'] == "user")
{
	if(
		isset($_POST['username']) && $_POST['username'] != "" &&
		isset($_POST['email']) && $_POST['email'] != "" &&
		isset($_POST['password']) &&  $_POST['password'] != ""
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
			$object = new User();
			$object->username   = $_POST['username'];
			$object->password   = $_POST['password'];
			$object->email      = $_POST['email'];
			$object->firstname  = $_POST['firstname'];
			$object->middlename = $_POST['middlename'];
			$object->lastname   = $_POST['lastname'];
			$object->gender   	= $_POST['gender'];
			$object->address    = $_POST['address'];
			$object->moto       = $_POST['moto'];
			$object->birthdate  = $_POST['birthdate'];
			$object->number     = $_POST['number'];
			$object->comments   = $_POST["comments"];
			$object->fbcomments = $_POST["fbcomments"];
			$object->enabled    = 1;
			$object->pending    = 0;

			$file = new File($_FILES['cover']);

			if($file->valid)
			{
				$object->cover  = $file->data;
			}

			$file = new File($_FILES['picture']);

			if($file->valid)
			{
				$object->picture  = $file->data;
			}

			$object->create();

			if(isset($_POST['schoolselect']) && $_POST['schoolselect'] != "NOTHING")
			{
				$school = School::get_by_id($_POST['schoolselect']);
			}

			$schooluser           = new SchoolUser();
			$schooluser->schoolid = $school->id;
			$schooluser->userid   = $object->id;
			$schooluser->level    = 0;
			$schooluser->enabled  = 1;
			$schooluser->pending  = 0;
			$schooluser->create();

			if(isset($_POST['batchselect']) && $_POST['batchselect'] != "NOTHING")
			{
				$batch    = Batch::get_by_id($_POST['batchselect']);

				$batchuser           = new BatchUser();
				$batchuser->schoolid = $school->id;
				$batchuser->batchid  = $batch->id;
				$batchuser->userid   = $object->id;
				$batchuser->level    = 0;
				$batchuser->enabled  = 1;
				$batchuser->pending  = 0;
				$batchuser->create();
			}

			if(isset($_POST['sectionselect']) && $_POST['sectionselect'] != "NOTHING")
			{
				$section  = Section::get_by_id($_POST['sectionselect']);
				$batch    = Batch::get_by_id($section->batchid);

				$sectionuser           = new SectionUser();
				$sectionuser->sectionid= $section->id;
				$sectionuser->batchid  = $batch->id;
				$sectionuser->userid   = $object->id;
				$sectionuser->schoolid   = $school->id;
				$sectionuser->level    = 0;
				$sectionuser->enabled  = 1;
				$sectionuser->pending  = 0;
				$sectionuser->create();
			}

			$log = new Log($session->user_id, $clientip, "WEB", "CREATED USER: ".$object->id); $log->create();

			$message = "success";
		}
	}
	else
	{
		$log = new Log($session->user_id, $clientip, "WEB", "CREATE USER NOT FILLED"); $log->create();
		$message = "Please enter a username and a password.";
	}
}
else
{
	$message = "unknown";
}

echo $message;

?>