<?php
namespace App\front_office\Controllers;
use App\front_office\Models\ServicesModel;
/**
 * Class who manage the pages of listing posts in front office
 */
class ServicesController extends \App\Core\Basecontroller
{
    /**
     * @var ServicesModel
     */
	private $ServicesModel;

    /**
     * ServicesController constructor.
     */
	public function __construct()
	{
		parent::__construct();
		$this->ServicesModel = new ServicesModel();
	}

    /**
     * It searches the services of the services and it will shows in the front office
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function listServicesAction()
	{

	    $services = $this->ServicesModel->getServices();

	    echo $this->twig->render('services.twig', ['services' => $services]);

	}

}