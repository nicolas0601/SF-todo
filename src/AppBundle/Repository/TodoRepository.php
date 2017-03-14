<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TodoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TodoRepository extends EntityRepository
{

    public function findByTrashed($isTrashed = false)
    {
        if (!$isTrashed ) {
            //premier array ts les champs et deuxieme ordrer by nom champs + asc ou dsc
            return $this->findBy(['trashed' => 0], ['date' => 'DESC',]);
        }
        else{
            return $this->findBy(['trashed' => 1], ['date' => 'DESC',]);

        }
    }

}