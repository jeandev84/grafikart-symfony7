<?php

namespace App\Repository;

use App\DTO\CategoryWithCountDTO;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }




    /**
     * @return CategoryWithCountDTO[]
    */
    public function findAllWithCount(): array
    {
        return $this->createQueryBuilder('c')
                    ->select('NEW App\\DTO\\CategoryWithCountDTO(c.id, c.name, COUNT(c.id))')
                    ->leftJoin('c.recipes', 'r')
                    ->groupBy('c.id')
                    ->getQuery()
                    /* Case translation
                    ->setHint(
                        Query::HINT_CUSTOM_OUTPUT_WALKER,
                        TranslationWalker::class
                    )
                    */
                    ->getResult();
    }



    /**
     * @return array
     */
    public function createDQLExample(): array
    {
        /*
        $this->getEntityManager()->createNativeQuery(
            'CREATE NATIVE SQL HERE'
        )->getResult();
        */

        /*
        dd(
            $this->getEntityManager()->createQuery(<<<DQL
SELECT c.id, COUNT(c.id), c.name
FROM App\Entity\Category c
LEFT JOIN c.recipes r
GROUP BY c.id
DQL)->getResult()
        );

           dd(
            $this->getEntityManager()->createQuery(<<<DQL
SELECT NEW App\\DTO\\CategoryWithCountDTO(c.id, c.name, COUNT(c.id))
FROM App\Entity\Category c
LEFT JOIN c.recipes r
GROUP BY c.id
DQL)->getResult()
        );
        */
        return [];
    }
}
