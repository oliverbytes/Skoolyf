<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class BatchUser extends DatabaseObject
{
	protected static $table_name = T_BATCHUSERS;
	protected static $col_id = C_BATCHUSER_ID;


	public $id;
	public $pending = 1;
	public $enabled = 0;
	public $schoolid;
	public $batchid;
	public $userid;
	public $level = 0;
	public $role;
	public $date;
	
	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_BATCHUSER_PENDING 	.", ";
		$sql .= C_BATCHUSER_ENABLED 	.", ";
		$sql .= C_BATCHUSER_SCHOOLID 	.", ";
		$sql .= C_BATCHUSER_BATCHID 	.", ";
		$sql .= C_BATCHUSER_USERID 		.", ";
		$sql .= C_BATCHUSER_LEVEL 		.", ";
		$sql .= C_BATCHUSER_ROLE 		.", ";
		$sql .= C_BATCHUSER_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->pending) 		. ", ";
		$sql .= $db->escape_string($this->enabled) 		. ", ";
		$sql .= $db->escape_string($this->schoolid) 	. ", ";
		$sql .= $db->escape_string($this->batchid) 		. ", ";
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
		$sql .= C_BATCHUSER_PENDING 	. "=" . $db->escape_string($this->pending) 		. ", ";
		$sql .= C_BATCHUSER_ENABLED 	. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_BATCHUSER_SCHOOLID 	. "=" . $db->escape_string($this->schoolid) 	. ", ";
		$sql .= C_BATCHUSER_BATCHID 	. "=" . $db->escape_string($this->batchid) 		. ", ";
		$sql .= C_BATCHUSER_USERID 		. "=" . $db->escape_string($this->userid) 		. ", ";
		$sql .= C_BATCHUSER_LEVEL 		. "=" . $db->escape_string($this->level) 		. ", ";
		$sql .= C_BATCHUSER_ROLE 		. "='" . $db->escape_string($this->role) 		. "', ";
		$sql .= C_BATCHUSER_DATE 		. "="  . "NOW()" 								. " ";
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
		$this_class->id 				= $record[C_BATCHUSER_ID];
		$this_class->pending 			= $record[C_BATCHUSER_PENDING];
		$this_class->enabled 			= $record[C_BATCHUSER_ENABLED];
		$this_class->schoolid 			= $record[C_BATCHUSER_SCHOOLID];
		$this_class->batchid 			= $record[C_BATCHUSER_BATCHID];
		$this_class->userid 			= $record[C_BATCHUSER_USERID];
		$this_class->level 				= $record[C_BATCHUSER_LEVEL];
		$this_class->role 				= $record[C_BATCHUSER_ROLE];
		$this_class->date 				= $record[C_BATCHUSER_DATE];
		return $this_class;
	}

	public static function getAdmins($id)
	{
		global $db;
		$id = $db->escape_string($id);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_BATCHUSER_BATCHID." = ".$id;
		$sql .= " AND ".C_BATCHUSER_LEVEL." = 1";
		
		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getBatchsInSchool($id)
	{
		global $db;
		$id 	= $db->escape_string($id);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_BATCHUSER_SCHOOLID . " = " . $id;
		$sql .= " GROUP BY " 	. C_BATCHUSER_BATCHID;
		
		$result_array = self::get_by_sql($sql);
		return !empty($result_array) ? $result_array : null;
	}

	public static function getUser($userid, $batchid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$batchid 	= $db->escape_string($batchid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_BATCHUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_BATCHUSER_BATCHID . " = " . $batchid;
		
		$result_array = self::get_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function userExists($userid, $batchid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$batchid 	= $db->escape_string($batchid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_BATCHUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_BATCHUSER_BATCHID . " = " . $batchid;
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function get($userid, $schoolid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$schoolid 	= $db->escape_string($schoolid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_BATCHUSER_USERID." = ".$userid;
		$sql .= " AND ".C_BATCHUSER_SCHOOLID." = ".$schoolid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInBatch($batchid)
	{
		global $db;
		$batchid = $db->escape_string($batchid);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_BATCHUSER_BATCHID." = ".$batchid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInBatchOrdered($batchid, $orderby, $order)
	{
		global $db;
		$batchid = $db->escape_string($batchid);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " INNER JOIN ".T_USERS;
		$sql .= " ON ".T_BATCHUSERS.".".C_BATCHUSER_USERID."=".T_USERS.".".C_USER_ID;
		$sql .= " WHERE ".C_BATCHUSER_BATCHID." = ".$batchid;
		$sql .= " GROUP BY ".T_BATCHUSERS.".".C_BATCHUSER_USERID;
		$sql .= " ORDER BY ".T_USERS.".".$orderby." ".$order;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInSchool($schoolid)
	{
		global $db;
		$schoolid = $db->escape_string($schoolid);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_BATCHUSER_SCHOOLID." = ".$schoolid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInMultipleBatch($batchids)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($batchids as $batchid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_BATCHUSER_BATCHID." = ".$batchid;
			}
			else if($counter == count($batchids))
			{
				$sql .= " GROUP BY ".C_BATCHUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_BATCHUSER_BATCHID." = ".$batchid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInMultipleBatchsSearch($batchids, $input)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($batchids as $batchid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_BATCHUSER_BATCHID." = ".$batchid;
				$sql .= " AND ".C_BATCHUSER_USERID;
				$sql .= " IN (SELECT ".C_USER_ID." FROM ".T_USERS;
				$sql .= " WHERE ".C_USER_FIRSTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_MIDDLENAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_LASTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_USERNAME." LIKE '%".$input."%')";
			}
			else if($counter == count($batchids))
			{
				$sql .= " GROUP BY ".C_BATCHUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_BATCHUSER_BATCHID." = ".$batchid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getBatchsIAdminInSchool($userid, $schoolid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$schoolid 	= $db->escape_string($schoolid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_BATCHUSER_USERID." = ".$userid;
		$sql .= " AND ".C_BATCHUSER_SCHOOLID." = ".$schoolid;
		$sql .= " AND ".C_BATCHUSER_LEVEL." = 1";
		
		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getBatchsImIn($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_BATCHUSER_USERID." = ".$userid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getAdminBatchs($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_BATCHUSER_USERID." = ".$userid;
		$sql .= " AND ".C_BATCHUSER_LEVEL." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function amIAdmin($userid, $batchid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$batchid 	= $db->escape_string($batchid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_BATCHUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_BATCHUSER_BATCHID . " = " . $batchid;
		$sql .= " AND " 	. C_BATCHUSER_LEVEL . " = 1";
		$sql .= " LIMIT 1";
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function delete_all_by_userid($id)
	{
		global $db;
		$id 	= $db->escape_string($id);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_BATCHUSER_USERID."=".$id;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function delete_all_by_batchid($batchid)
	{
		global $db;
		$batchid 	= $db->escape_string($batchid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_BATCHUSER_BATCHID."=".$batchid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function delete_all_by_schoolid($schoolid)
	{
		global $db;
		$schoolid 	= $db->escape_string($schoolid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_BATCHUSER_SCHOOLID."=".$schoolid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function get_all_pending()
	{
		global $db;

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_BATCHUSER_PENDING." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>