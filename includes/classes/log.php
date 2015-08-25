<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Log extends DatabaseObject
{
	protected static $table_name = T_LOGS;
	protected static $col_id = C_LOGS_ID;

	public $id;
	public $user_id;
	public $ip;
	public $platform;
	public $action;
	public $date;
	
	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_LOGS_USER_ID 		.", ";
		$sql .= C_LOGS_IP 			.", ";
		$sql .= C_LOGS_PLATFORM		.", ";
		$sql .= C_LOGS_ACTION 		.", ";
		$sql .= C_LOGS_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->user_id) 		. ", '";
		$sql .= $db->escape_string($this->ip) 			. "', '";
		$sql .= $db->escape_string($this->platform) 	. "', '";
		$sql .= $db->escape_string($this->action) 		. "', ";
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
		$sql .= C_LOGS_USER_ID 		. "=" . $db->escape_string($this->user_id) 			. ", ";
		$sql .= C_LOGS_IP 			. "='" . $db->escape_string($this->ip) 				. "', ";
		$sql .= C_LOGS_PLATFORM 	. "='" . $db->escape_string($this->platform) 		. "', ";
		$sql .= C_LOGS_ACTION 		. "='" . $db->escape_string($this->action) 			. "', ";
		$sql .= C_LOGS_DATE 		. "="  . "NOW()" 									. " ";
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
		$this_class = new self(0, "", "", "");
		$this_class->id 				= $record[C_LOGS_ID];
		$this_class->user_id 			= $record[C_LOGS_USER_ID];
		$this_class->ip 				= $record[C_LOGS_IP];
		$this_class->platform 			= $record[C_LOGS_PLATFORM];
		$this_class->action 			= $record[C_LOGS_ACTION];
		$this_class->date 				= $record[C_LOGS_DATE];
		return $this_class;
	}

	function __construct($userid, $ip, $platform, $action)
	{
		$this->user_id 		= $userid;
		$this->ip 			= $ip;
		$this->platform 	= $platform;
		$this->action 		= $action;

		if($this->ip == "::1")
		{
			$this->ip = "LOCALHOST";
		}
	}
}

?>