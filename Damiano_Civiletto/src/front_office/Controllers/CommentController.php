<?php
namespace App\front_office\Controllers;
use App\front_office\Models\CommentModel;


class CommentController extends \App\Core\Basecontroller
{
	private $CommentModel;

    /**
     * CommentController constructor.
     */
	public function __construct()
	{
		parent::__construct();
		$this->CommentModel = new CommentModel();
	}

	/**
	 * Add  a new comment in a post
	 * @param int $postId  id of the concerned post
	 * @param string $author  it adds the name of the author of a comment in the database
	 * @param string $comment it adds the comment in the database
     * @param string $email it adds the email in the database
	 */
	function addComment(int $postId, string $author, string $comment, string $email)
	{
	    //to encode the json
        header('Content-Type: application/json');
	    if(empty($author) || empty($comment) || empty($email))
        {
            $data = array('reponse' => 'error', 'content'=> "Per poter inviare il messaggio bisogna riempire tutti i campi");
        }
	    else
        {
            $affectedLines = $this->CommentModel->postComment($postId, $author, $comment, $email);

            if ($affectedLines === false)
            {
                throw new Exception('Impossibile aggiungere il commento !');
            }
            else {
                $data = array('reponse' => 'success', 'content'=> "Il messaggio è stato inviato correttamente ma sarà visibile soltanto dopo essere stato approvato");
            }
        }
	    echo json_encode( $data);
	}
}