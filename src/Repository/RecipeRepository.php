<?php
namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, Recipe::class);
    }




    /**
     * @param int $page
     * @param int|null $userId
     * @return PaginationInterface
    */
    public function paginateRecipes(int $page, ?int $userId = null): PaginationInterface
    {
        $qb = $this->createQueryBuilder('r')
                   ->leftJoin('r.category', 'c')
                   ->select('r', 'c');

        if ($userId) {
           $qb = $qb->andWhere('r.user = :user')
                    ->setParameter('user', $userId);
        }

        return $this->paginator->paginate(
            $qb,
            $page,
            20,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['r.id', 'r.title'] // permet de limiter les champs sortable
            ]
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
