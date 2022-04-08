<?php

class Tag
{

	// Properties
	public $TagID = null;
	public $TagName = null;

	public function __construct($data = array())
	{
		if(isset($data['TagID'])){ $this->TagID = $data["TagID"]; }

		if(isset($data['TagName'])){ $this->TagName = $data["TagName"]; }
	}

	/* Get Functions */
	public static function getByID(int $id)
	{
		if(!isset($id)){ return null; }

        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $get = $conn->prepare("
            SELECT *
            FROM Tags
            WHERE TagID=?
        ");

        if(!$get->execute([$id]))
        {
            return null;
        }

        $tag = $get->fetch();
        $conn = null;

        if($tag){ return new Tag($tag); }

        return null;
	}
}