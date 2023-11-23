<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
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
        $categories = $this->loadCategories($manager);
        $users = $this->loadUsers($manager);

        for ($i = 1; $i <= 100; $i++) {
            $post = $this->createPost();

            // On donne une catégorie au hasard dans la liste des catégories à l'article
            $post->setCategory($categories[array_rand($categories)]);

            // On rattache un utilisateur au hasard à un article
            $post->setUser($users[array_rand($users)]);

            for ($j = 1; $j <= 3; $j++) {
                $comment = $this->createComment($post);
                $manager->persist($comment);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @return Category[]
     */
    public function loadCategories(ObjectManager $manager): array
    {
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

        // Tableau qui contient des instances de catégories
        return array_map(function ($categoryName) use ($manager) {
            $category = $this->createCategory($categoryName);
            $manager->persist($category);
            return $category;
        }, $categories);
    }

    /**
     * @param ObjectManager $manager
     * @return User[]
     */
    public function loadUsers(ObjectManager $manager): array
    {
        $users = [
            [
                'email' => 'admin@symfoblog.dev',
                'name' => 'admin',
                'password' => 'adminadmin'
            ],
            [
                'email' => 'demo@symfoblog.dev',
                'name' => 'demo',
                'password' => 'demodemo'
            ]
        ];

        return array_map(function ($user) use ($manager) {
            $user = $this->createUser($user['email'], $user['name'], $user['password']);
            $manager->persist($user);
            return $user;
        }, $users);
    }

    public function createPost(): Post
    {
        $post = new Post();
        $post->setTitle($this->faker->words(mt_rand(3, 5), true))
            ->setContent($this->faker->paragraphs(mt_rand(3, 5), true));

        return $post;
    }

    public function createComment(Post $post): Comment
    {
        $comment = new Comment();
        $comment->setNickname($this->faker->userName())
            ->setContent($this->faker->paragraphs(mt_rand(3, 6), true))
            ->setPost($post);

        return $comment;
    }

    public function createCategory(string $categoryName): Category
    {
        $category = new Category();
        $category->setName($categoryName);
        return $category;
    }

    public function createUser(string $email, string $name, string $password): User
    {
        $user = new User();
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);

        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($hashedPassword);
        return $user;
    }
}
