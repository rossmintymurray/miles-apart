<?php
// src/MilesApart/PublicBundle/Controller/PageController.php

namespace MilesApart\PublicUserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MilesApart\AdminBundle\Entity\PersonalCustomer;
use MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative;
use MilesApart\AdminBundle\Entity\BusinessCustomer;
use MilesApart\AdminBundle\Entity\Customer;
use MilesApart\AdminBundle\Entity\CustomerAddress;
use MilesApart\AdminBundle\Entity\ReturnedProduct;


use MilesApart\PublicBundle\Form\FeedbackFormType;
use MilesApart\PublicBundle\Form\ContactUsMessageType;
use MilesApart\PublicBundle\Form\SignInFormType;
use MilesApart\PublicBundle\Form\PersonalCustomerRegistrationFormType;
use MilesApart\PublicUserBundle\Form\Type\EditBusinessCustomerType;
use MilesApart\PublicUserBundle\Form\Type\EditPersonalCustomerType;
use MilesApart\PublicUserBundle\Form\Type\ProductReturnType;

use MilesApart\PublicUserBundle\Form\Type\NewAddressType;

class UserController extends Controller
{
    public function currentordersAction($page=null)
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->get("router")->generate("fos_user_profile_show"));
        $breadcrumbs->addItem("Current orders", $this->get("router")->generate("miles_apart_public_user_bundle_current_orders"));

        //Set results as an arry to be sorted
        $current_orders = $user->getCustomer()->getCurrentCustomerOrder();
        
        //Check if there are orders
        if(count($current_orders) > 0) {
            //Reorder array
            array_multisort($current_orders, SORT_DESC);

            //Set up pagerfanta
            $adapter = new ArrayAdapter($current_orders);
            
            //Pass adapter to pagerfanta
            $pager =  new Pagerfanta($adapter);
            //Set the number of results
            $pager->setMaxPerPage(20);

            //Set current page if not set
            if (!$page)    
                $page = 1;
                try  {
                    $pager->setCurrentPage($page);
                }
                catch(NotValidCurrentPageException $e) {
                  throw new NotFoundHttpException('Illegal page');
                }
        } else {
            $pager = FALSE;
        }

        return $this->render('MilesApartPublicUserBundle:Profile:current_orders.html.twig', array(
            'pager' => $pager,
            'user' => $user
        ));
    }

    public function vieworderAction($customer_order_id)
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();
        
        //Get the customer order 
        $customer_order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($customer_order_id);
          
        //Check that the user that ordered the item is the one trying to view it.
        if($user->getCustomer()->getId() != $customer_order->getCustomer()->getId()){

            //Not the same customer to redirect to profile homepage & set flash bag message
            $customer_order = false;
            
            $this->get('session')->getFlashBag()->set('public-error', 'The order id does match any orders you have placed. Please try again.');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->get("router")->generate("fos_user_profile_show"));
        
        //Check if user came from previous orders or current orders
        if($customer_order->getCustomerOrderState()->getId() < 8) {
            $breadcrumbs->addItem("Current orders", $this->get("router")->generate("miles_apart_public_user_bundle_current_orders"));
        } else {
            $breadcrumbs->addItem("Previous orders", $this->get("router")->generate("miles_apart_public_user_bundle_previous_orders"));  
        }
        $breadcrumbs->addItem("View order", $this->get("router")->generate("miles_apart_public_user_bundle_view_order", array('customer_order_id' => $customer_order_id)));

        return $this->render('MilesApartPublicUserBundle:Profile:view_order.html.twig', array(
            'customer_order' => $customer_order,
        ));
    }

    public function previousordersAction($page=null)
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->get("router")->generate("fos_user_profile_show"));
        $breadcrumbs->addItem("Prevous orders", $this->get("router")->generate("miles_apart_public_user_bundle_previous_orders"));

        //Set results as an arry to be sorted
        $previous_orders = $user->getCustomer()->getPreviousCustomerOrder();
        
        //Check if there are orders
        if(count($previous_orders) > 0) {
            //Reorder array
            array_multisort($previous_orders, SORT_DESC);
        
            //Set up pagerfanta
            $adapter = new ArrayAdapter($previous_orders);
            
            //Pass adapter to pagerfanta
            $pager =  new Pagerfanta($adapter);
            //Set the number of results
            $pager->setMaxPerPage(20);

            //Set current page if not set
            if (!$page)    
                $page = 1;
                try  {
                    $pager->setCurrentPage($page);
                }
                catch(NotValidCurrentPageException $e) {
                  throw new NotFoundHttpException('Illegal page');
                }

        } else {
            $pager = FALSE;
        }
        
        return $this->render('MilesApartPublicUserBundle:Profile:previous_orders.html.twig', array(
            'pager' => $pager,
            'user' => $user,
        ));
    }

    public function returnsAction($page=null)
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->get("router")->generate("fos_user_profile_show"));
        $breadcrumbs->addItem("Returns", $this->get("router")->generate("miles_apart_public_user_bundle_returns"));

        //Get the reurns from the databse
        $em = $this->container->get('doctrine')->getManager();

        $returns = $em->getRepository('MilesApartAdminBundle:ReturnedProduct')->findReturnedProductByCustomerId($user->getCustomer()->getId());
        
        //Set up pagerfanta
        $adapter = new ArrayAdapter($returns);
        
        //Pass adapter to pagerfanta
        $pager =  new Pagerfanta($adapter);
        //Set the number of results
        $pager->setMaxPerPage(20);

        //Set current page if not set
        if (!$page)    
            $page = 1;
            try  {
                $pager->setCurrentPage($page);
            }
            catch(NotValidCurrentPageException $e) {
              throw new NotFoundHttpException('Illegal page');
            }

        return $this->render('MilesApartPublicUserBundle:Profile:returns.html.twig', array(
            'pager' => $pager
        ));
    }


    public function productreturnAction($customer_order_product_id)
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();
        
        //Get the customer order 
        $customer_order_product = $em->getRepository('MilesApartAdminBundle:CustomerOrderProduct')->findOneById($customer_order_product_id);

        //Check that the user that ordered the item is the one trying to view it.
        if($user->getCustomer()->getId() != $customer_order_product->getCustomerOrder()->getCustomer()->getId()){

            //Not the same customer to redirect to profile homepage & set flash bag message
            $customer_order_product = false;
            
            $this->get('session')->getFlashBag()->set('public-error', 'The order id does match any orders you have placed. Please try again.');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        //Check that the quantity able to return is higher than 0.
        if($customer_order_product->getCustomerOrderProductReturnRemaining() < 1) {
            //Return the product to fos user homepage nd show flashbag
            $this->get('session')->getFlashBag()->set('public-error', 'The product you are trying to return cannot be processed as it has already been returned.');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        } 

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->get("router")->generate("fos_user_profile_show"));
        
        //Check if user came from previous orders or current orders
        if($customer_order_product->getCustomerOrder()->getCustomerOrderState()->getId() < 8) {
            $breadcrumbs->addItem("Current orders", $this->get("router")->generate("miles_apart_public_user_bundle_current_orders"));
        } else {
            $breadcrumbs->addItem("Previous orders", $this->get("router")->generate("miles_apart_public_user_bundle_previous_orders"));  
        }
        
        $breadcrumbs->addItem("View order", $this->get("router")->generate("miles_apart_public_user_bundle_view_order", array('customer_order_id' => $customer_order_product->getCustomerOrder()->getId())));
        $breadcrumbs->addItem("Product Return", $this->get("router")->generate("miles_apart_public_user_bundle_product_return", array('customer_order_product_id' => $customer_order_product_id)));

        //Get the returns from the databse
        $entity = new ReturnedProduct();
        $form = $this->createProductReturnForm($entity, $customer_order_product->getCustomerOrderProductReturnRemaining(), $customer_order_product_id);

        //Render the page from template
        return $this->render('MilesApartPublicUserBundle:Profile:product_return.html.twig', array(
            'user' => $user,
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    
    /**
    * Creates a product return form.
    *
    * @param ProductReturnType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createProductReturnForm(ReturnedProduct $entity, $max_products, $customer_order_product_id)
    {

        $form = $this->createForm(new ProductReturnType(), $entity, array(
            'action' => $this->generateUrl('miles_apart_public_user_bundle_product_return_submit', array('customer_order_product_id' => $customer_order_product_id)),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal'),
            'max' => $max_products
            
        ));

        $form->add('submit', 'submit', array('label' => 'Process return', 'attr' => array(
                        'class' => 'button  large-12')));

        return $form;
    }

    /**
     * Edits a personal customer
     *
     */
    public function productreturnsubmitAction(Request $request, $customer_order_product_id)
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();
        
        //Get the customer order 
        $customer_order_product = $em->getRepository('MilesApartAdminBundle:CustomerOrderProduct')->findOneById($customer_order_product_id);

        //Check that the user that ordered the item is the one trying to view it.
        if($user->getCustomer()->getId() != $customer_order_product->getCustomerOrder()->getCustomer()->getId()){

            //Not the same customer to redirect to profile homepage & set flash bag message
            $customer_order_product = false;
            
            $this->get('session')->getFlashBag()->set('public-error', 'The order id does match any orders you have placed. Please try again.');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }


        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->get("router")->generate("fos_user_profile_show"));
        //Check if user came from previous orders or current orders
        if($customer_order_product->getCustomerOrder()->getCustomerOrderState()->getId() < 8) {
            $breadcrumbs->addItem("Current orders", $this->get("router")->generate("miles_apart_public_user_bundle_current_orders"));
        } else {
            $breadcrumbs->addItem("Previous orders", $this->get("router")->generate("miles_apart_public_user_bundle_previous_orders"));  
        }
        
        $breadcrumbs->addItem("View order", $this->get("router")->generate("miles_apart_public_user_bundle_view_order", array('customer_order_id' => $customer_order_product->getCustomerOrder()->getId())));
        $breadcrumbs->addItem("Product Return", $this->get("router")->generate("miles_apart_public_user_bundle_product_return", array('customer_order_product_id' => $customer_order_product_id)));

        //Get the returns from the databse
        $entity = new ReturnedProduct();
        $entity->setCustomerOrderProduct($customer_order_product);
        $form = $this->createProductReturnForm($entity, $customer_order_product->getCustomerOrderProductReturnRemaining(), $customer_order_product_id);

        $form->handleRequest($request);

        if ($form->isValid()) {

            //Update the state of the customer order product
            $customer_order_product->setCustomerOrderProductState(
                $em->getRepository('MilesApartAdminBundle:CustomerOrderProductState')->findOneBy(
                array( 'id' => 4 ))
            );

            //Set the customer of the added address
            
            $em->persist($entity);
            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('public-success', 'Thank you. Your return has been requested. Please follow the instructions below to complete your return.');
           
            return $this->redirect($this->generateUrl('miles_apart_public_user_bundle_product_return_completion', array('customer_order_product_id' => $customer_order_product_id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('public-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartPublicUserBundle:Profile:product_return.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits a personal customer
     *
     */
    public function productreturncompletionAction($customer_order_product_id)
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();
        
        //Get the customer order 
        $customer_order_product = $em->getRepository('MilesApartAdminBundle:CustomerOrderProduct')->findOneById($customer_order_product_id);

        //Check that the user that ordered the item is the one trying to view it.
        if($user->getCustomer()->getId() != $customer_order_product->getCustomerOrder()->getCustomer()->getId()){

            //Not the same customer to redirect to profile homepage & set flash bag message
            $customer_order_product = false;
            
            $this->get('session')->getFlashBag()->set('public-error', 'The order id does match any orders you have placed. Please try again.');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }


        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->get("router")->generate("fos_user_profile_show"));
        //Check if user came from previous orders or current orders
        if($customer_order_product->getCustomerOrder()->getCustomerOrderState()->getId() < 8) {
            $breadcrumbs->addItem("Current orders", $this->get("router")->generate("miles_apart_public_user_bundle_current_orders"));
        } else {
            $breadcrumbs->addItem("Previous orders", $this->get("router")->generate("miles_apart_public_user_bundle_previous_orders"));  
        }
        
        $breadcrumbs->addItem("View order", $this->get("router")->generate("miles_apart_public_user_bundle_view_order", array('customer_order_id' => $customer_order_product->getCustomerOrder()->getId())));
        $breadcrumbs->addItem("Product Return", $this->get("router")->generate("miles_apart_public_user_bundle_product_return", array('customer_order_product_id' => $customer_order_product_id)));

        //Render the page from template
        return $this->render('MilesApartPublicUserBundle:Profile:return_completion.html.twig', array(
            'customer_order_product' => $customer_order_product
            ));
    }

    public function addressesAction()
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->get("router")->generate("fos_user_profile_show"));
        $breadcrumbs->addItem("Addresses", $this->get("router")->generate("miles_apart_public_user_bundle_addresses"));

        $entity = new CustomerAddress();
        $form = $this->createNewAddressForm($entity);

        //Render the page from template
        return $this->render('MilesApartPublicUserBundle:Profile:addresses.html.twig', array(
            'user' => $user,
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a new address form.
    *
    * @param NewAddressType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createNewAddressForm(CustomerAddress $entity)
    {

        $form = $this->createForm(new NewAddressType(), $entity, array(
            'action' => $this->generateUrl('miles_apart_public_user_bundle_address_submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save address', 'attr' => array(
                        'class' => 'button  large-12')));

        return $form;
    }

    /**
     * Edits a personal customer
     *
     */
    public function adddresssubmitAction(Request $request)
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->get("router")->generate("fos_user_profile_show"));
        $breadcrumbs->addItem("Addresses", $this->get("router")->generate("miles_apart_public_user_bundle_addresses"));

        $entity = new CustomerAddress();
        $form = $this->createNewAddressForm($entity);

        $em = $this->getDoctrine()->getManager();

        $form = $this->createNewAddressForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            //Check if is billing is set
            if($entity->getCustomerAddressIsBilling() == TRUE) {

                //Check if any other customer addresses are billing and deactivate
                $existing_customer_addresses = $em->getRepository('MilesApartAdminBundle:CustomerAddress')->findBy(array('customer' => $user->getCustomer()));

                foreach($existing_customer_addresses as $existing_customer_address) {
                   
                    if($existing_customer_address->getCustomerAddressIsBilling() == TRUE) {
                      
                        $existing_customer_address->setCustomerAddressIsBilling(FALSE);
                    }
                }
            }

            //Set the customer of the added address
            $entity->setCustomer($user->getCustomer());
            $em->persist($entity);
            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('public-success', 'Thank you. The new address has been added.');
           
            return $this->redirect($this->generateUrl('miles_apart_public_user_bundle_addresses'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('public-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartPublicUserBundle:Profile:addresses.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    public function printinvoiceAction($customer_order_id) 
    {
        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();
        
        //Get the customer order 
        $customer_order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($customer_order_id);
          
        //Check that the user that ordered the item is the one trying to view it.
        if($user->getCustomer()->getId() != $customer_order->getCustomer()->getId()){

            //Not the same customer to redirect to profile homepage & set flash bag message
            $customer_order = false;
            
            $this->get('session')->getFlashBag()->set('public-error', 'The order id does match any orders you have placed. Please try again.');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        //Ensure that the order has been paid for
        if($customer_order->getCustomerOrderState()->getId() == 1) {
            
            $this->get('session')->getFlashBag()->set('public-error', 'The invoice for the order '. $customer_order_id. ' cannot be printed until it has been paid for.');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        } 

        //Get the VAT rate service so we can calculate vat rates for the date of the order
        $get_vat_service = $this->get('staff.get_vat_at_date');
        $vat_multiplier = $get_vat_service->getVATRate($customer_order->getCustomerOrderDateCreated());

        //Check if order id exists
        if($customer_order_id != null) {
            //Just get the order that matches the id
            //Get orders from db
            $entities = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findBy(array('id' => $customer_order_id));
        } 

        //Get the array for splitting up pages
        $page_array = $this->getPageArrayForInvoices($entities);
        
        //Render the page from template
        return $this->render('MilesApartPublicUserBundle:Profile:print_invoice.html.twig', array(
            'entities' => $entities,
            'page_array' => $page_array,
            'vat_multiplier' => $vat_multiplier
            ));

    }

    public function getPageArrayForInvoices($orders)
    {
        //Create the array that will define the number of products on each page
        $order_array = array();

        $logger = $this->get('logger');

        /*
        * Define the variables used to split the pages
        */
        $total_products_on_page_with_header = 23;
        $total_products_on_page_with_header_and_footer = 14;
        $total_products_on_page = 37;
        $total_products_on_page_with_footer = 27;


        //Iterate over the entities and find out how many products are in each order 
        foreach($orders as $key => $order) {
            $order_pages_array = array();
            
            $logger->info($order->getId());

            //Count the number of items in the order.
            $number_of_products = count($order->getCustomerOrderProduct());
            
            //Work out the pages 

            /* 
            * CASE 1 - All products will fit on first page
            */
            if($number_of_products <= $total_products_on_page_with_header) {
                
                /*
                * CASE 1.1 - The products and the totals will fit on the first page 
                */
                if($number_of_products <= $total_products_on_page_with_header_and_footer) {
                    
                    //Set the number per page
                    array_push($order_pages_array, $number_of_products);
                       
                    
                /*
                * CASE 1.2 - The products will fit on the first page but the totals and footer need to go on a new page
                */
                } else {
                    
                    //Set the number per page
                    array_push($order_pages_array, $number_of_products);
                    array_push($order_pages_array, "totals");
                    
                    
                }

            /*
            * CASE 2 - All products will fit on 2 pages
            */    
            } else if ($number_of_products <=  $total_products_on_page_with_header + $total_products_on_page) {

                /*
                * CASE 2.1 - The products and totals will fit on 2 pages
                */
                if($number_of_products <= $total_products_on_page_with_header + $total_products_on_page_with_footer) {
                    
                    //Set the number per page
                    array_push($order_pages_array, $total_products_on_page_with_header);
                    array_push($order_pages_array, $number_of_products - $total_products_on_page_with_header);
                   
                
                /*
                * CASE 2.2 - The products will fit on the first 2 pages but the totals will need a third page
                */
                } else {
                    
                    //Set the number per page
                    array_push($order_pages_array, $total_products_on_page_with_header);
                    array_push($order_pages_array, $total_products_on_page);
                    array_push($order_pages_array, "totals");
                    

                }
              
            /*
            * CASE 3 - All products will fit on 3 pages
            */    
            } else if ($number_of_products <= 98) {

                /*
                * CASE 3.1 - The products and totals will fit on 3 pages
                */
                if($number_of_products <= 84) {
                    
                    //Set the number per page
                    array_push($order_pages_array, 24);
                    array_push($order_pages_array, 37);
                    array_push($order_pages_array, $number_of_products - 24 - 37);

                
                /*
                * CASE 3.2 - The products will fit on the first 3 pages but the totals will need a forth page
                */
                } else {
                    
                    //Set the number per page
                    array_push($order_pages_array, 24);
                    array_push($order_pages_array, 37);
                    array_push($order_pages_array, 37);
                    array_push($order_pages_array, "totals");
                    
                }
              
            }

            //Add the page array to the order array
            array_push($order_array, $order_pages_array);
            
        }

        return $order_array;
    }

    public function updateaddressdefaultsAction(Request $request) 
    {
        $logger = $this->get('logger');
        
        $logger->info('I just got the logger add update price');

        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Check that the address belongs to the logged in user
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();


        $address_id = $response["addressId"];
        $address_type = $response["addressType"];
        
        //Get the address from the DB
        $address = $em->getRepository('MilesApartAdminBundle:CustomerAddress')->findOneById($address_id);
        
        //Check that the address belongs to the logged in user
        //Check if business of personal 
        if($user->getPersonalCustomer() != NULL) {
            $customer = $user->getPersonalCustomer()->getCustomer();
        } else {
            $customer = $user->getBusinessCustomerRepresentative()->getCustomer();
        }

        $logger->info('I just got the logger add update customer address start'); 
        if($address->getCustomer()->getId() == $customer->getId()) {

            $logger->info('I just got the logger add update customer ids match');

            //Update the address 
            if($address_type == "billing") {
                $logger->info('I just got the logger add update customer address is billing ');
                //Set all addresses for this user as not billing 
                foreach($customer->getCustomerAddress() as $key => $add ) {
                    $logger->info('I just got the logger add update billing is set in db ');
                    $add->setCustomerAddressIsBilling(FALSE);
                    $logger->info('I just got the logger add update price' . $key);
                }
                //Set selected address as billing 
                $address->setCustomerAddressIsBilling(TRUE);

            }

            //Update the address 
            if($address_type == "delivery") {
                //Set all addresses for this user as not billing 
                foreach($customer->getCustomerAddress() as $add) {
                    $add->setCustomerAddressDefaultDelivery(FALSE);
                }
                //Set selected address as billing 
                $address->setCustomerAddressDefaultDelivery(TRUE);

            }

            //Flush the changes
            $em->flush();
        }

        $response = array("success" => true);
        return new JsonResponse($response);
    }

    public function deleteaddressAction(Request $request) 
    {
        $logger = $this->get('logger');
        
        $logger->info('I just got the logger add update price');

        //Check that the user is logged in
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser();
        } else {
            //Redirect to homepage
            $user = false;
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Check that the address belongs to the logged in user
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();

        $address_id = $response["addressId"];
        
        //Get the address from the DB
        $address = $em->getRepository('MilesApartAdminBundle:CustomerAddress')->findOneById($address_id);
        $address->setCustomerAddressIsInactive(TRUE);
        $address->setCustomerAddressIsBilling(FALSE);
        $address->setCustomerAddressDefaultDelivery(FALSE);
        
        //Flush the changes
        $em->flush();
    

        $response = array("success" => true);
        return new JsonResponse($response);
    }

}
