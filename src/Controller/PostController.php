<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route(path: '/post', name: 'app_post_index')]
    public function index(PostRepository $postRepository): Response
    {
        // Récupération de la liste des articles triés du plus récent au plus ancien
        $posts = $postRepository->findLatest();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route(path: '/post/create', name: 'app_post_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $request = Request::createFromGlobals();

        // Si on est en GET on affiche le formulaire
        if ($request->getMethod() === 'GET') {
            return $this->render('post/create.html.twig');
        }

        // Créer un article grâce à notre entité Post
        $post = new Post();
        $post->setTitle($request->request->get('title'))
            ->setContent($request->request->get('content'));

        $entityManager->persist($post);
        $entityManager->flush();

        // Redirection vers la liste des articles
        return $this->redirectToRoute('app_post_index');
    }

    #[Route(path: '/post/{id}', name: 'app_post_show')]
    public function show(int $id, PostRepository $postRepository, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        $post = $postRepository->find($id);

        // On déclenche une exception si l'article n'existe pas
        if ($post === null) {
            throw $this->createNotFoundException("L'article demandé n'existe pas");
        }

        $request = Request::createFromGlobals();

        if ($request->getMethod() === 'POST') {
            // Ajout du commentaire
            $comment = new Comment();
            $comment->setNickname($request->request->get('nickname'))
                ->setContent($request->request->get('content'));

            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirection sur la page qui affiche le détail de l'article et les commentaires
            return $this->redirectToRoute('app_post_show', ['id' => $id]);
        }

        // Récupération de tous les commentaires
        $comments = $commentRepository->findOldest();

        // Affichage du détail de l'article et des commentaires
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }
}
