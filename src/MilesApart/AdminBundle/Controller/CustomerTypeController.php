<?php

namespace MilesApart\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\CustomerType;
use MilesApart\AdminBundle\Form\CustomerTypeType;

/**
 * CustomerType controller.
 *
 */
class CustomerTypeController extends Controller
{

    /**
     * Lists all CustomerType entities.
     *
     */
    public function indexAction($page=null)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Type", $this->get("router")->generate("customer_customer-type"));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:CustomerType')->findAll();

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



        return $this->render('MilesApartAdminBundle:CustomerType:index.html.twig', array(
            'pager' => $pager,
        ));
    }
    /**
     * Creates a new CustomerType entity.
     *
     */
    public function createAction(Request $request)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Type", $this->get("router")->generate("customer_customer-type"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("customer_customer-type_new"));


        $entity = new CustomerType();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Your form was submitted successfully. Thank you!');
          

            return $this->redirect($this->generateUrl('customer_customer-type_show', array('id' => $entity->getId())));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:CustomerType:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'submitted' => $submitted,
        ));
    }

    /**
    * Creates a form to create a CustomerType entity.
    *
    * @param CustomerType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CustomerType $entity)
    {
        $form = $this->createForm(new CustomerTypeType(), $entity, array(
            'action' => $this->generateUrl('customer_customer-type_create'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Displays a form to create a new CustomerType entity.
     *
     */
    public function newAction()
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Type", $this->get("router")->generate("customer_customer-type"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("customer_customer-type_new"));

        $entity = new CustomerType();
        $form   = $this->createCreateForm($entity);

        return $this->render('MilesApartAdminBundle:CustomerType:new.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CustomerType entity.
     *
     */
    public function showAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Type", $this->get("router")->generate("customer_customer-type"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("customer_customer-type_show", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:CustomerType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CustomerType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:CustomerType:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CustomerType entity.
     *
     */
    public function editAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Type", $this->get("router")->generate("customer_customer-type"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("customer_customer-type_edit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:CustomerType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CustomerType entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:CustomerType:edit.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CustomerType entity.
    *
    * @param CustomerType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CustomerType $entity)
    {
        $form = $this->createForm(new CustomerTypeType(), $entity, array(
            'action' => $this->generateUrl('customer_customer-type_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing CustomerType entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));
        $breadcrumbs->addItem("Customer Type", $this->get("router")->generate("customer_customer-type"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("customer_customer-type_edit", array('id' => $id)));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:CustomerType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CustomerType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The customer type was updated successfully.');

            return $this->redirect($this->generateUrl('customer_customer-type_edit', array('id' => $id)));
        } else {

            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:CustomerType:edit.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CustomerType entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MilesApartAdminBundle:CustomerType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CustomerType entity.');
            }

            $em->remove($entity);
            $em->flush();

            //Set the success flashbag
            $this->get('session')->getFlashBag()->set('admin-notice', 'The customer type was deleted successfully.');

        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }
        

        return $this->redirect($this->generateUrl('customer_customer-type'));
    }

    /**
     * Creates a form to delete a CustomerType entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer_customer-type_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array(
                        'class' => 'btn-xs btn-danger col-md-2')))
            ->getForm()
        ;
    }
}
