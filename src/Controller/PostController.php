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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
    public function create(EntityManagerInterface $manager, Request $request): Response
    {
        $post = new Post();
        return $this->save($post, $request, $manager);
    }

    #[Route(path: '/post/{id}/edit', name: 'app_post_edit')]
    public function edit(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        return $this->save($post, $request, $manager);
    }

    /**
     * @param Post $post
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    private function save(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        // Si l'utilisateur n'est pas connecté, on lui interdit l'accès
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Si l'utilisateur n'est pas l'auteur de l'article
        if ($post->getId() !== null) {
            if ($post->getUser() !== $this->getUser()) {
                throw new AccessDeniedException("Cet article n'est pas le vôtre");
            }
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On rattache l'utilisateur connecté à l'article créé
            $post->setUser($this->getUser());

            // Ajout/Modification dans la base de données
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/create.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }

    #[Route(path: '/post/{id}', name: 'app_post_show')]
    public function show(
        Post                   $post,
        EntityManagerInterface $manager,
        CommentRepository      $commentRepository,
        Request                $request
    ): Response
    {
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
            $manager->persist($comment);
            $manager->flush();

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
