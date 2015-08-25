<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Batch extends DatabaseObject
{
	protected static $table_name = T_BATCHS;
	protected static $col_id = C_BATCH_ID;

	public $id;
	public $comments = 1;
	public $fbcomments = 1;
	public $pending = 1;
	public $enabled = 0;
	public $fromyear;
	public $schoolid;
	public $picture;
	public $about;
	public $pubdate;
	public $published = 0;
	public $date;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " 		. self::$table_name . " (";
		$sql .= C_BATCH_COMMENTS 	.", ";
		$sql .= C_BATCH_FBCOMMENTS 	.", ";
		$sql .= C_BATCH_PENDING 	.", ";
		$sql .= C_BATCH_ENABLED 	.", ";
		$sql .= C_BATCH_FROMYEAR 	.", ";
		$sql .= C_BATCH_SCHOOLID	.", ";
		$sql .= C_BATCH_ABOUT		.", ";
		$sql .= C_BATCH_PICTURE		.", ";
		$sql .= C_BATCH_PUBDATE		.", ";
		$sql .= C_BATCH_PUBLISHED	.", ";
		$sql .= C_BATCH_DATE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->comments) 	. ", ";
		$sql .= $db->escape_string($this->fbcomments) 	. ", ";
		$sql .= $db->escape_string($this->pending) 		. ", ";
		$sql .= $db->escape_string($this->enabled) 		. ", ";
		$sql .= $db->escape_string($this->fromyear) 	. ", ";
		$sql .= $db->escape_string($this->schoolid) 	. ", '";
		$sql .= $db->escape_string($this->about) 		. "', '";
		$sql .= $db->escape_string($this->picture) 		. "', '";
		$sql .= $db->escape_string($this->pubdate) 		. "', ";
		$sql .= $db->escape_string($this->published) 	. ", ";
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
		$sql .= C_BATCH_COMMENTS 	. "=". $db->escape_string($this->comments) 		. ", ";
		$sql .= C_BATCH_FBCOMMENTS 	. "=". $db->escape_string($this->fbcomments) 	. ", ";
		$sql .= C_BATCH_PENDING 	. "=". $db->escape_string($this->pending) 		. ", ";
		$sql .= C_BATCH_ENABLED 	. "=". $db->escape_string($this->enabled) 		. ", ";
		$sql .= C_BATCH_FROMYEAR 	. "=". $db->escape_string($this->fromyear) 		. ", ";
		$sql .= C_BATCH_SCHOOLID 	. "=" . $db->escape_string($this->schoolid) 	. ", ";
		$sql .= C_BATCH_ABOUT 		. "='" . $db->escape_string($this->about) 		. "', ";
		$sql .= C_BATCH_PICTURE 	. "='" . $db->escape_string($this->picture) 	. "', ";
		$sql .= C_BATCH_PUBDATE 	. "='" . $db->escape_string($this->pubdate) 	. "', ";
		$sql .= C_BATCH_PUBLISHED 	. "=" . $db->escape_string($this->published) 	. ", ";
		$sql .= C_BATCH_DATE 		. "=" . "NOW()" 								. " ";
		$sql .="WHERE " . self::$col_id . "=" . $db->escape_string($this->id) 		. "";
		$db->query($sql);
		return ($db->get_affected_rows() == 1) ? true : false;
	}
	
	public function delete()
	{
		global $db;
		$sql = "DELETE FROM " . self::$table_name;
		$sql .= " WHERE " . self::$col_id . "=" . $this->id . "";
		$db->query($sql);
		return ($db->get_affected_rows() == 1) ? true : false;
	}
	
	protected static function instantiate($record)
	{
		$this_class = new self;
		$this_class->id 		= $record[C_BATCH_ID];
		$this_class->comments 	= $record[C_BATCH_COMMENTS];
		$this_class->fbcomments = $record[C_BATCH_FBCOMMENTS];
		$this_class->pending 	= $record[C_BATCH_PENDING];
		$this_class->enabled 	= $record[C_BATCH_ENABLED];
		$this_class->fromyear 	= $record[C_BATCH_FROMYEAR];
		$this_class->schoolid 	= $record[C_BATCH_SCHOOLID];
		$this_class->about 		= $record[C_BATCH_ABOUT];
		$this_class->picture 	= base64_encode($record[C_BATCH_PICTURE]);
		$this_class->pubdate 	= $record[C_BATCH_PUBDATE];
		$this_class->published 	= $record[C_BATCH_PUBLISHED];

		if($this_class->picture == "")
		{
			$this_class->picture = COVER;
		}

		$this_class->date 		= $record[C_BATCH_DATE];
		return $this_class;
	}

	public static function batch_exists($fromyear, $schoolid)
	{
		if($fromyear != "" && $schoolid != "")
		{
			global $db;

			$fromyear = $db->escape_string($fromyear);
			$schoolid = $db->escape_string($schoolid);

			$sql = "SELECT * FROM " . self::$table_name;
			$sql .= " WHERE " . C_BATCH_FROMYEAR . " = " . $fromyear;
			$sql .= " AND ". C_BATCH_SCHOOLID . " = " . $schoolid;
			$result = $db->query($sql);
			
			return ($db->get_num_rows($result) == 1) ? true : false;
		}
		else
		{
			return false;
		}
	}

	public static function get_all_by_schoolid($schoolid)
	{
		global $db;
		$schoolid 	= $db->escape_string($schoolid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_BATCH_SCHOOLID." = ".$schoolid;
		$sql .= " ORDER BY ".C_BATCH_FROMYEAR." DESC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function getBatchsInMultipleSchools($ids)
	{
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		
		$counter = 0;

		foreach ($ids as $id)
		{
			$counter++;

			if($counter == 1)
			{
				$sql .= " WHERE ".C_BATCH_SCHOOLID." = ".$id;
			}
			else if($counter == count($ids))
			{
				$sql .= " ORDER BY ".C_BATCH_FROMYEAR." DESC";
				$sql .= " GROUP BY ".C_BATCH_ID;
			}
			else
			{
				$sql .= " OR ".C_BATCH_SCHOOLID." = ".$id;
			}
		}

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function delete_all_by_schoolid($schoolid)
	{
		global $db;
		$schoolid 	= $db->escape_string($schoolid);

		$sql = "DELETE FROM ".self::$table_name;
		$sql .= " WHERE ".C_BATCH_SCHOOLID."=".$schoolid;
		$db->query($sql);

		return ($db->get_affected_rows() == 1) ? true : false;
	}

	public function get_batchyear()
	{
		return $this->fromyear."-".($this->fromyear + 1);
	}

	public static function search($input)
	{
		global $db;
		$input 	= $db->escape_string($input);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_BATCH_FROMYEAR." LIKE '%".$input."%'";
		$sql .= " AND ".C_BATCH_PENDING." = 0";
		$sql .= " AND ".C_BATCH_ENABLED." = 1";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function get_all_pending($schoolid)
	{
		global $db;
		$schoolid 	= $db->escape_string($schoolid);

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_BATCH_PENDING." = 1";
		$sql .= " AND ".C_BATCH_SCHOOLID." = ".$schoolid;
		$sql .= " ORDER BY ".C_BATCH_FROMYEAR." DESC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>