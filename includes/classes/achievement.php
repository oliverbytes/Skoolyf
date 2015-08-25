<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Achievement extends DatabaseObject
{
	protected static $table_name = T_ACHIEVEMENTS;
	protected static $col_id = C_ACHIEVEMENT_ID;

	public $id;
	public $batchid;
	public $itemid;
	public $itemtype;
	public $name;
	public $about;
	public $pending = 1;
	public $enabled = 1;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 				. self::$table_name . " (";
		$sql .= C_ACHIEVEMENT_BATCHID 		.", ";
		$sql .= C_ACHIEVEMENT_ITEMID 		.", ";
		$sql .= C_ACHIEVEMENT_ITEMTYPE 		.", ";
		$sql .= C_ACHIEVEMENT_NAME 			.", ";
		$sql .= C_ACHIEVEMENT_ABOUT 		.", ";
		$sql .= C_ACHIEVEMENT_PENDING 		.", ";
		$sql .= C_ACHIEVEMENT_ENABLED 		.", ";
		$sql .= C_ACHIEVEMENT_DATE;
		$sql .=") VALUES (";
		$sql .= " ".$db->escape_string($this->batchid) 		. ", ";
		$sql .= " ".$db->escape_string($this->itemid) 		. ", ";
		$sql .= " '".$db->escape_string($this->itemtype) 	. "', ";
		$sql .= " '".$db->escape_string($this->name) 		. "', ";
		$sql .= " '".$db->escape_string($this->about) 		. "', ";
		$sql .= " ".$db->escape_string($this->pending) 		. ", ";
		$sql .= " ".$db->escape_string($this->enabled) 		. ", ";
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
		$sql = "UPDATE " 					. self::$table_name . " SET ";
		$sql .= C_ACHIEVEMENT_BATCHID	. "=". $db->escape_string($this->batchid) 		. ", ";
		$sql .= C_ACHIEVEMENT_ITEMID	. "=". $db->escape_string($this->itemid) 		. ", ";
		$sql .= C_ACHIEVEMENT_ITEMTYPE 	. "='". $db->escape_string($this->itemtype) 		. "', ";
		$sql .= C_ACHIEVEMENT_NAME 		. "='". $db->escape_string($this->name) 		. "', ";
		$sql .= C_ACHIEVEMENT_ABOUT 	. "='". $db->escape_string($this->about) 		. "', ";
		$sql .= C_ACHIEVEMENT_PENDING 	. "=". $db->escape_string($this->pending) 		. ", ";
		$sql .= C_ACHIEVEMENT_ENABLED 	. "=". $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_ACHIEVEMENT_DATE 		. "=" . "NOW()" 								. " ";
		$sql .="WHERE " . self::$col_id . "=" . $db->escape_string($this->id) 		. "";
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
		$this_class->id 		= $record[C_ACHIEVEMENT_ID];
		$this_class->batchid 	= $record[C_ACHIEVEMENT_BATCHID];
		$this_class->itemid 	= $record[C_ACHIEVEMENT_ITEMID];
		$this_class->itemtype 	= $record[C_ACHIEVEMENT_ITEMTYPE];
		$this_class->name 		= $record[C_ACHIEVEMENT_NAME];
		$this_class->about 		= $record[C_ACHIEVEMENT_ABOUT];
		$this_class->pending 	= $record[C_ACHIEVEMENT_PENDING];
		$this_class->enabled 	= $record[C_ACHIEVEMENT_ENABLED];
		$this_class->date 		= $record[C_ACHIEVEMENT_DATE];
		return $this_class;
	}

	public static function get($itemid, $itemtype, $batchid)
	{
		global $db;
		$itemid 	= $db->escape_string($itemid);
		$itemtype 	= $db->escape_string($itemtype);
		$batchid 	= $db->escape_string($batchid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_ACHIEVEMENT_ITEMID." = ".$itemid;
		$sql .= " AND ".C_ACHIEVEMENT_ITEMTYPE." = '".$itemtype."'";
		$sql .= " AND ".C_ACHIEVEMENT_BATCHID." = ".$batchid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>