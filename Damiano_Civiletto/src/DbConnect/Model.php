<?php
namespace App\DbConnect;

Class Model 
{
    /**
     * @var \PDO
     */
	protected $pdo;

    /**
     * Connection to the database
     * Model constructor.
     */
	public function __construct()
	{
	    try
	    {
	       $this->pdo = new \PDO('mysql:host='. DB_HOST. ';dbname='. DB_NAME .';charset=utf8', DB_USER, DB_PASSWORD);
	    }
	    catch(Exception $e)
	    {
	        die('Errore : '.$e->getMessage());
	    }
	}
}