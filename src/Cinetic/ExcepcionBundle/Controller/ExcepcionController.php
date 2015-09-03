<?php

namespace Cinetic\ExcepcionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Cinetic\ExcepcionBundle\Entity\Excepcion;
use Cinetic\ExcepcionBundle\Entity\ExcepcionRepository;
use Cinetic\ExcepcionBundle\Form\ExcepcionType;

/**
 * @Route("/excepcion")
 * Class ExcepcionController
 * @package Cinetic\ExcepcionBundle\Controller
 */
class ExcepcionController extends Controller
{
    /**
     * @Route("/", name="excepcion")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('excepcion/excepcion.html.twig');
    }
    /**
     * @Route("/lista", name="excepcion_lista")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $excepciones = $em->getRepository('CineticExcepcionBundle:Excepcion')->findAllOrderedById();

        return $this->render('excepcion/lista.html.twig', array(
            'excepciones' =>$excepciones,
        ));
    }
    /**
     * @Route("/done", name="excepcion_done")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doneAction()
    {
        return $this->render('excepcion/done.html.twig');
    }

    /**
     * @Route("/", name="excepcion_create")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $excepcion = new Excepcion();
        $form = $this->createCreateForm($excepcion);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($excepcion);
            $em->flush();

            return $this->redirectToRoute('excepcion_done');
        }
        return $this->render('excecpion/new.html.twig', array(
            'excepcion' => $excepcion,
            'form' => $form->createView(),
        ));
    }
    /**
     * Enseña el formulario para hacer una nueva excepción.
     *
     * @Route("/new", name="excepcion_new")
     * @Method("GET")
     */
    public function newAction()
    {
        $excepcion = new Excepcion();
        $form   = $this->createCreateForm($excepcion);

        return $this->render('excepcion/new.html.twig',array(
            'excepcion' => $excepcion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Crear un formulario para crear una entidad Excepción.
     *
     * @param Excepcion $excepcion The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Excepcion $excepcion)
    {
        $form = $this->createForm(new ExcepcionType(), $excepcion, array(
            'action' => $this->generateUrl('excepcion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear Excepcion'));

        return $form;
    }

    /**
     * Enseña el formulario para editar una excepción.
     *
     * @Route("/{id}/edit", name="excepcion_edit")
     * @Method("GET")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $excepcion = $em->getRepository('CineticExcepcionBundle:Excepcion')->find($id);

        if(!$excepcion) {
            throw $this->createNotFoundException('No se ha encontrado esta excepción.');
        }

        $editForm = $this->createEditForm($excepcion);
        //$deleteForm = $this->createDeleteForm($id);

        return $this->render('excepcion/edit.html.twig',array(
            'excepcion' => $excepcion,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edita una excepción concreta.
     *
     * @Route("/{id}", name="excepcion_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $excepcion = $em->getRepository('CineticExcepcionBundle:Excepcion')->find($id);

        if (!$excepcion) {
            throw $this->createNotFoundException('No se ha encontrado esta excecpión.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($excepcion);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('excepcion_lista'));
        }

        return $this->render('excepcion/edit.html.twig',array(
            'excepción' => $excepcion,
            'edit_form' => $editForm->createView(),
        ));
    }
    /**
     * Creates a form to edit a Excepcion entity.
     *
     * @param Excepcion $excepcion The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Excepcion $excepcion)
    {
        $form = $this->createForm(new ExcepcionType(), $excepcion, array(
            'action' => $this->generateUrl('excepcion_update', array('id' => $excepcion->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
}