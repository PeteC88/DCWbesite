<?php
namespace App\back_office\Controllers;
use App\front_office\Models\PostModel;
Use App\front_office\Models\ServicesModel;
Use App\front_office\Models\ProjectModel;
/**
 * Class who manage the pages of listing posts in front office
 */

class AdminHomeController extends \App\Core\AdminBasecontroller
{
    /**
     * @var PostModel
     * @var ServicesModel
     * @var ProjectModel
     */
    private $PostModel;
    private $ServicesModel;
    private $ProjectModel;

    /**
     * AdminHomeController constructor.
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
    public function AdminHomeDisplay()
    {
        $post = $this->PostModel->getPosts(3); //it limits the posts shown on the home page
        $services = $this->ServicesModel->getServices();
        $projects = $this->ProjectModel->getProjects();

        echo $this->twig->render('AdminHome.twig', ['post' => $post, 'services' => $services, 'projects' => $projects]);
    }
}




