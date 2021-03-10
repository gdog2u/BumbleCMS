<?php

require_once(CLASS_PATH.'PostTags.php');

class Post
{
    // Properties
    public $PostID = null;
    public $AuthorID = null;
    public $PublishedDate = null;
    public $Title = null;
    public $Summary = null;
    public $Body = null;
    public $Tags = null;

    public function __constructor($data = array())
    {
        if(isset($data['PostID'])){ $this->PostID = $data['PostID']; }

        if(isset($data['AuthorID'])){ $this->AuthorID = $data['AuthorID']; }

        if(isset($data['PublishedDate'])){ $this->PublishedDate = $data['PublishedDate']; }

        if(isset($data['Title'])){ $this->Title = $data['Title']; }

        if(isset($data['Summary'])){ $this->Summary = $data['Summary']; }

        if(isset($data['Body'])){ $this->Body = $data['Body']; }

		if(isset($data['Tags'])){ $this->Tags = data['Tags']; }
    }

    public static function getByID($id)
    {
        if(!isset(id)){ return null; }

        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $get = $conn->prepare("
            SELECT *
            FROM Posts
            WHERE id=?
        ");

        if(!$get->execute([$id]))
        {
            return null;
        }

        $post = $get->fetch();
        $conn = null;

        if($row){ return new Post($row); }

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
            LIMIT ?
        ");

        if(!$get->execute([$numberOfRows]))
        {
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

		if(!$get->execute($this->PostID, $tagID))
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

        $tag = PostTags::getByID($tagID);

        if(!tag){ return; }

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