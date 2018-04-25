<?php
// src/MilesApart/StaffBundle/Controller/CustomersController.php

namespace MilesApart\StaffBundle\Controller;

use MilesApart\StaffBundle\Form\Customers\NewCustomerType;
use MilesApart\StaffBundle\Form\Customers\FindCustomerType;

use MilesApart\AdminBundle\Entity\Customer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;


class CustomersController extends Controller
{
    /*************************************************
    * Customers controller displays the functions and pages in customers menu area.
    *************************************************/

    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Customer Notifications", $this->get("router")->generate("staff-customers_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Customers:notifications.html.twig');
   
    }

    public function newcustomerAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Customer", $this->get("router")->generate("staff-customers_new-customer"));
        
        $entity = new Customer();
        $form   = $this->createCustomerForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Customers:new_customer.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
   
    }

    /**
    * Creates a new customer form.
    *
    * @param Customer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCustomerForm(Customer $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new NewCustomerType(), $entity, array(
            'action' => $this->generateUrl('staff-customers_create-new-customer'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Create Customer', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function createnewcustomerAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new Customer();
        $form = $this->createCustomerForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //Assign each submitted promotion
            foreach($form->get('promotion')->getData() as $promotion) {
                $promotion->setCampaign($entity);
                $entity->removePromotion($promotion);
                $entity->addPromotion($promotion); 
            }

            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New campaign has been created successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_new-campaign'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:new_campaign.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    public function viewcustomersAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Customers", $this->get("router")->generate("staff-customers_view-customers"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:Customer')->findAllCustomers();
        $customer_count = count($entities);

        //Create the form to add products.
        $entity = new Customer();
        $form = $this->createFindCustomerForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Customers:view_customers.html.twig', array(
            'entities' => $entities,
            'customer_count' => $customer_count,
            'submitted' => false,
            'form' => $form->createView(),
        ));
   
    }

    /**
    * Creates a form to find a Customer entity.
    *
    * @param Supplier $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createFindCustomerForm(Customer $entity)
    { 
        $form = $this->createForm(new FindCustomerType(), $entity, array(
            'action' => $this->generateUrl('staff-customers_view-customers-search'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        

        return $form;
    }

    public function viewcustomerssearchAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $customer_name = $response['customer_name'];
        $customer_email = $response['customer_email'];
        $business_name = $response['business_name'];

        $logger = $this->get('logger');
        $logger->info($customer_name);
        $logger->info($customer_email);
        $logger->info($business_name);

        //Get the suppliers that have missing data 
        $em = $this->getDoctrine()->getManager();

        $customers = $this->getDoctrine()
                     ->getRepository('MilesApartAdminBundle:Customer')
                     ->findByLetters($customer_name, $customer_email, $business_name);

        $customer_count = count($customers);
        //Render the page from template*/
        return $this->render('MilesApartStaffBundle:Customers:view_customers_search.html.twig', array(
            'entities' => $customers,
            'customer_count' => $customer_count,
            
            ));
   
    }

    public function viewcustomerAction($customer_id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Customers", $this->get("router")->generate("staff-customers_view-customers"));
        
        $breadcrumbs->addItem("View Customer", $this->get("router")->generate("staff-customers_view-customer", array('customer_id' => $customer_id)));
        
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('MilesApartAdminBundle:Customer')->findOneById($customer_id);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Customers:view_customer.html.twig', array(
            'customer' => $customer,
        ));
   
    }



    public function takecontactAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Take Contact", $this->get("router")->generate("staff-customers_take-contact"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Customers:take_contact.html.twig');
   
    }

    public function makecontactAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Make Contact", $this->get("router")->generate("staff-customers_make-contact"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Customers:make_contact.html.twig');
   
    }
}
