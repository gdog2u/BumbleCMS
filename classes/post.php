<?php

class Post
{
    /* Properties */
    public $PostID = null;
    public $AuthorID = null;
    public $StatusID = null;
    public $PublishedDate = null;
    public $Title = null;
    public $Summary = null;
    public $Body = null;
    public $Category = null;
    public $Tags = [];

    public function __construct($data = array())
    {
        if(isset($data['PostID'])){ $this->PostID = $data['PostID']; }

        if(isset($data['AuthorID'])){ $this->AuthorID = $data['AuthorID']; }
        
        if(isset($data['StatusID'])){ $this->StatusID = $data['StatusID']; }

        if(isset($data['PublishedDate'])){ $this->PublishedDate = $data['PublishedDate']; }

        if(isset($data['Title'])){ $this->Title = $data['Title']; }

        if(isset($data['Summary'])){ $this->Summary = $data['Summary']; }

        if(isset($data['Body'])){ $this->Body = $data['Body']; }

		if(isset($data['Category'])){ $this->Category = $data['Category']; }

		if(isset($data['Tags'])){ $this->Tags = $data['Tags']; }
        else{ $this->_getTags(); }
    }

    /* Get Functions */
    public static function getByID($id)
    {
        if(!isset($id)){ return null; }

        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $get = $conn->prepare("
            SELECT *
            FROM Posts
            WHERE PostID=?
        ");

        if(!$get->execute([$id]))
        {
            return null;
        }

        $post = $get->fetch();
        $conn = null;

        if($post){ return new Post($post); }

        return null;
    }

    public static function getBySlug(string $slug)
    {
        if(!isset($slug)){ return null; }

        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $get = $conn->prepare("
            SELECT *
            FROM Posts
            WHERE Slug=?
        ");

        if(!$get->execute(([$slug])))
        {
            return null;
        }

        $post = $get->fetch();
        $conn = null;

        if($post){ return new Post($post); }

        return null;
    }

    public static function getNRows($numberOfRows=999999)
    {
        $return = array(
            'count' => 0,
            'posts' => []
        );
		$conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $get = $conn->prepare("
            SELECT *
            FROM Posts
            ORDER BY PublishedDate DESC
            LIMIT :rows
        ");

        // LIMIT bound parameters act differently
        // bindParam can be used to bypass this
        // https://stackoverflow.com/a/11738633/2708601
        $get->bindParam(":rows", $numberOfRows, PDO::PARAM_INT);

        if(!$get->execute())
        {
            error_log($get->errorInfo()[2]);
            return null;
        }

        while($post = $get->fetch())
        {
            $return['posts'][] = new Post($post);
        }
        $conn = null;

        $return['count'] = count($return['posts']);

        return $return;
    }

	public static function getByTag($tagID, $numberOfRows=999999)
	{
		$return = array(
			'count' => 0,
			'posts' => []
		);
		$conn = new PDO(DB_DSN, DB_USER, DB_PASS);
		$get = $conn->prepare("
			SELECT Posts.*
			FROM Posts
				JOIN PostTagLookup ON PostTagLookup.PostID = Posts.PostID
			WHERE PostTagLookup.TagID = ?
			LIMIT ?
		");

		if(!$get->execute($tagID, $numberOfRows))
		{
			trigger_error("Post::getByTag failed to get posts with TagID $tagID");
			return null;
		}

		while($post = $get->fetch)
		{
			$return['posts'][] = $post;
		}

		$return['count'] = count($return['posts']);

		return $return;
	}

    protected function _getTags()
    {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $get = $conn->prepare("
            SELECT Tags.*
            FROM Tags
                LEFT JOIN PostTagLookup AS PTL ON PTL.TagID = Tags.TagID
            WHERE PTL.PostID = ?
        ");

        if(!$get->execute([$this->PostID]))
        {
            error_log($get->errorInfo()[2]);
        }
        else
        {
            while($tag = $get->fetch())
            {
                $this->Tags[] = new Tag($tag);
            }
        }
    }

    /* Modify Functions */
    public function insert()
    {
        if(!is_null($this->PostID)){ trigger_error("Post::insert(): Cannot insert a post that already exists"); }

        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $insert = $conn->prepare("
            INSERT INTO Posts(AuthorID, PublishedDate, Title, Summary, Body)
            VALUES(?, ?, ?, ?, ?)
        ");

        if(!$insert->execute([$this->AuthorID, $this->PublishedDate, $this->Title, $this->Summary, $this->Body]))
        {
            trigger_error("Post::insert failed to insert the post");
            return null;
        }

        $this->PostID = $conn->lastInsertId();
        $conn = null;
    }

    public function update()
    {
        if(is_null($this->PostID)){ trigger_error("Post::update(): Cannot update a post without a database ID."); }

        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $update = $conn->prepare("
            UPDATE Posts
            SET Title = ?,
                Summary = ?,
                Body = ?
            WHERE PostID=?
        ");

        if(!$update->execute([$this->Title, $this->Summary, $this->Body, $this->PostID]))
        {
            trigger_error("Post::update failed to update the post");
            return null;
        }

        $conn = null;
    }

    public function addTag($tagID)
    {
        if(is_null($this->PostID)){ return; }
        if(is_null($tagID)){ return; }

        $tag = Tag::getByID($tagID);

        if(!$tag){ return; }

        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $insert = $conn->prepare("
            INSERT INTO PostTagLookup(PostID, TagID)
            VALUES(?, ?)
        ");

        if(!$insert->execute([$this->PostID, $tag->TagID]))
        {
            trigger_error("Post::addTag: Failed to add tag (ID: $tagID) to post ($this->PostID)");
        }
    }
}