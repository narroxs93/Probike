<?php

namespace Cinetic\ExcepcionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Excepcion
 *
 * @ORM\Table(name="excepcion")
 * @ORM\Entity(repositoryClass="Cinetic\ExcepcionBundle\Entity\ExcepcionRepository")
 */
class Excepcion
{
    /**
     *
     * @ORM\ManytoMany(targetEntity="\Cinetic\HorarioBundle\Entity\Horario", cascade={"persist"})
     * @ORM\JoinTable(name="excepcion_horarios",
     *              joinColumns={@ORM\JoinColumn(name="excepcion_id", referencedColumnName="id")},
     *              inverseJoinColumns={@ORM\JoinColumn(name="horario_id", referencedColumnName="id")}
     *              )
     */
    private $horarios;

    public function __construct(){
        $this->horarios = new ArrayCollection();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=15)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dia", type="date")
     */
    private $dia;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Excepcion
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set dia
     *
     * @param \DateTime $dia
     * @return Excepcion
     */
    public function setDia($dia)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return \DateTime 
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Add horarios
     *
     * @param \Cinetic\HorarioBundle\Entity\Horario $horarios
     * @return Horario
     */
    public function addHorario(\Cinetic\HorarioBundle\Entity\Horario $horarios)
    {
        $this->horarios[] = $horarios;

        return $this;
    }

    /**
     * Remove horarios
     *
     * @param \Cinetic\HorarioBundle\Entity\Horario $horarios
     */
    public function removeHorario(\Cinetic\HorarioBundle\Entity\Horario $horarios)
    {
        $this->horarios->removeElement($horarios);
    }

    /**
     * Get horarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorarios()
    {
        return $this->horarios;
    }
}
