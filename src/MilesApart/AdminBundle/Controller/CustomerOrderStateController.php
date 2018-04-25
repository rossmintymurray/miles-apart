<?php

namespace MilesApart\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\CustomerOrderState;
use MilesApart\AdminBundle\Form\CustomerOrderStateType;

/**
 * CustomerOrderState controller.
 *
 */
class CustomerOrderStateController extends Controller
{

    /**
     * Lists all CustomerOrderState entities.
     *
     */
    public function indexAction($page=null)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer Order", $this->get("router")->generate("miles_apart_admin_customerorder"));
        $breadcrumbs->addItem("Customer Order State", $this->get("router")->generate("customer-order_customer-order-state"));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findAll();

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



        return $this->render('MilesApartAdminBundle:CustomerOrderState:index.html.twig', array(
            'pager' => $pager,
        ));
    }
    /**
     * Creates a new CustomerOrderState entity.
     *
     */
    public function createAction(Request $request)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer Order", $this->get("router")->generate("miles_apart_admin_customerorder"));
        $breadcrumbs->addItem("Customer Order State", $this->get("router")->generate("customer-order_customer-order-state"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("customer-order_customer-order-state_new"));


        $entity = new CustomerOrderState();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Your form was submitted successfully. Thank you!');
          

            return $this->redirect($this->generateUrl('customer-order_customer-order-state_show', array('id' => $entity->getId())));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:CustomerOrderState:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'submitted' => $submitted,
        ));
    }

    /**
    * Creates a form to create a CustomerOrderState entity.
    *
    * @param CustomerOrderState $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CustomerOrderState $entity)
    {
        $form = $this->createForm(new CustomerOrderStateType(), $entity, array(
            'action' => $this->generateUrl('customer-order_customer-order-state_create'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Displays a form to create a new CustomerOrderState entity.
     *
     */
    public function newAction()
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer Order", $this->get("router")->generate("miles_apart_admin_customerorder"));
        $breadcrumbs->addItem("Customer Order State", $this->get("router")->generate("customer-order_customer-order-state"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("customer-order_customer-order-state_new"));

        $entity = new CustomerOrderState();
        $form   = $this->createCreateForm($entity);

        return $this->render('MilesApartAdminBundle:CustomerOrderState:new.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CustomerOrderState entity.
     *
     */
    public function showAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer Order", $this->get("router")->generate("miles_apart_admin_customerorder"));
        $breadcrumbs->addItem("Customer Order State", $this->get("router")->generate("customer-order_customer-order-state"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("customer-order_customer-order-state_show", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CustomerOrderState entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:CustomerOrderState:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CustomerOrderState entity.
     *
     */
    public function editAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer Order", $this->get("router")->generate("miles_apart_admin_customerorder"));
        $breadcrumbs->addItem("Customer Order State", $this->get("router")->generate("customer-order_customer-order-state"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("customer-order_customer-order-state_edit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CustomerOrderState entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:CustomerOrderState:edit.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CustomerOrderState entity.
    *
    * @param CustomerOrderState $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CustomerOrderState $entity)
    {
        $form = $this->createForm(new CustomerOrderStateType(), $entity, array(
            'action' => $this->generateUrl('customer-order_customer-order-state_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing CustomerOrderState entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer Order", $this->get("router")->generate("miles_apart_admin_customerorder"));
        $breadcrumbs->addItem("Customer Order State", $this->get("router")->generate("customer-order_customer-order-state"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("customer-order_customer-order-state_edit", array('id' => $id)));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CustomerOrderState entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The customer-order_customer-order-state was updated successfully.');

            return $this->redirect($this->generateUrl('customer-order_customer-order-state_edit', array('id' => $id)));
        } else {

            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:CustomerOrderState:edit.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CustomerOrderState entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CustomerOrderState entity.');
            }

            $em->remove($entity);
            $em->flush();

            //Set the success flashbag
            $this->get('session')->getFlashBag()->set('admin-notice', 'The customer-order_customer-order-state was deleted successfully.');

        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }
        

        return $this->redirect($this->generateUrl('customer-order_customer-order-state'));
    }

    /**
     * Creates a form to delete a CustomerOrderState entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer-order_customer-order-state_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array(
                        'class' => 'btn-xs btn-danger col-md-2')))
            ->getForm()
        ;
    }
}
