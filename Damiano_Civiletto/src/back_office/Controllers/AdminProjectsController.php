<?php
namespace App\back_office\Controllers;
use App\front_office\Models\ProjectModel;

Class AdminProjectsController extends \App\Core\AdminBaseController
{
    protected $ProjectModel;

    /**
     * AdminProjectsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->ProjectModel = new ProjectModel();
    }

    /**
     * It lists all the projects in the page
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function adminListProjects()
    {
        $projects = $this->ProjectModel->getProjects();

        echo $this->twig->render('AdminProjects.twig', ['projects' => $projects]);

    }

    /**
     * it shows a single project in the page
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function adminProject()
    {
        $projects = $this->ProjectModel->getProject();;

        echo $this->twig->render('AdminSingleProject.twig', ['projects' => $projects]);

    }

    /**
     * It shows a page with a form to add a new project
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function addProjectShowFormAction()
    {
        echo $this->twig->render('AddProject.twig');
    }

    /**
     * It allows to add a new project in the database
     * @param string $title
     * @param string $content
     * @param string $file_name
     * @param string $city
     */
    public function addProjectAction(string $title, string $content, string $file_name, string $city)
    {
        $error = '';
        if(isset($title, $content, $file_name, $city))
        {
            // The folder where the images will be stocked
            $target_dir = 'img/projects_images/';
            // The path of the new uploaded image
            $file_name = $_FILES['image']['name'];
            $image_path = $target_dir . $file_name;


            // Check to make sure the image is valid
            if (!empty($_FILES['image']['tmp_name']) && getimagesize($_FILES['image']['tmp_name']))
            {

                if (file_exists($image_path))
                {
                    throw new \Exception('L\'immagine esiste nel database, cambia immagine o cambia nome all\'immagine.');
                }
                else if ($_FILES['image']['size'] > 1000000)
                {
                    throw new \Exception( 'Scegli un\'immagine minore di 1Mb.');
                }
                else
                {
                    // Everything checks out now we can move the uploaded image
                    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
                    // Connect to MySQL
                    $affectedLines = $this->ProjectModel->addProject($title, $content, $file_name, $city);

                    if ($affectedLines === false)
                    {
                        throw new \Exception('Impossible aggiungere l\'immagine');
                    }
                    else
                    {
                        header('Location: adminProjects');
                        exit();
                    }
                }
            }
        }
    }

    /**
     * it shows a form that allows to edit a project
     * @param int $id
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function editProjectShowForm(int $id)
    {
        $project = $this->ProjectModel->getProject($id);
        if($project)
        {
            echo $this->twig->render('AdminEditProject.twig', ['project' => $project]);
        }
        else
        {
            throw new \Exception('Questo progetto non esiste.');
        }

    }

    /**
     * It allows to add a new thumbnail image in the project page.
     * @param string $file_name
     * @param int $id
     */
    public function editProjectThumbnail(string $file_name, int $id)
    {
        //get the id of the project
        $project = $this->ProjectModel->getProject($id);

        if(!empty($file_name))
        {
            //path of the dir where the images are stocked
            $target_dir = 'img/projects_images/';
            $image_path = $target_dir . $project['file_name'];
            $new_image_path = $target_dir . $file_name;

            //Delete the actual image from the folder, doesn't work for now
            unlink($image_path);
            //Upload the new image
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $new_image_path);
        }
        else
        {
            $file_name = $project['file_name'];
        }

        $affectedLines = $this->ProjectModel->editProjectThumbnail($file_name,$id);

        if ($affectedLines === false)
        {
            throw new \InvalidArgumentException ('Impossibile modificare questo progetto');
        }
        else
        {
            header("Location: adminProjects");
            exit();
        }
    }

    /**
     * It allows to edit a project and save the modifications in the database
     * @param string $title
     * @param string $content
     * @param string $city
     * @param string $id
     */
    public function editProjectAction(string $title, string $content, string $city, int $id)
    {
        //get the id of the project
        $project = $this->ProjectModel->getProject($id);

        $affectedLines = $this->ProjectModel->editProject($title, $content, $city, $id);

        if ($affectedLines === false)
        {
            throw new \Exception ('Impossibile modificare questo progetto');
        }
        else
        {
            header("Location: adminProjects");
            exit();
        }

    }

    /**
     * It allows to delete a project from the database.
     * @param int $id Id of the post
     */
    public function deleteProjectAction(int $id)
    {
        //get the name of the image
        $project = $this->ProjectModel->getProject($id);

        $image_path = 'img/projects_images/' . $project['file_name'];

        unlink($image_path);
        $affectedLines = $this->ProjectModel->deleteProject($id);

        if ($affectedLines === false)
        {
            throw new \Exception('Impossibile cancellare il progetto');
        }
        else
        {
            header('Location: adminProjects');
            exit();
        }
    }
}