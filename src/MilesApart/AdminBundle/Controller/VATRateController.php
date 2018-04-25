<?php

namespace MilesApart\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use MilesApart\AdminBundle\Entity\VATRate;
use MilesApart\AdminBundle\Form\VATRateType;

/**
 * VATRate controller.
 *
 */
class VATRateController extends Controller
{

    /**
     * Lists all VATRate entities.
     *
     */
    public function indexAction($page=null)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Finance", $this->get("router")->generate("miles_apart_admin_finance"));
        $breadcrumbs->addItem("VAT Rate", $this->get("router")->generate("finance_vat-rate"));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:VATRate')->findAll();

        //Set up pagerfanta
        $adapter = new ArrayAdapter($entities);
        
        //Pass adapter to pagerfanta
        $pager =  new Pagerfanta($adapter);
        //Set the number of results
        $pager->setMaxPerPage(10);

        //Set current page if not set
        if (!$page)    
            $page = 1;
            try  {
                $pager->setCurrentPage($page);
            }
            catch(NotValidCurrentPageException $e) {
              throw new NotFoundHttpException('Illegal page');
            }

        return $this->render('MilesApartAdminBundle:VATRate:index.html.twig', array(
            'pager' => $pager,
        ));
    }
    /**
     * Creates a new VATRate entity.
     *
     */
    public function createAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Finance", $this->get("router")->generate("miles_apart_admin_finance"));
        $breadcrumbs->addItem("VAT Rate", $this->get("router")->generate("finance_vat-rate"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("finance_vat-rate_new"));

        $entity = new VATRate();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New VAT rate has been created successfully.');

            return $this->redirect($this->generateUrl('finance_vat-rate_show', array('id' => $entity->getId())));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:VATRate:new.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a VATRate entity.
    *
    * @param VATRate $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(VATRate $entity)
    {
        $form = $this->createForm(new VATRateType(), $entity, array(
            'action' => $this->generateUrl('finance_vat-rate_create'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Displays a form to create a new VATRate entity.
     *
     */
    public function newAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Finance", $this->get("router")->generate("miles_apart_admin_finance"));
        $breadcrumbs->addItem("VAT Rate", $this->get("router")->generate("finance_vat-rate"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("finance_vat-rate_new"));

        $entity = new VATRate();
        $form   = $this->createCreateForm($entity);

        return $this->render('MilesApartAdminBundle:VATRate:new.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a VATRate entity.
     *
     */
    public function showAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Finance", $this->get("router")->generate("miles_apart_admin_finance"));
        $breadcrumbs->addItem("VAT Rate", $this->get("router")->generate("finance_vat-rate"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("finance_vat-rate_show", array('id' => $id)));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:VATRate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VATRate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:VATRate:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing VATRate entity.
     *
     */
    public function editAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Finance", $this->get("router")->generate("miles_apart_admin_finance"));
        $breadcrumbs->addItem("VAT Rate", $this->get("router")->generate("finance_vat-rate"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("finance_vat-rate_edit", array('id' => $id)));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:VATRate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VATRate entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:VATRate:edit.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a VATRate entity.
    *
    * @param VATRate $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VATRate $entity)
    {
        $form = $this->createForm(new VATRateType(), $entity, array(
            'action' => $this->generateUrl('finance_vat-rate_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing VATRate entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Finance", $this->get("router")->generate("miles_apart_admin_finance"));
        $breadcrumbs->addItem("VAT Rate", $this->get("router")->generate("finance_vat-rate"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("finance_vat-rate_edit", array('id' => $id)));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:VATRate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VATRate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The VAT rate was updated successfully.');

            return $this->redirect($this->generateUrl('finance_vat-rate_edit', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:VATRate:edit.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a VATRate entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MilesApartAdminBundle:VATRate')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VATRate entity.');
            }

            $em->remove($entity);
            $em->flush();
            
            //Set the success flashbag
            $this->get('session')->getFlashBag()->set('admin-notice', 'The VAT rate was deleted successfully.');
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->redirect($this->generateUrl('finance_vat-rate'));
    }

    /**
     * Creates a form to delete a VATRate entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('finance_vat-rate_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array(
                        'class' => 'btn-xs btn-danger col-md-2')))
            ->getForm()
        ;
    }
}
