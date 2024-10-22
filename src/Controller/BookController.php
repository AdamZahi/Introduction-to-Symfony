<?php

namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/add', name:'add_book')]
    public function addBook(ManagerRegistry $manager, Request $request): Response
    {
        $book= new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager = $manager->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('listDB_author');
        }
        
        return $this->render('book/addBook.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/category', name:'category_book')]
    public function categoryBook(BookRepository $repo): Response{
        $repo -> countBook('Romance');
        return $this->render('book/category.html.twig');
    }
}
