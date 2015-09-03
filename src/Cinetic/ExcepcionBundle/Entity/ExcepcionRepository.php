<?php

namespace Cinetic\ExcepcionBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ExcepcionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExcepcionRepository extends EntityRepository
{
    /**
     * Busca y ordena los dias según su Id en la base de datos.
     */
    public function findAllOrderedById()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT d FROM CineticExcepcionBundle:Excepcion d ORDER BY d.id ASC'
            )
            ->getResult();
    }

    /**
     * Busca los dias festivos según un año y mes determinados.
     */
    public function findAllFromYearAndMonth($year, $month=null)
    {
        //formato la string para utilizarla en el operador like
        if(($month==10) | ($month==11) | ($month==12)) {
            $string = $year.'-'.$month.'%';
        }
        elseif(is_null($month)) {
            $string = $year.'%';
        }
        else {
            $string = $year.'-0'.$month.'%';
        }
        return $this->getEntityManager()
            ->createQuery(
                'SELECT o
                FROM CineticExcepcionBundle:Excepcion o
                WHERE o.dia LIKE :string
                ORDER BY o.id ASC
                '
            )
            ->setParameter('string',$string)
            ->getResult();
    }
}
