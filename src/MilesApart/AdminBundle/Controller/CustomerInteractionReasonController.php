<?php

namespace MilesApart\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\CustomerInteractionReason;
use MilesApart\AdminBundle\Form\CustomerInteractionReasonType;

/**
 * CustomerInteractionReason controller.
 *
 */
class CustomerInteractionReasonController extends Controller
{

    /**
     * Lists all CustomerInteractionReason entities.
     *
     */
    public function indexAction($page=null)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Interaction Reason", $this->get("router")->generate("customer_customer-interaction-reason"));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:CustomerInteractionReason')->findAll();

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



        return $this->render('MilesApartAdminBundle:CustomerInteractionReason:index.html.twig', array(
            'pager' => $pager,
        ));
    }
    /**
     * Creates a new CustomerInteractionReason entity.
     *
     */
    public function createAction(Request $request)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Interaction Reason", $this->get("router")->generate("customer_customer-interaction-reason"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("customer_customer-interaction-reason_new"));


        $entity = new CustomerInteractionReason();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Your form was submitted successfully. Thank you!');
          

            return $this->redirect($this->generateUrl('customer_customer-interaction-reason_show', array('id' => $entity->getId())));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:CustomerInteractionReason:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'submitted' => $submitted,
        ));
    }

    /**
    * Creates a form to create a CustomerInteractionReason entity.
    *
    * @param CustomerInteractionReason $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CustomerInteractionReason $entity)
    {
        $form = $this->createForm(new CustomerInteractionReasonType(), $entity, array(
            'action' => $this->generateUrl('customer_customer-interaction-reason_create'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Displays a form to create a new CustomerInteractionReason entity.
     *
     */
    public function newAction()
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Interaction Reason", $this->get("router")->generate("customer_customer-interaction-reason"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("customer_customer-interaction-reason_new"));

        $entity = new CustomerInteractionReason();
        $form   = $this->createCreateForm($entity);

        return $this->render('MilesApartAdminBundle:CustomerInteractionReason:new.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CustomerInteractionReason entity.
     *
     */
    public function showAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Interaction Reason", $this->get("router")->generate("customer_customer-interaction-reason"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("customer_customer-interaction-reason_show", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:CustomerInteractionReason')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CustomerInteractionReason entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:CustomerInteractionReason:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CustomerInteractionReason entity.
     *
     */
    public function editAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Interaction Reason", $this->get("router")->generate("customer_customer-interaction-reason"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("customer_customer-interaction-reason_edit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:CustomerInteractionReason')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CustomerInteractionReason entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:CustomerInteractionReason:edit.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CustomerInteractionReason entity.
    *
    * @param CustomerInteractionReason $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CustomerInteractionReason $entity)
    {
        $form = $this->createForm(new CustomerInteractionReasonType(), $entity, array(
            'action' => $this->generateUrl('customer_customer-interaction-reason_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing CustomerInteractionReason entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Interaction Reason", $this->get("router")->generate("customer_customer-interaction-reason"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("customer_customer-interaction-reason_edit", array('id' => $id)));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:CustomerInteractionReason')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CustomerInteractionReason entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The customer interaction reason was updated successfully.');

            return $this->redirect($this->generateUrl('customer_customer-interaction-reason_edit', array('id' => $id)));
        } else {

            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:CustomerInteractionReason:edit.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CustomerInteractionReason entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MilesApartAdminBundle:CustomerInteractionReason')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CustomerInteractionReason entity.');
            }

            $em->remove($entity);
            $em->flush();

            //Set the success flashbag
            $this->get('session')->getFlashBag()->set('admin-notice', 'The customer interaction reason was deleted successfully.');

        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }
        

        return $this->redirect($this->generateUrl('customer_customer-interaction-reason'));
    }

    /**
     * Creates a form to delete a CustomerInteractionReason entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer_customer-interaction-reason_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array(
                        'class' => 'btn-xs btn-danger col-md-2')))
            ->getForm()
        ;
    }
}
