<?php
namespace App\front_office\Controllers;
use App\front_office\Models\UserModel;
use App\lib\SessionAdmin;

Class RecoveryPasswordController extends \App\Core\Basecontroller
{

    public function __construct()
    {
        parent::__construct();
        $this->UserModel = new UserModel();
    }

    /**
     * It shows the form to recovery the password
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function RecoveryPasswordForm()
    {
        echo $this->twig->render('recoveryPassword.twig');
    }

    /**
     * If there is a token it will show the form to reset the password
     * @param string $reset_token
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function ResetPassword( string $reset_token)
    {
        $userModel = new UserModel();
        if($reset_token)// (if there is a token it will show the form to change the password)
        {
            echo $this->twig->render('recoveryPassword.twig');
        }

    }

    /**
     * it sends a mail with a token to reset the password
     * @param string $email
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function SendMailAction(string $email)
    {
        $userModel = new UserModel();
        $success = "";
        $error = "";

        if($userModel->getEmail($email))
        {
            $reset_token = uniqid();
            // Create the Transport
            $transport = (new \Swift_SmtpTransport('smtp.mailtrap.io', 2525))
                ->setUsername('dba91667e2ea0a')
                ->setPassword('dc8598d61f7a38')
            ;

            // Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);//configuration smtp

            // Create a message
            $message = (new \Swift_Message('Ripristino password dimenticata'))
                ->setFrom(['contact@damianociviletto.com' => 'Damiano Civiletto'])
                ->setTo([$email => 'A Damiano'])
                ->setBody('Ecco il link per reimpostare la password:<a href="http://localhost:8888/Damiano_Civiletto/resetPasswordForm='.$reset_token.'"> clicca qui</a>', 'text/html')
            ;

            // Send the message
            $result = $mailer->send($message);


            if($result)
            {
                $userModel->resetToken($reset_token, $email);
                $success = "La mail è stata inviata, controlla la tua casella di posta";
            }
            else
            {
                $error = "C'è stato un errore, la mail non è stata inviata";
            }
        }
        else
        {
            $error = "C'è stato un errore, la mail non sembra corretta";
        }
        echo $this->twig->render('recoveryPassword.twig', ['error' => $error, 'success' => $success]);

    }

    /**
     * It shows the form to reset the password
     * @param  string $reset_token
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function resetPasswordForm(string $reset_token)
    {

        $UserModel = new UserModel();
        if($UserModel->getToken($reset_token))
        {
            echo $this->twig->render('resetPassword.twig');
        }
        else
        {
            header('Location: 404');
        }
    }

    /**
     * It allows to change the password in the database
     * @param string $newPassword
     * @param string $rePass
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function resetPasswordAction(string $newPassword, string $rePass)
    {
        $error = '';
        $success ='';
        $userModel = new UserModel();
        if($newPassword == $rePass)
        {
            $userModel->resetPassword($newPassword);
            $success = "Nuova password cambiata correttamente, ora potrai riconnetterti con la nuova password. Sarai reindirizzato verso la pagina di connessione fra 10 secondi";
            header( "refresh:10;url=connection" );
        }
        else
        {
            $error = 'Le due password non corrispondono';
        }
        echo $this->twig->render('resetPassword.twig', ['error' => $error, 'success' => $success]);
    }


}
