<?php

namespace Cinetic\FranjasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Cinetic\FranjasBundle\Entity\Franja;

/**
 * Class FranjasController
 * @package Cinetic\FranjasBundle\Controller
 * @Route("/franjas")
 */
class FranjasController extends Controller
{
    /**
     * @Route("/", name="franjas")
     */
    public function indexAction()
    {
        return $this->render('franjas/index.html.twig');
    }

    /**
     * Crea la entidad franjas y llena la base de datos según el formulario/intervalo minutos.
     * Crea las franjas iniciales que no deben ser modificadas a posteriori.
     *
     * @Route("/create", name="franjas_create")
     * @Method("GET")
     */
    public function createAction(Request $request)
    {
        $franja = new Franja();

        //crear formulario para las franjas predefinidas y capturarlo
        $form = $this->createCreateFirstFranjasForm($franja);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $intervalo = $form["intervalo"]->getData();

            $franja = new Franja();

            $horaInicio = new \DateTime();
            $horaInicio->setTime(0,0,0);
            $franja->setHoraInicio($horaInicio);

            $horaFinal = new \DateTime();
            $horaFinal->setTime(0,$intervalo,0);
            $franja->setHoraFinal($horaFinal);

            $em = $this->getDoctrine()->getManager();
            $em->persist($franja);
            $em->flush();

            //añadimos tantas franjas como minutos/intervalo del formulario
            for($i=1 ; $i<(1440/$intervalo) ; $i++ ) {
                //añadimos los minutos del intervalo y los volvemos a asignar a franja
                $franja = new Franja();
                $horaInicio->add(new \DateInterval('PT'.$intervalo.'M'));
                $horaFinal->add(new \DateInterval('PT'.$intervalo.'M'));
                $franja->setHoraInicio($horaInicio);
                $franja->setHoraFinal($horaFinal);

                $em = $this->getDoctrine()->getManager();
                $em->persist($franja);
                $em->flush();
            }
            return $this->redirectToRoute('franjas_done');
        }

        return $this->render('franjas/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/done", name="franjas_done")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doneAction()
    {
        return $this->render('franjas/done.html.twig');
    }

    /**
     * @Route("/lista", name="franjas_lista")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $franjas = $em->getRepository('CineticFranjasBundle:Franja')->findAll();

        return $this->render('franjas/lista.html.twig', array(
            'franjas' => $franjas,
        ));
    }
    /**
     * Crea el formulario para las primeras franjas por defecto.
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateFirstFranjasForm()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('franjas_create'))
            ->setMethod('GET')
            ->add('intervalo','integer')
            ->add('guardar', 'submit')
            ->getForm();
        return $form;
    }
}

