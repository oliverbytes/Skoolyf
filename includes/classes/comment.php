<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Comment extends DatabaseObject
{
	protected static $table_name = T_COMMENTS;
	protected static $col_id = C_COMMENT_ID;

	public $id;
	public $comment;
	public $userid;
	public $itemid;
	public $itemtype;
	public $pending = 0;
	public $enabled = 1;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 		. self::$table_name . " (";
		$sql .= C_COMMENT_COMMENT 	.", ";
		$sql .= C_COMMENT_USERID 	.", ";
		$sql .= C_COMMENT_ITEMID 	.", ";
		$sql .= C_COMMENT_ITEMTYPE 	.", ";
		$sql .= C_COMMENT_PENDING 	.", ";
		$sql .= C_COMMENT_ENABLED 	.", ";
		$sql .= C_COMMENT_DATE;
		$sql .=") VALUES ('";
		$sql .= $db->escape_string($this->comment) 	. "', ";
		$sql .= $db->escape_string($this->userid) 	. ", ";
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
		$sql .= C_COMMENT_COMMENT	. "='". $db->escape_string($this->comment) 		. "', ";
		$sql .= C_COMMENT_USERID 	. "=". $db->escape_string($this->userid) 		. ", ";
		$sql .= C_COMMENT_ITEMID 	. "=". $db->escape_string($this->itemid) 		. ", ";
		$sql .= C_COMMENT_ITEMTYPE 	. "='". $db->escape_string($this->itemtype) 	. "', ";
		$sql .= C_COMMENT_PENDING 	. "=". $db->escape_string($this->pending) 		. ", ";
		$sql .= C_COMMENT_ENABLED 	. "=". $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_COMMENT_DATE 		. "=" . "NOW()" 								. " ";
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
		$this_class->id 		= $record[C_COMMENT_ID];
		$this_class->comment 	= $record[C_COMMENT_COMMENT];
		$this_class->userid 	= $record[C_COMMENT_USERID];
		$this_class->itemid 	= $record[C_COMMENT_ITEMID];
		$this_class->itemtype 	= $record[C_COMMENT_ITEMTYPE];
		$this_class->pending 	= $record[C_COMMENT_PENDING];
		$this_class->enabled 	= $record[C_COMMENT_ENABLED];
		$this_class->date 		= $record[C_COMMENT_DATE];
		return $this_class;
	}

	public static function get_all_comments($itemid, $itemtype)
	{
		global $db;
		$itemid 	= $db->escape_string($itemid);
		$itemtype 	= $db->escape_string($itemtype);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_COMMENT_ITEMID." = ".$itemid;
		$sql .= " AND ".C_COMMENT_ITEMTYPE." = '".$itemtype."'";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function delete_all_comments($userid, $itemid, $itemtype)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$itemid 	= $db->escape_string($itemid);
		$itemtype 	= $db->escape_string($itemtype);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_COMMENT_USERID."=".$userid;
		$sql .= " AND ".C_COMMENT_ITEMID." = ".$itemid;
		$sql .= " AND ".C_COMMENT_ITEMTYPE." = '".$itemtype."'";
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function get_all_pending($userid, $itemid, $itemtype)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$itemid 	= $db->escape_string($itemid);
		$itemtype 	= $db->escape_string($itemtype);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_COMMENT_USERID." = ".$userid;
		$sql .= " AND ".C_COMMENT_ITEMID." = ".$itemid;
		$sql .= " AND ".C_COMMENT_ITEMTYPE." = '".$itemtype."'";
		$sql .= " AND ".C_COMMENT_PENDING." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>