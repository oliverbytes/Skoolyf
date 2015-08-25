<?php 

require_once("../initialize.php");

$schoolsselect = null;

if(DEFENSEMODE)
{
	$schoolsselect .= '<option value='.CSNTRID.'>CSNTR</option>';
}
else
{
	if($user->is_super_admin())
	{
	  $schools = School::get_all();

	  if(count($schools) > 0)
	  {
	    foreach ($schools as $school) 
	    {
	      $schoolsselect .= "<option value='".$school->id."'>".$school->name."</option>";
	    }
	    
	    $schoolsselect .= "<option value='NOTHING'>NOTHING</option>";
	  }
	  else
	  {
	    $schoolsselect .= "<option value='0'>NO SCHOOLS</option>";
	  }
	}
	else
	{
	  if(count($schoolusers) > 0)
	  {
	    foreach ($schoolusers as $schooluser) 
	    {
	      $school = School::get_by_id($schooluser->schoolid);

	      $schoolsselect .= "<option value='".$school->id."'>".$school->name."</option>";
	    }
	    
	    if(User::get_by_id($session->user_id)->is_super_admin())
		{
			$batchsselect .= "<option value='NOTHING'>NOTHING</option>";
		}
	  }
	  else
	  {
	    $schoolsselect .= "<option value='0'>NO SCHOOLS YOU ADMIN</option>";
	  }
	}
}

echo $schoolsselect;

?>