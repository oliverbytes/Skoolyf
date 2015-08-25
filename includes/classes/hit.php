<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Hit extends DatabaseObject
{
	protected static $table_name = T_HITS;
	protected static $col_id = C_HITS_ID;

	public $id;
	public $name;
	public $platform;
	public $user_id;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_HITS_NAME 		.", ";
		$sql .= C_HITS_PLATFORM 	.", ";
		$sql .= C_HITS_USER_ID 		.", ";
		$sql .= C_HITS_DATE;
		$sql .=") VALUES ('";
		$sql .= $db->escape_string($this->name) 		. "', '";
		$sql .= $db->escape_string($this->platform) 	. "', ";
		$sql .= $db->escape_string($this->user_id) 		. ", ";
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
		$sql .= C_HITS_NAME 		. "='" . $db->escape_string($this->name) 	. "', ";
		$sql .= C_HITS_PLATFORM 	. "='" . $db->escape_string($this->platform) . "', ";
		$sql .= C_HITS_USER_ID 		. "=" . $db->escape_string($this->user_id) 	. ", ";
		$sql .= C_HITS_DATE 		. "="  . "NOW()" 							. " ";
		$sql .="WHERE " . self::$col_id . "=" . $db->escape_string($this->id) 	. "";
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
		$this_class->id 			= $record[C_HITS_ID];
		$this_class->name 			= $record[C_HITS_NAME];
		$this_class->platform 		= $record[C_HITS_PLATFORM];
		$this_class->user_id 		= $record[C_HITS_USER_ID];
		$this_class->date 			= $record[C_HITS_DATE];
		return $this_class;
	}
}

?>