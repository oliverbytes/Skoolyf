<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Picture extends DatabaseObject
{
	protected static $table_name = T_PICTUREES;
	protected static $col_id = C_PICTURE_ID;

	public $id;
	public $itemid;
	public $itemtype;
	public $picture;
	public $pending;
	public $enabled;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 		. self::$table_name . " (";
		$sql .= C_PICTURE_ITEMID 	.", ";
		$sql .= C_PICTURE_ITEMTYPE 	.", ";
		$sql .= C_PICTURE_PICTURE 	.", ";
		$sql .= C_PICTURE_PENDING 	.", ";
		$sql .= C_PICTURE_ENABLED 	.", ";
		$sql .= C_PICTURE_DATE;
		$sql .=") VALUES (";
		$sql .= " ".$db->escape_string($this->itemid) 		. ", ";
		$sql .= " '".$db->escape_string($this->itemtype) 	. "', ";
		$sql .= " '".$db->escape_string($this->picture) 	. "', ";
		$sql .= " ".$db->escape_string($this->pending) 		. ", ";
		$sql .= " ".$db->escape_string($this->enabled) 		. ", ";
		$sql .= "NOW()" 							. " ";
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
		
		$sql .= C_PICTURE_ITEMID 	. "=". $db->escape_string($this->itemid) 		. ", ";
		$sql .= C_PICTURE_ITEMTYPE 	. "='". $db->escape_string($this->itemtype) 	. "', ";
		$sql .= C_PICTURE_PICTURE	. "='". $db->escape_string($this->picture) 		. "', ";
		$sql .= C_PICTURE_PENDING 	. "=". $db->escape_string($this->pending) 		. ", ";
		$sql .= C_PICTURE_ENABLED 	. "=". $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_PICTURE_DATE 		. "=" . "NOW()" 								. " ";
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
		$this_class->id 		= $record[C_PICTURE_ID];
		$this_class->itemid 	= $record[C_PICTURE_ITEMID];
		$this_class->itemtype 	= $record[C_PICTURE_ITEMTYPE];
		$this_class->picture 	= $record[C_PICTURE_PICTURE];
		$this_class->pending 	= $record[C_PICTURE_PENDING];
		$this_class->enabled 	= $record[C_PICTURE_ENABLED];
		$this_class->date 		= $record[C_PICTURE_DATE];
		return $this_class;
	}

	public static function get($itemid, $itemtype)
	{
		global $db;
		$itemid 	= $db->escape_string($itemid);
		$itemtype 	= $db->escape_string($itemtype);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_PICTURE_ITEMID." = ".$itemid;
		$sql .= " AND ".C_PICTURE_ITEMTYPE." = '".$itemtype."'";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>