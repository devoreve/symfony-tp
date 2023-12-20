<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findLatest(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.featured', 'DESC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->leftJoin('p.tags', 'tag')
            ->addSelect('tag')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     *
     * @param int $page
     * @param int $postsPerPage
     * @return Post[] Returns an array of Post objects
     */
    public function findLatestByPage(int $page = 1, int $postsPerPage = 10): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.featured', 'DESC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->leftJoin('p.tags', 'tag')
            ->addSelect('tag')
            ->setMaxResults($postsPerPage)
            ->setFirstResult(($page - 1) * $postsPerPage)
            ->getQuery()
            ->getResult()
            ;
    }


    public function latestPostQuery(): Query
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.featured', 'DESC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->leftJoin('p.tags', 'tag')
            ->addSelect('tag')
            ->getQuery();
    }

    /**
     * @return int|null
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function totalPosts(): ?int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id) AS total')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Post $post
     * @return Post|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNextPost(Post $post): ?Post
    {
        return $this->createQueryBuilder('p')
            ->where('p.createdAt > :createdAt')
            ->orderBy('p.createdAt', 'ASC')
            ->setParameter('createdAt', $post->getCreatedAt())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Post $post
     * @return Post|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findPreviousPost(Post $post): ?Post
    {
        return $this->createQueryBuilder('p')
            ->where('p.createdAt < :createdAt')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('createdAt', $post->getCreatedAt())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
