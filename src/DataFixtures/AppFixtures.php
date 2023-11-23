<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
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
        $categories = $this->loadCategories($manager);

        for ($i = 1; $i <= 100; $i++) {
            $post = $this->createPost();

            // On donne une catégorie au hasard dans la liste des catégories à l'article
            $post->setCategory($categories[array_rand($categories)]);

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
}
