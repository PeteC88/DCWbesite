<?php 

namespace App\Core;

Class Basecontroller
{
    /**
     * @var \Twig_Environment
     */
	protected $twig;

    /**
     * Basecontroller constructor.
     */
 	public function __construct()
 	{
 		$loader = new \Twig_Loader_Filesystem([TEMPLATE_PATH . 'Front_Office_View' , TEMPLATE_PATH . 'Back_Office_View', TEMPLATE_PATH . 'Back_Office_View/Connection' ]);

		$this->twig = new \Twig_Environment($loader, [

        'debug' => true,
    	'cache' => false//__DIR__ .'/tmp'

		]);

        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
 	}
	
}