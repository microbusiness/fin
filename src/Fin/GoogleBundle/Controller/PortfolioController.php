<?php

namespace Fin\GoogleBundle\Controller;

use Fin\GoogleBundle\Form\FilterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fin\GoogleBundle\Entity\Portfolio;
use Fin\GoogleBundle\Form\PortfolioType;
use DateTime;
use DateInterval;

/**
 * Portfolio controller.
 *
 * @Route("/portfolio")
 */
class PortfolioController extends Controller
{

    /**
     * Lists all Portfolio entities.
     *
     * @Route("/", name="portfolio")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FinGoogleBundle:Portfolio')->findBy(array('user'=>$this->getUser()));

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Portfolio entity.
     *
     * @Route("/", name="portfolio_create")
     * @Method("POST")
     * @Template("FinGoogleBundle:Portfolio:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Portfolio();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('portfolio_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Portfolio entity.
     *
     * @param Portfolio $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Portfolio $entity)
    {
        $form = $this->createForm(new PortfolioType(), $entity, array(
            'action' => $this->generateUrl('portfolio_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Portfolio entity.
     *
     * @Route("/new", name="portfolio_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Portfolio();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Portfolio entity.
     *
     * @Route("/{id}", name="portfolio_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinGoogleBundle:Portfolio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Portfolio entity.
     *
     * @Route("/{id}/edit", name="portfolio_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinGoogleBundle:Portfolio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Portfolio entity.
    *
    * @param Portfolio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Portfolio $entity)
    {
        $form = $this->createForm(new PortfolioType(), $entity, array(
            'action' => $this->generateUrl('portfolio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Сохранить'));

        return $form;
    }
    /**
     * Edits an existing Portfolio entity.
     *
     * @Route("/{id}", name="portfolio_update")
     * @Method("PUT")
     * @Template("FinGoogleBundle:Portfolio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinGoogleBundle:Portfolio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('portfolio_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Portfolio entity.
     *
     * @Route("/{id}", name="portfolio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FinGoogleBundle:Portfolio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Portfolio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('portfolio'));
    }

    /**
     * Creates a form to delete a Portfolio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('portfolio_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
 * Displays a statistic an existing Portfolio entity.
 *
 * @Route("/{id}/stat", name="portfolio_stat")
 * @Method("GET")
 * @Template()
 */
    public function statAction($id)
    {
        $googleStatService=$this->get('fin_google.service');

        $end=new DateTime();
        $begin=clone $end;
        $begin=$begin->sub(new DateInterval('P'.(string)((integer)$end->format('d')-1).'D'));

        $stat=$googleStatService->getPorfolioFromGoogle($id,$begin,$end);

        $form = $this->createForm(new FilterType(), array($begin,$end), array(
            'action' => $this->generateUrl('portfolio_find_stat', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Найти'));

        return array(
            'form'=>$form->createView(),
            'stat'      => $stat,
        );
    }

    /**
     * Displays a statistic an existing Portfolio entity with filter.
     *
     * @Route("/{id}/stat", name="portfolio_find_stat")
     * @Method("POST")
     * @Template()
     */
    public function findStatAction($id)
    {
        $googleStatService=$this->get('fin_google.service');

        $end=new DateTime();
        $begin=$end->sub(new DateInterval('P'.$end->format('d').'D'));

        $stat=$googleStatService->getPorfolioFromGoogle($id,$begin,$end);

        $form = $this->createForm(new FilterType(), array(), array(
            'action' => $this->generateUrl('portfolio_find_stat', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Найти'));

        return array(
            'form'=>$form,
            'stat'      => $stat,
        );
    }
}
