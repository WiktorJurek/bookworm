<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/', name: 'app_book_list', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(BookSearchType::class, null, [
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        $queryBuilder = $entityManager->getRepository(Book::class)->createQueryBuilder('b');

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $queryBuilder
                ->where('b.title LIKE :query')
                ->orWhere('b.author LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('book/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/book/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
                'message' => 'Nie znaleziono książki',
            ]);
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
