<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Section extends DatabaseObject
{
	protected static $table_name = T_SECTIONS;
	protected static $col_id = C_SECTION_ID;

	public $id;
	public $comments = 1;
	public $fbcomments = 1;
	public $pending = 1;
	public $enabled = 0;
	public $schoolid;
	public $batchid;
	public $name;
	public $about;
	public $advisermessage;
	public $picture;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 		. self::$table_name . " (";
		$sql .= C_SECTION_COMMENTS 	.", ";
		$sql .= C_SECTION_FBCOMMENTS 	.", ";
		$sql .= C_SECTION_PENDING 	.", ";
		$sql .= C_SECTION_ENABLED 	.", ";
		$sql .= C_SECTION_SCHOOLID 	.", ";
		$sql .= C_SECTION_BATCHID 	.", ";
		$sql .= C_SECTION_NAME		.", ";
		$sql .= C_SECTION_ABOUT		.", ";
		$sql .= C_SECTION_ADVISERMESSAGE		.", ";
		$sql .= C_SECTION_PICTURE	.", ";
		$sql .= C_SECTION_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->comments) 	. ", ";
		$sql .= $db->escape_string($this->fbcomments) 	. ", ";
		$sql .= $db->escape_string($this->pending) 		. ", ";
		$sql .= $db->escape_string($this->enabled) 		. ", ";
		$sql .= $db->escape_string($this->schoolid) 	. ", ";
		$sql .= $db->escape_string($this->batchid) 		. ", '";
		$sql .= $db->escape_string($this->name) 		. "', '";
		$sql .= $db->escape_string($this->about) 		. "', '";
		$sql .= $db->escape_string($this->advisermessage) 		. "', '";
		$sql .= $db->escape_string($this->picture) 		. "', ";
		$sql .= "NOW()" 							 	. " ";
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
		$sql .= C_SECTION_COMMENTS 	. "=". $db->escape_string($this->comments) 		. ", ";
		$sql .= C_SECTION_FBCOMMENTS. "=". $db->escape_string($this->fbcomments) 	. ", ";
		$sql .= C_SECTION_PENDING 	. "=". $db->escape_string($this->pending) 		. ", ";
		$sql .= C_SECTION_ENABLED 	. "=". $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_SECTION_SCHOOLID 	. "=". $db->escape_string($this->schoolid) 		. ", ";
		$sql .= C_SECTION_BATCHID 	. "=". $db->escape_string($this->batchid) 		. ", ";
		$sql .= C_SECTION_NAME 		. "='" . $db->escape_string($this->name) 		. "', ";
		$sql .= C_SECTION_ABOUT 	. "='" . $db->escape_string($this->about) 		. "', ";
		$sql .= C_SECTION_ADVISERMESSAGE 	. "='" . $db->escape_string($this->advisermessage) 		. "', ";
		$sql .= C_SECTION_PICTURE 	. "='" . $db->escape_string($this->picture) 	. "', ";
		$sql .= C_SECTION_DATE 		. "=" . "NOW()" 								. " ";
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
		$this_class->id 		= $record[C_SECTION_ID];
		$this_class->comments 	= $record[C_SECTION_COMMENTS];
		$this_class->fbcomments = $record[C_SECTION_FBCOMMENTS];
		$this_class->pending 	= $record[C_SECTION_PENDING];
		$this_class->enabled 	= $record[C_SECTION_ENABLED];
		$this_class->schoolid 	= $record[C_SECTION_SCHOOLID];
		$this_class->batchid 	= $record[C_SECTION_BATCHID];
		$this_class->name 		= $record[C_SECTION_NAME];
		$this_class->about 		= $record[C_SECTION_ABOUT];
		$this_class->advisermessage 		= $record[C_SECTION_ADVISERMESSAGE];
		$this_class->picture 	= base64_encode($record[C_SECTION_PICTURE]);

		if($this_class->picture == "")
		{
			$this_class->picture = COVER;
		}

		$this_class->date 		= $record[C_SECTION_DATE];
		return $this_class;
	}

	public static function section_exists($name, $batchid)
	{
		if($name != "" && $batchid != "")
		{
			global $db;

			$name = $db->escape_string($name);
			$batchid = $db->escape_string($batchid);

			$sql = "SELECT * FROM " . self::$table_name . " WHERE " . C_SECTION_NAME . " = '" . $name . "' AND ". C_SECTION_BATCHID . " = " . $batchid;
			$result = $db->query($sql);
			
			return ($db->get_num_rows($result) == 1) ? true : false;
		}
		else
		{
			return false;
		}
	}

	public static function get_all_by_batchid($batchid)
	{
		global $db;
		$batchid 	= $db->escape_string($batchid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SECTION_BATCHID." = ".$batchid;
		$sql .= " ORDER BY ".C_SECTION_NAME." ASC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function get_all_by_schoolid($schoolid)
	{
		global $db;
		$schoolid 	= $db->escape_string($schoolid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SECTION_SCHOOLID." = ".$schoolid;
		$sql .= " ORDER BY ".C_SECTION_NAME." ASC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function delete_all_by_schoolid($schoolid)
	{
		global $db;
		$schoolid 	= $db->escape_string($schoolid);

		$sql = "DELETE FROM ".self::$table_name." WHERE ".C_SECTION_SCHOOLID."=".$schoolid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public static function search($input)
	{
		global $db;
		$input 	= $db->escape_string($input);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_SECTION_NAME." LIKE '%".$input."%'";
		$sql .= " AND ".C_SECTION_PENDING." = 0";
		$sql .= " AND ".C_SECTION_ENABLED." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function get_all_pending($batchid)
	{
		global $db;
		$batchid 	= $db->escape_string($batchid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SECTION_PENDING." = 1";
		$sql .= " AND ".C_SECTION_BATCHID." = ".$batchid;
		$sql .= " ORDER BY ".C_SECTION_NAME." DESC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>