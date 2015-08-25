<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

if($_POST['oper']=='add')
{
	$user 			  = new User();
	$user->comments   = $_POST['comments'];
	$user->pending 	  = $_POST['pending'];
	$user->enabled    = $_POST['enabled'];
	$user->username   = $_POST['username'];
	$user->password   = $_POST['password'];
	$user->email      = $_POST['email'];
	$user->firstname  = $_POST['firstname'];
	$user->middlename = $_POST['middlename'];
	$user->lastname   = $_POST['lastname'];
	$user->address    = $_POST['address'];
	$user->moto       = $_POST['moto'];
	$user->birthdate  = $_POST['birthdate'];
	$user->number     = $_POST['number'];
	$user->create();

	$folder_path = "../../public/users/";

	mkdir($folder_path.$user->id, 0700);
	mkdir($folder_path.$user->id."/albums/", 0700);

	copy("../../public/img/profile.png", $folder_path.$user->id."/profile.jpg");
    copy("../../public/img/cover.png", $folder_path.$user->id."/cover.jpg");

	$log = new Log($session->user_id, $clientip, "WEB", "CREATED USER: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='edit')
{
	$user 			  = User::get_by_id($_POST['id']);
	$user->comments   = $_POST['comments'];
	$user->pending 	  = $_POST['pending'];
	$user->enabled    = $_POST['enabled'];
	$user->username   = $_POST['username'];
	$user->password   = $_POST['password'];
	$user->email      = $_POST['email'];
	$user->firstname  = $_POST['firstname'];
	$user->middlename = $_POST['middlename'];
	$user->lastname   = $_POST['lastname'];
	$user->address    = $_POST['address'];
	$user->moto       = $_POST['moto'];
	$user->birthdate  = $_POST['birthdate'];
	$user->number     = $_POST['number'];
	$user->update();

	$log = new Log($session->user_id, $clientip, "WEB", "UPDATED USER: ".$_POST['id']); $log->create();
}
else if($_POST['oper']=='del')
{
	if($_POST['id'] != $session->user_id)
	{
		$log = new Log($session->user_id, $clientip, "WEB", "DELETED USER: ".$_POST['id']); $log->create();

		SchoolUser::delete_all_by_userid($_POST['id']);
		BatchUser::delete_all_by_userid($_POST['id']);
		SectionUser::delete_all_by_userid($_POST['id']);
		ClubUser::delete_all_by_userid($_POST['id']);
		GroupUser::delete_all_by_userid($_POST['id']);

		User::get_by_id($_POST['id'])->delete();
	}
}

?>