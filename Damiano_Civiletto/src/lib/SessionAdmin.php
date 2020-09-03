<?php
namespace App\lib;

Class SessionAdmin 
{
	/**
	 * It starts a session if it is not already started
	 */
	function __construct()
	{
		if(session_status() == PHP_SESSION_NONE)
		{
			session_start();
		}
	}

    /**
     * It allows to connect to the space admin
     * @param  array $user the data of the user
     */
    function connect(array $user)
    {
        //Save the datas of users in the session.
        $_SESSION['user'] = $user; // to associate the key user to the value $user and stock the user values in the $_SESSION
    }

    /**
     * It veryfies if the user exist before to connect
     * @return bool
     */
    function isConnected():bool
    {
        if(array_key_exists('user', $_SESSION))
        {
            if(isset($_SESSION['user']))
            {
                return true;
            }
        }
        return false;
    }

    /**
	 * It empty the variables of the session
	 */
	function deconnect()
	{
		//user is a key who mantain the informations about the user
		$_SESSION['user'] =  array(); //There is nothing in the array to indicate that the vars are empty

		session_destroy();
	}


}
