<?php
namespace App\back_office\Controllers;
use App\front_office\Models\PostModel;


/**
 * The class manage all the actions concerned the post in admin section
 */
Class AdminPostController extends \App\Core\AdminBaseController
{
    /**
     * @var PostModel
     */
	protected $PostModel;

    /**
     * AdminPostController constructor.
     */
	public function __construct()
	{
        parent::__construct();
        $this->PostModel = new PostModel();
	}

    /**
     * It gets the posts from the database and it shows it on the admin page
     * @param int $currentPage // it indicates the current page of the pagination.
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function listAll(int $currentPage = 1)
	{
        //Determinate how many items are in a page
        $perPage = 4;


        //calculate the first post in a page
        $firstPost = ($currentPage * $perPage) - $perPage;

        $count_posts = $this->PostModel->countPosts();
        $post = $this->PostModel->getPosts(null, $firstPost,$perPage);


        //calculate the total number of pages
        $pages = ceil($count_posts / $perPage);

        echo $this->twig->render('AdminBlog_hub.twig', ['post' => $post, 'currentPage' => $currentPage, 'countPosts' => $count_posts, 'pages' => $pages, 'firstPost' => $firstPost]);
	}

    /**
     * It gets a single post from the database and it shows it in a specific page.
     * @param int $id id of the post
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function adminPost(int $id)
	{
        $post = $this->PostModel->getPost($id);;

        if(!empty($post))
        {
            $comments = $this->PostModel->getPostComments($id);
            $countComments = $this->PostModel->countComments($id);
            $countAllComments = count($comments);

            echo $this->twig->render('adminPost.twig', ['post' => $post, 'comments' => $comments,'count_comments' => $countComments, 'countAllComments'=> $countAllComments]);

        }
        else
        {

            throw new \Exception('Il post che hai cercato non esiste');

        }

    }

    /**
     * It shows a page with the form to add a new post
     */
    public function addPostShowFormAction()
		{
            echo $this->twig->render('AdminAddPost.twig');
		}

     /**
	 * It adds a new post in the database if the user is connected and redirect to admin page.
	 * @param  string $title the title of the post you want to add
	 * @param  string $content the content of the post you want to add.
	 */
	public function addPostAction(string $title, string $content)
		{
            if(isset($title) && isset($content))
            {
                $affectedLines = $this->PostModel->addPost($title, $content);

                if ($affectedLines === false) {
                    throw new Exception('Impossible d\'ajouter le commentaire !');
                }
                else {
                    header('Location: adminBlog');
                    exit();
                }
            }
            else
            {
                throw new \Exception('Impossibile aggiungere il post, torna indietro e prova a riempire tutti i campi');
            }
		}

    /**
     * It shows the form to edit the post:
     * @param $id  the id of the post you want to edit
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function editPostShowForm(int $id)
    {
        $post = $this->PostModel->getPost($id);
        if($post)
        {
            echo $this->twig->render('editPost.twig', ['post' => $post]);
        }
        else
        {
            echo 'Questo post non esiste';
        }

    }

    /**
     * It gets the title and the content of an existing post and it shows it in a page where the user can edit it:
     * @param int $postId
     * @param int $id
     * @param string $title
     * @param string $content
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function editPostAction(int $postId, int $id, string $title, string $content )
	{   
	    //This function will display the pre-filled form.
	    if(empty($_POST))
	    {
	        //get the id of the post
	        $post = $this->PostModel->getPost($postId);
	        //test to verify if the post exist.
	        if($post)
	        {
                echo $this->twig->render('editPost.twig');
	        }
	        else
	        {
	            throw new Exception('Questo post non esiste');
	        }
	    }
	    else
	    {

	         $affectedLines = $this->PostModel->editPost($title, $content, $id);

	        if ($affectedLines === false) 
	        {
	        	throw new Exception('Impossibile modificare il post');
	        }
	        else 
	        {
	            header('Location: adminBlog');
	            exit();
	        }
	    }
	}

	/**
	 * it removes a post from the database
	 * @param int $id the id of the post you want to delete
	 */
	public function removePostAction(int $id)
	{

	    $affectedLines = $this->PostModel->removePost($id);

	    if ($affectedLines === false) 
	    {
	        throw new Exception('Impossibile eliminare il post');
	    }
	        else 
	    {
	        header('Location: adminBlog');
	        exit();
	    }  
	}
}