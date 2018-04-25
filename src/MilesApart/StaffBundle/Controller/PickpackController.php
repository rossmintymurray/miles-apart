<?php
// src/MilesApart/StaffBundle/Controller/ProductsController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use MilesApart\AdminBundle\Entity\CustomerOrder;
use MilesApart\AdminBundle\Entity\Product;
use MilesApart\StaffBundle\Form\Pickpack\PickType;
use MilesApart\StaffBundle\Form\Pickpack\PickProductType;
use MilesApart\StaffBundle\Form\Pickpack\PackType;
use MilesApart\StaffBundle\Form\Pickpack\PackProductType;
use MilesApart\StaffBundle\Form\Pickpack\FindOrderType;
use Symfony\Component\HttpFoundation\JsonResponse;

class PickpackController extends Controller
{
    /*************************************************
    * Pickpack controller displays the functions and pages in pickpack menu area.
    *************************************************/

    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));
        
        //Get orders from db
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findBy(array('customer_order_state' => 2));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:notifications.html.twig', array(
            'entities' => $entities,
            ));
   
    }

    public function findorderAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Find Order", $this->get("router")->generate("staff-pickpack_find-order"));

        //Create the form for scanning order id barcode.
        $customer_order = new CustomerOrder();
        $form = $this->createFindCustomerOrderForm($customer_order);
      
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:find_order.html.twig', array(
            'submitted' => false,   
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to find order by id.
    *
    * @param CustomerOrderType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createFindCustomerOrderForm(CustomerOrder $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new FindOrderType(), $entity, array(
            'action' => $this->generateUrl('staff-pickpack_find-order-form-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Find order', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-3')));

        return $form;
    
    }

    public function findordersubmitAction(Request $request) 
    {
        
        //Create the form for scanning order id barcode.
        $customer_order = new CustomerOrder();
        $form = $this->createFindCustomerOrderForm($customer_order);

        $form->handleRequest($request);

        if ($form->isValid()) {
            //Get the order from the database
            //Get orders from db
            $em = $this->getDoctrine()->getManager();

            $order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($form->get('id')->getData());


            //Set up breadcrumb.
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            // Add home to breadcrumb.
            $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
            // Add pick & pack to breadcrumb.
            $breadcrumbs->addItem("Find Order", $this->get("router")->generate("staff-pickpack_find-order"));
            $breadcrumbs->addItem("Order " . $order->getId(), $this->get("router")->generate("staff-pickpack_find-order"));

        
            //Render the page from template
            return $this->render('MilesApartStaffBundle:Pickpack:customer_order_details.html.twig', array(
                'order' => $order,
            ));

        } else {
            //Render the page from template
            return $this->render('MilesApartStaffBundle:Pickpack:find_order.html.twig', array(
                'submitted' => true,
                'form'   => $form->createView(),
            ));
        }
   
    }


    public function pickAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick", $this->get("router")->generate("staff-pickpack_pick"));

        //Create the form for scanning order id barcode.
        $customer_order = new CustomerOrder();
        $form = $this->createCustomerOrderPickForm($customer_order);


        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:pick.html.twig', array(
            'submitted' => false,
            
            'form'   => $form->createView(),
        ));
   
    }

    public function pickformsubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick", $this->get("router")->generate("staff-pickpack_pick"));

        //Create the form for scanning order id barcode.
        $customer_order = new CustomerOrder();
        $form = $this->createCustomerOrderPickForm($customer_order);

        $form->handleRequest($request);

        if ($form->isValid()) {
            //Get the order from the database
            //Get orders from db
            $em = $this->getDoctrine()->getManager();

            $order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($form->get('id')->getData());
            
            //Create the product barcode form 
            $product = new Product();
            $product_form = $this->createCustomerOrderPickProductForm($product);

        
            //Render the page from template
            return $this->render('MilesApartStaffBundle:Pickpack:pick_order_details.html.twig', array(
                'form'   => $product_form->createView(),
                'order' => $order,
            ));

        } else {
            //Render the page from template
            return $this->render('MilesApartStaffBundle:Pickpack:pick.html.twig', array(
                'submitted' => true,
                'form'   => $form->createView(),
            ));
        }
   
    }

    public function processpickproductAction(Request $request) 
    {

        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Get the barcode and search query from the request
        $barcode = $response["barcode"];
        $order_id = $response["order_id"];

        //Get the product
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_barcode'=> $barcode));

        //Check if it exists in the order (get the order product)
        $order_products = $em->getRepository('MilesApartAdminBundle:CustomerOrderProduct')->findBy(array('customer_order'=> $order_id));
        
        $match = false;
        foreach ($order_products as $key => $value) {
            //Match with product
            if ($value->getProduct() == $product) {
                $match = true;

                //Update the state of the order product
                $customer_order_product_state = $em->getRepository('MilesApartAdminBundle:CustomerOrderProductState')->findOneBy(array('id'=> 1));
                $value->setCustomerOrderProductState($customer_order_product_state);

                $em->flush($value);
                //Return success to Javascript
                $response = array(     
                            'success' => true, 
                           
                        );

                $response = new JsonResponse($response);
                return $response;
            }
        }

        //If match 
        if(!$match) {
            //Update the state of the order product

            //Return success to Javascript
            $response = array(     
                        'success' => false, 
                       
                    );

        $response = new JsonResponse($response);
        return $response;

        }
        
   
    }

    public function completeorderpickAction($order_id) 
    {

        //Get the order
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($order_id);

        //Check that remaining products is 0.
        if($order->getOrderRemainingPick() == 0) {

            //Update the state of the order
            $customer_order_state = $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(array('id'=> 4));
            $order->setCustomerOrderState($customer_order_state);

            $em->flush();
        }
        
        //Redirect to the pick choose order page.
        return $this->redirect($this->generateUrl('staff-pickpack_pick'));
        
   
    }

   
    /**
    * Creates a form to find order by barcode.
    *
    * @param CustomerOrderType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCustomerOrderPickForm(CustomerOrder $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PickType(), $entity, array(
            'action' => $this->generateUrl('staff-pickpack_pick-form-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Find order', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-3')));

        return $form;
    
    }

    /**
    * Creates a form to find product by barcode.
    *
    * @param ProductType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCustomerOrderPickProductForm(Product $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PickProductType(), $entity, array(
            'action' => $this->generateUrl('staff-pickpack_pick-product-form-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Pick product', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-3')));

        return $form;
    
    }

    public function packAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pack", $this->get("router")->generate("staff-pickpack_pack"));
        
        //Create the form for scanning order id barcode.
        $customer_order = new CustomerOrder();
        $form = $this->createCustomerOrderPackForm($customer_order);


        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:pack.html.twig', array(
            'submitted' => false,
            
            'form'   => $form->createView(),
        ));
   
    }


    public function packformsubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pack", $this->get("router")->generate("staff-pickpack_pack"));
        // Add pack order to breadcrumb.
        $breadcrumbs->addItem("Pack Order", $this->get("router")->generate("staff-pickpack_pack-form-submit"));

        //Create the form for scanning order id barcode.
        $customer_order = new CustomerOrder();
        $form = $this->createCustomerOrderPackForm($customer_order);

        $form->handleRequest($request);

        if ($form->isValid()) {
            //Get the order from the database
            //Get orders from db
            $em = $this->getDoctrine()->getManager();

            $order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($form->get('id')->getData());
            
            //Create the product barcode form 
            $product = new Product();
            $product_form = $this->createCustomerOrderPackProductForm($product);

        
            //Render the page from template
            return $this->render('MilesApartStaffBundle:Pickpack:pack_order_details.html.twig', array(
                'form'   => $product_form->createView(),
                'order' => $order,
            ));

        } else {
            //Render the page from template
            return $this->render('MilesApartStaffBundle:Pickpack:pack.html.twig', array(
                'submitted' => true,
                'form'   => $form->createView(),
            ));
        }
   
    }

    public function processpackproductAction(Request $request) 
    {

        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Get the barcode and search query from the request
        $barcode = $response["barcode"];
        $order_id = $response["order_id"];

        //Get the product
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_barcode'=> $barcode));

        //Check if it exists in the order (get the order product)
        $order_products = $em->getRepository('MilesApartAdminBundle:CustomerOrderProduct')->findBy(array('customer_order'=> $order_id));
        
        $match = false;
        foreach ($order_products as $key => $value) {
            //Match with product
            if ($value->getProduct() == $product) {
                $match = true;

                //Update the state of the order product
                $customer_order_product_state = $em->getRepository('MilesApartAdminBundle:CustomerOrderProductState')->findOneBy(array('id'=> 2));
                $value->setCustomerOrderProductState($customer_order_product_state);

                $em->flush($value);
                //Return success to Javascript
                $response = array(     
                            'success' => true, 
                           
                        );

                $response = new JsonResponse($response);
                return $response;
            }
        }

        //If match 
        if(!$match) {
            //Update the state of the order product

            //Return success to Javascript
            $response = array(     
                        'success' => false, 
                       
                    );

        $response = new JsonResponse($response);
        return $response;

        }
        
   
    }

    public function completeorderpackAction($order_id) 
    {
        //Get the order
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($order_id);

        //Check that remaining products is 0.
        if($order->getOrderRemainingPack() == 0) {

            //Update the state of the order
            $customer_order_state = $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(array('id'=> 5));
            $order->setCustomerOrderState($customer_order_state);

            $em->flush();
        }
        //Redirect to the pack choose order page.
        return $this->redirect($this->generateUrl('staff-pickpack_pack'));
    }


    /**
    * Creates a form to find order by barcode.
    *
    * @param CustomerOrderType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCustomerOrderPackForm(CustomerOrder $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PackType(), $entity, array(
            'action' => $this->generateUrl('staff-pickpack_pack-form-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Find order', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-3')));

        return $form;
    
    }

    /**
    * Creates a form to find product by barcode.
    *
    * @param ProductType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCustomerOrderPackProductForm(Product $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PackProductType(), $entity, array(
            'action' => $this->generateUrl('staff-pickpack_pack-product-form-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Pack product', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-3')));

        return $form;
    
    }

    public function recallAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Recall", $this->get("router")->generate("staff-pickpack_recall"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:recall.html.twig');
    }

    public function printpackingslipsAction($customer_order_id) 
    {

        $em = $this->getDoctrine()->getManager();

        //Check if order id exists
        if($customer_order_id != null) {
            //Just get the order that matches the id
            //Get orders from db
            $entities = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findBy(array('id' => $customer_order_id));
        } else {
            //Get all orders that have not been printed
            $entities = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findBy(array('customer_order_state' => 2));
        }

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:print_packing_slips.html.twig', array(
            'entities' => $entities,
            ));

    }

    public function printinvoicesAction($customer_order_id) 
    {

        $em = $this->getDoctrine()->getManager();

        //Check if order id exists
        if($customer_order_id != null) {
            //Just get the order that matches the id
            //Get orders from db
            $entities = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findBy(array('id' => $customer_order_id));
        } else {
            //Get all orders that have not been printed that are need to have invoices printed.
            $entities = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findInvoicesToPrint();
        }

        //Get the VAT rate for each order 
        $vat_rate_for_orders = array();
        foreach($entities as $customer_order) {
            //Get the VAT rate service so we can calculate vat rates for the date of the order
            $get_vat_service = $this->get('staff.get_vat_at_date');
            $vat_multiplier = $get_vat_service->getVATRate($customer_order->getCustomerOrderDateCreated());
            
            //Add to an array to pass to the invoices page
            $vat_rate_for_orders[$customer_order->getId()] = $vat_multiplier;
        }

        //Get the array for splitting up pages
        $page_array = $this->getPageArrayForInvoices($entities);
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:print_invoices.html.twig', array(
            'entities' => $entities,
            'page_array' => $page_array,
            'vat_rate_for_orders' => $vat_rate_for_orders
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

    public function updateorderstateprintedAction(Request $request)
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Get the barcode and search query from the request
        $order_id_array = $response["orderIdArray"];

        //Get the product
        $em = $this->getDoctrine()->getManager();

        //For each order id, update the order to printed
        foreach($order_id_array as $order_id) {
            $customer_order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($order_id);

            $customer_order->setCustomerOrderState(
                $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                array( 'id' => 3 )
            ));

            //Persist the change
            $em->persist($customer_order);
        }

        //Flush the db changes
        $em->flush();
        

        //Return success to the JS 
        $response = array(     
            'success' => true, 
        );

        $response = new JsonResponse($response);
        return $response;

        
    } 
    
}













