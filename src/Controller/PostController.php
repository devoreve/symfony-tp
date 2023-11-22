<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
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
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde de l'article en base de données
            $entityManager->persist($post);
            $entityManager->flush();

            // Redirection vers la liste des articles
            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route(path: '/post/{id}', name: 'app_post_show')]
    public function show(
        int                    $id, PostRepository $postRepository,
        EntityManagerInterface $entityManager,
        CommentRepository      $commentRepository,
        Request                $request
    ): Response
    {
        $post = $postRepository->find($id);

        // On déclenche une exception si l'article n'existe pas
        if ($post === null) {
            throw $this->createNotFoundException("L'article demandé n'existe pas");
        }

        // Création d'un commentaire vide
        $comment = new Comment();

        // Création du formulaire pour les commentaires avec l'entité liée
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // L'entité commentaire a été mise à jour avec les données du formulaire
            // Je rattache le commentaire à l'article
            $comment->setPost($post);

            // Mise à jour de la base de données
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirection sur la page qui affiche le détail de l'article et les commentaires
            return $this->redirectToRoute('app_post_show', ['id' => $id]);
        }

        // Récupération de tous les commentaires de l'article
        $comments = $commentRepository->findOldest($post->getId());

        // Affichage du détail de l'article et des commentaires
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'form' => $form
        ]);
    }
}
