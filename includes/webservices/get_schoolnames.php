<?php 

	require_once("../initialize.php");

	$schools = School::get_all();

	$schoolnames = array();

	foreach ($schools as $school) 
	{
		array_push($schoolnames, $school->name);
	}

	echo json_encode($schoolnames);

?>