<?php

namespace Cinetic\HorarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Cinetic\HorarioBundle\Entity\Horario;
use Cinetic\HorarioBundle\Form\HorarioType;
use Cinetic\FranjasBundle\Entity\Franja;
use Cinetic\HorarioBundle\Entity\HorarioRepository;


/**
 * Class HorarioController
 * @package Cinetic\HorarioBundle\Controller
 * @Route("/horario")
 */
class HorarioController extends Controller
{
    /**
     * Visualiza las opciones con los horarios.
     *
     * @Route("/", name="horario")
     * @Method("GET")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('horario/horario.html.twig');
    }

    /**
     * Coje el formulario y crea un nuevo horario a la base de datos.
     *
     * @Route("/", name="horario_create")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $horario = new Horario();
        $form = $this->createCreateForm($horario);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $franjas = $em->getRepository('CineticFranjasBundle:Franja')->findAll();

            $horaInicio = $form["horaInicio"]->getData();
            $horaFinal = $form["horaFinal"]->getData();

            $horaInicioHorario = $horaInicio->format("H:i");
            $horaFinalHorario = $horaFinal->format("H:i");

            //numero total de franjas que existen
            $numeroFranjas = count($franjas);
            //comprovar todas las franjas horarias si se quieren añadir
            for($i=0 ; $i<$numeroFranjas ; $i++) {
                if( ($horaInicioHorario) <= ($franjas[$i]->getHoraInicio()->format("H:i")) ) {
                    if($horaFinalHorario == "00:00") {
                        if( ($horaFinalHorario) <= ($franjas[$i]->getHoraFinal()->format("H:i")) ) {
                            $horario->addFranja($franjas[$i]);
                        }
                    }
                    else {
                        if( ($franjas[$i]->getHoraFinal()->format("H:i")) != ("00:00") ) {
                            if( ($horaFinalHorario) >= ($franjas[$i]->getHoraFinal()->format("H:i")) ) {
                                $horario->addFranja($franjas[$i]);
                            }
                        }
                    }
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($horario);
            $em->flush();

            return $this->redirectToRoute('horario_show',array(
                'id' =>$horario->getId(),
            ));
        }
        return $this->render('proves/new.html.twig', array(
            'horario' => $horario,
            'form' => $form->createView(),
        ));
    }

    /**
     * Enseña el formulario para hacer un nuevo horario.
     *
     * @Route("/new", name="horario_new")
     * @Method("GET")
     * @return Response
     */
    public function newAction()
    {
        $horario = new Horario();
        $form = $this->createCreateForm($horario);

        return $this->render('horario/new.html.twig', array(
           'horario' => $horario,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/done", name="horario_done")
     * @Method("GET")
     * @return Response
     */
    public function doneAction()
    {
        return $this->render('horario/done.html.twig');
    }

    /**
     * @Route("/lista", name="horario_lista")
     * @Method("GET")
     * @return Response
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $horarios = $em->getRepository('CineticHorarioBundle:Horario')->findAll();

        return $this->render('horario/lista.html.twig',array(
            'horarios' => $horarios,
        ));
    }

    /**
     * TODO: realment show i franjasHorarioAction estan fent el mateix quasi bé, veure si es podria suprimir o no
     * @Route("/show/{id}", name="horario_show")
     * @Method("GET")
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $horario = $em->getRepository('CineticHorarioBundle:Horario')->find($id);

        if(!$horario) {
            throw $this->createNotFoundException('No se ha encontrado el horario.');
        }

        $franjas = $horario->getFranjas();

        return $this->render('horario/show.html.twig',array(
            'horario' => $horario,
            'franjas' => $franjas,
        ));
    }

    private function createCreateForm(Horario $horario)
    {
        $form = $this->createForm(new HorarioType(), $horario, array(
           'action' => $this->generateUrl('horario_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label'=>'Crear Horario'));

        return $form;
    }
}

