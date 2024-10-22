<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/author')]
class AuthorController extends AbstractController{

    #[Route('/index', name:'index_author')]
    public function index(): Response{
        return $this->render('index.html.twig');
    }

    #[Route('/show/{name}', name:'show_author')]
    public function showAuthor($name):Response{
        return $this->render('author/show.html.twig',['authorName'=>$name]);
    }

    #[Route('/list', name:'list_author')]
    public function listAuthor (): Response{
        $authors = array(
            array('id' => 1, 'picture' => '/images/victor-hugo.jpeg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william.jpeg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/taha.jpeg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        return $this->render('author/list.html.twig',["authors"=>$authors]);
    }

    #[Route("/authorDetail/{id}", name:"authorDetail_author")]
    public function authorDetail(int $id):Response{
        $authors = array(
            array('id' => 1, 'picture' => '/images/victor-hugo.jpeg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william.jpeg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/taha.jpeg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        $author = array_filter($authors, fn($a)=> $a["id"]===$id);
        if (empty($author)) {
            throw $this->createNotFoundException('Author not found');
        }
        $author = array_values($author)[0];
        return $this -> render('author\showAuthor.html.twig',["author" => $author]);
    }

    #[Route("/listDB", name:"listDB_author")]
    public function listDB (ManagerRegistry $doctrine):Response{
        $repo = $doctrine->getRepository(Author::class);
        $list = $repo->orderedListQB();
        return $this->render('author/listDB.html.twig',[
            'list' => $list 
        ]) ;
    }
    #[Route("/interval", name:"more10_author")]
    public function intervalBooks (ManagerRegistry $doctrine, Request $request):Response{
        $repo = $doctrine->getRepository(Author::class);
        $valueMax = $request->get("max");
        $valueMin = $request->get("min");
        if($valueMax === null || $valueMin === null){
            $list = $repo->findAll();
        }else{
            $list = $repo->showInterval($valueMax,$valueMin);
        }
        return $this->render('author/listDB.html.twig',[
            'list' => $list 
        ]) ;
    }

    #[Route('/detailDB/{id}', name:'detailDB_author')]
    public function detailsDB(AuthorRepository $repo, $id): Response{
        $id = $repo->find($id);
        return $this->render('author/detailDB.html.twig',['author'=>$id]);
    }

    #[Route('/details/{id}', name: 'author_details', requirements: ['id' => '\d+'])]
    public function detailDb(AuthorRepository $repo, int $id): Response {
        $author = $repo->find($id);
    
        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }
    
        return $this->render('author/showAuthorDB.html.twig', [
            'author' => $author
        ]);
    }

    #[route('/details2/{id}', name:'author_details2')]
    public function authorDetails2(Author $author):Response{
        return $this->render('author/showAuthorDB.html.twig',
        ['author' => $author]);
    }
    #[Route('/addAuthorStatic', name:'author_add_static')]
    public function addAuthorStatic(ManagerRegistry $em):Response{
        $manager = $em -> getManager();
        $author = new Author();
        $author -> setUsername("Nate Furnestein");
        $author -> setNbBook(43);
        $manager->persist($author); // insert info
        $manager ->flush(); // execute in data base
        return new Response('Author Added successfully');
    }

    #[Route('/addAuthor', name:'author_add')]
    public function addAuthor(ManagerRegistry $manager, Request $request):Response{
        $em = $manager->getManager();
        $author = new Author();
        // Calling form
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em -> persist($author);
            $em -> flush();
            return $this -> redirectToRoute('listDB_author');
        }
        return $this -> render('author\addAuthor.html.twig',
        ['form'=> $form->createView()]);
    }

    #[Route('/update/{id}', name:'author_update')]
    public function updateAuthor(AuthorRepository $rep,ManagerRegistry $manager, Request $request, int $id):Response{
        $em = $manager->getManager();
        $author = $rep->find($id) ;
        // Calling form
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em -> persist($author);
            $em -> flush();
            return $this -> redirectToRoute('listDB_author');
        }
        return $this -> render('author\addAuthor.html.twig',
        ['form'=> $form->createView()]);
    }

    #[Route('/delete0' , name:'author_delete0')]
    public function deleteAuthor0(AuthorRepository $repo):Response{
        $repo->deleteAuthor0();
        return $this->redirectToRoute('listDB_author');
    }

    #[Route('/delete/{id}', name:'author_delete')]
    public function deleteAuthor(ManagerRegistry $manager, Author $author):Response{
        $em = $manager->getManager();
        $em -> remove($author);
        $em->flush();
        return $this -> redirectToRoute('listDB_author');
    }

}

