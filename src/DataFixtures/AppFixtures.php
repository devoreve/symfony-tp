<?php

namespace App\DataFixtures;

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
        for ($i = 1; $i <= 100; $i++) {
            $post = $this->createPost();

            for ($j = 1; $j <= 3; $j++) {
                $comment = $this->createComment($post);
                $manager->persist($comment);
            }

            $manager->persist($post);
        }

        $manager->flush();
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
}
