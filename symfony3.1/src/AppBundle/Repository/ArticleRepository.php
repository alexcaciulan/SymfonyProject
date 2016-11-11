<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/10/2016
 * Time: 11:17 AM
 */

namespace AppBundle\Repository;


use AppBundle\Entity\User;

class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param User $user
     * @return mixed
     */
    public function findAllByUser(User $user){
        return $this->createQueryBuilder('article')
            ->andWhere('article.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }

    /**
     * @return mixed
     */
    public function findAllOrderByLatest(){
        return $this->createQueryBuilder('article')
            ->orderBy('article.id', 'DESC')
            ->getQuery()
            ->execute();
    }
}