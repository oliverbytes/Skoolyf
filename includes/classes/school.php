<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class School extends DatabaseObject
{
	protected static $table_name = T_SCHOOLS;
	protected static $col_id = C_SCHOOL_ID;

	public $id;
	public $comments = 1;
	public $fbcomments = 1;
	public $pending = 1;
	public $enabled = 0;
	public $name;
	public $address;
	public $number;
	public $email;
	public $about;
	public $picture;
	public $logo;
	public $history;
	public $visionmission;
	public $corevalues;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 			. self::$table_name . " (";
		$sql .= C_SCHOOL_COMMENTS 		.", ";
		$sql .= C_SCHOOL_FBCOMMENTS 	.", ";
		$sql .= C_SCHOOL_PENDING 		.", ";
		$sql .= C_SCHOOL_ENABLED 		.", ";
		$sql .= C_SCHOOL_NAME 			.", ";
		$sql .= C_SCHOOL_ADDRESS		.", ";
		$sql .= C_SCHOOL_NUMBER 		.", ";
		$sql .= C_SCHOOL_EMAIL			.", ";
		$sql .= C_SCHOOL_ABOUT			.", ";
		$sql .= C_SCHOOL_PICTURE		.", ";
		$sql .= C_SCHOOL_LOGO			.", ";
		$sql .= C_SCHOOL_HISTORY		.", ";
		$sql .= C_SCHOOL_VISIONMISSION	.", ";
		$sql .= C_SCHOOL_COREVALUES		.", ";
		$sql .= C_SCHOOL_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($th1is->comments) 		. ", ";
		$sql .= $db->escape_string($this->fbcomments) 		. ", ";
		$sql .= $db->escape_string($this->pending) 			. ", ";
		$sql .= $db->escape_string($this->enabled) 			. ", '";
		$sql .= $db->escape_string($this->name) 			. "', '";
		$sql .= $db->escape_string($this->address) 			. "', '";
		$sql .= $db->escape_string($this->number) 			. "', '";
		$sql .= $db->escape_string($this->email) 			. "', '";
		$sql .= $db->escape_string($this->about) 			. "', '";
		$sql .= $db->escape_string($this->picture) 			. "', '";
		$sql .= $db->escape_string($this->logo) 			. "', ";
		$sql .= $db->escape_string($this->history) 			. "', '";
		$sql .= $db->escape_string($this->visionmission) 	. "', '";
		$sql .= $db->escape_string($this->corevalues) 		. "', ";
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
		$sql .= C_SCHOOL_COMMENTS 		. "=" . $db->escape_string($this->comments) 	. ", ";
		$sql .= C_SCHOOL_FBCOMMENTS 	. "=" . $db->escape_string($this->fbcomments) 	. ", ";
		$sql .= C_SCHOOL_PENDING 		. "=" . $db->escape_string($this->pending) 		. ", ";
		$sql .= C_SCHOOL_ENABLED 		. "=" . $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_SCHOOL_NAME 			. "='" . $db->escape_string($this->name) 		. "', ";
		$sql .= C_SCHOOL_ADDRESS 		. "='" . $db->escape_string($this->address) 	. "', ";
		$sql .= C_SCHOOL_NUMBER 		. "='" . $db->escape_string($this->number) 		. "', ";
		$sql .= C_SCHOOL_EMAIL 			. "='" . $db->escape_string($this->email) 		. "', ";
		$sql .= C_SCHOOL_ABOUT 			. "='" . $db->escape_string($this->about) 		. "', ";
		$sql .= C_SCHOOL_PICTURE 		. "='" . $db->escape_string($this->picture) 	. "', ";
		$sql .= C_SCHOOL_LOGO 			. "='" . $db->escape_string($this->logo) 		. "', ";
		$sql .= C_SCHOOL_HISTORY 		. "='" . $db->escape_string($this->history) 		. "', ";
		$sql .= C_SCHOOL_VISIONMISSION 	. "='" . $db->escape_string($this->visionmission) 	. "', ";
		$sql .= C_SCHOOL_COREVALUES 	. "='" . $db->escape_string($this->corevalues) 		. "', ";
		$sql .= C_SCHOOL_DATE 			. "="  . "NOW()" 								. " ";
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
		$this_class->id 		= $record[C_SCHOOL_ID];
		$this_class->comments 	= $record[C_SCHOOL_COMMENTS];
		$this_class->fbcomments = $record[C_SCHOOL_FBCOMMENTS];
		$this_class->pending 	= $record[C_SCHOOL_PENDING];
		$this_class->enabled 	= $record[C_SCHOOL_ENABLED];
		$this_class->name 		= $record[C_SCHOOL_NAME];
		$this_class->address 	= $record[C_SCHOOL_ADDRESS];
		$this_class->number 	= $record[C_SCHOOL_NUMBER];
		$this_class->email 		= $record[C_SCHOOL_EMAIL];
		$this_class->about 		= $record[C_SCHOOL_ABOUT];
		$this_class->picture 	= base64_encode($record[C_SCHOOL_PICTURE]);
		$this_class->logo 		= base64_encode($record[C_SCHOOL_LOGO]);

		if($this_class->picture == "")
		{
			$this_class->picture = COVER;
		}

		if($this_class->picture == "")
		{
			$this_class->picture = PROFILE;
		}

		$this_class->history 			= $record[C_SCHOOL_HISTORY];
		$this_class->visionmission 		= $record[C_SCHOOL_VISIONMISSION];
		$this_class->corevalues 		= $record[C_SCHOOL_COREVALUES];
		$this_class->date 				= $record[C_SCHOOL_DATE];
		return $this_class;
	}

	public static function name_exists($name)
	{
		global $db;
		$name = $db->escape_string($name);
		$sql = "SELECT * FROM " . self::$table_name . " WHERE " . C_SCHOOL_NAME . " = '" . $name . "'";
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function get_by_name($name)
	{
		global $db;
		$name = $db->escape_string($name);
		$sql = "SELECT * FROM " . self::$table_name . " WHERE " . C_SCHOOL_NAME . " = '" . $name . "'";
		$result_array = self::get_by_sql($sql);

		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function search($input)
	{
		global $db;
		$input 	= $db->escape_string($input);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_SCHOOL_NAME." LIKE '%".$input."%'";
		$sql .= " AND ".C_SCHOOL_PENDING." = 0";
		$sql .= " AND ".C_SCHOOL_ENABLED." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function get_all_pending()
	{
		global $db;

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_SCHOOL_PENDING." = 1";
		$sql .= " ORDER BY ".C_SCHOOL_NAME." DESC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>