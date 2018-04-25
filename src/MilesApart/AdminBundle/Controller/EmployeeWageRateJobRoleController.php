<?php

namespace MilesApart\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\EmployeeWageRateJobRole;
use MilesApart\AdminBundle\Form\EmployeeWageRateJobRoleType;

/**
 * EmployeeWageRateJobRole controller.
 *
 */
class EmployeeWageRateJobRoleController extends Controller
{

    /**
     * Lists all EmployeeWageRateJobRole entities.
     *
     */
    public function indexAction($page=null)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate Job Role", $this->get("router")->generate("hr_employee-wage-rate-job-role"));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:EmployeeWageRateJobRole')->findAll();

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



        return $this->render('MilesApartAdminBundle:EmployeeWageRateJobRole:index.html.twig', array(
            'pager' => $pager,
        ));
    }
    /**
     * Creates a new EmployeeWageRateJobRole entity.
     *
     */
    public function createAction(Request $request)
    {

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate Job Role", $this->get("router")->generate("hr_employee-wage-rate-job-role"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("hr_employee-wage-rate-job-role_new"));


        $entity = new EmployeeWageRateJobRole();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Your form was submitted successfully. Thank you!');
          

            return $this->redirect($this->generateUrl('hr_employee-wage-rate-job-role_show', array('id' => $entity->getId())));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:EmployeeWageRateJobRole:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'submitted' => $submitted,
        ));
    }

    /**
    * Creates a form to create a EmployeeWageRateJobRole entity.
    *
    * @param EmployeeWageRateJobRole $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EmployeeWageRateJobRole $entity)
    {
        $form = $this->createForm(new EmployeeWageRateJobRoleType(), $entity, array(
            'action' => $this->generateUrl('hr_employee-wage-rate-job-role_create'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Displays a form to create a new EmployeeWageRateJobRole entity.
     *
     */
    public function newAction()
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate Job Role", $this->get("router")->generate("hr_employee-wage-rate-job-role"));
        $breadcrumbs->addItem("New", $this->get("router")->generate("hr_employee-wage-rate-job-role_new"));

        $entity = new EmployeeWageRateJobRole();
        $form   = $this->createCreateForm($entity);

        return $this->render('MilesApartAdminBundle:EmployeeWageRateJobRole:new.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EmployeeWageRateJobRole entity.
     *
     */
    public function showAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate Job Role", $this->get("router")->generate("hr_employee-wage-rate-job-role"));
        $breadcrumbs->addItem("Show", $this->get("router")->generate("hr_employee-wage-rate-job-role_show", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:EmployeeWageRateJobRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EmployeeWageRateJobRole entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:EmployeeWageRateJobRole:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EmployeeWageRateJobRole entity.
     *
     */
    public function editAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate Job Role", $this->get("router")->generate("hr_employee-wage-rate-job-role"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("hr_employee-wage-rate-job-role_edit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:EmployeeWageRateJobRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EmployeeWageRateJobRole entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MilesApartAdminBundle:EmployeeWageRateJobRole:edit.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EmployeeWageRateJobRole entity.
    *
    * @param EmployeeWageRateJobRole $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EmployeeWageRateJobRole $entity)
    {
        $form = $this->createForm(new EmployeeWageRateJobRoleType(), $entity, array(
            'action' => $this->generateUrl('hr_employee-wage-rate-job-role_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing EmployeeWageRateJobRole entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));
        $breadcrumbs->addItem("Employee Wage Rate Job Role", $this->get("router")->generate("hr_employee-wage-rate-job-role"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("hr_employee-wage-rate-job-role_edit", array('id' => $id)));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:EmployeeWageRateJobRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EmployeeWageRateJobRole entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The employee wage rate job role was updated successfully.');

            return $this->redirect($this->generateUrl('hr_employee-wage-rate-job-role_edit', array('id' => $id)));
        } else {

            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartAdminBundle:EmployeeWageRateJobRole:edit.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a EmployeeWageRateJobRole entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MilesApartAdminBundle:EmployeeWageRateJobRole')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EmployeeWageRateJobRole entity.');
            }

            $em->remove($entity);
            $em->flush();

            //Set the success flashbag
            $this->get('session')->getFlashBag()->set('admin-notice', 'The employee wage rate job role was deleted successfully.');

        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }
        

        return $this->redirect($this->generateUrl('hr_employee-wage-rate-job-role'));
    }

    /**
     * Creates a form to delete a EmployeeWageRateJobRole entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hr_employee-wage-rate-job-role_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array(
                        'class' => 'btn-xs btn-danger col-md-2')))
            ->getForm()
        ;
    }
}
