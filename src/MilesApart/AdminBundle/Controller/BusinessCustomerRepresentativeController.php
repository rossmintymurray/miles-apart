<?php

namespace MilesApart\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative;
use MilesApart\AdminBundle\Form\BusinessCustomerRepresentativeType;

/**
 * BusinessCustomerRepresentative controller.
 *
 */
class BusinessCustomerRepresentativeController extends Controller
{

    /**
     * Lists all BusinessCustomerRepresentative entities.
     *
     */
    public function indexAction($page=null)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Business Customer Representative", $this->get("router")->generate("customer_business-customer-representative"));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:BusinessCustomerRepresentative')->findAll();

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



        return $this->render('MilesApartAdminBundle:BusinessCustomerRepresentative:index.html.twig', array(
            'pager' => $pager,
        ));
    }
    /**
     * Creates a new BusinessCustomerRepresentative entity.
     *
     */
    public function createAction(Request $request)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Business Customer Representative", $this->get("router")->generate("customer_business-customer-representative"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("customer_business-customer-representative_new"));


        $entity = new BusinessCustomerRepresentative();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Your form was submitted successfully. Thank you!');
          

            return $this->redirect($this->generateUrl('customer_business-customer-representative_show', array('id' => $entity->getId())));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:BusinessCustomerRepresentative:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'submitted' => $submitted,
        ));
    }

    /**
    * Creates a form to create a BusinessCustomerRepresentative entity.
    *
    * @param BusinessCustomerRepresentative $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(BusinessCustomerRepresentative $entity)
    {
        $form = $this->createForm(new BusinessCustomerRepresentativeType(), $entity, array(
            'action' => $this->generateUrl('customer_business-customer-representative_create'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Displays a form to create a new BusinessCustomerRepresentative entity.
     *
     */
    public function newAction()
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Business Customer Representative", $this->get("router")->generate("customer_business-customer-representative"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("customer_business-customer-representative_new"));

        $entity = new BusinessCustomerRepresentative();
        $form   = $this->createCreateForm($entity);

        return $this->render('MilesApartAdminBundle:BusinessCustomerRepresentative:new.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a BusinessCustomerRepresentative entity.
     *
     */
    public function showAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Business Customer Representative", $this->get("router")->generate("customer_business-customer-representative"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("customer_business-customer-representative_show", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:BusinessCustomerRepresentative')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BusinessCustomerRepresentative entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:BusinessCustomerRepresentative:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing BusinessCustomerRepresentative entity.
     *
     */
    public function editAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Business Customer Representative", $this->get("router")->generate("customer_business-customer-representative"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("customer_business-customer-representative_edit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:BusinessCustomerRepresentative')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BusinessCustomerRepresentative entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:BusinessCustomerRepresentative:edit.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a BusinessCustomerRepresentative entity.
    *
    * @param BusinessCustomerRepresentative $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(BusinessCustomerRepresentative $entity)
    {
        $form = $this->createForm(new BusinessCustomerRepresentativeType(), $entity, array(
            'action' => $this->generateUrl('customer_business-customer-representative_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing BusinessCustomerRepresentative entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Business Customer Representative", $this->get("router")->generate("customer_business-customer-representative"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("customer_business-customer-representative_edit", array('id' => $id)));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:BusinessCustomerRepresentative')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BusinessCustomerRepresentative entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The customer_business-customer-representative was updated successfully.');

            return $this->redirect($this->generateUrl('customer_business-customer-representative_edit', array('id' => $id)));
        } else {

            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:BusinessCustomerRepresentative:edit.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a BusinessCustomerRepresentative entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MilesApartAdminBundle:BusinessCustomerRepresentative')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BusinessCustomerRepresentative entity.');
            }

            $em->remove($entity);
            $em->flush();

            //Set the success flashbag
            $this->get('session')->getFlashBag()->set('admin-notice', 'The customer_business-customer-representative was deleted successfully.');

        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }
        

        return $this->redirect($this->generateUrl('customer_business-customer-representative'));
    }

    /**
     * Creates a form to delete a BusinessCustomerRepresentative entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer_business-customer-representative_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array(
                        'class' => 'btn-xs btn-danger col-md-2')))
            ->getForm()
        ;
    }
}
