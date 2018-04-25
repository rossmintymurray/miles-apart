<?php
// src/MilesApart/StaffBundle/Controller/HRController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\Employee;
use MilesApart\StaffBundle\Form\HR\EmployeeType;

use MilesApart\AdminBundle\Entity\AdminUser;
use MilesApart\AdminBundle\Form\AdminUserType;
class HRController extends Controller
{
    /*************************************************
    * HR controller displays the functions and pages in HR menu area.
    *************************************************/

    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("HR Notifications", $this->get("router")->generate("staff-hr_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:HR:notifications.html.twig');
   
    }

    public function viewemployeesAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Employees", $this->get("router")->generate("staff-hr_view-employees"));
        
        //Get the suppliers 
        $em = $this->getDoctrine()->getManager();

        $current_employees = $em->getRepository('MilesApartAdminBundle:Employee')->findBy(array("employee_leaving_date" => null));
        //Get the total number of suppliers

        $past_employees = $em->getRepository('MilesApartAdminBundle:Employee')->findPastEmployees();
       
        $employee_count = count($current_employees);

        $past_employee_count = count($past_employees);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:HR:view_employees.html.twig', array(
            'current_employees' => $current_employees,
            'employee_count' => $employee_count,
            'past_employees' => $past_employees,
            'past_employee_count' => $past_employee_count,

            ));
   
    }

    public function newemployeeAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Employee", $this->get("router")->generate("staff-hr_new-employee"));
        
        $entity = new Employee();

        $form = $this->createEmployeeForm($entity);

        return $this->render('MilesApartStaffBundle:HR:new_employee.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));

    }    

    /**
    * Creates a form to create a new employee.
    *
    * @param Employee $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEmployeeForm(Employee $entity)
    {
        $form = $this->createForm(new EmployeeType(), $entity, array(
            'action' => $this->generateUrl('staff-hr_new-employee-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function newemployeesubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Employee", $this->get("router")->generate("staff-hr_new-employee"));
        
        $entity = new Employee();
        $form = $this->createEmployeeForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            //Assign each submitted cost to this product
            foreach($form->get('employee_job_role_employee')->getData() as $role) {
                $role->setEmployee($entity);
                $entity->removeEmployeeJobRoleEmployee($role);
                $entity->addEmployeeJobRoleEmployee($role);
                
            }

            //Assign each submitted cost to this product
            foreach($form->get('employee_contracted_hours')->getData() as $hours) {
                $hours->setEmployee($entity);
                $entity->removeEmployeeContractedHours($hours);
                $entity->addEmployeeContractedHours($hours);
                
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New employee has been created successfully.');

            return $this->redirect($this->generateUrl('staff-hr_new-employee'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Employee:new_employee.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

     /**
     * Displays a form to create a new AdminUser entity.
     *
     */
    public function newadminuserAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("New Admin User", $this->get("router")->generate("staff-hr_new-admin-user"));

        $entity = new AdminUser();
        $form   = $this->createAdminUserForm($entity);

        return $this->render('MilesApartStaffBundle:HR:new_admin_user.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a AdminUser entity.
    *
    * @param AdminUser $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAdminUserForm(AdminUser $entity)
    {
        $form = $this->createForm(new AdminUserType(), $entity, array(
            'action' => $this->generateUrl('staff-hr_new-admin-user-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }


    /**
     * Creates a new AdminUser entity.
     *
     */
    public function newadminusersubmitAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("New Admin User", $this->get("router")->generate("staff-hr_new-admin-user"));

        $entity = new AdminUser();
        $form = $this->createAdminUserForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setPassword(password_hash($entity->getPassword(), PASSWORD_BCRYPT, array('cost' => 13)));
            

            //Persist to database
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New admin user has been created successfully.');
          
            return $this->redirect($this->generateUrl('staff-hr_new-admin-user'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:HR:new_admin_user.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    
    /**
     * Displays a form to edit an existing Employee entity.
     *
     */
    public function editEmployeeAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("View Employees", $this->get("router")->generate("staff-hr_view-employees"));
        $breadcrumbs->addItem("Edit Employee", $this->get("router")->generate("staff-hr_edit-employee", array ('id'=> $id )));
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Employee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Employee entity.');
        }

        $editForm = $this->createEditEmployeeForm($entity);
       

        return $this->render('MilesApartStaffBundle:HR:edit_employee.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Employee entity.
    *
    * @param Employee $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditEmployeeForm(Employee $entity)
    {
        $form = $this->createForm(new EmployeeType(), $entity, array(
            'action' => $this->generateUrl('staff-hr_edit-employee-submit', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing Supplier entity.
     *
     */
    public function editEmployeeSubmitAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("Supplier", $this->get("router")->generate("staff-hr_edit-employee-submit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Employee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Supplier entity.');
        }

        
        $editForm = $this->createEditEmployeeForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            //Assign each submitted cost to this product
            foreach($editForm->get('employee_job_role_employee')->getData() as $role) {
                $role->setEmployee($entity);
                $entity->removeEmployeeJobRoleEmployee($role);
                $entity->addEmployeeJobRoleEmployee($role);
                
            }

            //Assign each submitted cost to this product
            foreach($editForm->get('employee_contracted_hours')->getData() as $hours) {
                $hours->setEmployee($entity);
                $entity->removeEmployeeContractedHours($hours);
                $entity->addEmployeeContractedHours($hours);
                
            }
            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The employee was updated successfully.');

            return $this->redirect($this->generateUrl('staff-hr_view-employees', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:HR:edit_employee.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    public function viewemployeedetailsAction($id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("View Employees", $this->get("router")->generate("staff-hr_view-employees"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Employee Details", $this->get("router")->generate("staff-hr_view-employee-details", array('id'=>$id)));
        
        $em = $this->getDoctrine()->getManager();

        $employee = $em->getRepository('MilesApartAdminBundle:Employee')->findOneById($id);
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:HR:view_employee_details.html.twig', array(
            'employee' => $employee,
            ));
   
    }
   

    public function addworkweekAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Add Work Week", $this->get("router")->generate("staff-hr_add-work-week"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:HR:add_work_week.html.twig');
   
    }

    public function startpayrollAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Start Payroll", $this->get("router")->generate("staff-hr_start-payroll"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:HR:start_payroll.html.twig');
   
    }

    public function wagesettingsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Wage Settings", $this->get("router")->generate("staff-hr_wage-settings"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:HR:wage_settings.html.twig');
   
    }

    public function jobrolesettingsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Job Role Settings", $this->get("router")->generate("staff-hr_job-role-settings"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:HR:job_role_settings.html.twig');
   
    }

    public function printstatementofemploymentAction($id) 
    {

        $em = $this->getDoctrine()->getManager();

        $employee = $em->getRepository('MilesApartAdminBundle:Employee')->findOneBy(array('id' => $id));
        
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:HR:statement_of_employment.html.twig', array(
            'employee' => $employee,
            ));
   
    }

    public function holidayrequestformAction($id) 
    {

        $em = $this->getDoctrine()->getManager();

        $employee = $em->getRepository('MilesApartAdminBundle:Employee')->findOneBy(array('id' => $id));
        
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:HR:holiday_request_form.html.twig', array(
            'employee' => $employee,
            ));
   
    }

}
