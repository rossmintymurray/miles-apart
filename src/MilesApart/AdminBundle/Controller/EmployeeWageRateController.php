<?php

namespace MilesApart\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\EmployeeWageRate;
use MilesApart\AdminBundle\Form\EmployeeWageRateType;

/**
 * EmployeeWageRate controller.
 *
 */
class EmployeeWageRateController extends Controller
{

    /**
     * Lists all EmployeeWageRate entities.
     *
     */
    public function indexAction($page=null)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate", $this->get("router")->generate("hr_employee-wage-rate"));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:EmployeeWageRate')->findAll();

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



        return $this->render('MilesApartAdminBundle:EmployeeWageRate:index.html.twig', array(
            'pager' => $pager,
        ));
    }
    /**
     * Creates a new EmployeeWageRate entity.
     *
     */
    public function createAction(Request $request)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate", $this->get("router")->generate("hr_employee-wage-rate"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("hr_employee-wage-rate_new"));


        $entity = new EmployeeWageRate();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Your form was submitted successfully. Thank you!');
          

            return $this->redirect($this->generateUrl('hr_employee-wage-rate_show', array('id' => $entity->getId())));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:EmployeeWageRate:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'submitted' => $submitted,
        ));
    }

    /**
    * Creates a form to create a EmployeeWageRate entity.
    *
    * @param EmployeeWageRate $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EmployeeWageRate $entity)
    {
        $form = $this->createForm(new EmployeeWageRateType(), $entity, array(
            'action' => $this->generateUrl('hr_employee-wage-rate_create'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Displays a form to create a new EmployeeWageRate entity.
     *
     */
    public function newAction()
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate", $this->get("router")->generate("hr_employee-wage-rate"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("hr_employee-wage-rate_new"));

        $entity = new EmployeeWageRate();
        $form   = $this->createCreateForm($entity);

        return $this->render('MilesApartAdminBundle:EmployeeWageRate:new.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EmployeeWageRate entity.
     *
     */
    public function showAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate", $this->get("router")->generate("hr_employee-wage-rate"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("hr_employee-wage-rate_show", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:EmployeeWageRate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EmployeeWageRate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:EmployeeWageRate:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EmployeeWageRate entity.
     *
     */
    public function editAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate", $this->get("router")->generate("hr_employee-wage-rate"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("hr_employee-wage-rate_edit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:EmployeeWageRate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EmployeeWageRate entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:EmployeeWageRate:edit.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EmployeeWageRate entity.
    *
    * @param EmployeeWageRate $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EmployeeWageRate $entity)
    {
        $form = $this->createForm(new EmployeeWageRateType(), $entity, array(
            'action' => $this->generateUrl('hr_employee-wage-rate_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing EmployeeWageRate entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate", $this->get("router")->generate("hr_employee-wage-rate"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("hr_employee-wage-rate_edit", array('id' => $id)));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:EmployeeWageRate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EmployeeWageRate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The employee wage rate was updated successfully.');

            return $this->redirect($this->generateUrl('hr_employee-wage-rate_edit', array('id' => $id)));
        } else {

            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:EmployeeWageRate:edit.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a EmployeeWageRate entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MilesApartAdminBundle:EmployeeWageRate')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EmployeeWageRate entity.');
            }

            $em->remove($entity);
            $em->flush();

            //Set the success flashbag
            $this->get('session')->getFlashBag()->set('admin-notice', 'The employee wage rate was deleted successfully.');

        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }
        

        return $this->redirect($this->generateUrl('hr_employee-wage-rate'));
    }

    /**
     * Creates a form to delete a EmployeeWageRate entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hr_employee-wage-rate_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array(
                        'class' => 'btn-xs btn-danger col-md-2')))
            ->getForm()
        ;
    }
}
