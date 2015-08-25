<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class GroupUser extends DatabaseObject
{
	protected static $table_name = T_GROUPUSERS;
	protected static $col_id = C_GROUPUSER_ID;

	public $id;
	public $groupid;
	public $userid;
	public $level = 0;
	public $role;
	public $pending = 1;
	public $enabled = 0;
	public $date;
	
	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 			. self::$table_name . " (";
		$sql .= C_GROUPUSER_GROUPID 	.", ";
		$sql .= C_GROUPUSER_USERID 		.", ";
		$sql .= C_GROUPUSER_LEVEL 		.", ";
		$sql .= C_GROUPUSER_ROLE 		.", ";
		$sql .= C_GROUPUSER_PENDING 	.", ";
		$sql .= C_GROUPUSER_ENABLED 	.", ";
		$sql .= C_GROUPUSER_DATE;
		$sql .=") VALUES (";
		$sql .= " ".$db->escape_string($this->groupid) 		. ", ";
		$sql .= " ".$db->escape_string($this->userid) 		. ", ";
		$sql .= " ".$db->escape_string($this->level) 		. ", ";
		$sql .= " '".$db->escape_string($this->role) 		. "', ";
		$sql .= " ".$db->escape_string($this->pending) 		. ", ";
		$sql .= " ".$db->escape_string($this->enabled) 		. ", ";
		$sql .= "NOW()" 									. " ";
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
		$sql .= C_GROUPUSER_GROUPID 	. "=" . $db->escape_string($this->club) 		. ", ";
		$sql .= C_GROUPUSER_USERID 		. "=" . $db->escape_string($this->userid) 		. ", ";
		$sql .= C_GROUPUSER_LEVEL 		. "=" . $db->escape_string($this->level) 		. ", ";
		$sql .= C_GROUPUSER_ROLE 		. "='" . $db->escape_string($this->role) 		. "', ";
		$sql .= C_GROUPUSER_PENDING 	. "=" . $db->escape_string($this->pending) 		. ", ";
		$sql .= C_GROUPUSER_ENABLED 	. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_GROUPUSER_DATE 		. "="  . "NOW()" 								. " ";
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
		$this_class->id 				= $record[C_GROUPUSER_ID];
		$this_class->groupid 			= $record[C_GROUPUSER_GROUPID];
		$this_class->userid 			= $record[C_GROUPUSER_USERID];
		$this_class->level 				= $record[C_GROUPUSER_LEVEL];
		$this_class->role 				= $record[C_GROUPUSER_ROLE];
		$this_class->pending 			= $record[C_GROUPUSER_PENDING];
		$this_class->enabled 			= $record[C_GROUPUSER_ENABLED];
		$this_class->date 				= $record[C_GROUPUSER_DATE];
		return $this_class;
	}

	public static function getAdmins($id)
	{
		global $db;
		$id = $db->escape_string($id);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_GROUPUSER_GROUPID." = ".$id;
		$sql .= " AND ".C_GROUPUSER_LEVEL." = 1";
		
		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUser($userid, $groupid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$groupid 	= $db->escape_string($groupid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_GROUPUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_GROUPUSER_GROUPID . " = " . $groupid;
		
		$result_array = self::get_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function getUsersInMultipleGroups($groupids)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($groupids as $groupid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_GROUPUSER_GROUPID." = ".$groupid;
			}
			else if($counter == count($groupids))
			{
				$sql .= " GROUP BY ".C_GROUPUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_GROUPUSER_GROUPID." = ".$groupid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInMultipleGroupsSearch($groupid, $input)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($groupids as $groupid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_GROUPUSER_GROUPID." = ".$groupid;
				$sql .= " AND ".C_GROUPUSER_USERID;
				$sql .= " IN (SELECT ".C_USER_ID." FROM ".T_USERS;
				$sql .= " WHERE ".C_USER_FIRSTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_MIDDLENAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_LASTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_USERNAME." LIKE '%".$input."%')";
			}
			else if($counter == count($groupids))
			{
				$sql .= " GROUP BY ".C_GROUPUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_GROUPUSER_GROUPID." = ".$groupid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getGroupsImIn($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_GROUPUSER_USERID." = ".$userid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getStudentsInGroup($groupid)
	{
		global $db;
		$groupid 	= $db->escape_string($groupid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_GROUPUSER_GROUPID." = ".$groupid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getAdminGroups($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_GROUPUSER_USERID." = ".$userid;
		$sql .= " AND ".C_GROUPUSER_LEVEL." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function userExists($userid, $groupid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$groupid 	= $db->escape_string($groupid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_GROUPUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_GROUPUSER_GROUPID . " = " . $groupid;
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function amIAdmin($userid, $groupid)
	{
		global $db;
		$userid= $db->escape_string($userid);
		$groupid= $db->escape_string($groupid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_GROUPUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_GROUPUSER_GROUPID . " = " . $groupid;
		$sql .= " AND " 	. C_GROUPUSER_LEVEL . " = 1";
		$sql .= " LIMIT 1";
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function delete_all_by_userid($id)
	{
		global $db;
		$id 	= $db->escape_string($id);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_GROUPUSER_USERID."=".$id;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function delete_all_by_groupid($groupid)
	{
		global $db;
		$groupid 	= $db->escape_string($groupid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_GROUPUSER_GROUPID."=".$groupid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function get_all_pending()
	{
		global $db;

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_GROUPUSER_PENDING." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>