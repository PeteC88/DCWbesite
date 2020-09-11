<?php
namespace App\back_office\Controllers;

use App\front_office\Models\ServicesModel;
use Exception;

Class AdminServicesController extends \App\Core\AdminBaseController
{
    protected $ServicesModel;

    /**
     * AdminServicesController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->ServicesModel = new ServicesModel();
    }

    /**
     * it shows all the services in the page
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function adminServices()
    {
        $services = $this->ServicesModel->getServices();

        echo $this->twig->render('AdminServices.twig', ['services' => $services]);
        
    }

    /**
     * It shows a form to add a new service
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function addServiceShowFormAction()
    {
        echo $this->twig->render('AddService.twig');
    }

    /**
     * It allows to add a new service in the database
     * @param string $title
     * @param string $content
     * @param string $file_name
     * @throws Exception
     * @return bool [It returns true if success and false if falure]
     */
    public function addServiceAction(string $title, string $content, string $file_name)
    {
        $error = '';
        if(isset($title, $content, $file_name))
        {
            // The folder where the images will be stocked
            $target_dir = 'img/services_images/';
            // The path of the new uploaded image
            $file_name = $_FILES['image']['name'];
            $image_path = $target_dir . $file_name;


            // Check to make sure the image is valid
            if (!empty($_FILES['image']['tmp_name']) && getimagesize($_FILES['image']['tmp_name']))
            {
                if (file_exists($image_path))
                {
                    echo 'L\'immagine esiste nel database, cambia immagine o cambia nome all\'immagine.';
                }
                else if ($_FILES['image']['size'] > 2000000)
                {
                    throw new \Exception ("Scegli un'immagine minore di 2MB. Torna indietro e riprova.");
                }
                else
                {
                    // Everything checks out now we can move the uploaded image
                    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
                    // Connect to MySQL
                    $affectedLines = $this->ServicesModel->addService($title, $content, $file_name);

                    if ($affectedLines === false)
                    {
                        throw new \Exception('Impossible aggiungere l\'immagine');
                    }
                    else
                    {
                        header('Location: AdminServices');
                        exit();
                    }
                }
            }
        }
    }

    /**
     * It shows a form that allows to modify the service.
     * @param int $id
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function editServiceShowForm(int $id)
    {
        $service = $this->ServicesModel->getServiceById($id);
        if($service)
        {
            echo $this->twig->render('editService.twig', ['service' => $service]);
        }
        else
        {
            throw new Exception('Questo servizio non esiste');
        }

    }

    /**
     * It allows to modify the service in the database
     * @param string $title
     * @param string $content
     * @param string $file_name
     * @param int $id
     * @throws Exception
     * @return bool [It returns true if success and false if falure]
     */
    public function editServiceAction(string $title, string $content, string $file_name, int $id):bool
    {
        //get the id of the service
        $service = $this->ServicesModel->getServiceById($id);

        if(!empty($file_name))
        {
            //path of the dir where the images are stocked
            $target_dir = 'img/Services_images/';
            $image_path = $target_dir . $service['file_name'];
            $new_image_path = $target_dir . $file_name;

            //Delete the actual image from the folder, doesn't work for now
            unlink($image_path);
            //Upload the new image
            move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path);
        }
        else
        {
            $file_name = $service['file_name'];
        }
       
         $affectedLines = $this->ServicesModel->editService($title, $content, $file_name, $id);

        if ($affectedLines === false) 
        {
            throw new Exception('Impossibile modificare questa immagine');
        }
        else 
        {
            header("Location: AdminServices");
            exit();
        }
    
    }

    /**
     * It allows to delete a service from the database and it deletes the image updated from the folder
     * @param int $id
     * @throws Exception
     */
    public function deleteServiceAction(int $id)
    {
        //get the name of the image 
        $service = $this->ServicesModel->getServiceById($id);

        $image_path = 'img/Services_images/' . $service['file_name'];
        
        unlink($image_path); //to delete the image from the folder
        $affectedLines = $this->ServicesModel->deleteService($id);

        if ($affectedLines === false) 
                {
                    throw new Exception('Impossibile cancellare il servizio');
                }
                else 
                {
                    header('Location: AdminServices');
                    exit();
                }
    }
}