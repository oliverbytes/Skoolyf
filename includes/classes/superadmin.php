<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class SuperAdmin extends DatabaseObject
{
	protected static $table_name = T_SUPERADMINS;
	protected static $col_id = C_SUPERADMIN_ID;

	public $id;
	public $userid;
	public $date;
	
	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_SUPERADMIN_USERID 		.", ";
		$sql .= C_SUPERADMIN_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->userid) 		. ", ";
		$sql .= "NOW()" 								. " ";
		$sql .=")";

		if($db->query($sql))
		{
			$this->id = $db->get_last_id();
			return true;
		}
		else
		{
			return false;	
		}
	}
	
	public function update()
	{
		global $db;
		$sql = "UPDATE " 			. self::$table_name . " SET ";
		$sql .= C_SUPERADMIN_USERID 	. "=" . $db->escape_string($this->userid) 		. ", ";
		$sql .= C_SUPERADMIN_DATE 		. "="  . "NOW()" 								. " ";
		$sql .="WHERE " . self::$col_id . "=" . $db->escape_string($this->id) 			. "";
		$db->query($sql);
		return ($db->get_affected_rows() == 1) ? true : false;
	}
	
	public function delete()
	{
		global $db;
		$sql = "DELETE FROM " . self::$table_name . " WHERE " . self::$col_id . "=" . $this->id . "";
		$db->query($sql);
		return ($db->get_affected_rows() == 1) ? true : false;
	}
	
	protected static function instantiate($record)
	{
		$this_class = new self;
		$this_class->id 				= $record[C_SUPERADMIN_ID];
		$this_class->userid 			= $record[C_SUPERADMIN_USERID];
		$this_class->date 				= $record[C_SUPERADMIN_DATE];
		return $this_class;
	}
}

?>