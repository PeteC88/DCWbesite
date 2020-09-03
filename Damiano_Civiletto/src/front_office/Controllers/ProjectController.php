<?php
namespace App\front_office\Controllers;
use App\front_office\Models\ProjectModel;

Class ProjectController extends \App\Core\Basecontroller
{
    protected $ProjectModel;

    /**
     * ProjectController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->ProjectModel = new ProjectModel();
    }

    /**
     * It shows all the projects in the front office
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listProjects()
    {
        $projects = $this->ProjectModel->getProjects();;

        echo $this->twig->render('projects.twig', ['projects' => $projects]);

    }

    /**
     * It shows a single project in the front office
     * @param int $id
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showProject(int $id)
    {
        $project = $this->ProjectModel->getProject($id);
        if($project)
        {
            echo $this->twig->render('singleProject.twig', ['project' => $project]);
        }
        else
        {
            throw new \Exception('Impossibile trovare il progetto');
        }

    }

}