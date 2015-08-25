<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class ClubUser extends DatabaseObject
{
	protected static $table_name = T_CLUBUSERS;
	protected static $col_id = C_CLUBUSER_ID;

	public $id;
	public $clubid;
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
		$sql .= C_CLUBUSER_CLUBID 		.", ";
		$sql .= C_CLUBUSER_USERID 		.", ";
		$sql .= C_CLUBUSER_LEVEL 		.", ";
		$sql .= C_CLUBUSER_ROLE 		.", ";
		$sql .= C_CLUBUSER_PENDING 		.", ";
		$sql .= C_CLUBUSER_ENABLED 		.", ";
		$sql .= C_CLUBUSER_DATE;
		$sql .=") VALUES (";
		$sql .= " ".$db->escape_string($this->clubid) 		. ", ";
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
		$sql .= C_CLUBUSER_CLUBID 		. "=" . $db->escape_string($this->club) 		. ", ";
		$sql .= C_CLUBUSER_USERID 		. "=" . $db->escape_string($this->userid) 		. ", ";
		$sql .= C_CLUBUSER_LEVEL 		. "=" . $db->escape_string($this->level) 		. ", ";
		$sql .= C_CLUBUSER_ROLE 		. "='" . $db->escape_string($this->role) 		. "', ";
		$sql .= C_CLUBUSER_PENDING 		. "=" . $db->escape_string($this->pending) 		. ", ";
		$sql .= C_CLUBUSER_ENABLED 		. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_CLUBUSER_DATE 		. "="  . "NOW()" 								. " ";
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
		$this_class->id 				= $record[C_CLUBUSER_ID];
		$this_class->clubid 			= $record[C_CLUBUSER_CLUBID];
		$this_class->userid 			= $record[C_CLUBUSER_USERID];
		$this_class->level 				= $record[C_CLUBUSER_LEVEL];
		$this_class->role 				= $record[C_CLUBUSER_ROLE];
		$this_class->pending 			= $record[C_CLUBUSER_PENDING];
		$this_class->enabled 			= $record[C_CLUBUSER_ENABLED];
		$this_class->date 				= $record[C_CLUBUSER_DATE];
		return $this_class;
	}

	public static function getAdmins($id)
	{
		global $db;
		$id = $db->escape_string($id);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_CLUBUSER_CLUBID." = ".$id;
		$sql .= " AND ".C_CLUBUSER_LEVEL." = 1";
		
		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUser($userid, $clubid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$clubid 	= $db->escape_string($clubid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_CLUBUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_CLUBUSER_CLUBID . " = " . $clubid;
		
		$result_array = self::get_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function getUsersInMultipleClubs($clubids)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($clubids as $clubid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_CLUBUSER_CLUBID." = ".$clubid;
			}
			else if($counter == count($clubids))
			{
				$sql .= " GROUP BY ".C_CLUBUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_CLUBUSER_CLUBID." = ".$clubid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInMultipleClubsSearch($clubid, $input)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($clubids as $clubid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_CLUBUSER_CLUBID." = ".$clubid;
				$sql .= " AND ".C_CLUBUSER_USERID;
				$sql .= " IN (SELECT ".C_USER_ID." FROM ".T_USERS;
				$sql .= " WHERE ".C_USER_FIRSTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_MIDDLENAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_LASTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_USERNAME." LIKE '%".$input."%')";
			}
			else if($counter == count($clubids))
			{
				$sql .= " GROUP BY ".C_CLUBUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_CLUBUSER_CLUBID." = ".$clubid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getClubsImIn($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_CLUBUSER_USERID." = ".$userid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getStudentsInClub($clubid)
	{
		global $db;
		$clubid 	= $db->escape_string($clubid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_CLUBUSER_CLUBID." = ".$clubid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getAdminClubs($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_CLUBUSER_USERID." = ".$userid;
		$sql .= " AND ".C_CLUBUSER_LEVEL." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function userExists($userid, $clubid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$clubid 	= $db->escape_string($clubid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_CLUBUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_CLUBUSER_CLUBID . " = " . $clubid;
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function amIAdmin($userid, $clubid)
	{
		global $db;
		$userid= $db->escape_string($userid);
		$clubid= $db->escape_string($clubid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_CLUBUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_CLUBUSER_CLUBID . " = " . $clubid;
		$sql .= " AND " 	. C_CLUBUSER_LEVEL . " = 1";
		$sql .= " LIMIT 1";
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function delete_all_by_userid($id)
	{
		global $db;
		$id 	= $db->escape_string($id);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_CLUBUSER_USERID."=".$id;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function delete_all_by_clubid($clubid)
	{
		global $db;
		$clubid 	= $db->escape_string($clubid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_CLUBUSER_CLUBID."=".$clubid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function get_all_pending()
	{
		global $db;

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_CLUBUSER_PENDING." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>