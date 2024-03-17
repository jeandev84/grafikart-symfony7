<?php
namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }


    /**
     * @param int $page
     * @param int $limit
     * @return Paginator
    */
    public function paginateRecipes(int $page, int $limit): Paginator
    {
        return new Paginator(
            $this->createQueryBuilder('r')
                 ->setFirstResult(($page - 1) * $limit)
                 ->setMaxResults($limit)
                 ->getQuery()
                 ->setHint(Paginator::HINT_ENABLE_DISTINCT, false),
 false
        );
    }





    /**
     * @return int
    */
    public function findTotalDuration(): int
    {
        return $this->createQueryBuilder('r')
                    ->select("SUM(r.duration) as total")
                    ->getQuery()
                    ->getSingleScalarResult();
    }



    /**
     * Find all recipes where duration lower given $duration in minutes
     *
     * @param int $duration
     * @return Recipe[]
    */
    public function findWithDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder('r')
                    ->where("r.duration <= :duration")
                    ->orderBy("r.duration", 'ASC')
                    ->setMaxResults(10)
                    ->setParameter("duration", $duration)
                    ->getQuery()
                    ->getResult();
    }




    /*
    public function demoSomething(int $duration): array
    {
        return $this->createQueryBuilder('r')
            #->select('r', 'c')
            ->where("r.duration <= :duration")
            ->orderBy("r.duration", 'ASC')
            ->leftJoin('r.category', 'c')
            #->andWhere('c.slug = \'dessert\'')
            #->andWhere('c.id = 1')
            ->andWhere('r.category = 1')
            ->setMaxResults(10)
            ->setParameter("duration", $duration)
            ->getQuery()
            ->getResult();
    }
    */

}
