<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class SectionUser extends DatabaseObject
{
	protected static $table_name = T_SECTIONUSERS;
	protected static $col_id = C_SECTIONUSER_ID;

	public $id;
	public $pending = 1;
	public $enabled = 0;
	public $userid;
	public $schoolid;
	public $batchid;
	public $sectionid;
	public $level;
	public $role;
	public $date;
	
	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_SECTIONUSER_PENDING 		.", ";
		$sql .= C_SECTIONUSER_ENABLED 		.", ";
		$sql .= C_SECTIONUSER_USERID 		.", ";
		$sql .= C_SECTIONUSER_SCHOOLID 		.", ";
		$sql .= C_SECTIONUSER_BATCHID 		.", ";
		$sql .= C_SECTIONUSER_SECTIONID 	.", ";
		$sql .= C_SECTIONUSER_LEVEL 		.", ";
		$sql .= C_SECTIONUSER_ROLE 			.", ";
		$sql .= C_SECTIONUSER_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->pending) 		. ", ";
		$sql .= $db->escape_string($this->enabled) 		. ", ";
		$sql .= $db->escape_string($this->userid) 		. ", ";
		$sql .= $db->escape_string($this->schoolid) 	. ", ";
		$sql .= $db->escape_string($this->batchid) 		. ", ";
		$sql .= $db->escape_string($this->sectionid) 	. ", ";
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
		$sql .= C_SECTIONUSER_PENDING 	. "=" . $db->escape_string($this->pending) 		. ", ";
		$sql .= C_SECTIONUSER_ENABLED 	. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_SECTIONUSER_USERID 	. "=" . $db->escape_string($this->userid) 		. ", ";
		$sql .= C_SECTIONUSER_SCHOOLID 	. "=" . $db->escape_string($this->schoolid) 	. ", ";
		$sql .= C_SECTIONUSER_BATCHID 	. "=" . $db->escape_string($this->batchid) 		. ", ";
		$sql .= C_SECTIONUSER_SECTIONID . "=" . $db->escape_string($this->sectionid) 	. ", ";
		$sql .= C_SECTIONUSER_LEVEL 	. "=" . $db->escape_string($this->level) 		. ", ";
		$sql .= C_SECTIONUSER_ROLE 	. "='" . $db->escape_string($this->level) 		. "', ";
		$sql .= C_SECTIONUSER_DATE 		. "="  . "NOW()" 								. " ";
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
		$this_class->id 				= $record[C_SECTIONUSER_ID];
		$this_class->pending 			= $record[C_SECTIONUSER_PENDING];
		$this_class->enabled 			= $record[C_SECTIONUSER_ENABLED];
		$this_class->userid 			= $record[C_SECTIONUSER_USERID];
		$this_class->schoolid 			= $record[C_SECTIONUSER_SCHOOLID];
		$this_class->batchid 			= $record[C_SECTIONUSER_BATCHID];
		$this_class->sectionid 			= $record[C_SECTIONUSER_SECTIONID];
		$this_class->level 				= $record[C_SECTIONUSER_LEVEL];
		$this_class->role 				= $record[C_SECTIONUSER_ROLE];
		$this_class->date 				= $record[C_SECTIONUSER_DATE];
		return $this_class;
	}

	public static function getAdmins($id)
	{
		global $db;
		$id = $db->escape_string($id);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_SECTIONUSER_SECTIONID." = ".$id;
		$sql .= " AND ".C_SECTIONUSER_LEVEL." = 1";
		
		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUser($userid, $sectionid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$sectionid 	= $db->escape_string($sectionid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_SECTIONUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_SECTIONUSER_SECTIONID . " = " . $sectionid;
		
		$result_array = self::get_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function userExists($userid, $sectionid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$sectionid 	= $db->escape_string($sectionid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_SECTIONUSER_USERID . " = " . $userid;
		$sql .= " AND " 	. C_SECTIONUSER_SECTIONID . " = " . $sectionid;
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function get($userid, $schoolid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$schoolid 	= $db->escape_string($schoolid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SECTIONUSER_USERID." = ".$userid;
		$sql .= " AND ".C_SECTIONUSER_SCHOOLID." = ".$schoolid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInMultipleSections($sectionids)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($sectionids as $sectionid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_SECTIONUSER_SECTIONID." = ".$sectionid;
			}
			else if($counter == count($sectionids))
			{
				$sql .= " GROUP BY ".C_SECTIONUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_SECTIONUSER_SECTIONID." = ".$sectionid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInMultipleSectionsSearch($sectionids, $input)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($sectionids as $sectionid)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_SECTIONUSER_SECTIONID." = ".$sectionid;
				$sql .= " AND ".C_SECTIONUSER_USERID;
				$sql .= " IN (SELECT ".C_USER_ID." FROM ".T_USERS;
				$sql .= " WHERE ".C_USER_FIRSTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_MIDDLENAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_LASTNAME." LIKE '%".$input."%'";
				$sql .= " OR ".C_USER_USERNAME." LIKE '%".$input."%')";
			}
			else if($counter == count($sectionids))
			{
				$sql .= " GROUP BY ".C_SECTIONUSER_USERID;
			}
			else
			{
				$sql .= " OR ".C_SECTIONUSER_SECTIONID." = ".$sectionid;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getUsersInSection($sectionid)
	{
		global $db;
		$sectionid = $db->escape_string($sectionid);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " INNER JOIN ".T_USERS;
		$sql .= " ON ".T_SECTIONUSERS.".".C_SECTIONUSER_USERID."=".T_USERS.".".C_USER_ID;
		$sql .= " WHERE ".T_SECTIONUSERS.".".C_SECTIONUSER_SECTIONID." = ".$sectionid;
		$sql .= " AND ".T_SECTIONUSERS.".".C_SECTIONUSER_PENDING." = 0";
		$sql .= " AND ".T_SECTIONUSERS.".".C_SECTIONUSER_ENABLED." = 1";
		$sql .= " AND ".T_USERS.".".C_USER_PENDING." = 0";
		$sql .= " AND ".T_USERS.".".C_SECTIONUSER_ENABLED." = 1";
		$sql .= " GROUP BY ".T_SECTIONUSERS.".".C_SECTIONUSER_USERID;
		$sql .= " ORDER BY ".T_USERS.".".C_USER_LASTNAME." ASC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getSectionsIAdminInBatch($userid, $batchid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$batchid 	= $db->escape_string($batchid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SECTIONUSER_USERID." = ".$userid;
		$sql .= " AND ".C_SECTIONUSER_BATCHID." = ".$batchid;
		$sql .= " AND ".C_SECTIONUSER_LEVEL." = 1";
		
		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getSectionsImIn($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SECTIONUSER_USERID." = ".$userid;

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getAdminSections($userid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SECTIONUSER_USERID." = ".$userid;
		$sql .= " AND ".C_SECTIONUSER_LEVEL." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function amIAdmin($userid, $sectionid)
	{
		global $db;
		$userid 	= $db->escape_string($userid);
		$sectionid 	= $db->escape_string($sectionid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_SECTIONUSER_USERID 		. " = " . $userid;
		$sql .= " AND " 	. C_SECTIONUSER_SECTIONID 	. " = " . $sectionid;
		$sql .= " AND " 	. C_SECTIONUSER_LEVEL 		. " = 1";
		$sql .= " LIMIT 1";
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function delete_all_by_userid($id)
	{
		global $db;
		$id 	= $db->escape_string($id);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_SECTIONUSER_USERID."=".$id;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function delete_all_by_sectionid($sectionid)
	{
		global $db;
		$sectionid 	= $db->escape_string($sectionid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_SECTIONUSER_SECTIONID."=".$sectionid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function delete_all_by_batchid($batchid)
	{
		global $db;
		$batchid 	= $db->escape_string($batchid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_SECTIONUSER_BATCHID."=".$batchid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function delete_all_by_schoolid($schoolid)
	{
		global $db;
		$schoolid 	= $db->escape_string($schoolid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_SECTIONUSER_SCHOOLID."=".$schoolid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function get_all_pending()
	{
		global $db;

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SECTIONUSER_PENDING." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>