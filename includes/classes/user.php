<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class User extends DatabaseObject
{
	protected static $table_name = T_USERS;
	protected static $col_id = C_USER_ID;

	public $id;
	public $username;
	public $password;
	public $email;
	public $firstname;
	public $middlename;
	public $lastname;
	public $gender = 0;
	public $address;
	public $moto;
	public $birthdate;
	public $picture;
	public $cover;
	public $number;
	public $date;
	public $comments = 1;
	public $fbcomments = 1;
	public $pending = 1;
	public $enabled = 0;
	public $oauth_uid = 0;
	public $oauth_provider;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_USER_USERNAME		.", ";
		$sql .= C_USER_PASSWORD 	.", ";
		$sql .= C_USER_EMAIL 		.", ";
		$sql .= C_USER_FIRSTNAME 	.", ";
		$sql .= C_USER_MIDDLENAME 	.", ";
		$sql .= C_USER_LASTNAME 	.", ";
		$sql .= C_USER_GENDER 		.", ";
		$sql .= C_USER_ADDRESS 		.", ";
		$sql .= C_USER_MOTO 		.", ";
		$sql .= C_USER_BIRTHDATE	.", ";
		$sql .= C_USER_PICTURE 		.", ";
		$sql .= C_USER_COVER 		.", ";
		$sql .= C_USER_NUMBER 		.", ";
		$sql .= C_USER_DATE 		.", ";
		$sql .= C_USER_COMMENTS 	.", ";
		$sql .= C_USER_FBCOMMENTS 	.", ";
		$sql .= C_USER_PENDING 		.", ";
		$sql .= C_USER_ENABLED 		.", ";
		$sql .= C_USER_OAUTH_UID	.", ";
		$sql .= C_USER_OAUTH_PROVIDER;
		$sql .=") VALUES (";
		$sql .= " '".$db->escape_string($this->username) 		. "', ";
		$sql .= " '".$db->escape_string($this->password) 		. "', ";
		$sql .= " '".$db->escape_string($this->email) 			. "', ";
		$sql .= " '".$db->escape_string($this->firstname) 		. "', ";
		$sql .= " '".$db->escape_string($this->middlename) 		. "', ";
		$sql .= " '".$db->escape_string($this->lastname) 		. "', ";
		$sql .= " ".$db->escape_string($this->gender) 			. ", ";
		$sql .= " '".$db->escape_string($this->address) 		. "', ";
		$sql .= " '".$db->escape_string($this->moto) 			. "', ";
		$sql .= " '".$db->escape_string($this->birthdate) 		. "', ";
		$sql .= " '".$db->escape_string($this->picture) 		. "', ";
		$sql .= " '".$db->escape_string($this->cover) 			. "', ";
		$sql .= " '".$db->escape_string($this->number) 			. "', ";
		$sql .= "NOW()" 										. ", ";
		$sql .= " ".$db->escape_string($this->comments) 		. ", ";
		$sql .= " ".$db->escape_string($this->fbcomments) 		. ", ";
		$sql .= " ".$db->escape_string($this->pending) 			. ", ";
		$sql .= " ".$db->escape_string($this->enabled) 			. ", ";
		$sql .= " ".$db->escape_string($this->oauth_uid) 		. ", ";
		$sql .= " '".$db->escape_string($this->oauth_provider) 	. "' ";
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
		$sql .= C_USER_USERNAME 		. "='" . $db->escape_string($this->username) 		. "', ";
		$sql .= C_USER_PASSWORD			. "='" . $db->escape_string($this->password) 		. "', ";
		$sql .= C_USER_EMAIL 			. "='" . $db->escape_string($this->email) 			. "', ";
		$sql .= C_USER_FIRSTNAME		. "='" . $db->escape_string($this->firstname) 		. "', ";
		$sql .= C_USER_MIDDLENAME		. "='" . $db->escape_string($this->middlename) 		. "', ";
		$sql .= C_USER_LASTNAME			. "='" . $db->escape_string($this->lastname) 		. "', ";
		$sql .= C_USER_GENDER			. "=" . $db->escape_string($this->gender) 			. ", ";
		$sql .= C_USER_ADDRESS 			. "='" . $db->escape_string($this->address) 		. "', ";
		$sql .= C_USER_MOTO 			. "='" . $db->escape_string($this->moto) 			. "', ";
		$sql .= C_USER_BIRTHDATE 		. "='" . $db->escape_string($this->birthdate) 		. "', ";
		$sql .= C_USER_PICTURE 			. "='" . $db->escape_string($this->picture) 		. "', ";
		$sql .= C_USER_COVER 			. "='" . $db->escape_string($this->cover) 			. "', ";
		$sql .= C_USER_NUMBER 			. "='" . $db->escape_string($this->number) 			. "', ";
		$sql .= C_USER_DATE 			. "=" . "NOW()" 									. ", ";
		$sql .= C_USER_COMMENTS 		. "=" . $db->escape_string($this->comments) 		. ", ";
		$sql .= C_USER_FBCOMMENTS 		. "=" . $db->escape_string($this->fbcomments) 		. ", ";
		$sql .= C_USER_PENDING 			. "=" . $db->escape_string($this->pending) 			. ", ";
		$sql .= C_USER_ENABLED 			. "=" . $db->escape_string($this->enabled) 			. ", ";
		$sql .= C_USER_OAUTH_UID 		. "=" . $db->escape_string($this->oauth_uid) 		. ", ";
		$sql .= C_USER_OAUTH_PROVIDER 	. "='" . $db->escape_string($this->oauth_provider) 	. "' ";
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
		$this_class->id 			= $record[C_USER_ID];
		$this_class->comments 		= $record[C_USER_COMMENTS];
		$this_class->fbcomments 	= $record[C_USER_FBCOMMENTS];
		$this_class->pending 		= $record[C_USER_PENDING];
		$this_class->enabled 		= $record[C_USER_ENABLED];
		$this_class->username 		= $record[C_USER_USERNAME];
		$this_class->password 		= $record[C_USER_PASSWORD];
		$this_class->email 			= $record[C_USER_EMAIL];
		$this_class->firstname 		= $record[C_USER_FIRSTNAME];
		$this_class->middlename 	= $record[C_USER_MIDDLENAME];
		$this_class->lastname 		= $record[C_USER_LASTNAME];
		$this_class->gender 		= $record[C_USER_GENDER];
		$this_class->address 		= $record[C_USER_ADDRESS];
		$this_class->moto 			= $record[C_USER_MOTO];
		$this_class->birthdate 		= $record[C_USER_BIRTHDATE];
		$this_class->picture 		= base64_encode($record[C_USER_PICTURE]);
		$this_class->cover 			= base64_encode($record[C_USER_COVER]);

		if($this_class->picture == "")
		{
			$this_class->picture = PROFILE;
		}

		if($this_class->cover == "")
		{
			$this_class->cover = COVER;
		}

		$this_class->number 		= $record[C_USER_NUMBER];
		$this_class->date			= $record[C_USER_DATE];
		$this_class->oauth_uid 		= $record[C_USER_OAUTH_UID];
		$this_class->oauth_provider = $record[C_USER_OAUTH_PROVIDER];
		
		return $this_class;
	}

	public function is_super_admin()
	{
		global $db;
		$sql = "SELECT * FROM ".T_SUPERADMINS." WHERE ".C_SUPERADMIN_USERID." = ".$this->id;
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public function get_full_name()
	{
		if($this->firstname != "" || $this->middlename != "" || $this->lastname)
		{
			return $this->firstname." ".substr($this->middlename, 0, 1).". ".$this->lastname;
		}
		else
		{
			return "update your profile";
		}
	}

	public static function username_exists($username)
	{
		global $db;
		$username = $db->escape_string($username);
		$sql = "SELECT * FROM " . self::$table_name . " WHERE " . C_USER_USERNAME . " = '" . $username . "'";
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function email_exists($email)
	{
		if($email != "")
		{
			global $db;
			$email = $db->escape_string($email);
			$sql = "SELECT * FROM " . self::$table_name . " WHERE " . C_USER_EMAIL . " = '" . $email . "'";
			$result = $db->query($sql);
			return ($db->get_num_rows($result) == 1) ? true : false;
		}
		else
		{
			return false;
		}
	}

	public static function authenticate($paramUsername="", $paramPassword="")
	{
		global $db;
		$paramUsername= $db->escape_string($paramUsername);
		$paramPassword= $db->escape_string($paramPassword);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_USER_USERNAME . " = '" . $paramUsername. "'";
		$sql .= " AND " 	. C_USER_PASSWORD . " = '" . $paramPassword. "'";
		$sql .= " LIMIT 1";
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function login($username="", $password="")
	{
		global $db;
		$username 	= $db->escape_string($username);
		$password 	= $db->escape_string($password);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_USER_USERNAME . " = '" . $username . "'";
		$sql .= " AND " 	. C_USER_PASSWORD . " = '" . $password . "'";
		$sql .= " LIMIT 1";
		
		$result = self::get_by_sql($sql);
		return !empty($result) ? array_shift($result) : null;
	}

	public static function get_by_username($username="")
	{
		global $db;
		$username = $db->escape_string($username);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_USER_USERNAME . " = '" . $username . "'";
		$sql .= " LIMIT 1";
		
		$result_array = self::get_by_sql($sql);

		return !empty($result_array) ? $result_array : false;
	}

	public static function get_by_oauthid($oauth_uid="")
	{
		global $db;
		$oauth_uid = $db->escape_string($oauth_uid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_USER_OAUTH_UID . " = '" . $oauth_uid . "'";
		$sql .= " LIMIT 1";
		
		$result_array = self::get_by_sql($sql);

		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function search($input)
	{
		global $db;
		$input 		= $db->escape_string($input);

		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE ".C_USER_FIRSTNAME." LIKE '%".$input."%'";
		$sql .= " OR ".C_USER_MIDDLENAME." LIKE '%".$input."%'";
		$sql .= " OR ".C_USER_LASTNAME." LIKE '%".$input."%'";
		$sql .= " OR ".C_USER_USERNAME." LIKE '%".$input."%'";
		$sql .= " AND ".C_USER_PENDING." = 0";
		$sql .= " AND ".C_USER_ENABLED." = 1";
		$sql .= " LIMIT 20";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}

	public static function get_all_pending()
	{
		global $db;

		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE ".C_USER_PENDING." = 1";
		$sql .= " ORDER BY ".C_USER_LASTNAME." DESC";

		$result = self::get_by_sql($sql);
		
		return !empty($result) ? $result : null;
	}
}

?>