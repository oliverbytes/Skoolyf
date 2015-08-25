<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Status extends DatabaseObject
{
	protected static $table_name = T_STATUSES;
	protected static $col_id = C_STATUS_ID;

	public $id;
	public $status;
	public $itemid;
	public $itemtype;
	public $pending;
	public $enabled;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 		. self::$table_name . " (";
		$sql .= C_STATUS_STATUS 	.", ";
		$sql .= C_STATUS_ITEMID 	.", ";
		$sql .= C_STATUS_ITEMTYPE 	.", ";
		$sql .= C_STATUS_PENDING 	.", ";
		$sql .= C_STATUS_ENABLED 	.", ";
		$sql .= C_STATUS_DATE;
		$sql .=") VALUES ('";
		$sql .= $db->escape_string($this->status) 	. "', ";
		$sql .= $db->escape_string($this->itemid) 	. ", '";
		$sql .= $db->escape_string($this->itemtype) . "', ";
		$sql .= $db->escape_string($this->pending) 	. ", ";
		$sql .= $db->escape_string($this->enabled) 	. ", ";
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
		$sql .= C_STATUS_STATUS		. "='". $db->escape_string($this->status) 		. "', ";
		$sql .= C_STATUS_ITEMID 	. "=". $db->escape_string($this->itemid) 		. ", ";
		$sql .= C_STATUS_ITEMTYPE 	. "='". $db->escape_string($this->itemtype) 	. "', ";
		$sql .= C_STATUS_PENDING 	. "=". $db->escape_string($this->pending) 		. ", ";
		$sql .= C_STATUS_ENABLED 	. "=". $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_STATUS_DATE 		. "=" . "NOW()" 								. " ";
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
		$this_class->id 		= $record[C_STATUS_ID];
		$this_class->status 	= $record[C_STATUS_STATUS];
		$this_class->itemid 	= $record[C_STATUS_ITEMID];
		$this_class->itemtype 	= $record[C_STATUS_ITEMTYPE];
		$this_class->pending 	= $record[C_STATUS_PENDING];
		$this_class->enabled 	= $record[C_STATUS_ENABLED];
		$this_class->date 		= $record[C_STATUS_DATE];
		return $this_class;
	}

	public static function getStatuses($itemid, $itemtype)
	{
		global $db;
		$itemid 	= $db->escape_string($itemid);
		$itemtype 	= $db->escape_string($itemtype);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_STATUS_ITEMID." = ".$itemid;
		$sql .= " AND ".C_STATUS_ITEMTYPE." = '".$itemtype."'";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function deleteStatuses($itemid, $itemtype)
	{
		global $db;
		$itemid 	= $db->escape_string($itemid);
		$itemtype 	= $db->escape_string($itemtype);

		$sql = "DELETE FROM ".self::$table_name;
		$sql .= " WHERE ".C_STATUS_ITEMID." = ".$itemid;
		$sql .= " AND ".C_STATUS_ITEMTYPE." = '".$itemtype."'";
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function getPendingStatuses($itemid, $itemtype)
	{
		global $db;
		$itemid 	= $db->escape_string($itemid);
		$itemtype 	= $db->escape_string($itemtype);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_STATUS_ITEMID." = ".$itemid;
		$sql .= " AND ".C_STATUS_ITEMTYPE." = '".$itemtype."'";
		$sql .= " AND ".C_STATUS_PENDING." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>