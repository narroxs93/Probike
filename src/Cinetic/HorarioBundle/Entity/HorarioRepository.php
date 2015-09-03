<?php

namespace Cinetic\HorarioBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * HorarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HorarioRepository extends EntityRepository
{
    /**
     * Busca y ordena los dias según su Id en la base de datos.
     */
    public function findAllOrderedById()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT d FROM CineticHorarioBundle:Horario d ORDER BY d.id ASC'
            )
            ->getResult();
    }
}
