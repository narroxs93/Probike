<?php

namespace Cinetic\DiaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cinetic\DiaBundle\Entity\Dia;
use Cinetic\DiaBundle\Entity\DiaRepository;
use Cinetic\DiaBundle\Form\DiaType;

/**
 * Dia controller.
 *
 * @Route("/dia")
 */
class DiaController extends Controller
{
    /**
     * @Route("/lista", name="dia_lista")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $dias = $em->getRepository('CineticDiaBundle:Dia')->findAllOrderedById();

        return $this->render('dia/lista.html.twig', array(
            'dias' =>$dias,
        ));
    }

    /**
     * @Route("/defecto", name="dia_defecto")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function diasSemanaAction()
    {
        $nombres=[];
        $nombres[] = "Lunes";
        $nombres[] = "Martes";
        $nombres[] = "Miércoles";
        $nombres[] = "Jueves";
        $nombres[] = "Viernes";
        $nombres[] = "Sábado";
        $nombres[] = "Domingo";

        foreach($nombres as $nombre){
            $dia = new Dia();
            $dia->setNombre($nombre);

            $em = $this->getDoctrine()->getManager();
            $em->persist($dia);
            $em->flush();
        }
        return $this->redirectToRoute('dia_done');
    }

    /**
     * @Route("/done", name="dia_done")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doneAction()
    {
        return $this->render('dia/done.html.twig');
    }

    /**
     * @Route("/", name="dia_create")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $dia = new Dia();
        $form = $this->createCreateForm($dia);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dia);
            $em->flush();

            return $this->redirectToRoute('dia_done');
        }
        return $this->render('dia/new.html.twig', array(
            'dia' => $dia,
            'form' => $form->createView(),
        ));
    }

    /**
     * Enseña el formulario para hacer un nuevo dia.
     *
     * @Route("/new", name="dia_new")
     * @Method("GET")
     */
    public function newAction()
    {
        $dia = new Dia();
        $form   = $this->createCreateForm($dia);

        return $this->render('dia/new.html.twig', array(
            'dia' => $dia,
            'form' => $form->createView(),
        ));
    }

    /**
     * Crear un formulario para crear una entidad Dia.
     *
     * @param Dia $dia The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Dia $dia)
    {
        $form = $this->createForm(new DiaType(), $dia, array(
            'action' => $this->generateUrl('dia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear Dia'));

        return $form;
    }

    /**
     * Enseña el formulario para editar un dia.
     *
     * @Route("/{id}/edit", name="dia_edit")
     * @Method("GET")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $dia = $em->getRepository('CineticDiaBundle:Dia')->find($id);

        if(!$dia) {
            throw $this->createNotFoundException('No se ha encontrado este dia.');
        }

        $editForm = $this->createEditForm($dia);
        //$deleteForm = $this->createDeleteForm($id);

        return $this->render('dia/edit.html.twig',array(
           'dia' => $dia,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edita un dia concreto.
     *
     * @Route("/{id}", name="dia_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $dia = $em->getRepository('CineticDiaBundle:Dia')->find($id);

        if (!$dia) {
            throw $this->createNotFoundException('No se ha encontrado el dia.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($dia);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('dia_lista'));
        }

        return $this->render('dia/edit.html.twig',array(
            'dia' => $dia,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Dia entity.
     *
     * @param Dia $dia The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Dia $dia)
    {
        $form = $this->createForm(new DiaType(), $dia, array(
            'action' => $this->generateUrl('dia_update', array('id' => $dia->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }



    // A PARTIR D'AQUÍ ES EL CRUD QUE ET FA SYMFONY TAL QUAL NO M'INTERESSA ARA

    /**
     * Finds and displays a Dia entity.
     *
     * @Route("/{id}", name="config_dia_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CineticDiaBundle:Dia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Dia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $franjas = $entity->getFranjas();

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'franjas' => $franjas,
        );
    }

    /**
     * Deletes a Dia entity.
     *
     * @Route("/{id}", name="config_dia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CineticHorariosBundle:Dia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Dia entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('config_dia'));
    }

    /**
     * Creates a form to delete a Dia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('config_dia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
