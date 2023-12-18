<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Factory\CategoryFactory;
use App\Factory\PostFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
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

        CategoryFactory::createMany(count($categories), static function (int $i) use ($categories) {
            return ['name' => $categories[$i - 1]];
        });

        $user = UserFactory::createOne([
            'email' => 'admin@symfoblog.dev',
            'name' => 'admin',
            'password' => 'adminadmin'
        ]);

        UserFactory::createMany(10);

        PostFactory::createMany(10, [
            'user' => $user
        ]);

        PostFactory::createMany(100);
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
