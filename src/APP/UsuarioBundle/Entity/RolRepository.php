<?php

namespace APP\UsuarioBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RolRepository extends EntityRepository
{
    public function findRoles($roles_id)
    {
        return $this->getEntityManager()
                ->createQuery("SELECT r
                    FROM UsuarioBundle:Rol r
                    WHERE r.id in (:roles)")
                ->setParameter('roles', $roles_id)
                ->getResult();
    }
}