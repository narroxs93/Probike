<?php

namespace Cinetic\ReservaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reserva
 *
 *
 * @ORM\Table(name="reserva")
 * @ORM\Entity(repositoryClass="Cinetic\ReservaBundle\Entity\ReservaRepository")
 */
class Reserva
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
     * @ORM\Column(name="dia", type="date")
     */
    private $dia;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicio", type="string")
     */
    private $horaInicio;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final", type="string")
     */
    private $horaFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=15)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=59)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=9)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=63)
     */
    private $email;

    /**
     * @var integer
     * @ORM\Column(name="asistencia", type="integer", nullable=true)
     * @Assert\Null()
     */
    private $asistencia;


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
     * Set dia
     *
     * @param \DateTime $dia
     * @return Reserva
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

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Reserva
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Reserva
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Reserva
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Reserva
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set asistencia
     *
     * @param int $asistencia
     * @return int
     */
    public function setAsistencia($asistencia)
    {
        $this->asistencia = $asistencia;

        return $this;
    }

    /**
     * Get asistencia
     *
     * @return int
     */
    public function getAsistencia()
    {
        return $this->asistencia;
    }
}

