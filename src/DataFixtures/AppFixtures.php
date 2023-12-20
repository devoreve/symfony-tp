<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Factory\CategoryFactory;
use App\Factory\CommentFactory;
use App\Factory\PostFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        /* TODO
         * Créer entre 2 et 3 commentaires pour chacun des articles
         */

        // Liste des titres de catégories
        $categories = [
            'Voyage',
            'Cinéma',
            'Musique',
            'Cuisine',
            'Voiture',
            'Divers',
            'Programmation',
            'Loisir',
            'Sport',
            'Immobilier',
            'Série TV',
            'Livre/Mangas'
        ];

        // Catégories
        CategoryFactory::createMany(count($categories), static function (int $i) use ($categories) {
            return ['name' => $categories[$i - 1]];
        });

        // Tags
        $tags = [
            'php',
            'js',
            'symfony',
            'laravel',
            'react',
            'dev web',
            'unity',
            'c#',
            'dev jeu',
            'rock',
            'métal',
            'aventure',
            'rpg',
            'action'
        ];

        TagFactory::createMany(count($tags), static function (int $i) use ($tags) {
            return ['name' => $tags[$i - 1]];
        });

        // Utilisateurs
        UserFactory::createMany(10);

        // Articles avec commentaires
        PostFactory::createMany(100, ['comments' => CommentFactory::new()->many(2, 3)]);

        // Création de l'utilisateur admin
        $user = UserFactory::createOne([
            'email' => 'admin@symfoblog.dev',
            'name' => 'admin',
            'password' => 'adminadmin',
            'roles' => ['ROLE_ADMIN']
        ]);

        // Création des articles de l'adminstrateur
        PostFactory::createMany(10, [
            'user' => $user,
            'comments' => CommentFactory::new()->many(1)
        ]);

    }

    public function createComment(Post $post): Comment
    {
        $comment = new Comment();
        $comment->setNickname($this->faker->userName())
            ->setContent($this->faker->paragraphs(mt_rand(3, 6), true))
            ->setPost($post);

        return $comment;
    }
}
