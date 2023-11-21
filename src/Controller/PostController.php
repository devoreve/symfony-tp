<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        // Créer un article grâce à notre entité Post
        $post = new Post();
        $post->setTitle('Hello world')
            ->setContent('Lorem ipsum...');

        $entityManager->persist($post);
        $entityManager->flush();

        return new Response("L'article n°{$post->getId()} a bien été ajouté");
    }
}
