<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class SchoolUser extends DatabaseObject
{
	protected static $table_name = T_SCHOOLUSERS;
	protected static $col_id = C_SCHOOLUSER_ID;

	public $id;
	public $pending = 1;
	public $enabled = 0;
	public $schoolid;
	public $userid;
	public $level = 0;
	public $role;
	public $date;
	
	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_SCHOOLUSER_PENDING 		.", ";
		$sql .= C_SCHOOLUSER_ENABLED 		.", ";
		$sql .= C_SCHOOLUSER_SCHOOLID 		.", ";
		$sql .= C_SCHOOLUSER_USERID 		.", ";
		$sql .= C_SCHOOLUSER_LEVEL 			.", ";
		$sql .= C_SCHOOLUSER_ROLE 			.", ";
		$sql .= C_SCHOOLUSER_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->pending) 		. ", ";
		$sql .= $db->escape_string($this->enabled) 		. ", ";
		$sql .= $db->escape_string($this->schoolid) 	. ", ";
		$sql .= $db->escape_string($this->userid) 		. ", ";
		$sql .= $db->escape_string($this->level) 		. ", '";
		$sql .= $db->escape_string($this->role) 		. "', ";
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
		$sql = "UPDATE " 				. self::$table_name . " SET ";
		$sql .= C_SCHOOLUSER_PENDING 	. "=" . $db->escape_string($this->pending) 		. ", ";
		$sql .= C_SCHOOLUSER_ENABLED 	. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_SCHOOLUSER_SCHOOLID 	. "=" . $db->escape_string($this->schoolid) 	. ", ";
		$sql .= C_SCHOOLUSER_USERID 	. "=" . $db->escape_string($this->userid) 		. ", ";
		$sql .= C_SCHOOLUSER_LEVEL 		. "=" . $db->escape_string($this->level) 		. ", ";
		$sql .= C_SCHOOLUSER_ROLE 		. "='" . $db->escape_string($this->role) 		. "', ";
		$sql .= C_SCHOOLUSER_DATE 		. "="  . "NOW()" 								. " ";
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
		$this_class->id 				= $record[C_SCHOOLUSER_ID];
		$this_class->pending 			= $record[C_SCHOOLUSER_PENDING];
		$this_class->enabled 			= $record[C_SCHOOLUSER_ENABLED];
		$this_class->schoolid 			= $record[C_SCHOOLUSER_SCHOOLID];
		$this_class->userid 			= $record[C_SCHOOLUSER_USERID];
		$this_class->level 				= $record[C_SCHOOLUSER_LEVEL];
		$this_class->role 				= $record[C_SCHOOLUSER_ROLE];
		$this_class->date 				= $record[C_SCHOOLUSER_DATE];
		return $this_class;
	}

	public static function getUser($userid, $schoolid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$schoolid 	= $db->escape_string($schoolid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_SCHOOLUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_SCHOOLUSER_SCHOOLID . " = " . $schoolid;
		
		$result_array = self::get_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function getAdmins($id)
	{
		global $db;
		$id = $db->escape_string($id);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_SCHOOLUSER_SCHOOLID." = ".$id;
		$sql .= " AND ".C_SCHOOLUSER_LEVEL." = 1";
		
		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInSchool($id)
	{
		global $db;
		$id = $db->escape_string($id);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_SCHOOLUSER_SCHOOLID." = ".$id;
		
		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInMultipleSchools($schoolids)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($schoolids as $schoolid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_SCHOOLUSER_SCHOOLID." = ".$schoolid;
			}
			else if($counter == count($schoolids))
			{
				$sql .= " GROUP BY ".C_SCHOOLUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_SCHOOLUSER_SCHOOLID." = ".$schoolid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInMultipleSchoolsSearch($schoolids, $input)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($schoolids as $schoolid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_SCHOOLUSER_SCHOOLID." = ".$schoolid;
				$sql .= " AND ".C_SCHOOLUSER_USERID;
				$sql .= " IN (SELECT ".C_USER_ID." FROM ".T_USERS;
				$sql .= " WHERE ".C_USER_FIRSTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_MIDDLENAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_LASTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_USERNAME." LIKE '%".$input."%')";
			}
			else if($counter == count($schoolids))
			{
				$sql .= " GROUP BY ".C_SCHOOLUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_SCHOOLUSER_SCHOOLID." = ".$schoolid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getSchoolsImIn($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SCHOOLUSER_USERID." = ".$userid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getStudentsInSchool($schoolid)
	{
		global $db;
		$schoolid 	= $db->escape_string($schoolid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SCHOOLUSER_SCHOOLID." = ".$schoolid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getAdminSchools($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SCHOOLUSER_USERID." = ".$userid;
		$sql .= " AND ".C_SCHOOLUSER_LEVEL." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function userExists($userid, $schoolid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$schoolid 	= $db->escape_string($schoolid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_SCHOOLUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_SCHOOLUSER_SCHOOLID . " = " . $schoolid;
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function amIAdmin($userid, $schoolid)
	{
		global $db;
		$userid= $db->escape_string($userid);
		$schoolid= $db->escape_string($schoolid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_SCHOOLUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_SCHOOLUSER_SCHOOLID . " = " . $schoolid;
		$sql .= " AND " 	. C_SCHOOLUSER_LEVEL . " = 1";
		$sql .= " LIMIT 1";
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function delete_all_by_userid($id)
	{
		global $db;
		$id 	= $db->escape_string($id);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_SCHOOLUSER_USERID."=".$id;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function delete_all_by_schoolid($schoolid)
	{
		global $db;
		$schoolid 	= $db->escape_string($schoolid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_SCHOOLUSER_SCHOOLID."=".$schoolid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function get_all_pending()
	{
		global $db;

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SCHOOLUSER_PENDING." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>