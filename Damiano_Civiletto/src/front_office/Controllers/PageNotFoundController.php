<?php
namespace App\front_office\Controllers;

// Class that shows the page 404 and the exception catched
Class PageNotFoundController extends \App\Core\Basecontroller
{
    /**
     * It shows the 404 page and the messages in the exception catched.
     * @param string $errorMessage
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show404(string $errorMessage)
    {
        echo $this->twig->render('404.twig', ['errorMessage' => $errorMessage]);
    }
}