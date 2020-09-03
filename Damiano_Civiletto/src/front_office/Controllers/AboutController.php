<?php
namespace App\front_office\Controllers;

class AboutController extends \App\Core\Basecontroller
{
    public function showAboutPage()
    {
        echo $this->twig->render('about.twig');
    }

}