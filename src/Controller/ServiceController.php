<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/service')]
class ServiceController extends AbstractController
{

    #[Route('/show/{name}')]
    public function showService($name): Response{
        return $this->render('service/service.html.twig',['name'=>$name]);
    }
}
