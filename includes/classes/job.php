<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Job extends DatabaseObject
{
	protected static $table_name = T_JOBS;
	protected static $col_id = C_JOB_ID;

	public $id;
	public $userid;
	public $role;
	public $company;
	public $address;
	public $fromdate;
	public $todate;
	public $present = 0;
	public $pending = 0;
	public $enabled = 1;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 	. self::$table_name . " (";
		$sql .= C_JOB_USERID 	.", ";
		$sql .= C_JOB_ROLE 		.", ";
		$sql .= C_JOB_COMPANY 	.", ";
		$sql .= C_JOB_ADDRESS 	.", ";
		$sql .= C_JOB_FROMDATE 	.", ";
		$sql .= C_JOB_TODATE 	.", ";
		$sql .= C_JOB_PRESENT 	.", ";
		$sql .= C_JOB_PENDING 	.", ";
		$sql .= C_JOB_ENABLED 	.", ";
		$sql .= C_JOB_DATE;
		$sql .=") VALUES (";
		$sql .= " ".$db->escape_string($this->userid) 	. ", ";
		$sql .= " '".$db->escape_string($this->role) 	. "', ";
		$sql .= " '".$db->escape_string($this->company) . "', ";
		$sql .= " '".$db->escape_string($this->address) . "', ";
		$sql .= " ".$db->escape_string($this->fromdate) . ", ";
		$sql .= " ".$db->escape_string($this->todate) 	. ", ";
		$sql .= " ".$db->escape_string($this->present) 	. ", ";
		$sql .= " ".$db->escape_string($this->pending) 	. ", ";
		$sql .= " ".$db->escape_string($this->enabled) 	. ", ";
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
		$sql = "UPDATE " 		. self::$table_name . " SET ";
		$sql .= C_JOB_USERID	. "=". $db->escape_string($this->userid) 		. ", ";
		$sql .= C_JOB_ROLE 		. "='". $db->escape_string($this->role) 		. "', ";
		$sql .= C_JOB_COMPANY 	. "='". $db->escape_string($this->company) 		. "', ";
		$sql .= C_JOB_ADDRESS 	. "='". $db->escape_string($this->address) 		. "', ";
		$sql .= C_JOB_FROMDATE 	. "=". $db->escape_string($this->fromdate) 		. ", ";
		$sql .= C_JOB_TODATE 	. "=". $db->escape_string($this->todate) 		. ", ";
		$sql .= C_JOB_PRESENT 	. "=". $db->escape_string($this->present) 		. ", ";
		$sql .= C_JOB_PENDING 	. "=". $db->escape_string($this->pending) 		. ", ";
		$sql .= C_JOB_ENABLED 	. "=". $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_JOB_DATE 		. "=" . "NOW()" 								. " ";
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
		$this_class->id 		= $record[C_JOB_ID];
		$this_class->userid 	= $record[C_JOB_USERID];
		$this_class->role 		= $record[C_JOB_ROLE];
		$this_class->company 	= $record[C_JOB_COMPANY];
		$this_class->address 	= $record[C_JOB_ADDRESS];
		$this_class->fromdate 	= $record[C_JOB_FROMDATE];
		$this_class->todate 	= $record[C_JOB_TODATE];
		$this_class->present 	= $record[C_JOB_PRESENT];
		$this_class->pending 	= $record[C_JOB_PENDING];
		$this_class->enabled 	= $record[C_JOB_ENABLED];
		$this_class->date 		= $record[C_JOB_DATE];
		return $this_class;
	}

	public static function get($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_JOB_USERID." = ".$userid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>