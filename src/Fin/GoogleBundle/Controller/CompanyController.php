<?php

namespace Fin\GoogleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fin\GoogleBundle\Entity\Company;
use Fin\GoogleBundle\Form\CompanyType;

/**
 * Company controller.
 *
 * @Route("/company")
 */
class CompanyController extends Controller
{

    /**
     * Lists all Company entities.
     *
     * @Route("/{portfolioId}", name="company")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($portfolioId)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FinGoogleBundle:Company')->findBy(array('portfolio'=>$portfolioId));

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Company entity.
     *
     * @Route("/create/{portfolioId}", name="company_create")
     * @Method("POST")
     * @Template("FinGoogleBundle:Company:new.html.twig")
     */
    public function createAction(Request $request,$portfolioId)
    {
        $entity = new Company();
        $form = $this->createCreateForm($entity,$portfolioId);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setPortfolio($em->getRepository('FinGoogleBundle:Portfolio')->find($portfolioId));
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('portfolio_show', array('id' => $portfolioId)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Company entity.
     *
     * @param Company $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Company $entity,$portfolioId)
    {
        $form = $this->createForm(new CompanyType(), $entity, array(
            'action' => $this->generateUrl('company_create',array('portfolioId'=>$portfolioId)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Сохранить'));

        return $form;
    }

    /**
     * Displays a form to create a new Company entity.
     *
     * @Route("/new/{portfolioId}", name="company_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($portfolioId)
    {
        $entity = new Company();
        $form   = $this->createCreateForm($entity,$portfolioId);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'portfolio' =>$portfolioId,
        );
    }

    /**
     * Finds and displays a Company entity.
     *
     * @Route("/show/{id}", name="company_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinGoogleBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     * @Route("/{id}/edit", name="company_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinGoogleBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'portfolioId' => $entity->getPortfolio()->getId(),
        );
    }

    /**
    * Creates a form to edit a Company entity.
    *
    * @param Company $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Company $entity)
    {
        $form = $this->createForm(new CompanyType(), $entity, array(
            'action' => $this->generateUrl('company_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Сохранить'));

        return $form;
    }
    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}", name="company_update")
     * @Method("PUT")
     * @Template("FinGoogleBundle:Company:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinGoogleBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('company_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Company entity.
     *
     * @Route("/{id}", name="company_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FinGoogleBundle:Company')->find($id);
            $portfolioId=$entity->getPortfolio()->getId();
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Company entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('portfolio_show',array('id'=>$portfolioId)));
    }

    /**
     * Creates a form to delete a Company entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('company_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
