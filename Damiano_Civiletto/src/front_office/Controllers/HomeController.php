<?php
namespace App\front_office\Controllers;
use App\front_office\Models\PostModel;
Use App\front_office\Models\ServicesModel;
Use App\front_office\Models\ProjectModel;
/**
 * Class who manage the pages of listing posts in front office
 */
class HomeController extends \App\Core\Basecontroller
{
	private $PostModel;
	private $ServicesModel;
	private $ProjectModel;

    /**
     * HomeController constructor.
     */
	public function __construct()
	{
		parent::__construct();
		$this->PostModel = new PostModel();
		$this->ServicesModel = new ServicesModel();
		$this->ProjectModel = new ProjectModel();
	}

    /**
     * It searchs the posts, the services, the projects and the testimony from the models and it will shows in the home page front office
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function homeDisplay()
	{

	    $post = $this->PostModel->getPosts(3, null, null);//  $firstpost and perPage will determinate the pagination
	    $services = $this->ServicesModel->getServices(3);
	    $projects = $this->ProjectModel->getProjects( 4);

	    echo $this->twig->render('home.twig', ['post' => $post, 'services' => $services, 'projects' => $projects]);
	}
}



