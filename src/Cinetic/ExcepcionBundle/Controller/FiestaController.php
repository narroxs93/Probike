<?php

namespace Cinetic\ExcepcionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Cinetic\ExcepcionBundle\Entity\Fiesta;
use Cinetic\ExcepcionBundle\Entity\FiestaRepository;
use Cinetic\ExcepcionBundle\Form\FiestaType;

/**
 * @Route("/fiesta")
 * Class FiestaController
 * @package Cinetic\ExcepcionBundle\Controller
 */
class FiestaController extends Controller
{
    /**
     * @Route("/", name="fiesta")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('fiesta/fiesta.html.twig');
    }

    /**
     * @Route("/lista", name="fiesta_lista")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $fiestas = $em->getRepository('CineticExcepcionBundle:Fiesta')->findAllOrderedById();

        return $this->render('fiesta/lista.html.twig', array(
            'fiestas' => $fiestas,
        ));
    }

    /**
     * @Route("/done", name="fiesta_done")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doneAction()
    {
        return $this->render('fiesta/done.html.twig');
    }

    /**
     * @Route("/", name="fiesta_create")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $fiesta = new Fiesta();
        $form = $this->createCreateForm($fiesta);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fiesta);
            $em->flush();

            return $this->redirectToRoute('fiesta_done');
        }
        return $this->render('fiesta/new.html.twig', array(
            'fiesta' => $fiesta,
            'form' => $form->createView(),
        ));
    }

    /**
     * Enseña el formulario para hacer una nueva fiesta.
     *
     * @Route("/new", name="fiesta_new")
     * @Method("GET")
     */
    public function newAction()
    {
        $fiesta = new Fiesta();
        $form = $this->createCreateForm($fiesta);

        return $this->render('fiesta/new.html.twig', array(
            'fiesta' => $fiesta,
            'form' => $form->createView(),
        ));
    }

    /**
     * Crear un formulario para crear una entidad Fiesta.
     *
     * @param Fiesta $fiesta The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Fiesta $fiesta)
    {
        $form = $this->createForm(new FiestaType(), $fiesta, array(
            'action' => $this->generateUrl('fiesta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear Fiesta'));

        return $form;
    }

    /**
     * Enseña el formulario para editar una excepción.
     *
     * @Route("/edit/{id}", name="fiesta_edit")
     * @Method("GET")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $fiesta = $em->getRepository('CineticExcepcionBundle:Fiesta')->find($id);

        if (!$fiesta) {
            throw $this->createNotFoundException('No se ha encontrado esta fiesta.');
        }

        $editForm = $this->createEditForm($fiesta);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('fiesta/edit.html.twig', array(
            'fiesta' => $fiesta,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edita una fiesta concreta.
     *
     * @Route("/{id}", name="fiesta_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $fiesta = $em->getRepository('CineticExcepcionBundle:Fiesta')->find($id);

        if (!$fiesta) {
            throw $this->createNotFoundException('No se ha encontrado esta fiesta.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($fiesta);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('fiesta_lista'));
        }

        return $this->render('fiesta/edit.html.twig', array(
            'fiesta' => $fiesta,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Fiesta entity.
     *
     * @Route("/{id}", name="fiesta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $fiesta = $em->getRepository('CineticExcepcionBundle:Fiesta')->find($id);
            if (!$fiesta) {
                throw $this->createNotFoundException('Unable to find Fiesta entity.');
            }
            $em->remove($fiesta);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('fiesta_lista'));
    }

    /**
     * Creates a form to delete a Fiesta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fiesta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar Fiesta'))
            ->getForm();
    }

    /**
     * Creates a form to edit a Fiesta entity.
     *
     * @param Fiesta $fiesta The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Fiesta $fiesta)
    {
        $form = $this->createForm(new FiestaType(), $fiesta, array(
            'action' => $this->generateUrl('fiesta_update', array('id' => $fiesta->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
}
