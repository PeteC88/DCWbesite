<?php
namespace App\back_office\Controllers;

use App\front_office\Models\UserModel;
use App\lib\SessionAdmin;

class AdminUserController extends \App\Core\AdminBasecontroller
{
    /**
     * AdminUserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->UserModel = new UserModel();
    }


	/**
	 * It deconnect the user from the admin space
	 *
	 */
	public function signOut()
	{
		$sessionAdmin = new sessionAdmin();
		$sessionAdmin->deconnect();

		header("Location: connection");
        exit();
	}

    /**
     * It shows the form to change the password
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
	public function changePasswordForm()
    {
        echo $this->twig->render('ChangePassword.twig');

    }

    /**
     * It allows to change the password and update it in the database
     * @param string $email
     * @param string $password
     * @param string $newPass
     * @param string $rePass
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function changePasswordAction(string $email, string $password, string $newPass, string $rePass)
    {
        $error = '';
        $success ='';

        $userModel = new UserModel();

        if(isset($_POST['re_password']))
        {

            $user = $userModel->getUser($email, $password);

            if(!$user)
            {
                $error = 'Email o attuale password non valida';
            }
            elseif($newPass != $rePass)
            {
                $error = 'La nuova password e la conferma non corrispondono ';
            }
            elseif($password == $newPass)
            {
                $error = 'Impossibile usare questa password, prova a cambiarla';
            }
            elseif(empty($password) && empty($newPass) && empty($rePass))
            {
                $error = 'Tutti i campi devono essere riempiti';
            }
            elseif($newPass == $rePass)
            {

                $userModel->changePassword($email, $newPass);

                $success = 'La password è stata modificata con successo, ora puoi usare questa nuova password la prossima volta che ti connetti';

            }

        }

        echo $this->twig->render('ChangePassword.twig', ['error' => $error, 'success' => $success]);
	}


}