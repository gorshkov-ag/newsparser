<?php
/**
 * Created by PhpStorm.
 * User: gorshkov-ag
 * Date: 08.06.2018
 * Time: 22:44
 */

namespace App\Repository;


use App\Entity\NewsItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class NewsItemsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NewsItem::class);
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $orderField
     * @param $orderDirection
     * @return NewsItem[]
     */
    public function filterByDate(\DateTime $dateFrom = null, \DateTime $dateTo = null, string $orderField = 'pubDate',
                                 string $orderDirection = 'DESC') : array {
        $qb = $this->createQueryBuilder('n');

        if (!is_null($dateFrom)) {
            $qb->where('n.pubDate >= :df')
                ->setParameter('df', $dateFrom);
        }
        if (!is_null($dateTo)) {
            $qb->andWhere('n.pubDate <= :dt')
                ->setParameter('dt', $dateTo);
        }

        $q = $qb->orderBy('n.'.$orderField, $orderDirection)
            ->getQuery();

        return $q->execute();
    }
}