<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Friend extends DatabaseObject
{
	protected static $table_name = T_FRIENDS;
	protected static $col_id = C_FRIEND_ID;

	public $id;
	public $userid;
	public $touserid;
	public $pending = 1;
	public $enabled = 1;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 		. self::$table_name . " (";
		$sql .= C_FRIEND_USERID 	.", ";
		$sql .= C_FRIEND_TOUSERID 	.", ";
		$sql .= C_FRIEND_PENDING 	.", ";
		$sql .= C_FRIEND_ENABLED 	.", ";
		$sql .= C_FRIEND_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->userid) 	. ", ";
		$sql .= $db->escape_string($this->touserid) . ", ";
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
		$sql .= C_FRIEND_USERID 	. "=". $db->escape_string($this->userid) 		. ", ";
		$sql .= C_FRIEND_TOUSERID 	. "=". $db->escape_string($this->touserid) 		. ", ";
		$sql .= C_FRIEND_PENDING 	. "=". $db->escape_string($this->pending) 		. ", ";
		$sql .= C_FRIEND_ENABLED 	. "=". $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_FRIEND_DATE 		. "=" . "NOW()" 								. " ";
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
		$this_class->id 		= $record[C_FRIEND_ID];
		$this_class->userid 	= $record[C_FRIEND_USERID];
		$this_class->touserid 	= $record[C_FRIEND_TOUSERID];
		$this_class->pending 	= $record[C_FRIEND_PENDING];
		$this_class->enabled 	= $record[C_FRIEND_ENABLED];
		$this_class->date 		= $record[C_FRIEND_DATE];
		return $this_class;
	}

	public static function getFriends($userid, $input)
	{
		global $db;

		$userid = $db->escape_string($userid);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_FRIEND_USERID;
		$sql .= " IN (SELECT ".C_USER_ID." FROM ".T_USERS;
		$sql .= " WHERE ".C_USER_FIRSTNAME." LIKE '%".$input."%'";
		$sql .= " OR ".C_USER_MIDDLENAME." LIKE '%".$input."%'";
		$sql .= " OR ".C_USER_LASTNAME." LIKE '%".$input."%'";
		$sql .= " OR ".C_USER_USERNAME." LIKE '%".$input."%')";
		$sql .= " OR ".C_FRIEND_TOUSERID;
		$sql .= " IN (SELECT ".C_USER_ID." FROM ".T_USERS;
		$sql .= " WHERE ".C_USER_FIRSTNAME." LIKE '%".$input."%'";
		$sql .= " OR ".C_USER_MIDDLENAME." LIKE '%".$input."%'";
		$sql .= " OR ".C_USER_LASTNAME." LIKE '%".$input."%'";
		$sql .= " OR ".C_USER_USERNAME." LIKE '%".$input."%')";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getApprovedFriends($userid)
	{
		global $db;

		$userid = $db->escape_string($userid);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_FRIEND_USERID." = ".$userid;
		$sql .= " OR ".C_FRIEND_TOUSERID." = ".$userid;
		$sql .= " AND ".C_FRIEND_PENDING." = 0";
		$sql .= " AND ".C_FRIEND_ENABLED." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getFriendship($userid, $touserid)
	{
		global $db;

		$userid = $db->escape_string($userid);
		$touserid = $db->escape_string($touserid);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_FRIEND_USERID." = ".$userid;
		$sql .= " AND ".C_FRIEND_TOUSERID." = ".$touserid;
		$sql .= " OR ".C_FRIEND_USERID." = ".$touserid;
		$sql .= " AND ".C_FRIEND_TOUSERID." = ".$userid;
		$sql .= " LIMIT 1";

		$result_array = self::get_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function isFriends($userid, $touserid)
	{
		global $db;

		$userid = $db->escape_string($userid);
		$touserid = $db->escape_string($touserid);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_FRIEND_USERID." = ".$userid;
		$sql .= " AND ".C_FRIEND_TOUSERID." = ".$touserid;
		$sql .= " OR ".C_FRIEND_USERID." = ".$touserid;
		$sql .= " AND ".C_FRIEND_TOUSERID." = ".$userid;

		$result = $db->query($sql);
		
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function get_all_by_userid($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_FRIEND_USERID." = ".$userid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function delete_all_by_userid($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_FRIEND_USERID."=".$userid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function get_all_pending($batchid)
	{
		global $db;
		$batchid 	= $db->escape_string($batchid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_FRIEND_PENDING." = 1";
		$sql .= " AND ".C_FRIEND_BATCHID." = ".$batchid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>