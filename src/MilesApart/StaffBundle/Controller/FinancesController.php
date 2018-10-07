<?php
// src/MilesApart/StaffBundle/Controller/FinancesController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Session\Session;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Zend\Json\Expr;
use MilesApart\AdminBundle\Entity\BusinessPremises;
use MilesApart\AdminBundle\Entity\DailyTake;
use MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises;
use MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesPettyCash;
use MilesApart\AdminBundle\Entity\ShopDepartment;
use MilesApart\AdminBundle\Entity\ExpensesType;
use MilesApart\AdminBundle\Entity\Employee;
use MilesApart\StaffBundle\Form\Finances\DailyTakeType;
use MilesApart\StaffBundle\Form\Finances\NextDailyTakeType;
use MilesApart\StaffBundle\Form\Finances\DailyTakeBusinessPremisesType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;

class FinancesController extends Controller
{
    /*************************************************
    * Finances controller displays the functions and pages in finances menu area.
    *************************************************/

    public function notificationsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Finance Notifications", $this->get("router")->generate("staff-finances_notifications"));
        
        //Load notification modules.

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Finances:notifications.html.twig');
   
    }

    //aily take inital date select.
    public function processdailytakeAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Daily Take Date Select", $this->get("router")->generate("staff-finances_process-daily-take"));
        
        //Create the form and load.
        $entity = new DailyTake();
        $entity->setDailyTakeDate(new \DateTime());
        $form = $this->createDailyTakeForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Finances:process_daily_take.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
   
    }

    /**
    * Creates a form to create the initial daily take process.
    *
    * @param DailyTake $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createDailyTakeForm(DailyTake $entity)
    {
        $form = $this->createForm(new DailyTakeType(), $entity, array(
            'action' => $this->generateUrl('staff-finances_process-daily-take-date-select'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal'),
        ));

        $form->add('submit', 'submit', array('label' => 'Next', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4')));

        return $form;
    }

    /**
    * Creates a form to create the initial daily take process.
    *
    * @param DailyTake $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createNextDailyTakeForm(DailyTake $entity)
    {
        $form = $this->createForm(new NextDailyTakeType(), $entity, array(
            'action' => $this->generateUrl('staff-finances_process-daily-take-date-select'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal'),
        ));

        $form->add('submit', 'submit', array('label' => '>', 'attr' => array(
                        'class' => 'btn btn-primary col-sm-12')));

        return $form;
    }

    /**
    * Creates a form to create the initial daily take process.
    *
    * @param DailyTake $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPreviousDailyTakeForm(DailyTake $entity)
    {
        $form = $this->createForm(new NextDailyTakeType(), $entity, array(
            'action' => $this->generateUrl('staff-finances_process-daily-take-date-select'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal'),
        ));

        $form->add('submit', 'submit', array('label' => '<', 'attr' => array(
                        'class' => 'btn btn-primary col-sm-12')));

        return $form;
    }

    //Handle process daily take action for selecting date of daily take and loading business premises form.
    public function processdailytakedateselectAction(Request $request) 
    {   
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Daily Take Date Select", $this->get("router")->generate("staff-finances_process-daily-take"));
        $breadcrumbs->addItem("Process Daily Take Business Premises", $this->get("router")->generate("staff-finances_process-daily-take-business-premises"));

        //Start the session for date access.
        $session = new Session();
        
        //Get the date from the form and check if a daily take already exists for that day.
        //Set the entity
        $entity = new DailyTake();
        $form   = $this->createDailyTakeForm($entity);
        //Handle the submitted form
        $form->handleRequest($request);


        //If the form is valid
        if ($form->isValid()) {

            //Get the date from the form
            $daily_take_date = $form["daily_take_date"]->getData();

            //Format the date for database.
            $dailyTakeDate = $daily_take_date->format('Y-m-d');

            //Get the entity manager.
            $em = $this->getDoctrine()->getManager();

            //Set the existing daily take.
            $existing_daily_take = new DailyTake();
            //Retrieve daily take that matches date selected.
            $existing_daily_take = $em->getRepository('MilesApartAdminBundle:DailyTake')->findDailyTakeByDate($dailyTakeDate);
            
            //If an existing daily take has not been matched.
            if (!$existing_daily_take)
            {
                
                //Persist the new date to the database.
                $em->persist($entity);
                $em->flush();

                //Define the daily take date as the id of the newly persisted date 
                $daily_take_date = $entity->getId();

                //Set existsing daily take as entity just added.
                $existing_daily_take = $entity;
            } else {

                //Call repository to check for existsing daily take
                $daily_take_date = $existing_daily_take[0]['id'];
            }

            //Set session date for use in daily take business premises action.
            $session->set('daily_take_date_id', $daily_take_date);
            $session->set('daily_take_date', $dailyTakeDate);
            
            //Create the daily take business premises form and load.
            $entity = new DailyTakeBusinessPremises();
            $form   = $this->createDailyTakeBusinessPremisesForm($entity, $daily_take_date, false);

            //Find out if any daily take business premises have been added (used to add existing to top of table)
            $existing_daily_take_business_premises = $em->getRepository('MilesApartAdminBundle:DailyTakeBusinessPremises')->findExistingDailyTakeBusinessPremisesByDate($daily_take_date);


            //Set up the next daily take form
            //Set the entity
            $next_entity = new DailyTake();
            $next_date = new \DateTime($dailyTakeDate);

            $next_date->modify('+1 day');
            $next_entity->setDailyTakeDate($next_date);
            $next_form   = $this->createNextDailyTakeForm($next_entity);

            //Set up the previous daily take form
            //Set the entity
            $previous_entity = new DailyTake();
            $previous_date = new \DateTime($dailyTakeDate);

            $previous_date->modify('-1 day');
            $previous_entity->setDailyTakeDate($previous_date);
            $previous_form   = $this->createPreviousDailyTakeForm($previous_entity);

            //Render the view from template passing required data.
            return $this->render('MilesApartStaffBundle:Finances:process_daily_take_business_premises.html.twig', array(
                'submitted' => false,
                'entity' => $entity,
                'form'   => $form->createView(),
                'daily_take_date' => $dailyTakeDate,
                'id' => $daily_take_date,
                'existing_daily_take_business_premises' => $existing_daily_take_business_premises,
                'daily_take' => $existing_daily_take,
                'next_form'   => $next_form->createView(),
                'previous_form'   => $previous_form->createView(),


            ));
        
        } else {
        
            //Render the page from template
            return $this->render('MilesApartStaffBundle:Finances:process_daily_take.html.twig', array(
                'submitted' => true,
                'entity' => $entity,
                'form'   => $form->createView(),
                
            ));
        }
    }

    public function processdailytakebusinesspremisesAction(Request $request) 
    {   
        //Start session and get the daily take id.
        $session = new Session();
        $id = $session->get('daily_take_date_id');

        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the daily take that this daily take business premises is related to
        $daily_take = new DailyTake();
        $daily_take = $em->getRepository('MilesApartAdminBundle:DailyTake')->findOneBy(array('id' => $id));
        //Set the entity
        $entity = new DailyTakeBusinessPremises();
        
        //Create the form
        $form = $this->createDailyTakeBusinessPremisesForm($entity, $id, false);
        
        //Handle the submitted form
        $form->handleRequest($request);
        
        

        //If the form is valid
        if ($form->isValid()) {

            //Assign each submitted petty cash to this daily take premises
            foreach($form->get('daily_take_business_premises_petty_cash')->getData() as $petty) {
                $petty->setDailyTakeBusinessPremises($entity);
                $entity->removeDailyTakeBusinessPremisesPettyCash($petty);
                $entity->addDailyTakeBusinessPremisesPettyCash($petty);
                
            }

            //Assign each submitted shop departments to this daily take premises
            foreach($form->get('daily_take_business_premises_shop_department')->getData() as $dept) {
                $dept->setDailyTakeBusinessPremises($entity);
                $entity->removeDailyTakeBusinessPremisesShopDepartment($dept);
                $entity->addDailyTakeBusinessPremisesShopDepartment($dept);
            }

            //Assign each submitted employee payment to this daily take premises
            foreach($form->get('employee_payment')->getData() as $pay) {
                
                 //$week_end_date->setDate('2014', '02', $week_end_date);
                //Set the week end date
                //$pay->setEmployeePaymentWeekEndDate($week_end_date);
                
                $pay->setDailyTakeBusinessPremises($entity);
                $entity->removeEmployooPayment($pay);
                $entity->addEmployooPayment($pay);
            }
            
            //Assign each submitted employee statutory payment to this daily take premises
            foreach($form->get('employee_statutory_payment')->getData() as $stat_pay) {
                
                 //$week_end_date->setDate('2014', '02', $week_end_date);
                //Set the week end date
                //$pay->setEmployeePaymentWeekEndDate($week_end_date);
                
                $stat_pay->setDailyTakeBusinessPremises($entity);
                //$entity->removeEmployooStatutoryPayment($stat_pay);
                $entity->addEmployooStatutoryPayment($stat_pay);
            }

            //Set the daily take of this business premises 
            $entity->setDailyTake($daily_take);
           
            //Persist to daily take business premises to the database
            $em->persist($entity);
            
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New daily take business premises daily take has been created successfully.');
            
            //Count the number of daily take business premises for this date 

            //Create the form and load.
            $entity = new DailyTakeBusinessPremises();
            $form   = $this->createDailyTakeBusinessPremisesForm($entity, $id, false);

            //Find out if any daily take business premises have been added
            $existing_daily_take_business_premises = $em->getRepository('MilesApartAdminBundle:DailyTakeBusinessPremises')->findExistingDailyTakeBusinessPremisesByDate($id);

             //Set up the next daily take form
            //Set the entity
            $next_entity = new DailyTake();
            $next_date = $daily_take->getDailyTakeDate();

            $next_date->modify('+1 day');
            $next_entity->setDailyTakeDate($next_date);
            $next_form   = $this->createNextDailyTakeForm($next_entity);

             //Set up the previous daily take form
            //Set the entity
            $previous_entity = new DailyTake();
            $previous_date =  $daily_take->getDailyTakeDate();

            $previous_date->modify('-1 day');
            $previous_entity->setDailyTakeDate($previous_date);
            $previous_form   = $this->createPreviousDailyTakeForm($previous_entity);

            //Render the page from template
            return $this->render('MilesApartStaffBundle:Finances:process_daily_take_business_premises.html.twig', array(
                'submitted' => false,
                'entity' => $entity,
                'form'   => $form->createView(),
                'daily_take_date' => $session->get('daily_take_date'),
                'existing_daily_take_business_premises' => $existing_daily_take_business_premises,
                'daily_take' => $daily_take,
                'next_form'   => $next_form->createView(),
                'previous_form'   => $previous_form->createView(),
            ));
           
        //Form is not valid 
        } else {
            $id = "Not Valid";
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
           
           //Find out if any daily take business premises have been added
            $existing_daily_take_business_premises = $em->getRepository('MilesApartAdminBundle:DailyTakeBusinessPremises')->findExistingDailyTakeBusinessPremisesByDate($id);

            //Set up the next daily take form
            //Set the entity
            $next_entity = new DailyTake();
            $next_date = $daily_take->getDailyTakeDate();

            $next_date->modify('+1 day');
            $next_entity->setDailyTakeDate($next_date);
            $next_form   = $this->createNextDailyTakeForm($next_entity);

            //Set up the previous daily take form
            //Set the entity
            $previous_entity = new DailyTake();
            $previous_date =  $daily_take->getDailyTakeDate();

            $previous_date->modify('-1 day');
            $previous_entity->setDailyTakeDate($previous_date);
            $previous_form   = $this->createPreviousDailyTakeForm($previous_entity);

           return $this->render('MilesApartStaffBundle:Finances:process_daily_take_business_premises.html.twig', array(
                'submitted' => $submitted,
                'entity' => $entity,
                'form'   => $form->createView(),
                'daily_take_date' => $session->get('daily_take_date'),
                'existing_daily_take_business_premises' => $existing_daily_take_business_premises,
                'next_form'   => $next_form->createView(),
                'previous_form'   => $previous_form->createView(),

            ));
        }
        
        
    }

    /**
     * Displays a form to edit an existing AdminUserType entity.
     *
     */
    public function editprocessdailytakebusinesspremisesAction($id)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Daily Take Date Select", $this->get("router")->generate("staff-finances_process-daily-take"));
        $breadcrumbs->addItem("Process Daily Take Business Premises", $this->get("router")->generate("staff-finances_process-daily-take-business-premises"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("staff-finances_view-daily-takes-business-premises_update", array ('id'=> $id )));

        //Start session and get the daily take id.
        $session = new Session();
        $date_id = $session->get('daily_take_date_id'); 

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:DailyTakeBusinessPremises')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Daily Take Business Premises entity.');
        }

        $editForm = $this->createEditProcessDailyTakeBusinessPremisesForm($entity, $date_id, true);
        

        return $this->render('MilesApartStaffBundle:Finances:edit_process_daily_take_business_premises.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'test' => "null",
            'edit_form'   => $editForm->createView(),
            'daily_take_date' => $session->get('daily_take_date'),
            
        ));
    }

    /**
     * Edits an existing daily take business premises entity.
     *
     */
    public function updateprocessdailytakebusinesspremisesAction(Request $request, $id)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Daily Take Date Select", $this->get("router")->generate("staff-finances_process-daily-take"));
        $breadcrumbs->addItem("Process Daily Take Business Premises", $this->get("router")->generate("staff-finances_process-daily-take-business-premises"));
        $breadcrumbs->addItem("Edit", $this->get("router")->generate("staff-finances_view-daily-takes-business-premises_update", array ('id'=> $id )));

        //Start session and get the daily take id.
        $session = new Session();
        $date_id = $session->get('daily_take_date_id'); 

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:DailyTakeBusinessPremises')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find daily take business premises entity.');
        }

        //Put the existing relations into arraycollection so they can be checked.
        //Petty Cash
        $originalPettyCash = new ArrayCollection();

        // Create an ArrayCollection of the current petty cash objects in the database
        foreach ($entity->getDailyTakeBusinessPremisesPettyCash() as $dtbppc) {
            $originalPettyCash->add($dtbppc);
        }

        //Department
        $originalShopDepartment = new ArrayCollection();

        // Create an ArrayCollection of the current shop department objects in the database
        foreach ($entity->getDailyTakeBusinessPremisesShopDepartment() as $dtbpsd) {
            $originalShopDepartment->add($dtbpsd);
        }

        //Employee Payment
        $originalEmployeePayment = new ArrayCollection();

        // Create an ArrayCollection of the current employee payment objects in the database
        foreach ($entity->getEmployeePayment() as $dtbpep) {
            $originalEmployeePayment->add($dtbpep);
        }

        //Employee Statutory Payment
        $originalEmployeeStatutoryPayment = new ArrayCollection();

        // Create an ArrayCollection of the current eployee statutory payment objects in the database
        foreach ($entity->getEmployeeStatutoryPayment() as $dtbpesp) {
            $originalEmployeeStatutoryPayment->add($dtbpesp);
        }

        $editForm = $this->createEditProcessDailyTakeBusinessPremisesForm($entity, $date_id, true);
        $editForm->handleRequest($request);
    
        if ($editForm->isValid()) {
            
            // remove the relationship between the tag and the Task
            foreach ($originalPettyCash as $pc) {
                if (false === $entity->getDailyTakeBusinessPremisesPettyCash()->contains($pc)) {
                    // remove the Task from the Tag
                    $pc->getDailyTakeBusinessPremises()->removeDailyTakeBusinessPremisesPettyCash($pc);

                    // if it was a many-to-one relationship, remove the relationship like this
                    //$pc->setDailyTakeBusinessPremises(null);

                    //$em->persist($pc);

                    // if you wanted to delete the Tag entirely, you can also do that
                    $em->remove($pc);
                }
            }

            // remove the relationship between the tag and the Task
            foreach ($originalShopDepartment as $sd) {
                if (false === $entity->getDailyTakeBusinessPremisesShopDepartment()->contains($sd)) {
                    // remove the Task from the Tag
                    $sd->getDailyTakeBusinessPremises()->removeDailyTakeBusinessPremisesShopDepartment($sd);

                    // if it was a many-to-one relationship, remove the relationship like this
                    //$pc->setDailyTakeBusinessPremises(null);

                    //$em->persist($pc);

                    // if you wanted to delete the Tag entirely, you can also do that
                    $em->remove($sd);
                }
            }

            // remove the relationship between the tag and the Task
            foreach ($originalEmployeePayment as $ep) {
                if (false === $entity->getEmployeePayment()->contains($ep)) {
                    // remove the Task from the Tag
                    $ep->getDailyTakeBusinessPremises()->removeEmployeePayment($ep);

                    // if it was a many-to-one relationship, remove the relationship like this
                    //$pc->setDailyTakeBusinessPremises(null);

                    //$em->persist($pc);

                    // if you wanted to delete the Tag entirely, you can also do that
                    $em->remove($ep);
                }
            }

            // remove the relationship between the tag and the Task
            foreach ($originalEmployeeStatutoryPayment as $esp) {
                if (false === $entity->getEmployeeStatutoryPayment()->contains($esp)) {
                    // remove the Task from the Tag
                    $esp->getDailyTakeBusinessPremises()->removeEmployeeStatutoryPayment($esp);

                    // if it was a many-to-one relationship, remove the relationship like this
                    //$pc->setDailyTakeBusinessPremises(null);

                    //$em->persist($pc);

                    // if you wanted to delete the Tag entirely, you can also do that
                    $em->remove($esp);
                }
            }

            //Assign each submitted petty cash to this daily take premises
            foreach($editForm->get('daily_take_business_premises_petty_cash')->getData() as $petty) {

                

                $petty->setDailyTakeBusinessPremises($entity);
                $entity->removeDailyTakeBusinessPremisesPettyCash($petty);
                $entity->addDailyTakeBusinessPremisesPettyCash($petty);
                
            }

            //Assign each submitted shop departments to this daily take premises
            foreach($editForm->get('daily_take_business_premises_shop_department')->getData() as $dept) {
                $dept->setDailyTakeBusinessPremises($entity);
                $entity->removeDailyTakeBusinessPremisesShopDepartment($dept);
                $entity->addDailyTakeBusinessPremisesShopDepartment($dept);
            }

            //Assign each submitted employee payment to this daily take premises
            foreach($editForm->get('employee_payment')->getData() as $pay) {
                $pay->setDailyTakeBusinessPremises($entity);
                $entity->removeEmployooPayment($pay);
                $entity->addEmployooPayment($pay);
            }

            //Assign each submitted employee statutory payment to this daily take premises
            foreach($editForm->get('employee_statutory_payment')->getData() as $stat_pay) {
                
                $stat_pay->setDailyTakeBusinessPremises($entity);
                $entity->removeEmployooStatutoryPayment($stat_pay);
                $entity->addEmployooStatutoryPayment($stat_pay);
            }            
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The daily take business premises was updated successfully.');

            return $this->redirect($this->generateUrl('staff-finances_process-daily-take'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
         
        }
        return $this->render('MilesApartStaffBundle:Finances:edit_process_daily_take_business_premises.html.twig', array(
            'submitted' => true,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'daily_take_date' => $session->get('daily_take_date'),
            
        ));
    }

    /**
    * Creates a form to edit a AdminUserType entity.
    *
    * @param DailyTakeBusinessPremisesType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditProcessDailyTakeBusinessPremisesForm(DailyTakeBusinessPremises $entity, $daily_take_date, $edit)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new DailyTakeBusinessPremisesType($daily_take_date, $em, $edit), $entity, array(
            'action' => $this->generateUrl('staff-finances_view-daily-takes-business-premises_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    

    /**
    * Creates a form to create a DailyTake entity.
    *
    * @param DailyTake $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createDailyTakeBusinessPremisesForm(DailyTakeBusinessPremises $entity, $daily_take_date, $edit)
    {
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Define the form
        $form = $this->createForm(new DailyTakeBusinessPremisesType($daily_take_date, $em, $edit), $entity, array(
            'action' => $this->generateUrl('staff-finances_process-daily-take-business-premises'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal'),
            
        ));

        //Create submit button
        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function viewDailyTakesByDateAction(Request $request)
    {
        //Get the dates from the post request.
         //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }
        
        //Set the new price and the product id
        $start_date = $response["start_date"];
        $end_date = $response["end_date"];
        $compare_start_date = $response["compare_start_date"];
        $compare_end_date = $response["compare_end_date"];

        //Start the session
        $session = new Session();
        $session->set('start_date', $start_date);
        $session->set('end_date', $end_date);
        $session->set('compare_start_date', $compare_start_date);
        $session->set('compare_end_date', $compare_end_date);

        
        $response = array("success" => true, "start" => $session->get('start_date'), "end" => $session->get('end_date'));
        return new JsonResponse(
            $response 
        );
    }


    public function viewdailytakesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Daily Takes", $this->get("router")->generate("staff-finances_view-daily-takes"));
        
        //Start the session
        $session = new Session();


        //Check if period start date and end date have been set.
        if ($session->has('start_date') && $session->has('end_date')) {

            //$session->remove('start_date');
            //$session->remove('end_date');

            //Use the session dates for search query.
            $default_start_date = $session->get('start_date');
            $default_end_date = $session->get('end_date');

            //Default start date to be 30 prior to todays date
           //$default_start_date = Date('y-m-d', strtotime("-30 days"));

            //$default_end_date = date('y-m-d');

            //$default_start_date = Date('2017-09-01');

            //$default_end_date = date('2017-09-30');

        //Get current month and use these dates.
       } else {
            //Get the chart data form daily take.
            //Set the default date range to be the current month.
            

            //Default start date to be 30 prior to todays date
            $default_start_date = Date('y-m-d', strtotime("-30 days"));
           
            $default_end_date = date('y-m-d');

            //$default_start_date = Date('2014-01-01', strtotime("-30 days"));
           
            //$default_end_date = date('2015-07-01');

            //Set the start and end date into the session
            $session->set('start_date', $default_start_date);
            $session->set('end_date', $default_end_date);
        }

        //Create the timestamp used by highcarts on x axis.
        $default_start_date_obj = strtotime($default_start_date);
        $default_end_date_obj = strtotime(date($default_end_date));

        /************************************************************
        * Calculate the totals for previous month and year.
        ************************************************************/
        //Month 
        $previous_month_start_date = Date('y-m-d', strtotime("-1 month" . $default_start_date));
        $previous_month_end_date = Date('y-m-d', strtotime("-1 month" . $default_end_date));

        //Get the data
        $em = $this->getDoctrine()->getManager();
        $previous_month_daily_take = $em->getRepository('MilesApartAdminBundle:DailyTakeBusinessPremises')->getDailyTakeBusinessPremisesByStartAndEndDate($previous_month_start_date, $previous_month_end_date);
        
        //MA aorders
        $previous_month_ma_customer_orders = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->getMACustomerOrderByStartAndEndDate($previous_month_start_date, $previous_month_end_date);
        //Amazon orders
        $previous_month_amazon_customer_orders = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->getAmazonCustomerOrderByStartAndEndDate($previous_month_start_date, $previous_month_end_date);
     
        //Create the arrays to hold totals.
        $previous_month_twenty_six_amesbury_take = [];
        $previous_month_twenty_eight_amesbury_take =[];
        $previous_month_westbury_take =[];

        //Create the arrays to hold transactions
        $previous_month_twenty_six_amesbury_transactions = [];
        $previous_month_twenty_eight_amesbury_transactions =[];
        $previous_month_westbury_transactions =[];

        //Create the arrays to hold departments
        $previous_month_twenty_six_amesbury_departments = [];
        $previous_month_twenty_eight_amesbury_departments =[];
        $previous_month_westbury_departments =[];

        //Create the arrays to hold receipts
        $previous_month_twenty_six_amesbury_receipts = [];
        $previous_month_twenty_eight_amesbury_receipts =[];
        $previous_month_westbury_receipts =[];

        //Create the arrays to hold payments.
        $previous_month_twenty_six_amesbury_payments = [];
        $previous_month_twenty_eight_amesbury_payments =[];
        $previous_month_westbury_payments =[];
$test = "";
        //Iterate over the daily take business premises that have been retrieved from the database.
        //For each, check which premises it is for and add details to respective 
        foreach($previous_month_daily_take as $key => $value) {
            
            //If business premises is 26 amesbury
            if($value->getBusinessPremises()->getBusinessPremisesName() == '26 Amesbury') {
                
                //Set the take array as the id = date and value = z reading
                $previous_month_twenty_six_amesbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $previous_month_twenty_six_amesbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();

                //Set the departments
                foreach($value->getDailyTakeBusinessPremisesShopDepartment() as $key => $department) {
                    
                    //Create array with department name and department value
                    $previous_month_twenty_six_amesbury_departments_array = array("department_name" => $department->getShopDepartment()->getShopDepartmentName(), "department_value" => $department->getShopDepartmentValue());
                    $previous_month_twenty_six_amesbury_departments[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_month_twenty_six_amesbury_departments_array;
                    $test .= "SD " .$department->getShopDepartment()->getShopDepartmentName() .$department->getShopDepartmentValue(); 

                }

                //Set the receipts
                foreach($value->getDailyTakeBusinessPremisesPettyCash() as $key => $petty_cash) {
                    
                    //Create array with petty cash type and petty cash value
                    $previous_month_twenty_six_amesbury_petty_cash_array = array("petty_cash_type" => $petty_cash->getExpensesType()->getExpensesTypeName(), "petty_cash_value" => $petty_cash->getPettyCashValue());
                    $previous_month_twenty_six_amesbury_petty_cash[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_month_twenty_six_amesbury_petty_cash_array;
                    $test .= "PC ".$petty_cash->getPettyCashValue(); 
                    
                }

                //Set the payments
                foreach($value->getEmployeePayment() as $key => $employee_payment) {
                    
                    //Create array with employee payment employee and payment value
                    $previous_month_twenty_six_amesbury_employee_payment_array = array("employee_payment_employee" => $employee_payment->getEmployee()->getEmployeeFullName(), "employee_payment_total" => $employee_payment->getEmployeePaymentTotal());
                    $previous_month_twenty_six_amesbury_employee_payment[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_month_twenty_six_amesbury_employee_payment_array;
                    $test .= "EP ".$employee_payment->getEmployeePaymentTotal(); 
                    
                }

            //If business premises is 28 amesbury
            } elseif($value->getBusinessPremises()->getBusinessPremisesName() == '28 Amesbury') {
                
                //Set the take array as the id = date and value = z reading
                $previous_month_twenty_eight_amesbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $previous_month_twenty_eight_amesbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();

                 //Set the departments
                foreach($value->getDailyTakeBusinessPremisesShopDepartment() as $key => $department) {
                    
                    //Create array with department name and department value
                    $previous_month_twenty_eight_amesbury_departments_array = array("department_name" => $department->getShopDepartment()->getShopDepartmentName(), "department_value" => $department->getShopDepartmentValue());
                    $previous_month_twenty_eight_amesbury_departments[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_month_twenty_eight_amesbury_departments_array;
                    $test .= " *SD " .$department->getShopDepartment()->getShopDepartmentName() .$department->getShopDepartmentValue() . "* "; 
                    
                }

                //Set the receipts
                foreach($value->getDailyTakeBusinessPremisesPettyCash() as $key => $petty_cash) {
                    
                    //Create array with petty cash type and petty cash value
                    $previous_month_twenty_eight_amesbury_petty_cash_array = array("petty_cash_type" => $petty_cash->getExpensesType()->getExpensesTypeName(), "petty_cash_value" => $petty_cash->getPettyCashValue());
                    $previous_month_twenty_eight_amesbury_petty_cash[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_month_twenty_eight_amesbury_petty_cash_array;
                    $test .= " *PC ".$petty_cash->getPettyCashValue(). "* "; 
                    
                }

                //Set the payments
                foreach($value->getEmployeePayment() as $key => $employee_payment) {
                    
                    //Create array with employee payment employee and payment value
                    $previous_month_twenty_eight_amesbury_employee_payment_array = array("employee_payment_employee" => $employee_payment->getEmployee()->getEmployeeFullName(), "employee_payment_total" => $employee_payment->getEmployeePaymentTotal());
                    $previous_month_twenty_eight_amesbury_employee_payment[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_month_twenty_eight_amesbury_employee_payment_array;
                    $test .= " *EP ".$employee_payment->getEmployeePaymentTotal(). "* "; 
                    
                }

            //If business premises is westbury
            } elseif($value->getBusinessPremises()->getBusinessPremisesName() == 'Westbury') {
                
                //Set the take array as the id = date and value = z reading
                $previous_month_westbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $previous_month_westbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();
                
                 //Set the departments
                foreach($value->getDailyTakeBusinessPremisesShopDepartment() as $key => $department) {
                    
                    //Create array with department name and department value
                    $previous_month_westbury_departments_array = array("department_name" => $department->getShopDepartment()->getShopDepartmentName(), "department_value" => $department->getShopDepartmentValue());
                    $previous_month_westbury_departments[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_month_westbury_departments_array;
                    
                    
                }

                //Set the receipts
                foreach($value->getDailyTakeBusinessPremisesPettyCash() as $key => $petty_cash) {
                    
                    //Create array with petty cash type and petty cash value
                    $previous_month_westbury_petty_cash_array = array("petty_cash_type" => $petty_cash->getExpensesType()->getExpensesTypeName(), "petty_cash_value" => $petty_cash->getPettyCashValue());
                    $previous_month_westbury_petty_cash[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_month_westbury_petty_cash_array;
                    
                    
                }

                //Set the payments
                foreach($value->getEmployeePayment() as $key => $employee_payment) {
                    
                    //Create array with employee payment employee and payment value
                    $previous_month_westbury_employee_payment_array = array("employee_payment_employee" => $employee_payment->getEmployee()->getEmployeeFullName(), "employee_payment_total" => $employee_payment->getEmployeePaymentTotal());
                    $previous_month_westbury_employee_payment[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_month_westbury_employee_payment_array;
                 
                    
                }
            }
        }

        //Set up the previous month take totals
        $previous_month_twenty_six_amesbury_total_take = array_sum($previous_month_twenty_six_amesbury_take);
        $previous_month_twenty_eight_amesbury_total_take = array_sum($previous_month_twenty_eight_amesbury_take);
        $previous_month_westbury_total_take = array_sum($previous_month_westbury_take);

        //MA website
        $previous_month_ma_website_total_take = 0.00;
        foreach($previous_month_ma_customer_orders as $value) {
            $previous_month_ma_website_total_take = $previous_month_ma_website_total_take + $value->getCustomerOrderTotalPricePaid();
        }

        //Amazon
        $previous_month_amazon_total_take = 0.00;
        foreach($previous_month_amazon_customer_orders as $value) {
            $previous_month_amazon_total_take = $previous_month_amazon_total_take + $value->getCustomerOrderTotalPricePaid();
        }

        $previous_month_total_take = $previous_month_twenty_six_amesbury_total_take + $previous_month_twenty_eight_amesbury_total_take +$previous_month_westbury_total_take + $previous_month_ma_website_total_take +$previous_month_amazon_total_take;

        if ($previous_month_total_take > 0) {
            $previous_month_total_take = "+". $previous_month_total_take;
        } else if ($previous_month_total_take < 0) {
            $previous_month_total_take = "-". $previous_month_total_take;
        }

        //Year
        $previous_year_start_date = Date('y-m-d', strtotime("-1 year" . $default_start_date));
        $previous_year_end_date = Date('y-m-d', strtotime("-1 year" . $default_end_date));
        

        //Get the data
        $em = $this->getDoctrine()->getManager();
        $previous_year_daily_take = $em->getRepository('MilesApartAdminBundle:DailyTakeBusinessPremises')->getDailyTakeBusinessPremisesByStartAndEndDate($previous_year_start_date, $previous_year_end_date);
        //MA aorders
        $previous_year_ma_customer_orders = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->getMACustomerOrderByStartAndEndDate($previous_year_start_date, $previous_year_end_date);
        //Amazon orders
        $previous_year_amazon_customer_orders = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->getAmazonCustomerOrderByStartAndEndDate($previous_year_start_date, $previous_year_end_date);
     
        //Create the arrays to hold totals.
        $previous_year_twenty_six_amesbury_take = [];
        $previous_year_twenty_eight_amesbury_take =[];
        $previous_year_westbury_take =[];

        //Create the arrays to hold transactions
        $previous_year_twenty_six_amesbury_transactions = [];
        $previous_year_twenty_eight_amesbury_transactions =[];
        $previous_year_westbury_transactions =[];        

        //Create the arrays to hold departments
        $previous_year_twenty_six_amesbury_departments = [];
        $previous_year_twenty_eight_amesbury_departments =[];
        $previous_year_westbury_departments =[];

        //Create the arrays to hold receipts
        $previous_year_twenty_six_amesbury_receipts = [];
        $previous_year_twenty_eight_amesbury_receipts =[];
        $previous_year_westbury_receipts =[];

        //Create the arrays to hold payments.
        $previous_year_twenty_six_amesbury_payments = [];
        $previous_year_twenty_eight_amesbury_payments =[];
        $previous_year_westbury_payments =[];

    $test = "";
        //Iterate over the daily take business premises that have been retrieved from the database.
        //For each, check which premises it is for and add details to respective 
        foreach($previous_year_daily_take as $key => $value) {
            
            //If business premises is 26 amesbury
            if($value->getBusinessPremises()->getBusinessPremisesName() == '26 Amesbury') {
                
                //Set the take array as the id = date and value = z reading
                $previous_year_twenty_six_amesbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $previous_year_twenty_six_amesbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();

                //Set the departments
                foreach($value->getDailyTakeBusinessPremisesShopDepartment() as $key => $department) {
                    
                    //Create array with department name and department value
                    $previous_year_twenty_six_amesbury_departments_array = array("department_name" => $department->getShopDepartment()->getShopDepartmentName(), "department_value" => $department->getShopDepartmentValue());
                    $previous_year_twenty_six_amesbury_departments[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_year_twenty_six_amesbury_departments_array;
                    $test .= "SD " .$department->getShopDepartment()->getShopDepartmentName() .$department->getShopDepartmentValue(); 

                }

                //Set the receipts
                foreach($value->getDailyTakeBusinessPremisesPettyCash() as $key => $petty_cash) {
                    
                    //Create array with petty cash type and petty cash value
                    $previous_year_twenty_six_amesbury_petty_cash_array = array("petty_cash_type" => $petty_cash->getExpensesType()->getExpensesTypeName(), "petty_cash_value" => $petty_cash->getPettyCashValue());
                    $previous_year_twenty_six_amesbury_petty_cash[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_year_twenty_six_amesbury_petty_cash_array;
                    $test .= "PC ".$petty_cash->getPettyCashValue(); 
                    
                }

                //Set the payments
                foreach($value->getEmployeePayment() as $key => $employee_payment) {
                    
                    //Create array with employee payment employee and payment value
                    $previous_year_twenty_six_amesbury_employee_payment_array = array("employee_payment_employee" => $employee_payment->getEmployee()->getEmployeeFullName(), "employee_payment_total" => $employee_payment->getEmployeePaymentTotal());
                    $previous_year_twenty_six_amesbury_employee_payment[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_year_twenty_six_amesbury_employee_payment_array;
                    $test .= "EP ".$employee_payment->getEmployeePaymentTotal(); 
                    
                }

            //If business premises is 28 amesbury
            } elseif($value->getBusinessPremises()->getBusinessPremisesName() == '28 Amesbury') {
                
                //Set the take array as the id = date and value = z reading
                $previous_year_twenty_eight_amesbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $previous_year_twenty_eight_amesbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();

                 //Set the departments
                foreach($value->getDailyTakeBusinessPremisesShopDepartment() as $key => $department) {
                    
                    //Create array with department name and department value
                    $previous_year_twenty_eight_amesbury_departments_array = array("department_name" => $department->getShopDepartment()->getShopDepartmentName(), "department_value" => $department->getShopDepartmentValue());
                    $previous_year_twenty_eight_amesbury_departments[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_year_twenty_eight_amesbury_departments_array;
                    $test .= " *SD " .$department->getShopDepartment()->getShopDepartmentName() .$department->getShopDepartmentValue() . "* "; 
                    
                }

                //Set the receipts
                foreach($value->getDailyTakeBusinessPremisesPettyCash() as $key => $petty_cash) {
                    
                    //Create array with petty cash type and petty cash value
                    $previous_year_twenty_eight_amesbury_petty_cash_array = array("petty_cash_type" => $petty_cash->getExpensesType()->getExpensesTypeName(), "petty_cash_value" => $petty_cash->getPettyCashValue());
                    $previous_year_twenty_eight_amesbury_petty_cash[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_year_twenty_eight_amesbury_petty_cash_array;
                    $test .= " *PC ".$petty_cash->getPettyCashValue(). "* "; 
                    
                }

                //Set the payments
                foreach($value->getEmployeePayment() as $key => $employee_payment) {
                    
                    //Create array with employee payment employee and payment value
                    $previous_year_twenty_eight_amesbury_employee_payment_array = array("employee_payment_employee" => $employee_payment->getEmployee()->getEmployeeFullName(), "employee_payment_total" => $employee_payment->getEmployeePaymentTotal());
                    $previous_year_twenty_eight_amesbury_employee_payment[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_year_twenty_eight_amesbury_employee_payment_array;
                    $test .= " *EP ".$employee_payment->getEmployeePaymentTotal(). "* "; 
                    
                }

            //If business premises is westbury
            } elseif($value->getBusinessPremises()->getBusinessPremisesName() == 'Westbury') {
                
                //Set the take array as the id = date and value = z reading
                $previous_year_westbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $previous_year_westbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();
                
                 //Set the departments
                foreach($value->getDailyTakeBusinessPremisesShopDepartment() as $key => $department) {
                    
                    //Create array with department name and department value
                    $previous_year_westbury_departments_array = array("department_name" => $department->getShopDepartment()->getShopDepartmentName(), "department_value" => $department->getShopDepartmentValue());
                    $previous_year_westbury_departments[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_year_westbury_departments_array;
                    
                    
                }

                //Set the receipts
                foreach($value->getDailyTakeBusinessPremisesPettyCash() as $key => $petty_cash) {
                    
                    //Create array with petty cash type and petty cash value
                    $previous_year_westbury_petty_cash_array = array("petty_cash_type" => $petty_cash->getExpensesType()->getExpensesTypeName(), "petty_cash_value" => $petty_cash->getPettyCashValue());
                    $previous_year_westbury_petty_cash[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_year_westbury_petty_cash_array;
                    
                    
                }

                //Set the payments
                foreach($value->getEmployeePayment() as $key => $employee_payment) {
                    
                    //Create array with employee payment employee and payment value
                    $previous_year_westbury_employee_payment_array = array("employee_payment_employee" => $employee_payment->getEmployee()->getEmployeeFullName(), "employee_payment_total" => $employee_payment->getEmployeePaymentTotal());
                    $previous_year_westbury_employee_payment[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $previous_year_westbury_employee_payment_array;
                 
                    
                }
            }
        }
        

        //Set up the previous year take totals
        $previous_year_twenty_six_amesbury_total_take = array_sum($previous_year_twenty_six_amesbury_take);
        $previous_year_twenty_eight_amesbury_total_take = array_sum($previous_year_twenty_eight_amesbury_take);
        $previous_year_westbury_total_take = array_sum($previous_year_westbury_take);
        
        //MA website
        $previous_year_ma_website_total_take = 0.00;
        foreach($previous_year_ma_customer_orders as $value) {
            $previous_year_ma_website_total_take = $previous_year_ma_website_total_take + $value->getCustomerOrderTotalPricePaid();
        }

        //Amazon
        $previous_year_amazon_total_take = 0.00;
        foreach($previous_year_amazon_customer_orders as $value) {
            $previous_year_amazon_total_take = $previous_year_amazon_total_take + $value->getCustomerOrderTotalPricePaid();
        }

        $previous_year_total_take = $previous_year_twenty_six_amesbury_total_take + $previous_year_twenty_eight_amesbury_total_take +$previous_year_westbury_total_take + $previous_year_ma_website_total_take + $previous_year_amazon_total_take;

        if ($previous_year_total_take > 0) {
            $previous_year_total_take = "+". $previous_year_total_take;
        } else if ($previous_year_total_take < 0) {
            $previous_year_total_take = "-". $previous_year_total_take;
        }

        //Call repository class (passing the dates to it - these will be updated by ajax when it makes the call after the dates have been changed.)
        $em = $this->getDoctrine()->getManager();
        $daily_take = new DailyTakeBusinessPremises();
        $daily_take = $em->getRepository('MilesApartAdminBundle:DailyTakeBusinessPremises')->getDailyTakeBusinessPremisesByStartAndEndDate($default_start_date, $default_end_date);
            
        //For each day, set the total, 26 amesbur, 28 amesbury and Westbury figures.
        //To be added to an array for chart and used for the totals textboxes on the page.
        
        //Create the arrays to hold totals.
        $twenty_six_amesbury_take = [];
        $twenty_eight_amesbury_take =[];
        $westbury_take =[];

        //Create the arrays to hold transactions
        $twenty_six_amesbury_transactions = [];
        $twenty_eight_amesbury_transactions =[];
        $westbury_transactions =[];

        //Create the arrays to hold departments
        $twenty_six_amesbury_departments = [];
        $twenty_eight_amesbury_departments =[];
        $westbury_departments =[];

        //Create the arrays to hold receipts
        $twenty_six_amesbury_receipts = [];
        $twenty_eight_amesbury_receipts =[];
        $westbury_receipts =[];

        //Create the arrays to hold payments.
        $twenty_six_amesbury_payments = [];
        $twenty_eight_amesbury_payments =[];
        $westbury_payments =[];

    $test = "";
        //Iterate over the daily take business premises that have been retrieved from the database.
        //For each, check which premises it is for and add details to respective 
        foreach($daily_take as $key => $value) {
            
            //If business premises is 26 amesbury
            if($value->getBusinessPremises()->getBusinessPremisesName() == '26 Amesbury') {
                
                //Set the take array as the id = date and value = z reading
                $twenty_six_amesbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $twenty_six_amesbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();

                //Set the departments
                foreach($value->getDailyTakeBusinessPremisesShopDepartment() as $key => $department) {
                    
                    //Create array with department name and department value
                    $twenty_six_amesbury_departments_array = array("department_name" => $department->getShopDepartment()->getShopDepartmentName(), "department_value" => $department->getShopDepartmentValue());
                    $twenty_six_amesbury_departments[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $twenty_six_amesbury_departments_array;
                    $test .= "SD " .$department->getShopDepartment()->getShopDepartmentName() .$department->getShopDepartmentValue(); 

                }

                //Set the receipts
                foreach($value->getDailyTakeBusinessPremisesPettyCash() as $key => $petty_cash) {
                    
                    //Create array with petty cash type and petty cash value
                    $twenty_six_amesbury_petty_cash_array = array("petty_cash_type" => $petty_cash->getExpensesType()->getExpensesTypeName(), "petty_cash_value" => $petty_cash->getPettyCashValue());
                    $twenty_six_amesbury_petty_cash[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $twenty_six_amesbury_petty_cash_array;
                    $test .= "PC ".$petty_cash->getPettyCashValue(); 
                    
                }

                //Set the payments
                foreach($value->getEmployeePayment() as $key => $employee_payment) {
                    
                    //Create array with employee payment employee and payment value
                    $twenty_six_amesbury_employee_payment_array = array("employee_payment_employee" => $employee_payment->getEmployee()->getEmployeeFullName(), "employee_payment_total" => $employee_payment->getEmployeePaymentTotal());
                    $twenty_six_amesbury_employee_payment[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $twenty_six_amesbury_employee_payment_array;
                    $test .= "EP ".$employee_payment->getEmployeePaymentTotal(); 
                    
                }

            //If business premises is 28 amesbury
            } elseif($value->getBusinessPremises()->getBusinessPremisesName() == '28 Amesbury') {
                
                //Set the take array as the id = date and value = z reading
                $twenty_eight_amesbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $twenty_eight_amesbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();

                 //Set the departments
                foreach($value->getDailyTakeBusinessPremisesShopDepartment() as $key => $department) {
                    
                    //Create array with department name and department value
                    $twenty_eight_amesbury_departments_array = array("department_name" => $department->getShopDepartment()->getShopDepartmentName(), "department_value" => $department->getShopDepartmentValue());
                    $twenty_eight_amesbury_departments[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $twenty_eight_amesbury_departments_array;
                    $test .= " *SD " .$department->getShopDepartment()->getShopDepartmentName() .$department->getShopDepartmentValue() . "* "; 
                    
                }

                //Set the receipts
                foreach($value->getDailyTakeBusinessPremisesPettyCash() as $key => $petty_cash) {
                    
                    //Create array with petty cash type and petty cash value
                    $twenty_eight_amesbury_petty_cash_array = array("petty_cash_type" => $petty_cash->getExpensesType()->getExpensesTypeName(), "petty_cash_value" => $petty_cash->getPettyCashValue());
                    $twenty_eight_amesbury_petty_cash[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $twenty_eight_amesbury_petty_cash_array;
                    $test .= " *PC ".$petty_cash->getPettyCashValue(). "* "; 
                    
                }

                //Set the payments
                foreach($value->getEmployeePayment() as $key => $employee_payment) {
                    
                    //Create array with employee payment employee and payment value
                    $twenty_eight_amesbury_employee_payment_array = array("employee_payment_employee" => $employee_payment->getEmployee()->getEmployeeFullName(), "employee_payment_total" => $employee_payment->getEmployeePaymentTotal());
                    $twenty_eight_amesbury_employee_payment[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $twenty_eight_amesbury_employee_payment_array;
                    $test .= " *EP ".$employee_payment->getEmployeePaymentTotal(). "* "; 
                    
                }

            //If business premises is westbury
            } elseif($value->getBusinessPremises()->getBusinessPremisesName() == 'Westbury') {
                
                //Set the take array as the id = date and value = z reading
                $westbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $westbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();
                
                 //Set the departments
                foreach($value->getDailyTakeBusinessPremisesShopDepartment() as $key => $department) {
                    
                    //Create array with department name and department value
                    $westbury_departments_array = array("department_name" => $department->getShopDepartment()->getShopDepartmentName(), "department_value" => $department->getShopDepartmentValue());
                    $westbury_departments[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $westbury_departments_array;
                    
                    
                }

                //Set the receipts
                foreach($value->getDailyTakeBusinessPremisesPettyCash() as $key => $petty_cash) {
                    
                    //Create array with petty cash type and petty cash value
                    $westbury_petty_cash_array = array("petty_cash_type" => $petty_cash->getExpensesType()->getExpensesTypeName(), "petty_cash_value" => $petty_cash->getPettyCashValue());
                    $westbury_petty_cash[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $westbury_petty_cash_array;
                    
                    
                }

                //Set the payments
                foreach($value->getEmployeePayment() as $key => $employee_payment) {
                    
                    //Create array with employee payment employee and payment value
                    $westbury_employee_payment_array = array("employee_payment_employee" => $employee_payment->getEmployee()->getEmployeeFullName(), "employee_payment_total" => $employee_payment->getEmployeePaymentTotal());
                    $westbury_employee_payment[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")][$key] = $westbury_employee_payment_array;
                 
                    
                }
            }
        }

        //Get customer orders (for dates provided) FOR ONLINE SALES
        //MA aorders
        $ma_customer_orders = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->getMACustomerOrderByStartAndEndDate($default_start_date, $default_end_date);
        //Amazon orders
        $amazon_customer_orders = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->getAmazonCustomerOrderByStartAndEndDate($default_start_date, $default_end_date);
        

        //Create the take chart arrays
        $twenty_six_amesbury_take_chart = [];
        $twenty_eight_amesbury_take_chart = [];
        $westbury_take_chart = [];
        $ma_website_take_chart = [];
        $amazon_take_chart = [];
        $total_take_chart = [];

        //Create the transactions chart arrays
        $twenty_six_amesbury_transactions_chart = [];
        $twenty_eight_amesbury_transactions_chart = [];
        $westbury_transactions_chart = [];
        $ma_website_transactions_chart = [];
        $amazon_transactions_chart = [];
        $total_transactions_chart = [];
        
        //Add values to the chart array by date.
        $loopdate = $default_start_date_obj;

        //Calculate the start date to be passed to the chart series array
        $jquery_start_date = $loopdate * 1000;

        //Set the take running total for each premises
        $twenty_six_amesbury_period_take_total = 0.00;
        $twenty_eight_amesbury_period_take_total = 0.00;
        $westbury_period_take_total = 0.00;
        $ma_website_period_take_total = 0.00;
        $amazon_period_take_total = 0.00;
        $ma_period_profit = 0.00;
        $amazon_period_profit = 0.00;

        //Set the transactions running total for each premises
        $twenty_six_amesbury_period_transactions_total = 0;
        $twenty_eight_amesbury_period_transactions_total = 0;
        $westbury_period_transactions_total = 0;
        $ma_website_period_transactions_total = 0;
        $amazon_period_transactions_total = 0;

        //Set the transactions running total for each department
        $twenty_six_amesbury_period_department_total = [];
        $twenty_eight_amesbury_period_department_total = [];
        $westbury_period_department_total = [];
        $department_total = [];

        //Calculate the online profit figures 
        //MA 
        foreach($ma_customer_orders as $value) {
        //Add to the period the profit total
            $ma_period_profit = $ma_period_profit + $value->getOrderProfit();
        }

        //Amazon 
        foreach($amazon_customer_orders as $value) {
        //Add to the period the profit total
            $amazon_period_profit = $amazon_period_profit + $value->getOrderProfit();
        }

        //NEED TO GET FEES FOR THE PRODUCTS ON THE ORDERS FOR THIS PERIOD
        $total_amazon_fees_for_period = $this->get('amazon_service')->getAmazonFeesForPeriodAction($amazon_customer_orders);
                

        //Take amazon fees off amazon profit
        $amazon_period_profit = $amazon_period_profit - $total_amazon_fees_for_period;

        //Iterate through each day within the period selection.
        while ($loopdate <= $default_end_date_obj)
        {
            //Reset the daily totals 
            $daily_take_total = 0.00;
            $daily_transactions_total = 0;

            $idate = date("Y-m-d", $loopdate);
            //Check if value set.
            if (isset($twenty_six_amesbury_take[$idate]) && isset($twenty_six_amesbury_transactions[$idate])) {
                
                //Is set to add to array.
                array_push($twenty_six_amesbury_take_chart, $twenty_six_amesbury_take[$idate]); 
                array_push($twenty_six_amesbury_transactions_chart, $twenty_six_amesbury_transactions[$idate]);

                //Add to running daily take total 
                $daily_take_total = $daily_take_total + $twenty_six_amesbury_take[$idate];

                //Add to running daily transactions total 
                $daily_transactions_total = $daily_transactions_total + $twenty_six_amesbury_transactions[$idate];
           
                //Add to period business premises transactions total
                $twenty_six_amesbury_period_transactions_total =  $twenty_six_amesbury_period_transactions_total + $twenty_six_amesbury_transactions[$idate];

                //Add to period business premises take total
                $twenty_six_amesbury_period_take_total =  $twenty_six_amesbury_period_take_total + $twenty_six_amesbury_take[$idate];
                
            } else {
                //Is not set so add 0 to array.
                array_push($twenty_six_amesbury_take_chart, 0.00);
                array_push($twenty_six_amesbury_transactions_chart, 0);
              
            }

            //Check if value set.
            if (isset($twenty_eight_amesbury_take[$idate]) && isset($twenty_eight_amesbury_transactions[$idate])) {
                
                //Is set to add to array.
                array_push($twenty_eight_amesbury_take_chart, $twenty_eight_amesbury_take[$idate]); 
                array_push($twenty_eight_amesbury_transactions_chart, $twenty_eight_amesbury_transactions[$idate]);
                
                //Add to running daily take total
                $daily_take_total = $daily_take_total + $twenty_eight_amesbury_take[$idate];
                
                //Add to running daily transactions total 
                $daily_transactions_total = $daily_transactions_total + $twenty_eight_amesbury_transactions[$idate];
            
                //Add to period business premises transactions total
                $twenty_eight_amesbury_period_transactions_total =  $twenty_eight_amesbury_period_transactions_total + $twenty_eight_amesbury_transactions[$idate];

                //Add to period business premises take total
                $twenty_eight_amesbury_period_take_total =  $twenty_eight_amesbury_period_take_total + $twenty_eight_amesbury_take[$idate];
           
            } else {
                //Is not set so add 0 to array.
                array_push($twenty_eight_amesbury_take_chart, 0.00);
                array_push($twenty_eight_amesbury_transactions_chart, 0);
            }

            //Check if value set.
            if (isset($westbury_take[$idate]) && isset($westbury_transactions[$idate])) {
                //Is set to add to array.
                array_push($westbury_take_chart, $westbury_take[$idate]); 
                array_push($westbury_transactions_chart, $westbury_transactions[$idate]);

                //Add to running daily take total
                $daily_take_total = $daily_take_total + $westbury_take[$idate];

                //Add to running daily transactions total 
                $daily_transactions_total = $daily_transactions_total + $westbury_transactions[$idate];
                
                //Add to period business premises transactions total
                $westbury_period_transactions_total =  $westbury_period_transactions_total + $westbury_transactions[$idate];

                //Add to period business premises take total
                $westbury_period_take_total =  $westbury_period_take_total + $westbury_take[$idate];

            } else {
                //Is not set so add 0 to array.
                array_push($westbury_take_chart, 0.00);
                array_push($westbury_transactions_chart, 0);
            }





            //ONLINE SALES
            //MA website
            $ma_daily_take_array = [];
            //Iterate over orders
            foreach($ma_customer_orders as $key => $value) {
                //Check if the order date is equal to the loopdate
                if(date_format($value->getCustomerOrderDateCreated(), 'Y-m-d') == $idate) {
                    array_push($ma_daily_take_array, $value->getCustomerOrderTotalPricePaid()); 
                }
            }


            $ma_website_daily_take = 0;

            foreach($ma_daily_take_array as $value) {
                $ma_website_daily_take = $ma_website_daily_take + $value;
            }

            $ma_website_daily_transactions = count($ma_daily_take_array);

            //Add to running daily take total
            $daily_take_total = $daily_take_total + $ma_website_daily_take;

            //Add to running daily transactions total 
            $daily_transactions_total = $daily_transactions_total + $ma_website_daily_transactions;
            
            //Add to period business premises transactions total
            $ma_website_period_transactions_total =  $ma_website_period_transactions_total + $ma_website_daily_transactions;

            //Add to period business premises take total
            $ma_website_period_take_total =  $ma_website_period_take_total + $ma_website_daily_take;


            //Add figures to the chart array.
            array_push($ma_website_take_chart, $ma_website_daily_take); 
            array_push($ma_website_transactions_chart, $ma_website_daily_transactions);
          




            //Amazon
            $amazon_daily_take_array = [];
           
            //Iterate over orders
            foreach($amazon_customer_orders as $key => $value) {
                //Check if the order date is equal to the loopdate
                if(date_format($value->getCustomerOrderDateCreated(), 'Y-m-d') == $idate) {
                    array_push($amazon_daily_take_array, $value->getCustomerOrderTotalPricePaid());   
                }
            }

            $amazon_website_daily_take = 0;
           
            foreach($amazon_daily_take_array as $value) {
                $amazon_website_daily_take = $amazon_website_daily_take + $value;
            }

            $amazon_website_daily_transactions = count($amazon_daily_take_array);

           
            //Add to running daily take total
            $daily_take_total = $daily_take_total + $amazon_website_daily_take;

            //Add to running daily transactions total 
            $daily_transactions_total = $daily_transactions_total + $amazon_website_daily_transactions;
            
            //Add to period business premises transactions total
            $amazon_period_transactions_total =  $amazon_period_transactions_total + $amazon_website_daily_transactions;

            //Add to period business premises take total
            $amazon_period_take_total =  $amazon_period_take_total + $amazon_website_daily_take;

            //Add figures to the chart array.
            array_push($amazon_take_chart, $amazon_website_daily_take); 
            array_push($amazon_transactions_chart, $amazon_website_daily_transactions);





            //Update the total array.
            array_push($total_take_chart, $daily_take_total);
            array_push($total_transactions_chart, $daily_transactions_total);

            //Increment the time loop.
            $loopdate = strtotime('+1 day', $loopdate);      
        }

        //Set the totals for the time period selected.
        $period_take_total = array_sum($total_take_chart);

        //Set the totals for the time period selected.
        $period_transactions_total = array_sum($total_transactions_chart);

        //Check if take total and transactions are > 0
        if ($period_take_total > 0 && $period_transactions_total > 0) {
            //Calculate spend per transaction
            $period_spend_per_transaction = $period_take_total / $period_transactions_total;
        } else {
            $period_spend_per_transaction = null;
        }

        //Check if take total and transactions are > 0
        if ($twenty_six_amesbury_period_take_total > 0 && $twenty_six_amesbury_period_transactions_total > 0) {
            //Calculate spend per transaction
            $twenty_six_amesbury_period_spend_per_transaction = $twenty_six_amesbury_period_take_total / $twenty_six_amesbury_period_transactions_total;
        } else {
            $twenty_six_amesbury_period_spend_per_transaction = null;
        }

        //Check if take total and transactions are > 0
        if ($twenty_eight_amesbury_period_take_total > 0 && $twenty_eight_amesbury_period_transactions_total > 0) {
            //Calculate spend per transaction
            $twenty_eight_amesbury_period_spend_per_transaction = $twenty_eight_amesbury_period_take_total / $twenty_eight_amesbury_period_transactions_total;
        } else {
            $twenty_eight_amesbury_period_spend_per_transaction = null;
        }

        //Check if take total and transactions are > 0
        if ($westbury_period_take_total > 0 && $westbury_period_transactions_total > 0) {
            //Calculate spend per transaction
            $westbury_period_spend_per_transaction = $westbury_period_take_total / $westbury_period_transactions_total;
        } else {
            $westbury_period_spend_per_transaction = null;
        }

        //Check if take total and transactions are > 0
        if ($ma_website_period_take_total > 0 && $ma_website_period_transactions_total > 0) {
            //Calculate spend per transaction
            $ma_website_period_spend_per_transaction = $ma_website_period_take_total / $ma_website_period_transactions_total;
        } else {
            $ma_website_period_spend_per_transaction = null;
        }

        //Check if take total and transactions are > 0
        if ($amazon_period_take_total > 0 && $amazon_period_transactions_total > 0) {
            //Calculate spend per transaction
            $amazon_period_spend_per_transaction = $amazon_period_take_total / $amazon_period_transactions_total;
        } else {
            $amazon_period_spend_per_transaction = null;
        }

        //Take chart data
        $series = array(
            array("name" => "Total Take",    "data" => $total_take_chart, "color" => '#36456e', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
            array("name" => "26 Amesbury",    "data" => $twenty_six_amesbury_take_chart, "color" => '#dc961a', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
            array("name" => "28 Amesbury",    "data" => $twenty_eight_amesbury_take_chart, "color" => '#72ac24', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
            array("name" => "Westbury",    "data" => $westbury_take_chart, "color" => '#8c154d',  'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
            array("name" => "Ma Website",    "data" => $ma_website_take_chart, "color" => '#119d9a',  'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
            array("name" => "Amazon",    "data" => $amazon_take_chart, "color" => '#551f6d',  'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
        );

        //Set the X axis title 
        if (isset($current_month)) {
            $x_axis_title = "of " .$current_month;

        } else {
            $x_axis_title = "Date";
        }
        
        //Set up the graph for the overall daily take
        $ob = new Highchart();
        $ob->chart->renderTo('graph_container');  // The #id of the div where to render the chart
        $ob->title->text('Daily Takes for Period ');
        $ob->xAxis->title(array('text'  => $x_axis_title));
        $ob->xAxis->type("datetime");
        $ob->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob->yAxis->min(0);
        $ob->yAxis->title(array('text'  => "Daily Take Total"));
        $ob->yAxis->labels(array('format'  => "£{value}"));
        $ob->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob->tooltip->formatter($formatter);
        //Add the data
        $ob->series($series);

        //26 Amesbury take chart data
        $series_two = array(
            array("name" => "26 Amesbury",    "data" => $twenty_six_amesbury_take_chart, "color" => '#dc961a', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000), 
        );

        //Set up the graph for 26 amesbury take data
        $ob_two = new Highchart();
        $ob_two->chart->renderTo('twenty_six_graph_container');  // The #id of the div where to render the chart
        $ob_two->title->text('26 Amesbury Daily Takes for Period ');
        $ob_two->xAxis->title(array('text'  => $x_axis_title));
        $ob_two->xAxis->type("datetime");
        $ob_two->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob_two->yAxis->min(0);
        $ob_two->yAxis->title(array('text'  => "Daily Take Total"));
        $ob_two->yAxis->labels(array('format'  => "£{value}"));
        $ob_two->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob_two->tooltip->formatter($formatter);
        //Add the data
        $ob_two->series($series_two);

        //28 Amesbury take data
        $series_three = array(
            array("name" => "28 Amesbury",    "data" => $twenty_eight_amesbury_take_chart, "color" => '#72ac24', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
        );

        //Set up the graph for 28 Amesburuy take data
        $ob_three = new Highchart();
        $ob_three->chart->renderTo('twenty_eight_graph_container');  // The #id of the div where to render the chart
        $ob_three->title->text('28 Amesbury Daily Takes for Period ');
        $ob_three->xAxis->title(array('text'  => $x_axis_title));
        $ob_three->xAxis->type("datetime");
        $ob_three->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob_three->yAxis->min(0);
        $ob_three->yAxis->title(array('text'  => "Daily Take Total"));
        $ob_three->yAxis->labels(array('format'  => "£{value}"));
        $ob_three->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob_three->tooltip->formatter($formatter);
        //Add the data 
        $ob_three->series($series_three);

        //Westbury take chart data
        $series_four = array(
            array("name" => "Westbury",    "data" => $westbury_take_chart, "color" => '#8c154d', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
        );

        //Set up th westbury take chart
        $ob_four = new Highchart();
        $ob_four->chart->renderTo('westbury_graph_container');  // The #id of the div where to render the chart
        $ob_four->title->text('Westbury Daily Takes for Period ');
        $ob_four->xAxis->title(array('text'  => $x_axis_title));
        $ob_four->xAxis->type("datetime");
        $ob_four->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob_four->yAxis->min(0);
        $ob_four->yAxis->title(array('text'  => "Daily Take Total"));
        $ob_four->yAxis->labels(array('format'  => "£{value}"));
        $ob_four->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob_four->tooltip->formatter($formatter);
        //Add the data
        $ob_four->series($series_four);

         //Ma Website take chart data
        $series_four_one = array(
            array("name" => "MA Website",    "data" => $ma_website_take_chart, "color" => '#119d9a', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
        );

        //Set up th westbury take chart
        $ob_four_one = new Highchart();
        $ob_four_one->chart->renderTo('ma_website_graph_container');  // The #id of the div where to render the chart
        $ob_four_one->title->text('MA Website Daily Takes for Period ');
        $ob_four_one->xAxis->title(array('text'  => $x_axis_title));
        $ob_four_one->xAxis->type("datetime");
        $ob_four_one->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob_four_one->yAxis->min(0);
        $ob_four_one->yAxis->title(array('text'  => "Daily Take Total"));
        $ob_four_one->yAxis->labels(array('format'  => "£{value}"));
        $ob_four_one->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob_four_one->tooltip->formatter($formatter);
        //Add the data
        $ob_four_one->series($series_four_one);

        //Ma Website take chart data
        $series_four_two = array(
            array("name" => "Amazon",    "data" => $amazon_take_chart, "color" => '#551f6d', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
        );

        //Set up th westbury take chart
        $ob_four_two = new Highchart();
        $ob_four_two->chart->renderTo('amazon_graph_container');  // The #id of the div where to render the chart
        $ob_four_two->title->text('Amazon Daily Takes for Period ');
        $ob_four_two->xAxis->title(array('text'  => $x_axis_title));
        $ob_four_two->xAxis->type("datetime");
        $ob_four_two->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob_four_two->yAxis->min(0);
        $ob_four_two->yAxis->title(array('text'  => "Daily Take Total"));
        $ob_four_two->yAxis->labels(array('format'  => "£{value}"));
        $ob_four_two->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob_four_two->tooltip->formatter($formatter);
        //Add the data
        $ob_four_two->series($series_four_two);

        //Get the departments and create Departments graph.
        $categories = [];

        $em = $this->getDoctrine()->getManager();
        $shop_departments = new ShopDepartment();
        $shop_departments = $em->getRepository('MilesApartAdminBundle:ShopDepartment')->findAll();
       

         //Need to set up the category arrays for the chart
        $twenty_six_amesbury_departments_chart = [];
        $twenty_eight_amesbury_departments_chart = [];
        $westbury_departments_chart = [];
        $twenty_six_amesbury_period_department_total_count = [];


        //Iterate over each department returned from the database
        foreach ($shop_departments as $key1 => $value) {

            //Get shop department name.
            $shop_department_name = $value->getShopDepartmentName();
            //Add the department to the categories array for chart x axis
            array_push($categories, $shop_department_name);

            //Reset the departments figure for each shop
            $twenty_six_amesbury_period_department_total_figure = 0.00;
            $twenty_eight_amesbury_period_department_total_figure = 0.00;
            $westbury_period_department_total_figure = 0.00;

            //Add departments for 26 amesbury
            //For each department total figure (for each shop on each day) 
            foreach ($twenty_six_amesbury_departments as $key => $value2) {
                foreach ($value2 as $key2 => $value3) {
                //Check if the department name is the same as this iteration.
                    if($value3['department_name'] == $shop_department_name) {

                        //If it is the same, add to this total 
                        $twenty_six_amesbury_period_department_total_figure = $twenty_six_amesbury_period_department_total_figure + $value3['department_value']; 
                    }
                }
            }

            //Add this departments total to the chart array.
            array_push($twenty_six_amesbury_departments_chart, $twenty_six_amesbury_period_department_total_figure);
     
            //Add departments for 28 amesbury
            //For each department total figure (for each shop on each day) 
            foreach ($twenty_eight_amesbury_departments as $key => $value2) {
                foreach ($value2 as $key2 => $value3) {
                    //Check if the department name is the same as this iteration.
                    if($value3['department_name'] == $shop_department_name) {

                        //If it is the same, add to this total 
                        $twenty_eight_amesbury_period_department_total_figure = $twenty_eight_amesbury_period_department_total_figure + $value3['department_value']; 
                    }
                }
            }

            //Add this departments total to the chart array.
            array_push($twenty_eight_amesbury_departments_chart, $twenty_eight_amesbury_period_department_total_figure);
        
            //Add departments for westbury
            //For each department total figure (for each shop on each day) 
            foreach ($westbury_departments as $key => $value2) {
                foreach ($value2 as $key2 => $value3) {
                    //Check if the department name is the same as this iteration.
                    if($value3['department_name'] == $shop_department_name) {

                        //If it is the same, add to this total 
                        $westbury_period_department_total_figure = $westbury_period_department_total_figure + $value3['department_value']; 
                    }
                }
            }

            //Add this departments total to the chart array.
            array_push($westbury_departments_chart, $westbury_period_department_total_figure);
        }
    
        //Set up the chart data
        $series_five = array(
            array("name" => "26 Amesbury",    "data" => $twenty_six_amesbury_departments_chart, "color" => '#dc961a'),
            array("name" => "28 Amesbury",    "data" => $twenty_eight_amesbury_departments_chart, "color" => '#72ac24'),
            array("name" => "Westbury",    "data" => $westbury_departments_chart, "color" => '#8c154d'),
           
        );

        //Set up the chart
        $ob_five = new Highchart();
        $ob_five->chart->renderTo('westbury_departments_graph_container');  // The #id of the div where to render the chart
        $ob_five->chart->type('column');
        $ob_five->title->text('Departments for Period ');
        $ob_five->xAxis->title(array('text'  => "Departments"));
        $ob_five->xAxis->type("categories");
        $ob_five->xAxis->categories($categories);
        $ob_five->plotOptions->column(array('pointPadding' => 0.2, 'border_width' => 0));      
        $ob_five->yAxis->min(0);
        $ob_five->yAxis->title(array('text'  => "Daily Take Total"));
        $ob_five->yAxis->labels(array('format'  => "£{value}"));
        
        //Add the data
        $ob_five->series($series_five);

        //Get the expenses types and create petty cash graph.
        $categories = [];

        //Get the expenses types
        $em = $this->getDoctrine()->getManager();
        $expenses_type = new ExpensesType();
        $expenses_type = $em->getRepository('MilesApartAdminBundle:ExpensesType')->findAll();
       

         //Need to set up the category arrays for the chart
        $twenty_six_amesbury_petty_cash_chart = [];
        $twenty_eight_amesbury_petty_cash_chart = [];
        $westbury_petty_cash_chart = [];
        $twenty_six_amesbury_period_petty_cash_total_count = [];


        //Iterate over each expenses type returned from the database
        foreach ($expenses_type as $key1 => $value) {

            //Get shop expenses type name.
            $expenses_type_name = $value->getExpensesTypeName();
            //Add the expenses type to the categories array for chart x axis
            array_push($categories, $expenses_type_name);

            //Reset the expenses type figure for each shop
            $twenty_six_amesbury_period_expenses_type_total_figure = 0.00;
            $twenty_eight_amesbury_period_expenses_type_total_figure = 0.00;
            $westbury_period_expenses_type_total_figure = 0.00;

            //Add expenses type for 26 amesbury
            if (isset($twenty_six_amesbury_petty_cash)) {
                //For each expenses type total figure (for each shop on each day) 
                foreach ($twenty_six_amesbury_petty_cash as $key => $value2) {
                    foreach ($value2 as $key2 => $value3) {
                    //Check if the expenses type name is the same as this iteration.
                        if($value3['petty_cash_type'] == $expenses_type_name) {

                            //If it is the same, add to this total 
                            $twenty_six_amesbury_period_expenses_type_total_figure = $twenty_six_amesbury_period_expenses_type_total_figure + $value3['petty_cash_value']; 
                        }
                    }
                } 


                //Add this expenses type total to the chart array.
                array_push($twenty_six_amesbury_petty_cash_chart, $twenty_six_amesbury_period_expenses_type_total_figure);
            }

            //Add expenses type for 28 amesbury
            if (isset($twenty_eight_amesbury_petty_cash)) {
                //For each expenses type total figure (for each shop on each day) 
                foreach ($twenty_eight_amesbury_petty_cash as $key => $value2) {
                    foreach ($value2 as $key2 => $value3) {
                        //Check if the expenses type name is the same as this iteration.
                        if($value3['petty_cash_type'] == $expenses_type_name) {

                            //If it is the same, add to this total 
                            $twenty_eight_amesbury_period_expenses_type_total_figure = $twenty_eight_amesbury_period_expenses_type_total_figure + $value3['petty_cash_value']; 
                        }
                    }
                }

                //Add this expenses type total to the chart array.
                array_push($twenty_eight_amesbury_petty_cash_chart, $twenty_eight_amesbury_period_expenses_type_total_figure);
            }

            //Add expenses type for westbury
            if (isset($westbury_petty_cash)) {
                //For each expenses type total figure (for each shop on each day) 
                foreach ($westbury_petty_cash as $key => $value2) {
                    foreach ($value2 as $key2 => $value3) {
                        //Check if the expenses type name is the same as this iteration.
                        if($value3['petty_cash_type'] == $expenses_type_name) {

                            //If it is the same, add to this total 
                            $westbury_period_expenses_type_total_figure = $westbury_period_expenses_type_total_figure + $value3['petty_cash_value']; 
                        }
                    }
                }

                //Add this expenses type total to the chart array.
                array_push($westbury_petty_cash_chart, $westbury_period_expenses_type_total_figure);
            }
        }
    
        //Set up the chart data
        $series_six = array(
            array("name" => "26 Amesbury",    "data" => $twenty_six_amesbury_petty_cash_chart, "color" => '#dc961a'),
            array("name" => "28 Amesbury",    "data" => $twenty_eight_amesbury_petty_cash_chart, "color" => '#72ac24'),
            array("name" => "Westbury",    "data" => $westbury_petty_cash_chart, "color" => '#8c154d'),
           
        );

        //Set up the chart
        $ob_six = new Highchart();
        $ob_six->chart->renderTo('petty_cash_graph_container');  // The #id of the div where to render the chart
        $ob_six->chart->type('column');
        $ob_six->title->text('Receipts for Period ');
        $ob_six->xAxis->title(array('text'  => "Expenses Types"));
        $ob_six->xAxis->type("categories");
        $ob_six->xAxis->categories($categories);
        $ob_six->plotOptions->column(array('pointPadding' => 0.2, 'border_width' => 0));      
        $ob_six->yAxis->min(0);
        $ob_six->yAxis->title(array('text'  => "Daily Cost Total"));
        $ob_six->yAxis->labels(array('format'  => "£{value}"));
        
        //Add the data
        $ob_six->series($series_six);

        //Get the employee payments and create petty cash graph.
        $categories = [];

        //Get the employee payments
        $em = $this->getDoctrine()->getManager();
        $employee = new Employee();
        $employee = $em->getRepository('MilesApartAdminBundle:Employee')->getCurrentEmployees();
       

         //Need to set up the category arrays for the chart
        $twenty_six_amesbury_employee_payment_chart = [];
        $twenty_eight_amesbury_employee_payment_chart = [];
        $westbury_employee_payment_chart = [];
        $twenty_six_amesbury_period_employee_payment_total_count = [];


        //Iterate over each employee payments returned from the database
        foreach ($employee as $key1 => $value) {

            //Get employee payments name.
            $employee_name = $value->getEmployeeFullName();
            //Add the employee payments to the categories array for chart x axis
            array_push($categories, $employee_name);

            //Reset the employee payments figure for each shop
            $twenty_six_amesbury_period_employee_payment_total_figure = 0.00;
            $twenty_eight_amesbury_period_employee_payment_total_figure = 0.00;
            $westbury_period_employee_payment_total_figure = 0.00;

            //Add employee payments for 26 amesbury
            if (isset($twenty_six_amesbury_employee_payment)) {
                //For each employee payments total figure (for each shop on each day) 
                foreach ($twenty_six_amesbury_employee_payment as $key => $value2) {
                    foreach ($value2 as $key2 => $value3) {
                    //Check if the employee payments name is the same as this iteration.
                        if($value3['employee_payment_employee'] == $employee_name) {

                            //If it is the same, add to this total 
                            $twenty_six_amesbury_period_employee_payment_total_figure = $twenty_six_amesbury_period_employee_payment_total_figure + $value3['employee_payment_total']; 
                        }
                    }
                } 


                //Add this employee payments total to the chart array.
                array_push($twenty_six_amesbury_employee_payment_chart, $twenty_six_amesbury_period_employee_payment_total_figure);
            }

            //Add employee payments for 28 amesbury
            if (isset($twenty_eight_amesbury_employee_payment)) {
                //For each employee payments total figure (for each shop on each day) 
                foreach ($twenty_eight_amesbury_employee_payment as $key => $value2) {
                    foreach ($value2 as $key2 => $value3) {
                        //Check if the employee name is the same as this iteration.
                        if($value3['employee_payment_employee'] == $employee_name) {

                            //If it is the same, add to this total 
                            $twenty_eight_amesbury_period_employee_payment_total_figure = $twenty_eight_amesbury_period_employee_payment_total_figure + $value3['employee_payment_total']; 
                        }
                    }
                }

                //Add this expenses type total to the chart array.
                array_push($twenty_eight_amesbury_employee_payment_chart, $twenty_eight_amesbury_period_employee_payment_total_figure);
            }

            //Add employee payments for westbury
            if (isset($westbury_employee_payment)) {
                //For each employee payments total figure (for each shop on each day) 
                foreach ($westbury_employee_payment as $key => $value2) {
                    foreach ($value2 as $key2 => $value3) {
                        //Check if the employee payments name is the same as this iteration.
                        if($value3['employee_payment_employee'] == $employee_name) {

                            //If it is the same, add to this total 
                            $westbury_period_employee_payment_total_figure = $westbury_period_employee_payment_total_figure + $value3['employee_payment_total']; 
                        }
                    }
                }

                //Add this employee payments total to the chart array.
                array_push($westbury_employee_payment_chart, $westbury_period_employee_payment_total_figure);
            }
        }
    
        //Set up the chart data
        $series_seven = array(
            array("name" => "26 Amesbury",    "data" => $twenty_six_amesbury_employee_payment_chart, "color" => '#dc961a'),
            array("name" => "28 Amesbury",    "data" => $twenty_eight_amesbury_employee_payment_chart, "color" => '#72ac24'),
            array("name" => "Westbury",    "data" => $westbury_employee_payment_chart, "color" => '#8c154d'),
           
        );

        //Set up the chart
        $ob_seven = new Highchart();
        $ob_seven->chart->renderTo('employee_payment_graph_container');  // The #id of the div where to render the chart
        $ob_seven->chart->type('column');
        $ob_seven->title->text('Employee Payments for Period ');
        $ob_seven->xAxis->title(array('text'  => "Employee"));
        $ob_seven->xAxis->type("categories");
        $ob_seven->xAxis->categories($categories);
        $ob_seven->plotOptions->column(array('pointPadding' => 0.2, 'border_width' => 0));      
        $ob_seven->yAxis->min(0);
        $ob_seven->yAxis->title(array('text'  => "Daily Cost Total"));
        $ob_seven->yAxis->labels(array('format'  => "£{value}"));
        
        //Add the data
        $ob_seven->series($series_seven);




        return $this->render('MilesApartStaffBundle:Finances:view_daily_takes.html.twig', array(
            'chart' => $ob, 
            'chart_two' => $ob_two,
            'chart_three' => $ob_three,
            'chart_four' => $ob_four,
            'chart_four_one' => $ob_four_one,
            'chart_four_two' => $ob_four_two,
            'chart_five' => $ob_five,
            'chart_six' => $ob_six,
            'chart_seven' => $ob_seven,
            'daily_take' => $daily_take,
            'twenty_six_amesbury_take'=> $twenty_six_amesbury_take_chart,
            'twenty_eight_amesbury_take'=> $twenty_eight_amesbury_take_chart,
            'westbury_take'=> $westbury_take_chart,
            'ma_website_take' =>$ma_website_take_chart,
            'amazon_take' =>$amazon_take_chart,
            'total_take_chart' => $total_take_chart,
            'start_date' => $default_start_date,
            'end_date' => $default_end_date,
            'period_take_total' => $period_take_total,
            'period_transactions_total' => $period_transactions_total,
            'period_spend_per_transaction' => $period_spend_per_transaction,
            'twenty_six_amesbury_period_transactions_total' => $twenty_six_amesbury_period_transactions_total,
            'twenty_eight_amesbury_period_transactions_total' => $twenty_eight_amesbury_period_transactions_total,
            'westbury_period_transactions_total' => $westbury_period_transactions_total,
            'ma_website_period_transactions_total' => $ma_website_period_transactions_total,
            'amazon_period_transactions_total' => $amazon_period_transactions_total,
            'twenty_six_amesbury_period_take_total' => $twenty_six_amesbury_period_take_total,
            'twenty_eight_amesbury_period_take_total' => $twenty_eight_amesbury_period_take_total,
            'westbury_period_take_total' => $westbury_period_take_total,
            'ma_website_period_take_total' => $ma_website_period_take_total,
            'amazon_period_take_total' => $amazon_period_take_total,
            'categories' => $categories,
            'test' => $test,
            'test2' => $twenty_eight_amesbury_departments,
            'twenty_six_amesbury_period_department_total' => $twenty_six_amesbury_departments,
            'twenty_six_amesbury_period_spend_per_transaction' => $twenty_six_amesbury_period_spend_per_transaction,
            'twenty_eight_amesbury_period_spend_per_transaction' => $twenty_eight_amesbury_period_spend_per_transaction,
            'westbury_period_spend_per_transaction' => $westbury_period_spend_per_transaction,
            'ma_website_period_spend_per_transaction' => $ma_website_period_spend_per_transaction,
            'amazon_period_spend_per_transaction' => $amazon_period_spend_per_transaction,

            'previous_year_total_take' => $previous_year_total_take,
            'previous_month_total_take' => $previous_month_total_take,
            'previous_month_twenty_six_amesbury_total_take' => $previous_month_twenty_six_amesbury_total_take,
            'previous_month_twenty_eight_amesbury_total_take' => $previous_month_twenty_eight_amesbury_total_take,
            'previous_month_westbury_total_take' => $previous_month_westbury_total_take,
            'previous_month_ma_website_total_take' => $previous_month_ma_website_total_take,
            'previous_month_amazon_total_take' => $previous_month_amazon_total_take,

            'previous_year_twenty_six_amesbury_total_take' => $previous_year_twenty_six_amesbury_total_take,
            'previous_year_twenty_eight_amesbury_total_take' => $previous_year_twenty_eight_amesbury_total_take,
            'previous_year_westbury_total_take' => $previous_year_westbury_total_take,
            'previous_year_ma_website_total_take' => $previous_year_ma_website_total_take,
            'previous_year_amazon_total_take' => $previous_year_amazon_total_take,

            'ma_customer_orders' => $ma_customer_orders,
            'amazon_customer_orders' => $amazon_customer_orders,
            'ma_period_profit' => $ma_period_profit,
            'amazon_period_profit' => $amazon_period_profit,
        ));

    }

    public function printdailytakesAction() 
    {
        //Start the session
        $session = new Session();

        //Check if period start date and end date have been set.
        if ($session->has('start_date') && $session->has('end_date')) {

            //Use the session dates for search query.
            $default_start_date = Date('y-m-d', strtotime($session->get('start_date')));
            $default_end_date = Date('y-m-d', strtotime($session->get('end_date')));

            //$default_start_date = Date('2016-09-01');
           
            //$default_end_date = date('2016-09-30');

       } else {
           
            //Default start date to be 30 prior to todays date
            $default_start_date = Date('y-m-d', strtotime("-30 days"));
           
            $default_end_date = date('y-m-d');

            //Set the start and end date into the session
            $session->set('start_date', $default_start_date);
            $session->set('end_date', $default_end_date);
        }

        //Call repository class (passing the dates to it - these will be updated by ajax when it makes the call after the dates have been changed.)
        $em = $this->getDoctrine()->getManager();
        $business_premises = new BusinessPremises();
        $business_premises = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->getBusinessPremisesByStartAndEndDate($default_start_date, $default_end_date);
            
        $business = $em->getRepository('MilesApartAdminBundle:Business')->findOneById(1);
        $employees = $em->getRepository('MilesApartAdminBundle:Employee')->getEmployeesByStartAndEndDate($default_start_date, $default_end_date);
        
        $expenses_types = $em->getRepository('MilesApartAdminBundle:ExpensesType')->getExpensesTypesByStartAndEndDate($default_start_date, $default_end_date);
         
        
        //Get employee payments by the week end payment date
        $employee_payments = $em->getRepository('MilesApartAdminBundle:EmployeePayment')->getEmployeePaymentByStartAndEndDate($default_start_date, $default_end_date);
        
        //Get employee payments by the week end payment date
        $employee_statutory_payments = $em->getRepository('MilesApartAdminBundle:EmployeeStatutoryPayment')->getEmployeeStatutoryPaymentByStartAndEndDate($default_start_date, $default_end_date);
        
        return $this->render('MilesApartStaffBundle:Finances:print_daily_takes.html.twig', array(
            'business_premises' => $business_premises,
            'business' => $business,
            'employees' => $employees,
            'start_date'=> $default_start_date,
            'end_date'=> $default_end_date,
            'expenses_types' => $expenses_types,
            'employee_payments' => $employee_payments,
            'employee_statutory_payments' => $employee_statutory_payments,
            ));
    }


    public function viewdailytakesbusinesspremisesAction($business_premises) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Daily Takes", $this->get("router")->generate("staff-finances_view-daily-takes"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Daily Takes Business Premises", $this->get("router")->generate("staff-finances_view-daily-takes-business-premises", array('business_premises' => $business_premises)));
       
        $session = new Session;

        $start_date = $session->get('start_date');
        $end_date = $session->get('end_date');

        return $this->render('MilesApartStaffBundle:Finances:view_daily_takes_business_premises.html.twig', array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'business_premises' => $business_premises,
            ));
    }

   

    public function employeepaymentcalculatorAction(Request $request) 
    {
        //Get the dates from the post request.
         //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }
        
        //Set the new price and the product id
        $hours_value = $response["hours_value"];
        $employee_id = $response["employee_id"];
       
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Employee')->findOneById(intval($employee_id));

        $employee_total_pay = $hours_value * $entity->getCurrentJobRole()->getCurrentWageRate();
        
        $employee_total_pay = round($employee_total_pay, 2);
        $response = array("employee_total_pay" => $employee_total_pay);
        return new JsonResponse(
                    $response 
                );
       
    }

    //Function to calculate online profit for period
    public function getOnlineProfitAction() 
    {
        //Get the dates 

    }

    /************* Helper functions ***************/
    public function getAggregatedDailyTakeBusinessPremises() 
    {
        //Return all details required for view daily take business premises.
    }


     public function analysefinancesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Analyse Finances", $this->get("router")->generate("staff-finances_analyse-finances"));
     
        //Start the session
        $session = new Session();

        //Check if period start date and end date have been set.
        if ($session->has('start_date') && $session->has('end_date')) {

            //Use the session dates for search query.
            $default_start_date = Date('y-m-d', strtotime($session->get('start_date')));
            $default_end_date = Date('y-m-d', strtotime($session->get('end_date')));



            //Default start date to be 30 prior to todays date
           //$default_start_date = Date('y-m-d', strtotime("-30 days"));
           
            //$default_end_date = date('y-m-d');

            //$default_start_date = Date('2014-01-01', strtotime("-30 days"));
           
            //$default_end_date = date('2015-07-01');

        //Get current month and use these dates.
       } else {
            //Get the chart data form daily take.
            //Set the default date range to be the current month.
            

            //Default start date to be first recorded take
            $default_start_date = Date('2014-11-01');
           
            $default_end_date = date('y-m-d');

            //$default_start_date = Date('2014-01-01', strtotime("-30 days"));
           
            //$default_end_date = date('2015-07-01');

            //Set the start and end date into the session
            $session->set('start_date', $default_start_date);
            $session->set('end_date', $default_end_date);
        }

        //Create the timestamp used by highcarts on x axis.
        $default_start_date_obj = strtotime($default_start_date);
        $default_end_date_obj = strtotime(date($default_end_date));

        

        //Call repository class (passing the dates to it - these will be updated by ajax when it makes the call after the dates have been changed.)
        $em = $this->getDoctrine()->getManager();
        $daily_take = new DailyTakeBusinessPremises();
        $daily_take = $em->getRepository('MilesApartAdminBundle:DailyTakeBusinessPremises')->getDailyTakeBusinessPremisesByStartAndEndDate($default_start_date, $default_end_date);
            
        //For each day, set the total, 26 amesbur, 28 amesbury and Westbury figures.
        //To be added to an array for chart and used for the totals textboxes on the page.
        
        //Create the arrays to hold totals.
        $twenty_six_amesbury_take = [];
        $twenty_eight_amesbury_take =[];
        $westbury_take =[];

        //Create the arrays to hold transactions
        $twenty_six_amesbury_transactions = [];
        $twenty_eight_amesbury_transactions =[];
        $westbury_transactions =[];

        
    $test = "";
        //Iterate over the daily take business premises that have been retrieved from the database.
        //For each, check which premises it is for and add details to respective 
        foreach($daily_take as $key => $value) {
            
            //If business premises is 26 amesbury
            if($value->getBusinessPremises()->getBusinessPremisesName() == '26 Amesbury') {
                
                //Set the take array as the id = date and value = z reading
                $twenty_six_amesbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $twenty_six_amesbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();

               

            //If business premises is 28 amesbury
            } elseif($value->getBusinessPremises()->getBusinessPremisesName() == '28 Amesbury') {
                
                //Set the take array as the id = date and value = z reading
                $twenty_eight_amesbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $twenty_eight_amesbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();

                 

            //If business premises is westbury
            } elseif($value->getBusinessPremises()->getBusinessPremisesName() == 'Westbury') {
                
                //Set the take array as the id = date and value = z reading
                $westbury_take[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTotalZ();

                //Set the transactions array as the id = date and value = transactions
                $westbury_transactions[date_format($value->getDailyTake()->getDailyTakeDate(), "Y-m-d")] = $value->getTransactions();
                
                
            }
        }

        //Create the take chart arrays
        $twenty_six_amesbury_take_chart = [];
        $twenty_eight_amesbury_take_chart = [];
        $westbury_take_chart = [];
        $total_take_chart = [];

        //Create the transactions chart arrays
        $twenty_six_amesbury_transactions_chart = [];
        $twenty_eight_amesbury_transactions_chart = [];
        $westbury_transactions_chart = [];
        $total_transactions_chart = [];
        
        //Add values to the chart array by date.
        $loopdate = $default_start_date_obj;

        //Calculate the start date to be passed to the chart series array
        $jquery_start_date = $loopdate * 1000;

        //Set the take running total for each premises
        $twenty_six_amesbury_period_take_total = 0.00;
        $twenty_eight_amesbury_period_take_total = 0.00;
        $westbury_period_take_total = 0.00;

        //Set the transactions running total for each premises
        $twenty_six_amesbury_period_transactions_total = 0;
        $twenty_eight_amesbury_period_transactions_total = 0;
        $westbury_period_transactions_total = 0;

        //Set the transactions running total for each department
        $twenty_six_amesbury_period_department_total = [];
        $twenty_eight_amesbury_period_department_total = [];
        $westbury_period_department_total = [];
        $department_total = [];

        //Iterate through each day within the period selection.
        while ($loopdate <= $default_end_date_obj)
        {
            //Reset the daily totals 
            $daily_take_total = 0.00;
            $daily_transactions_total = 0;

            $idate = date("Y-m-d", $loopdate);
            //Check if value set.
            if (isset($twenty_six_amesbury_take[$idate]) && isset($twenty_six_amesbury_transactions[$idate])) {
                
                //Is set to add to array.
                array_push($twenty_six_amesbury_take_chart, $twenty_six_amesbury_take[$idate]); 
                array_push($twenty_six_amesbury_transactions_chart, $twenty_six_amesbury_transactions[$idate]);

                //Add to running daily take total 
                $daily_take_total = $daily_take_total + $twenty_six_amesbury_take[$idate];

                //Add to running daily transactions total 
                $daily_transactions_total = $daily_transactions_total + $twenty_six_amesbury_transactions[$idate];
           
                //Add to period business premises transactions total
                $twenty_six_amesbury_period_transactions_total =  $twenty_six_amesbury_period_transactions_total + $twenty_six_amesbury_transactions[$idate];

                //Add to period business premises take total
                $twenty_six_amesbury_period_take_total =  $twenty_six_amesbury_period_take_total + $twenty_six_amesbury_take[$idate];
                
            } else {
                //Is not set so add 0 to array.
                array_push($twenty_six_amesbury_take_chart, 0.00);
                array_push($twenty_six_amesbury_transactions_chart, 0);
              
            }

            //Check if value set.
            if (isset($twenty_eight_amesbury_take[$idate]) && isset($twenty_eight_amesbury_transactions[$idate])) {
                
                //Is set to add to array.
                array_push($twenty_eight_amesbury_take_chart, $twenty_eight_amesbury_take[$idate]); 
                array_push($twenty_eight_amesbury_transactions_chart, $twenty_eight_amesbury_transactions[$idate]);
                
                //Add to running daily take total
                $daily_take_total = $daily_take_total + $twenty_eight_amesbury_take[$idate];
                
                //Add to running daily transactions total 
                $daily_transactions_total = $daily_transactions_total + $twenty_eight_amesbury_transactions[$idate];
            
                //Add to period business premises transactions total
                $twenty_eight_amesbury_period_transactions_total =  $twenty_eight_amesbury_period_transactions_total + $twenty_eight_amesbury_transactions[$idate];

                //Add to period business premises take total
                $twenty_eight_amesbury_period_take_total =  $twenty_eight_amesbury_period_take_total + $twenty_eight_amesbury_take[$idate];
           
            } else {
                //Is not set so add 0 to array.
                array_push($twenty_eight_amesbury_take_chart, 0.00);
                array_push($twenty_eight_amesbury_transactions_chart, 0);
            }

            //Check if value set.
            if (isset($westbury_take[$idate]) && isset($westbury_transactions[$idate])) {
                //Is set to add to array.
                array_push($westbury_take_chart, $westbury_take[$idate]); 
                array_push($westbury_transactions_chart, $westbury_transactions[$idate]);

                //Add to running daily take total
                $daily_take_total = $daily_take_total + $westbury_take[$idate];

                //Add to running daily transactions total 
                $daily_transactions_total = $daily_transactions_total + $westbury_transactions[$idate];
                
                //Add to period business premises transactions total
                $westbury_period_transactions_total =  $westbury_period_transactions_total + $westbury_transactions[$idate];

                //Add to period business premises take total
                $westbury_period_take_total =  $westbury_period_take_total + $westbury_take[$idate];

            } else {
                //Is not set so add 0 to array.
                array_push($westbury_take_chart, 0.00);
                array_push($westbury_transactions_chart, 0);
            }

            //Update the total array.
            array_push($total_take_chart, $daily_take_total);
            array_push($total_transactions_chart, $daily_transactions_total);

            //Increment the time loop.
            $loopdate = strtotime( '+1 day', $loopdate );
                
        }

        //Set the totals for the time period selected.
        $period_take_total = array_sum($total_take_chart);

        //Set the totals for the time period selected.
        $period_transactions_total = array_sum($total_transactions_chart);

        //Check if take total and transactions are > 0
        if ($period_take_total > 0 && $period_transactions_total > 0) {
            //Calculate spend per transaction
            $period_spend_per_transaction = $period_take_total / $period_transactions_total;
        } else {
            $period_spend_per_transaction = null;
        }

        //Check if take total and transactions are > 0
        if ($twenty_six_amesbury_period_take_total > 0 && $twenty_six_amesbury_period_transactions_total > 0) {
            //Calculate spend per transaction
            $twenty_six_amesbury_period_spend_per_transaction = $twenty_six_amesbury_period_take_total / $twenty_six_amesbury_period_transactions_total;
        } else {
            $twenty_six_amesbury_period_spend_per_transaction = null;
        }

        //Check if take total and transactions are > 0
        if ($twenty_eight_amesbury_period_take_total > 0 && $twenty_eight_amesbury_period_transactions_total > 0) {
            //Calculate spend per transaction
            $twenty_eight_amesbury_period_spend_per_transaction = $twenty_eight_amesbury_period_take_total / $twenty_eight_amesbury_period_transactions_total;
        } else {
            $twenty_eight_amesbury_period_spend_per_transaction = null;
        }

        //Check if take total and transactions are > 0
        if ($westbury_period_take_total > 0 && $westbury_period_transactions_total > 0) {
            //Calculate spend per transaction
            $westbury_period_spend_per_transaction = $westbury_period_take_total / $westbury_period_transactions_total;
        } else {
            $westbury_period_spend_per_transaction = null;
        }

        //Take chart data
        $series = array(
            array("name" => "Total Take",    "data" => $total_take_chart, "color" => '#36456e', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
            array("name" => "26 Amesbury",    "data" => $twenty_six_amesbury_take_chart, "color" => '#dc961a', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
            array("name" => "28 Amesbury",    "data" => $twenty_eight_amesbury_take_chart, "color" => '#72ac24', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
            array("name" => "Westbury",    "data" => $westbury_take_chart, "color" => '#8c154d',  'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
        );

        //Set the X axis title 
        if (isset($current_month)) {
            $x_axis_title = "of " .$current_month;

        } else {
            $x_axis_title = "Date";
        }
        
        //Set up the graph for the overall daily take
        $ob = new Highchart();
        $ob->chart->renderTo('graph_container');  // The #id of the div where to render the chart
        $ob->title->text('Daily Takes for Period ');
        $ob->xAxis->title(array('text'  => $x_axis_title));
        $ob->xAxis->type("datetime");
        $ob->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob->yAxis->min(0);
        $ob->yAxis->title(array('text'  => "Daily Take Total"));
        $ob->yAxis->labels(array('format'  => "£{value}"));
        $ob->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob->tooltip->formatter($formatter);
        //Add the data
        $ob->series($series);

        //26 Amesbury take chart data
        $series_two = array(
            array("name" => "26 Amesbury",    "data" => $twenty_six_amesbury_take_chart, "color" => '#dc961a', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000), 
        );

        //Set up the graph for 26 amesbury take data
        $ob_two = new Highchart();
        $ob_two->chart->renderTo('twenty_six_graph_container');  // The #id of the div where to render the chart
        $ob_two->title->text('26 Amesbury Daily Takes for Period ');
        $ob_two->xAxis->title(array('text'  => $x_axis_title));
        $ob_two->xAxis->type("datetime");
        $ob_two->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob_two->yAxis->min(0);
        $ob_two->yAxis->title(array('text'  => "Daily Take Total"));
        $ob_two->yAxis->labels(array('format'  => "£{value}"));
        $ob_two->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob_two->tooltip->formatter($formatter);
        //Add the data
        $ob_two->series($series_two);

        //28 Amesbury take data
        $series_three = array(
            array("name" => "28 Amesbury",    "data" => $twenty_eight_amesbury_take_chart, "color" => '#72ac24', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
        );

        //Set up the graph for 28 Amesburuy take data
        $ob_three = new Highchart();
        $ob_three->chart->renderTo('twenty_eight_graph_container');  // The #id of the div where to render the chart
        $ob_three->title->text('28 Amesbury Daily Takes for Period ');
        $ob_three->xAxis->title(array('text'  => $x_axis_title));
        $ob_three->xAxis->type("datetime");
        $ob_three->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob_three->yAxis->min(0);
        $ob_three->yAxis->title(array('text'  => "Daily Take Total"));
        $ob_three->yAxis->labels(array('format'  => "£{value}"));
        $ob_three->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob_three->tooltip->formatter($formatter);
        //Add the data 
        $ob_three->series($series_three);

        //Westbury take chart data
        $series_four = array(
            array("name" => "Westbury",    "data" => $westbury_take_chart, "color" => '#8c154d', 'pointStart' => $jquery_start_date, 'pointInterval' => 86400000),
        );

        //Set up th westbury take chart
        $ob_four = new Highchart();
        $ob_four->chart->renderTo('westbury_graph_container');  // The #id of the div where to render the chart
        $ob_four->title->text('Westbury Daily Takes for Period ');
        $ob_four->xAxis->title(array('text'  => $x_axis_title));
        $ob_four->xAxis->type("datetime");
        $ob_four->xAxis->dateTimeLabelFormats(array('day' => '%e %b'));
        $ob_four->yAxis->min(0);
        $ob_four->yAxis->title(array('text'  => "Daily Take Total"));
        $ob_four->yAxis->labels(array('format'  => "£{value}"));
        $ob_four->xAxis->allowDecimals(false);
        $formatter = new Expr('function () {
                
                 return "<b>£" + this.y + "</b> ";
             }');
        $ob_four->tooltip->formatter($formatter);
        //Add the data
        $ob_four->series($series_four);

       
        return $this->render('MilesApartStaffBundle:Finances:analyse_finances.html.twig', array(
            'chart' => $ob, 
            'chart_two' => $ob_two,
            'chart_three' => $ob_three,
            'chart_four' => $ob_four,
           
            'daily_take' => $daily_take,
            'twenty_six_amesbury_take'=> $twenty_six_amesbury_take_chart,
            'twenty_eight_amesbury_take'=> $twenty_eight_amesbury_take_chart,
            'westbury_take'=> $westbury_take_chart,
            'total_take_chart' => $total_take_chart,
            
            'start_date' => $default_start_date,
            'end_date' => $default_end_date,
            
            'period_take_total' => $period_take_total,
            'period_transactions_total' => $period_transactions_total,
            'period_spend_per_transaction' => $period_spend_per_transaction,
            
            'twenty_six_amesbury_period_transactions_total' => $twenty_six_amesbury_period_transactions_total,
            'twenty_eight_amesbury_period_transactions_total' => $twenty_eight_amesbury_period_transactions_total,
            'westbury_period_transactions_total' => $westbury_period_transactions_total,
            
            'twenty_six_amesbury_period_take_total' => $twenty_six_amesbury_period_take_total,
            'twenty_eight_amesbury_period_take_total' => $twenty_eight_amesbury_period_take_total,
            'westbury_period_take_total' => $westbury_period_take_total,
            
            'twenty_six_amesbury_period_spend_per_transaction' => $twenty_six_amesbury_period_spend_per_transaction,
            'twenty_eight_amesbury_period_spend_per_transaction' => $twenty_eight_amesbury_period_spend_per_transaction,
            'westbury_period_spend_per_transaction' => $westbury_period_spend_per_transaction,
            
        ));

    }

}
