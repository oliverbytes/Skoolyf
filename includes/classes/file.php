<?php 

class File
{
	public $name;
	public $type;
	public $size;
	public $tempname;
	public $error;
	public $data;
	public $valid = false;

	function __construct($file_array)
	{
		$this->name 		= $file_array['name'];
		$this->type 		= $file_array['type'];
		$this->size 		= $file_array['size'];
		$this->tempname 	= $file_array['tmp_name'];
		$this->error 		= $file_array['error'];

		if($this->size > 0)
		{
			$this->data 	= file_get_contents($this->tempname);
			$this->valid 	= true;
		}
	}

	public function upload($upload_path, $file_name)
	{
		move_uploaded_file($this->tempname, $upload_path.$file_name);
	}
}

?>