<?php 

require_once("../initialize.php");

$input 	 = $_POST['input'];

$users 	  = User::search($input);
$schools  = School::search($input);
$batchs   = Batch::search($input);
$sections = Section::search($input);
$clubs 	  = Club::search($input);
$groups   = Group::search($input);

$tables = array();

if($users != null)
{
	$table = new Table("users", $users);
	array_push($tables, $table);
}

if($schools != null)
{
	$table = new Table("schools", $schools);
	//array_push($tables, $table);
}

if($batchs != null)
{
	$table = new Table("batchs", $batchs);
	array_push($tables, $table);
}

if($sections != null)
{
	$table = new Table("sections", $sections);
	array_push($tables, $table);
}

if($clubs != null)
{
	$table = new Table("clubs", $clubs);
	array_push($tables, $table);
}

if($groups != null)
{
	$table = new Table("groups", $groups);
	array_push($tables, $table);
}

if(count($tables) > 0)
{
	echo json_encode($tables);
}

class Table
{
	public $name;
	public $objects;

	function __construct($name, $objects)
	{
		$this->name 	= $name;
		$this->objects 	= $objects;
	}
}

?>