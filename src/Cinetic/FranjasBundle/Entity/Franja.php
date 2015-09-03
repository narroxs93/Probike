<?php

namespace Cinetic\FranjasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Franja
 *
 * TODO: validació de tots els camps corresponguin als que jo vull tenir a la base de dades
 * @ORM\Table(name="franja")
 * @ORM\Entity(repositoryClass="Cinetic\FranjasBundle\Entity\FranjaRepository")
 */
class Franja
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_inicio", type="time")
     */
    private $horaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_final", type="time")
     */
    private $horaFinal;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set horaInicio
     *
     * @param \DateTime $horaInicio
     * @return Franja
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    /**
     * Get horaInicio
     *
     * @return \DateTime 
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * Set horaFinal
     *
     * @param \DateTime $horaFinal
     * @return Franja
     */
    public function setHoraFinal($horaFinal)
    {
        $this->horaFinal = $horaFinal;

        return $this;
    }

    /**
     * Get horaFinal
     *
     * @return \DateTime 
     */
    public function getHoraFinal()
    {
        return $this->horaFinal;
    }

    public function __toString()
    {
        return 'HI:'.$this->getHoraInicio()->format('H:i:s')
                .' HF:'.$this->getHoraFinal()->format('H:i:s').'';
    }
}
