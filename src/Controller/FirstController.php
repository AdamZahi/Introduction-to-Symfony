<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[route('/first')]
class FirstController extends AbstractController
{
    #[Route('/index', name: 'first_index')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'FirstName' => 'Adam',
            'LastName' => 'Zahi',
        ]);
    }

    #[route('/show/{FileName}', name: 'first_show')]
    public function show($FileName):Response{
        return $this->render('first/show.html.twig',['file'=>$FileName]);
    }

    #[route('/redirect', name: 'first_redirect')]
    public function redirectExample():Response{
        return $this->redirectToRoute('first_index');
    }
}
