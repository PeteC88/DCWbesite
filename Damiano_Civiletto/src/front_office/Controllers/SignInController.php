<?php
namespace App\front_office\Controllers;
use App\front_office\Models\UserModel;
use App\lib\SessionAdmin;

/**
 * Class SignInController
 * @package App\front_office\Controllers
 */
Class SignInController extends \App\Core\BaseController
{
    /**
     * It shows a form that allows the user to connect to the admin space
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function connectForm()
    {
        $SessionAdmin = new SessionAdmin();
        if($SessionAdmin->isConnected() == false)
        {
            echo $this->twig->render('Connect.twig');
        }
        else
        {
            header('Location: adminHome');
            exit();
        }

    }

    /**
     * It allows the user to connect to the admin space
     * @param string $email
     * @param string $password
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function signIn(string $email, string $password)
    {
        $error = "";
        if(isset($_POST['submit']))
        {
            if(!empty($email) AND !empty($password))
            {
                $userModel = new UserModel();
                if($user = $userModel->getUser($email, $password))
                {
                    $sessionAdmin = new SessionAdmin();
                    $sessionAdmin->connect($user);

                    header("Location: adminHome");

                    exit();

                }
                else
                {
                    $error = "L'email o la password non sono corretti";
                }
            }
            else
            {
                $error = "Tutti i campi devono essere riempiti";
            }

            echo $this->twig->render('Connect.twig', ['error' => $error]);
        }
        else
        {
            echo $this->twig->render('Connect.twig');
        }
    }
}
