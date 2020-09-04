<?php
namespace App\front_office\Controllers;
use App\front_office\Models\CommentModel;


class CommentController extends \App\Core\Basecontroller
{
	private $CommentModel;

    /**
     * CommentController constructor.
     */
	public function __construct()
	{
		parent::__construct();
		$this->CommentModel = new CommentModel();
	}

	/**
	 * Add  a new comment in a post
	 * @param int $postId  id of the concerned post
	 * @param string $author  it adds the name of the author of a comment in the database
	 * @param string $comment it adds the comment in the database
     * @param string $email it adds the email in the database
	 */
	function addComment(int $postId, string $postTitle,  string $author, string $comment, string $email)
    {
        //to encode the json
        header( 'Content-Type: application/json' );
        if (empty( $author ) || empty( $comment ) || empty( $email )) {
            $data = array('reponse' => 'error', 'content' => "Per poter inviare il messaggio bisogna riempire tutti i campi");
        } else {
            $affectedLines = $this->CommentModel->postComment( $postId, $author, $comment, $email );

            if ($affectedLines === false) {
                throw new Exception( 'Impossibile aggiungere il commento !' );
            } else {
                // Create the Transport
                $transport = (new \Swift_SmtpTransport( 'smtp.mailtrap.io', 2525 ))
                    ->setUsername( 'dba91667e2ea0a' )
                    ->setPassword( 'dc8598d61f7a38' );

                // Create the Mailer using your created Transport
                $mailer = new \Swift_Mailer( $transport );//configuration smtp

                // Create a message
                $message = (new \Swift_Message( 'Hai ricevuto un nuovo commento al tuo post "' . $postTitle . '"' ))
                    ->setFrom( [$email => $author] )
                    ->setTo( ['pietrociccarello@gmail.com' => 'A Damiano'] )
                    ->setBody( 'Hai ricevuto un nuovo commento al tuo post dal titolo "'. $postTitle. '" <br><br>Ecco il commento:<br> '.$comment . ' <br><br>Indirizzo mail dell\'autore del commento: ' . $email. '<br><br>Puoi accedere alla pagina del commento, per decidere se approvarlo o meno, direttamente dal tuo spazio admin tramite questo <a href="http://localhost:8888/Damiano_Civiletto/adminPost-'. $postId. '">link</a>', 'text/html' );

                // Send the message
                $result = $mailer->send( $message );

                if ($result) {
                    $data = array('reponse' => 'success', 'content' => "Il messaggio è stato inviato correttamente ma sarà visibile soltanto dopo essere stato approvato");
                }
            }
            echo json_encode( $data );
        }
    }

}