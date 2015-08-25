<?php

require_once("../initialize.php");

$html = "";

if($session->is_logged_in())
{
	if(isset($_GET['touserid']))
	{
		$notifications = Notification::get($session->user_id);

		if(count($notifications) > 0)
		{
			foreach ($notifications as $notification) 
			{
				$user = User::get_by_id($notification->fromuserid);

				if($user)
				{
					$html .= "<tr>";
					$html .= "  <td style='width:50px;'>";
			        $html .= "  	<img style='height:50px;' src='data:image/jpeg;base64, ".$user->picture."' />";
			        $html .= "  </td>";
			        $html .= "  <td>";

			        $title = "";
			        $done = false;

			        if($notification->itemtype == "friend")
			        {
			        	$title = "Friend Request";
			        	$friendobject = Friend::get_by_id($notification->itemid);

			        	if(!$friendobject)
			        	{
			        		$notification->delete();
			        		continue;
			        	}
			        	else
			        	{
			        		if($friendobject->pending == 0)
			        		{
			        			$done = true;
			        		}
			        	}
			        }
			        else if($notification->itemtype == "schooluser" && $notification->pending == 1)
			        {
			        	$object 	= SchoolUser::get_by_id($notification->itemid);
			        	
			        	if(!$object)
			        	{
			        		$notification->delete();
			        		continue;
			        	}
			        	else
			        	{
			        		$school 	= School::get_by_id($object->schoolid);
			        		$title 		= $notification->title." to join school <a href='school.php?id=".$school->id."'>".$school->name."</a>";
			        		$html .= "  	<div style='display:block;'>".$title." <a href='student.php?id=".$user->id."'>".$user->get_full_name()."</a><br/>";

			        		if($object->pending == 0)
			        		{
			        			$done = true;
			        		}
			        	}
			        }
			        else if($notification->itemtype == "batchuser" && $notification->pending == 1)
			        {
			        	$object 	= BatchUser::get_by_id($notification->itemid);

			        	if(!$object)
			        	{
			        		$notification->delete();
			        		continue;
			        	}
			        	else
			        	{
			        		$batch 		= Batch::get_by_id($object->batchid);
				        	$school 	= School::get_by_id($object->schoolid);
				        	$title 		= $notification->title." to join batch <a href='batch.php?id=".$batch->id."'>".$batch->get_batchyear()."</a> of <a href='school.php?id=".$school->id."'>".$school->name."</a>";
				        	$html .= "  	<div style='display:block;'>".$title." <a href='student.php?id=".$user->id."'>".$user->get_full_name()."</a><br/>";

			        		if($object->pending == 0)
			        		{
			        			$done = true;
			        		}
			        	}
			        }
			        else if($notification->itemtype == "sectionuser" && $notification->pending == 1)
			        {
			        	$object 	= SectionUser::get_by_id($notification->itemid);
			        	
			        	if(!$object)
			        	{
			        		$notification->delete();
			        		continue;
			        	}
			        	else
			        	{
			        		$section 	= Section::get_by_id($object->sectionid);
				        	$batch 		= Batch::get_by_id($object->batchid);
				        	$school 	= School::get_by_id($object->schoolid);
				        	$title 		= $notification->title." to join section <a href='section.php?id=".$section->id."'>".$section->name."</a> of batch <a href='batch.php?id=".$batch->id."'>".$batch->get_batchyear()."</a> of <a href='school.php?id=".$school->id."'>".$school->name."</a>";
				        	$html .= "  	<div style='display:block;'>".$title." <a href='student.php?id=".$user->id."'>".$user->get_full_name()."</a><br/>";

			        		if($object->pending == 0)
			        		{
			        			$done = true;
			        		}
			        	}
			        }
			        else if($notification->itemtype == "clubuser" && $notification->pending == 1)
			        {
			        	$object 	= ClubUser::get_by_id($notification->itemid);

			        	if(!$object)
			        	{
			        		$notification->delete();
			        		continue;
			        	}
			        	else
			        	{
			        		$club 		= Club::get_by_id($object->clubid);
				        	$school 	= School::get_by_id($object->schoolid);
				        	$title 		= $notification->title." to join club <a href='club.php?id=".$club->id."'>".$club->name."</a> of <a href='school.php?id=".$school->id."'>".$school->name."</a>";
				        	$html .= "  	<div style='display:block;'>".$title." <a href='student.php?id=".$user->id."'>".$user->get_full_name()."</a><br/>";

			        		if($object->pending == 0)
			        		{
			        			$done = true;
			        		}
			        	}
			        }
			        else if($notification->itemtype == "groupuser" && $notification->pending == 1)
			        {
			        	$object 	= GroupUser::get_by_id($notification->itemid);
			        	
			        	if(!$object)
			        	{
			        		$notification->delete();
			        		continue;
			        	}
			        	else
			        	{
			        		$group 		= Group::get_by_id($object->groupid);
				        	$school 	= School::get_by_id($object->schoolid);
				        	$title 		= $notification->title." to join group <a href='group.php?id=".$group->id."'>".$group->name."</a> of <a href='school.php?id=".$school->id."'>".$school->name."</a>";
				        	$html .= "  	<div style='display:block;'>".$title." <a href='student.php?id=".$user->id."'>".$user->get_full_name()."</a><br/>";

			        		if($object->pending == 0)
			        		{
			        			$done = true;
			        		}
			        	}
			        }   

			        if($notification->itemtype != "message" && $notification->pending == 1 && !$done)
			        {
			        	$html .="		<button class='btn btn-primary btnaccept'>Accept<span hidden class='itemid'>".$notification->itemid."</span><span hidden class='itemtype'>".$notification->itemtype."</span><span hidden class='fromuserid'>".$notification->fromuserid."</span><span hidden class='notificationid'>".$notification->id."</span></button> ";
			        	$html .="		<button class='btn btn-danger btndecline'>Decline<span hidden class='itemid'>".$notification->itemid."</span><span hidden class='itemtype'>".$notification->itemtype."</span><span hidden class='fromuserid'>".$notification->fromuserid."</span><span hidden class='notificationid'>".$notification->id."</span></button>";
			        }
			        else
			        {
			        	if($notification->itemtype == "friend")
				        {
				        	$object 	= Friend::get_by_id($notification->itemid);

				        	if($object->userid != $session->user_id)
				        	{
				        		$touser 	= User::get_by_id($object->userid);
				        	}
				        	else if($object->touserid != $session->user_id)
				        	{
				        		$touser 	= User::get_by_id($object->touserid);
				        	}

				        	$html .="Now friends";
				        }
				        else if($notification->itemtype == "schooluser")
				        {
				        	$object 	= SchoolUser::get_by_id($notification->itemid);
				        	$school 	= School::get_by_id($object->schoolid);

				        	$html .="Now a member in School <a href='school.php?id=".$school->id."'>".$school->name."</a>";
				        }
				        else if($notification->itemtype == "batchuser")
				        {
				        	$object 	= BatchUser::get_by_id($notification->itemid);
				        	$batch 		= Batch::get_by_id($object->batchid);
				        	$school 	= School::get_by_id($object->schoolid);

				        	$html .="Now a member in Batch <a href='batch.php?id=".$batch->id."'>".$batch->get_batchyear()."</a> of School <a href='school.php?id=".$school->id."'>".$school->name."</a>";
				        }
				        else if($notification->itemtype == "sectionuser")
				        {
				        	$object 	= SectionUser::get_by_id($notification->itemid);
				        	$section 	= Section::get_by_id($object->sectionid);
				        	$batch 		= Batch::get_by_id($object->batchid);
				        	$school 	= School::get_by_id($object->schoolid);

				        	$html .="Now a member in Section <a href='section.php?id=".$section->id."'>".$section->name."</a> of Batch <a href='batch.php?id=".$batch->id."'>".$batch->get_batchyear()."</a> of School <a href='school.php?id=".$school->id."'>".$school->name."</a>";
				        }
				        else if($notification->itemtype == "clubuser")
				        {
				        	$object 	= ClubUser::get_by_id($notification->itemid);
				        	$club 		= Club::get_by_id($object->clubid);
			        		$school 	= School::get_by_id($object->schoolid);

				        	$html .="Now a member in Club <a href='club.php?id=".$club->id."'>".$club->name."</a> of School <a href='school.php?id=".$school->id."'>".$school->name."</a>";
				        }
				        else if($notification->itemtype == "groupuser")
				        {
				        	$object 	= GroupUser::get_by_id($notification->itemid);
				        	$group 		= Group::get_by_id($object->groupid);
			        		$school 	= School::get_by_id($object->schoolid);

				        	$html .="Now a member in Group <a href='group.php?id=".$group->id."'>".$group->name."</a> of School <a href='school.php?id=".$school->id."'>".$school->name."</a>";
				        }

				        $html .="		<button class='btn btn-mini btndelete pull-right'>x<span hidden class='notificationid'>".$notification->id."</span></button>";
			        }

			        $html .="		<p>".$notification->date."</p>";
			        $html .="		</div>";
			        $html .= "  </td>";
			        $html .= "</tr>";
				}
			}
		}
		else
		{
			$html = "no notifications";
		}
	}
	else
	{
		$html = "error";
	}
}

echo $html;

?>