<?php

namespace Cinetic\DiaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Dia
 * TODO: validació de tots els camps corresponguin als que jo vull tenir a la base de dades
 * @ORM\Table(name="dia")
 * @ORM\Entity(repositoryClass="Cinetic\DiaBundle\Entity\DiaRepository")
 */
class Dia
{
    /**
     *
     * @ORM\ManytoMany(targetEntity="\Cinetic\HorarioBundle\Entity\Horario", cascade={"persist"})
     * @ORM\JoinTable(name="dias_horarios",
     *              joinColumns={@ORM\JoinColumn(name="dia_id", referencedColumnName="id")},
     *              inverseJoinColumns={@ORM\JoinColumn(name="horario_id", referencedColumnName="id")}
     *              )
     */
    private $horarios;

    public function __construct(){
        $this->horarios = new ArrayCollection();
    }

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50, unique=true)
     * @Assert\NotBlank()
     */
    private $nombre;


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
     * @return Dia
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
     * Add horarios
     *
     * @param \Cinetic\HorarioBundle\Entity\Horario $horarios
     * @return Dia
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
