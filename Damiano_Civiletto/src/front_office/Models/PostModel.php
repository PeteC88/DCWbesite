<?php 
namespace App\front_office\Models;
//use PDO;

Class PostModel extends \App\DbConnect\Model
{
    /**
     * It gets all the posts from the database
     * @param int|null $limit
     * @param int|null $firstPost //it determinates the first post for pagination
     * @param int|null $perPage //it determinates how many posts there are per page for the pagination
     * @return array returns in an array all the posts
     */
    public function getPosts(int $limit = null, int $firstPost = null, int $perPage = null):array
    {
        $sql = 'SELECT id, title, content, DATE_FORMAT(creation_date, \'%d/%m/%Y alle %H:%i\') AS creation_date_it, DATE_FORMAT(editing_date, \'%d/%m/%Y à %H:%i\') AS editing_date_it FROM posts ORDER BY creation_date DESC';

        if($limit)
        {
            $sql .= ' LIMIT ' . $limit;
        }

        if(isset($firstPost) && isset($perPage))
        {
            $sql .=  ' LIMIT ' . $firstPost . ' , ' . $perPage;
        }

        $req = $this->pdo->query($sql);
        $data =$req->fetchAll();
        return $data;
    }


    /**
     * Get the total numbers of posts
     * @return int it returns the number of the posts
     */
    public function countPosts():int
    {
        $posts_count = $this->pdo->prepare('SELECT COUNT(*) AS nb_posts FROM posts');
        $posts_count->execute();

        $result = $posts_count->fetch();

        $nbPosts = (int)$result['nb_posts'];

        return $nbPosts;
    }


    /**
	* Get the comments in a post
	*
	* @param int $postId id of the post
	*
	* @return array returns in an array the comments of a post
	*
  	*/
	public function getPostComments(int $postId):array
    {
        $comments = $this->pdo->prepare('SELECT id, author, comment, post_id, reported_comment, authorised_comment, DATE_FORMAT(comment_date, \'%d/%m/%Y alle %Hh%imin\') AS comment_date_it FROM comments WHERE post_id = :postId ORDER BY comment_date DESC');


        $comments->bindValue(':postId', (int) $postId);
        $comments->execute();

        return $comments->fetchAll();
    }

    /**
     * return the number of the comments not yet approved in a post
     * @param  int $post_id the id of the post
     * @return mixed
     */
    function countComments(int $postId)
    {

        $comments_count = $this->pdo->prepare('SELECT COUNT(comment) AS allComments FROM comments WHERE post_id = :postId AND authorised_comment = 0');
        $comments_count->execute(array(
            ':postId' => (int) $postId,
        ));

        return $comments_count->fetchColumn();
    }

    /**
     * return the number of the approved comments in a post
     * @param  int $post_id the id of the post
     * @return mixed
     */
    function countApprovedComments(int $postId)
    {
        $comments_count = $this->pdo->prepare('SELECT COUNT(comment) AS approved FROM comments WHERE post_id = :postId AND authorised_comment = 1');
        $comments_count->execute(array(
            ':postId' => (int) $postId,
        ));

        return $comments_count->fetchColumn();
    }



    /**
     * Get a single post
     * @param  int $id id of the post
     * @return bool|array returns false on failure or fetches the next row from a result set
     */
   public function getPost(int $id)
    {
        $req = $this->pdo->prepare('SELECT id, title, content, DATE_FORMAT(creation_date, \'%d/%m/%Y alle %Hh%imin\') AS creation_date_it, DATE_FORMAT(editing_date, \'%d/%m/%Y à %Hh%imin\') AS editing_date_fr FROM posts WHERE id = :id');

        $req->bindValue(':id', (int) $id);
        $req->execute();

        $post = $req->fetch();

        return $post;
    }

   	/**
   	 * Add a new post
   	 * @param string $title the title of the post
   	 * @param string $content the content of the post
   	 *
   	 * @return bool Returns TRUE on succes or FALSE on failure
   	 */
    public function addPost(string $title, string $content):bool
    {

        $addPost = $this->pdo->prepare('INSERT INTO posts(title, content, creation_date) VALUES(:title, :content, NOW())');

        $addPost->bindValue(':title', $title);
        $addPost->bindValue(':content', $content);

        return  $addPost->execute();

    }

    /**
     * Edit a existent post
     * @param  string $title the title of the post
     * @param  string $content the content of the post
     * @param  int $id the id of the post
     * @return bool Returns TRUE on succes or FALSE on failure.
     */
    public function editPost(string $title, string $content, int $id):bool
    {

        $editPost = $this->pdo->prepare('UPDATE posts SET title = :title, content = :content, editing_date = NOW() WHERE id = :id');
        $req = $editPost->execute(array(
                        ':title' => $title,
                        ':content' => $content,
                        ':id' => $id
                    ));

        return $req;
    }

    /**
     * Remove a post from the list
     * @param  int $id the id of the post
     * @return bool Returns True on succes or False on failure.
     */
    public function removePost(int $id):bool
    {
        $removePost = $this->pdo->prepare('DELETE FROM posts WHERE id = :id');
        $affectedLines = $removePost->execute(array( ':id' => $id));

        return $affectedLines;
    }

}