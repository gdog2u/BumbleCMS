<?php

class PostCategory
{

	// Properties
	public $PostCategoryID = null;
	public $CategoryName = null;
	public $CategoryURIPrefix = null;
	public $TemplateFile = null;

	public function __construct($data = array())
	{
		if(isset($data['PostCategoryID'])){ $this->PostCategoryID = $data["PostCategoryID"]; }

		if(isset($data['CategoryName'])){ $this->CategoryName = $data["CategoryName"]; }

		if(isset($data['CategoryURIPrefix'])){ $this->CategoryURIPrefix = $data["CategoryURIPrefix"]; }

		if(isset($data['TemplateFile'])){ $this->TemplateFile = $data["TemplateFile"]; }
	}

	/* Get Functions */
	public static function getByID(int $id)
	{
		if(!isset($id)){ return null; }

        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $get = $conn->prepare("
            SELECT *
            FROM PostCategories
            WHERE PostCategoryID = ?
        ");

        if(!$get->execute([$id]))
        {
            return null;
        }

        $category = $get->fetch();
        $conn = null;

        if($category){ return new PostCategory($category); }

        return null;
	}
}