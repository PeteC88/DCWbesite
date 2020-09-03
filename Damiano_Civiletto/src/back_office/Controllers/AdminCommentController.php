<?php
namespace App\back_office\Controllers;
use App\front_office\Models\CommentModel;
/**
 * The class manage the comments in admin space and allow to delete them
 */
Class AdminCommentController extends \App\Core\AdminBasecontroller
{
	private $CommentModel;

    /**
     * AdminCommentController constructor.
     */
	public function __construct()
	{
		parent::__construct();
		$this->CommentModel = new CommentModel();
	}

	/**
	 * it removes the comment. If there is the post id in the URL it redirects to the same post page.
	 * @param   int	$commentId id of the comment
	 * @param   int $postId id of the post that contains the concerned comment
	 */
	function removeCommentAction(int $commentId, int $postId)
	{
	    $affectedLines = $this->CommentModel->removeComment($commentId);

	    if ($affectedLines === false) 
	    {
	        //Error managed. It will be up to the router's try block and the error will be displayed in a new page!
	        throw new Exception('Impossibile cancellare il commento !');
	    }

        header('Location: adminPost-'.$postId);
        exit();
	}

    /**
     * [it allows to approve a comment and show it in the front office]
     * @param $commentId
     * @param $postId
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    function approveComment(string $commentId, int $postId)
    {
        //when you click on the button it will modify the value reported comment of the db.
        $affectedLines = $this->CommentModel->approveComment(1,
            $commentId);

        if ($affectedLines === false)
        {
            throw new \Exception('Impossibile approvare il post');
        }
        else
        {
            header('Location: adminPost-'.$postId);
            exit();
        }
    }
}

