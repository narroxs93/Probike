<?php

namespace Cinetic\HorarioBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Horario
 *
 * @ORM\Table(name="horario")
 * @ORM\Entity(repositoryClass="Cinetic\HorarioBundle\Entity\HorarioRepository")
 */
class Horario
{
    /**
     *
     * @ORM\ManytoMany(targetEntity="\Cinetic\FranjasBundle\Entity\Franja", cascade={"persist"})
     * @ORM\JoinTable(name="horarios_franjas",
     *              joinColumns={@ORM\JoinColumn(name="horario_id", referencedColumnName="id")},
     *              inverseJoinColumns={@ORM\JoinColumn(name="franja_id", referencedColumnName="id")}
     *              )
     */
    private $franjas;

    public function __construct(){
        $this->franjas = new ArrayCollection();
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
     * @ORM\Column(name="nombre", type="string", length=65)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaInicio", type="time")
     */
    private $horaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaFinal", type="time")
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
     * Set nombre
     *
     * @param string $nombre
     * @return Horario
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
     * Set horaInicio
     *
     * @param \DateTime $horaInicio
     * @return Horario
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
     * @return Horario
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

    /**
     * Add franjas
     *
     * @param \Cinetic\FranjasBundle\Entity\Franja $franjas
     * @return Horario
     */
    public function addFranja(\Cinetic\FranjasBundle\Entity\Franja $franjas)
    {
        $this->franjas[] = $franjas;

        return $this;
    }

    /**
     * Remove franjas
     *
     * @param \Cinetic\FranjasBundle\Entity\Franja $franjas
     */
    public function removeFranja(\Cinetic\FranjasBundle\Entity\Franja $franjas)
    {
        $this->franjas->removeElement($franjas);
    }

    /**
     * Get franjas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFranjas()
    {
        return $this->franjas;
    }

    /**
     * Como se representa la entidad horario para convertirlo en string.
     * @return string
     */
    public function __toString()
    {
        return ''.$this->getNombre().' ('
                .$this->getHoraInicio()->format("H:i")
                .'-'.$this->getHoraFinal()->format("H:i").')';
    }

}
