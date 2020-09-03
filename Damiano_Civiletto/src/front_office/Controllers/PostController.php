<?php
namespace App\front_office\Controllers;
use App\front_office\Models\PostModel;


/**
 * Class who manage the pages of listing posts in front office
 */
class PostController extends \App\Core\Basecontroller
{
    /**
     * @var PostModel
     */
	private $PostModel;

    /**
     * PostController constructor.
     */
	public function __construct()
	{
		parent::__construct();
		$this->PostModel = new PostModel();
	}


    /**
     * It searchs the posts and it will shows in the front office
     * @param int $currentPage //It determinate the current page of the blog pagination
     */
    public function listPosts(int $currentPage = 1)
    {
        //Determinate how many items are in a page
        $perPage = 2;


        //calculate the first post in a page
        $firstPost = ($currentPage * $perPage) - $perPage;

        $count_posts = $this->PostModel->countPosts();
        $post = $this->PostModel->getPosts(null, $firstPost,$perPage);


        //calculate the total number of pages
        $pages = ceil($count_posts / $perPage);

        echo $this->twig->render('blog_hub.twig', ['post' => $post, 'currentPage' => $currentPage, 'countPosts' => $count_posts, 'pages' => $pages, 'firstPost' => $firstPost]);
    }

    /**
     * It get a single post and shows it in the front office
     * @param int $id
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function post(int $id)
	{
    	$post = $this->PostModel->getPost($id);;

    	if(!empty($post))
    	{

    		$comments = $this->PostModel->getPostComments($id);
    	    $countApprovedComments = $this->PostModel->countApprovedComments($id);
    		echo $this->twig->render('post.twig', ['post' => $post, 'comments' => $comments,'countApprovedComments' => $countApprovedComments]);

    	}
    	else
    	{
            throw new \Exception('Impossibile trovare il post');

    	}	
	}
}



