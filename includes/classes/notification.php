<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Notification extends DatabaseObject
{
	protected static $table_name = T_NOTIFICATIONS;
	protected static $col_id = C_NOTIFICATION_ID;

	public $id;
	public $fromuserid;
	public $touserid;
	public $title;
	public $itemid;
	public $itemtype;
	public $pending = 1;
	public $enabled = 1;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 				. self::$table_name . " (";
		$sql .= C_NOTIFICATION_FROMUSERID 	.", ";
		$sql .= C_NOTIFICATION_TOUSERID 	.", ";
		$sql .= C_NOTIFICATION_TITLE 		.", ";
		$sql .= C_NOTIFICATION_ITEMID 		.", ";
		$sql .= C_NOTIFICATION_ITEMTYPE 		.", ";
		$sql .= C_NOTIFICATION_PENDING 		.", ";
		$sql .= C_NOTIFICATION_ENABLED 		.", ";
		$sql .= C_NOTIFICATION_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->fromuserid) 	. ", ";
		$sql .= $db->escape_string($this->touserid) 	. ", '";
		$sql .= $db->escape_string($this->title) 		. "', ";
		$sql .= $db->escape_string($this->itemid) 		. ", '";
		$sql .= $db->escape_string($this->itemtype) 	. "', ";
		$sql .= $db->escape_string($this->pending) 		. ", ";
		$sql .= $db->escape_string($this->enabled) 		. ", ";
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
		$sql .= C_NOTIFICATION_FROMUSERID	. "=". $db->escape_string($this->fromuserid) 	. ", ";
		$sql .= C_NOTIFICATION_TOUSERID 	. "=". $db->escape_string($this->touserid) 		. ", ";
		$sql .= C_NOTIFICATION_TITLE 		. "='". $db->escape_string($this->title) 		. "', ";
		$sql .= C_NOTIFICATION_ITEMID 		. "=". $db->escape_string($this->itemid) 		. ", ";
		$sql .= C_NOTIFICATION_ITEMTYPE 	. "='". $db->escape_string($this->itemtype) 	. "', ";
		$sql .= C_NOTIFICATION_PENDING 		. "=". $db->escape_string($this->pending) 		. ", ";
		$sql .= C_NOTIFICATION_ENABLED 		. "=". $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_NOTIFICATION_DATE 		. "=" . "NOW()" 								. " ";
		$sql .="WHERE " . self::$col_id 	. "=" . $db->escape_string($this->id) 		. "";
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
		$this_class->id 		= $record[C_NOTIFICATION_ID];
		$this_class->fromuserid = $record[C_NOTIFICATION_FROMUSERID];
		$this_class->touserid 	= $record[C_NOTIFICATION_TOUSERID];
		$this_class->title 		= $record[C_NOTIFICATION_TITLE];
		$this_class->itemid 	= $record[C_NOTIFICATION_ITEMID];
		$this_class->itemtype 	= $record[C_NOTIFICATION_ITEMTYPE];
		$this_class->pending 	= $record[C_NOTIFICATION_PENDING];
		$this_class->enabled 	= $record[C_NOTIFICATION_ENABLED];
		$this_class->date 		= $record[C_NOTIFICATION_DATE];
		return $this_class;
	}

	public static function get($id)
	{
		global $db;
		$id = $db->escape_string($id);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_NOTIFICATION_TOUSERID." = ".$id;
		$sql .= " ORDER BY ".C_NOTIFICATION_PENDING." DESC";
		$sql .= " , ".C_NOTIFICATION_DATE." DESC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function get_unread($id)
	{
		global $db;
		$id = $db->escape_string($id);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_NOTIFICATION_TOUSERID." = ".$id;
		$sql .= " AND ".C_NOTIFICATION_PENDING." = 1";
		$sql .= " ORDER BY ".C_NOTIFICATION_PENDING." DESC";
		$sql .= " , ".C_NOTIFICATION_DATE." DESC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function delete_by_userid($id)
	{
		global $db;
		$id 	= $db->escape_string($id);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_NOTIFICATION_TOUSERID."=".$id;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}
}

?>