<?php

namespace Cinetic\ReservaBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Cinetic\ReservaBundle\Entity\Reserva;
use Cinetic\ReservaBundle\Form\ReservaType;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * Class CalendarioController
 * @package Cinetic\CalendarioBundle\Controller
 * @Route("/calendario")
 */
class CalendarioController extends Controller
{
    /**
     * Printa un calendario según el año y mes que se le pasan del qual se puede realizar una reserva.
     * @Route("/show/{year}/{mes}", name="calendario_mes")
     */
    public function mesAction($year,$mes){

        //Objeto DateTime para ponerlo al primer dia del año y mes determinados
        $data = new \DateTime;
        $data->setDate($year,$mes,1);
        $cantidad_dias = $data->format('t');
        //

        //array con los dias del mes en formato [1]Miércoles, [2]Jueves según mes y año
        $diasMes=[];
        for($i=1 ; $i <= $cantidad_dias ; $i++) {
            $dia = $this->traduccionDia($data->format('l'));
            $diasMes[$i] = $dia; //añade el primer dia del mes según Martes, Lunes...
            $data->modify('tomorrow');
        }
        //

        $em = $this->getDoctrine()->getManager();

        //array de las fiestas de un año y mes determinados
        $fiestas = $em->getRepository('CineticExcepcionBundle:Fiesta')
            ->findAllFromYearAndMonth($year, $mes);
        //
        //array de las excepciones en un año y mes determinados
        $excepciones = $em->getRepository('CineticExcepcionBundle:Excepcion')
            ->findAllFromYearAndMonth($year, $mes);
        //

        //array de las reservas en un año y mes determinados
        $reservas = $em->getRepository('CineticReservaBundle:Reserva')
            ->findAllFromYearAndMonth($year, $mes);
        //

        //array que relaciona numero del dia con las franjas o no del objeto
        $diasObjeto=[];
        for($i=1 ; $i <= $cantidad_dias ; $i++) {
            //en primer lugar miro si existe una fiestaObjeto en este dia numero $i
            $fiestaObjeto = $this->buscarDia($fiestas, $i);
            if ($fiestaObjeto) {
                $diasObjeto[$i] = $fiestaObjeto;
            }
            else {
                //en segundo lugar miro si existe una ExcepcionObjeto en esta dia numero $i
                $excepcionObjeto = $this->buscarDia($excepciones, $i);
                if($excepcionObjeto) {
                    $diasObjeto[$i] = $excepcionObjeto;
                }
                //finalmente si no se encuentra ni fiesta, ni excepcion, se establece por defecto
                else {
                    $diasObjeto[$i] = $em->getRepository('CineticDiaBundle:Dia')
                        ->findOneByNombre($diasMes[$i]);
                }
            }
        }

        //array que mira si hay dias reservados en este mes
        $diasReservados=[];
        for($i=1 ; $i <= $cantidad_dias ; $i++) {
            $reservaObjeto = $this->buscarDia($reservas, $i);
            if ($reservaObjeto) {
                $diasReservados[$i] = $reservaObjeto;
            }
        }
        //

        return $this->render('calendario/mes.html.twig',array(
            'year' => $year,
            'mes' => $mes,
            'diasMes' => $diasMes,
            'diasObjeto' => $diasObjeto,
            'diasReservados' => $diasReservados,
        ));
    }

    /**
     * Busca en una array con objetos que tiene propiedad dia si el número coincide.
     *
     * @param $array
     * @param $numero
     * @return Object or False
     */
    private function buscarDia($array, $numero) {
        foreach ($array as $dia) {
            if ( ($dia->getDia()->format('j')) == ($numero) ) {
                return $dia;
            }
        }
        return false;
    }

    /**
     * Hace la traducción necesaria del objeto DateTime para poder ser compatible con base de datos
     * @param $dia
     * @return string
     */
    private function traduccionDia($dia)
    {
        if($dia == 'Monday') {
            $dia = 'Lunes';
        }
        elseif($dia == 'Tuesday') {
            $dia = 'Martes';
        }
        elseif($dia == 'Wednesday') {
            $dia = 'Miércoles';
        }
        elseif($dia == 'Thursday') {
            $dia = 'Jueves';
        }
        elseif($dia == 'Friday') {
            $dia = 'Viernes';
        }
        elseif($dia == 'Saturday') {
            $dia = 'Sábado';
        }
        else {
            $dia = 'Domingo';
        }
        return $dia;
    }

    /**
     * Renderiza el formulario según la franja seleccionada.
     *
     * @Route("/seleccion/{year}/{mes}/{day}/{horaInicio}/{horaFinal}", name="calendario_seleccion")
     */
    public function seleccionAction($year, $mes, $day, $horaInicio, $horaFinal){

        $data = new \DateTime;
        $data->setDate($year,$mes,1);

        //Popular el formulario con lo seleccionado del calendario
        $reserva = new Reserva();
        $diaSeleccionado = new \DateTime();
        $diaSeleccionado->setDate($year,$mes,$day);
        $reserva->setHoraInicio($horaInicio);
        $reserva->setHoraFinal($horaFinal);
        $reserva->setDia($diaSeleccionado);
        $form = $this->createCreateForm($reserva);

        return $this->render('calendario/seleccion.html.twig',array(
            'reserva' => $reserva,
            'form' => $form->createView(),
            ));
    }

    /**
     * Crea el formulario para hacer una reserva
     * @param Reserva $reserva entity
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Reserva $reserva)
    {
        $form = $this->createForm(new ReservaType(), $reserva, array(
            'action' => $this->generateUrl('reserva_create'),
            'method' => "POST",
        ));

        $form ->add('submit', 'submit', array('label'=>'Hacer la reserva'));

        return $form;
    }
}
