<?php

namespace Cinetic\ReservaBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Cinetic\ReservaBundle\Entity\Reserva;
use Cinetic\ReservaBundle\Form\ReservaType;


/**
 * Class ReservaController
 * @package Cinetic\ReservaBundle\Controller
 * @Route("/reserva")
 */
class ReservaController extends Controller
{
    /**
     * Muestra las opciones que hay para reservas
     *
     * @Route("/", name="reserva")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('reserva/reserva.html.twig');
    }

    /**
     * Captura el formulario de una nueva reserva y la guarda a la base de datos
     *
     * @Route("/", name="reserva_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $reserva = new Reserva();
        $form = $this->createCreateForm($reserva);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reserva);
            $em->flush();

            return $this->redirectToRoute('reserva_done');
        }

        return $this->render('reserva/new.html.twig');
    }

    /**
     * Crea una nueva reserva
     *
     * @Route("/new", name="reserva_new")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $reserva = new Reserva();
        $form = $this->createCreateForm($reserva);

        return $this->render('reserva/new.html.twig', array(
           'reserva' => $reserva,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/done", name="reserva_done")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doneAction(){
        return $this->render('reserva/done.html.twig');
    }

    /**
     * @Route("/lista", name="reserva_lista")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reservas = $em->getRepository('CineticReservaBundle:Reserva')->findAll();

        return $this->render('reserva/lista.html.twig', array(
            'reservas' => $reservas,
        ));
    }

    /**
     * @Route("/asistencia/{id}", name="asistencia_modify")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function asistenciaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $reserva = $em->getRepository('CineticReservaBundle:Reserva')->find($id);

        //canvia el valor de la asistencia que te aquesta reserva
        if(($reserva->getAsistencia()) == 1) {
            $reserva->setAsistencia(0);
        }
        else {
            $reserva->setAsistencia(1);
        }
        $em->persist($reserva);
        $em->flush();

        return $this->redirectToRoute('reserva_lista');
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
