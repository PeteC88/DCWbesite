<?php
namespace App\front_office\Models;

Class CommentModel extends \App\DbConnect\Model
{
    /**
     * post a new comment in a determinate post
     * @param int $postId
     * @param string $author
     * @param string $comment
     * @param string $email
     * @return bool [Returns TRUE on succes or FALSE on Failure]
     */
	public function postComment(int $postId, string $author, string $comment, string $email):bool
	{
	    $comments = $this->pdo->prepare('INSERT INTO comments(post_id, author, comment, email, comment_date) VALUES(:post_id, :author, :comment, :email, NOW())');
	    $affectedLines = $comments->execute(array(
	    	
	    	':post_id' => (int) $postId, 
	    	':author' => $author, 
	    	':comment' => $comment,
            ':email' => $email
	    ));

	    return $affectedLines;
	}

	/**
	 * It allows the user to approve a comment
	 * @param  bool $approveComment if TRUE the comment will approved
	 * @param  int $id the id of the comment
	 * @return bool Returns TRUE on succes or FALSE on Failure
	 */
	public function approveComment(bool $authorisedComment, int $id):bool
	{

	    $approveComment = $this->pdo->prepare('UPDATE comments SET authorised_comment = :authorised_comment WHERE id = :id');
	    $affectedLines = $approveComment->execute(array(
	                    ':authorised_comment' => $authorisedComment,
	                    ':id' => $id
	                ));
	    return $affectedLines;

	}

	/**
	 * It allows the admin to remove a comment
	 * @param  int $id the id of the comment
	 * @return bool Returns TRUE on succes of FALSE on Failure
	 */
	function removeComment(int $id):bool
	{
	    $removeComment = $this->pdo->prepare('DELETE FROM comments WHERE id = :id');
	    $req = $removeComment->execute(array(':id' => $id));

	    return $req;
	}

}