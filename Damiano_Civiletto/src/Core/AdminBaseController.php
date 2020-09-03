<?php
namespace App\Core;
use App\lib\SessionAdmin;

Class AdminBaseController extends Basecontroller
{
    /**
     * It allows the user to connect to the admin space
     * AdminBaseController constructor.
     */
    function __construct()
    {
        parent::__construct();

        $sessionAdmin = new SessionAdmin();
            if($sessionAdmin->isConnected() == false)
        	{
            	header('Location: connection');
            	exit();
        	}
    }
}
