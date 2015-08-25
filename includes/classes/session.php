<?php 

class Session
{
	private $logged_in;
	public $user_id;
	public $message;

	function __construct()
	{
		session_start();
		$this->check_login();
	}

	private function check_login()
	{
		if(isset($_SESSION[C_USER_ID]))
		{
			$this->user_id 			= $_SESSION[C_USER_ID];
			$this->logged_in 		= true;
		}
		else
		{
			unset($this->user_id);
			
			$this->logged_in = false;
		}
	}
	
	public function is_logged_in()
	{
		return $this->logged_in;
	}
	
	public function login($user)
	{
		if($user)
		{
			$this->user_id 		= $_SESSION[C_USER_ID] 			= $user->id;
			$this->check_login();
		}
	}
	
	public function logout()
	{
		unset($_SESSION[C_USER_ID]);
		unset($this->user_id);

		$this->logged_in = false;
	}

	public function message()
	{
		$newmessage = $this->message;
		$this->message = "";
		return $newmessage;
	}
}

$session = new Session();

?>