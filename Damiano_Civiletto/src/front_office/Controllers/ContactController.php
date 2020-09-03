<?php
namespace App\front_office\Controllers;

/**
 * Class who manages the page Contact in the front office
 */
class ContactController extends \App\Core\Basecontroller
{
    /**
     * ContactController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * It shows the contact page
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showContactPage()
    {
        echo $this->twig->render('contact.twig');
    }

    /**
     * It allows the user to send a mail
     * @param string $name
     * @param string $surname
     * @param string $emailAddress
     * @param int $phone
     * @param string $object //object of the mail
     * @param string $content
     * @throws \Exception
     */
    public function sendMail(string $name, string $surname, string $emailAddress, int $phone, string $object, string $content)
    {
        header('Content-Type: application/json');
        if(isset($name) && isset($surname) && isset($emailAddress) && isset($phone) && isset($object) && isset($content))
        {
            // Create the Transport
            $transport = (new \Swift_SmtpTransport('smtp.mailtrap.io', 2525))
                ->setUsername('dba91667e2ea0a')
                ->setPassword('dc8598d61f7a38')
            ;

            // Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);//configuration smtp

            // Create a message
            $message = (new \Swift_Message($object))
                ->setFrom([$emailAddress => $name . ' ' . $surname])
                ->setTo(['pietrociccarello@gmail.com' => 'A Damiano'])
                ->setBody($content . ' <br><br><br>Telefono: ' .$phone, 'text/html')
            ;

            // Send the message
            $result = $mailer->send($message);


            if($result)
            {
                $data = array('reponse' => 'success', 'content'=> "Il messaggio è stato inviato correttamente, la ricontatterò nel più breve tempo possibile.");
            }
            else
            {
                $data = array('reponse' => 'error', 'content'=>'Impossibile inviare il messaggio, riprovi più tardi o può contattarmi via telefono o via mail');
            }
        }
        else
        {
            throw new \Exception('C\'è stato un errore, la preghiamo di riprovare più tardi');
        }
        echo json_encode( $data);
    }
}