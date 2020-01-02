<?php

namespace AlbumReview\AlbumReviewBundle\Repository;

/**
 * AlbumEntryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AlbumEntryRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLatest($limit, $offset)
    {
        $queryBuilder = $this->createQueryBuilder('entry');
        $queryBuilder->orderBy('entry.timestamp', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getSearchResults($query)
    {
        //Select album from album table where title or artist is
        //like the query string entered.
        $queryBuilder = $this->createQueryBuilder('album');
        $queryBuilder->select('album')
            ->where('album.title LIKE :string')
            ->orWhere('album.artist LIKE :string')
            ->setParameter('string', '%'.$query.'%');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
