<?php
// src/MilesApart/StaffBundle/Controller/TransferRequestsController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


use MilesApart\AdminBundle\Entity\Product;

use MilesApart\AdminBundle\Entity\ProductPrice;
use MilesApart\AdminBundle\Entity\ProductSupplier;
use MilesApart\AdminBundle\Entity\TransferRequest;
use MilesApart\AdminBundle\Entity\ProductTransferRequest;
use MilesApart\AdminBundle\Entity\BusinessPremises;
use MilesApart\StaffBundle\Form\TransferRequests\TransferRequestType;
use MilesApart\StaffBundle\Form\TransferRequests\ProductTransferRequestType;


class TransferRequestsController extends Controller
{
    /*************************************************
    * Transfer requests controller displays the functions and pages in transfer requests menu area.
    *************************************************/

    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Transfer Request Notifications", $this->get("router")->generate("staff-transfer-requests_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:TransferRequests:notifications.html.twig');
   
    }

     public function requestproductsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
       // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Request Products Premises Select", $this->get("router")->generate("staff-transfer-requests_request-products"));
        
        //Get business premises (retail) from db and send to the page.
        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();
        $business_premises = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->findBy(array('business_premises_type' => 1));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:TransferRequests:request_products_business_premises_select.html.twig', array(
            'business_premises' => $business_premises,

            ));
   
    }

    public function requestproductsbusinesspremisesselectedAction($business_premises_slug) 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Request Products Premises Select", $this->get("router")->generate("staff-transfer-requests_request-products"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Request Products", $this->get("router")->generate("staff-transfer-requests_request-products-business-premises-selected", array ('business_premises_slug'=> $business_premises_slug )));

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();

        //Get the business premises
        $business_premises = new BusinessPremises(); 
        $business_premises = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->findOneBy(array('business_premises_slug'=>$business_premises_slug));

        //Check if transfer request exists, if not create one.
        $existing_transfer_request = $em->getRepository('MilesApartAdminBundle:TransferRequest')->findOneBy(array('business_premises'=>$business_premises, 'transfer_request_state'=>1));

        

        //Create new transfer request
        if ($existing_transfer_request == null) {

            //Create new transfer request.
            $transfer_request = new TransferRequest();

            //Get the business premises and request states from data.
            //$business_premises = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->findOneById(3);
            $transfer_request_state = $em->getRepository('MilesApartAdminBundle:TransferRequestState')->findOneById(1);
            
            $transfer_request->setBusinessPremises($business_premises);
            $transfer_request->setTransferRequestState($transfer_request_state);
            //Persist row to the database.
            $em->persist($transfer_request);
            $em->flush();
        
        } else {
            $existing_transfer_request = $em->merge($existing_transfer_request);
            //Add to existing transfer request
            $transfer_request = $existing_transfer_request;
        }

        //Save the transfer request in the session so it's available for adding product transfer request.
        $session = new Session();
        $session->set('transfer_request', $transfer_request);

        //Create the form to add products.
        $entity = new Product();
        $form = $this->createTransferRequestProductForm($entity);

        //If they do exists, populate a table with their details
        return $this->render('MilesApartStaffBundle:TransferRequests:request_products.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
            'transfer_request'=> $transfer_request,
            'business_premises' => $business_premises,
            'transfer_request_id' => $transfer_request->getId(),
            
        ));
    }

    /**
    * Creates a form to create a new product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createTransferRequestProductForm(Product $entity)
    {
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(new ProductTransferRequestType($em), $entity, array(
            'action' => $this->generateUrl('staff-transfer-requests_request-products-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal add_product_to_list_form')
        ));

       
        $form->add('submit', 'submit', array('label' => 'Add', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }

    public function requestproductssubmitAction(Request $request) 
    {
        //Set the function variable.
        $function_variable = "TransferRequest";
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

       $barcode = $response["barcode"];
       $search_query = $response["search_query"];
       
       $response = $this->forward('MilesApartStaffBundle:Helper:addProductToList', array(
        'function_variable'  => $function_variable,
        'barcode' => $barcode,
        'search_query' => $search_query,
        ));
       

        
        return $response;
         
    }

    public function productsearchAction(Request $request) 
    {
        $string = $this->getRequest()->request->get('searchText');
        //$string = "alfa";
        $products = $this->getDoctrine()
                     ->getRepository('MilesApartAdminBundle:Product')
                     ->findByLetters($string);

        //return users on json format
        //Create array of products
        $jsonContentArray = array('products' => []);

        foreach ($products as $key => $value) {
            $jsonContent = array(
                'product_name' => $value->getProductName(), 
                'product_price'=> $value->getCurrentPrice(),
                'product_supplier_code'=> $value->getProductSupplierCode(),
                'product_barcode' => $value->getProductBarcode(),
                'supplier_name' => $value->getDefaultProductSupplier(),            
                'product_id' => $value->getId(),
            );

            array_push($jsonContentArray['products'], $jsonContent);
        }
        
       

        $response = new JsonResponse($jsonContentArray);
        return $response;
    }

    public function requestproductsmultipleproductsselectsubmitAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('I just got the logger tr 0');
        //Set the function variable
        $function_variable = "TransferRequest";

        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }
        $logger->info('I just got the logger tr 1');
       $selection_string = $response["selected_string"];
       $logger->info('I just got the logger tr 2');
       $response = $this->forward('MilesApartStaffBundle:Helper:addproducttolistmultipleproductsselectsubmit', array(
        'function_variable'  => $function_variable,
        'selection_string' => $selection_string,
       
        ));
$logger->info('I just got the logger tr 3');
       return $response;
$logger->info('I just got the logger tr 4');
    }

    public function submittransferrequestAction($transfer_request_id) 
    {
        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $transfer_request = $em->getRepository('MilesApartAdminBundle:TransferRequest')->findOneBy(array('id' => $transfer_request_id));

        $transfer_request_state = $em->getRepository('MilesApartAdminBundle:TransferRequestState')->findOneBy(array('id' => 2));

        //Set the product
        $transfer_request->setTransferRequestState($transfer_request_state);
        
        

        //Persist row to the database.
        $em->persist($transfer_request);
        $em->flush();

        //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The transfer request has been submitted successfully.');

        $business_premises = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->findBy(array('business_premises_type' => 1));

        //Create and send the email.
        $email_send = $this->sendTransferRequestEmail($transfer_request);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:TransferRequests:request_products_business_premises_select.html.twig', array(
            'business_premises' => $business_premises,

            ));
    }

    function sendTransferRequestEmail($transfer_request)
    {
        //Set up the mailer
        $mailer = $this->container->get('swiftmailer.mailer.system_mailer');

        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('New Transfer Request - ' . $transfer_request->getBusinessPremises()->getBusinessPremisesName())
            ->setFrom(array('admin@miles-apart.biz' => 'Miles Apart'))
            ->setTo('rossmintymurray@icloud.com')
            ->setBody(
                $this->renderView(
                    'MilesApartStaffBundle:TransferRequests:new_transfer_request_email.html.twig',
                    array('transfer_request' => $transfer_request)
                )
            )
        ;

        //Avoid localhost issues
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        $mailer->send($message);

        return true;
    }

    public function viewrequestdetailsAction($transfer_request_id)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Requests", $this->get("router")->generate("staff-transfer-requests_view-requests"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Request Details", $this->get("router")->generate("staff-transfer-requests_view-request-details", array ('transfer_request_id'=> $transfer_request_id )));

        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $transfer_request = $em->getRepository('MilesApartAdminBundle:TransferRequest')->findOneById($transfer_request_id);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:TransferRequests:view_request_details.html.twig', array(
            'transfer_request' => $transfer_request,
            ));
    }

     public function requestproductsnewqtyAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Set the new qty and the id of the product transfer request
        $new_qty = $response["new_qty"];
        $product_transfer_request_id = $response["product_id"];

        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $product_transfer_request = $em->getRepository('MilesApartAdminBundle:ProductTransferRequest')->findOneBy(array('id' => $product_transfer_request_id));

        //Set the product
        $product_transfer_request->setProductTransferRequestQty($new_qty);

        //Persist row to the database.
        $em->persist($product_transfer_request);
        $em->flush();

        $response = array("success" => true);
        return new JsonResponse(
                    $response 
                );
    }

     public function requestproductsnewproductAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $em = $this->getDoctrine()->getManager();

        $new_product_name = $response["new_product_name"];
        $new_product_barcode = $response["new_product_barcode"];
        $new_product_transfer_qty = $response["new_product_qty"];
        $new_product_transfer_product_supplier_code = $response["new_product_supplier_code"];
        $new_product_transfer_product_price = $response["new_product_price"];
        $new_product_supplier_id = $response["new_product_supplier_id"];
        
        //Get the supplier
        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneBy(array('id' => $new_product_supplier_id));

        //Set the session for next add.
        $session = new Session();
        $session->set('new_product_supplier', $supplier);

        //Create new product in the database with the product name and barcode.
        $product = new Product();

        $product->setProductName($new_product_name);
        $product->setProductBarcode($new_product_barcode);
        $product->setProductSupplierCode($new_product_transfer_product_supplier_code);

        if ($new_product_transfer_product_price) {
            $product_price = new ProductPrice();
         
            $product_price->setProductPriceValue($new_product_transfer_product_price);
            $product_price->setProduct($product);
            $product_price->setProductPriceValidFrom(new \DateTime());
            $em->persist($product_price);
             
        
            $product->addProductPrice($product_price);
         
        }

        $product_supplier = new ProductSupplier();
        $product_supplier->setDefaultSupplier(true);
        $product_supplier->setSupplier($supplier);
        $product_supplier->setProduct($product);

        $em->persist($product);
        $em->persist($product_supplier);
        //Get transfer request
        
        $transfer_request_id = $session->get('transfer_request');

        //Get transfer request by id
        $transfer_request = $em->getRepository('MilesApartAdminBundle:TransferRequest')->findOneBy(array('id' => $transfer_request_id));

        //Then create new product transfer request, adding the quantity
        $product_transfer_request = new ProductTransferRequest();

        $product_transfer_request->setProduct($product);
        $product_transfer_request->setTransferRequest($transfer_request);
        $product_transfer_request->setProductTransferRequestQty($new_product_transfer_qty);

        $em->persist($product_transfer_request);
        //Persist the database

        
        $em->flush();

        //Then return success.

        $response = array(
                    'product_name' => $product_transfer_request->getProduct()->getProductName(), 
                    'product_stock_qty'=>'NA', 
                    'product_price'=>$product_transfer_request->getProduct()->getCurrentPrice(), 
                    'product_supplier_code'=> $product_transfer_request->getProduct()->getProductSupplierCode(),
                    'product_barcode' => $product_transfer_request->getProduct()->getProductBarcode(),
                    'supplier_name' => $product_transfer_request->getProduct()->getDefaultProductSupplier(),
                    'product_qty'=> $product_transfer_request->getProductTransferRequestQty(), 
                    'product_id' => $product_transfer_request->getId(),
                    'success'=> true);


                return new JsonResponse(
                    $response 
                );


            
    }

    public function addproductgrouptotransferrequestAction() 
    {

    }

    public function viewrequestsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Requests", $this->get("router")->generate("staff-transfer-requests_view-requests"));
        
        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $outstanding_transfer_requests = $em->getRepository('MilesApartAdminBundle:TransferRequest')->findBy(array('transfer_request_state' => 2));
        $incomplete_transfer_requests = $em->getRepository('MilesApartAdminBundle:TransferRequest')->findBy(array('transfer_request_state' => 1));
        $completed_transfer_requests = $em->getRepository('MilesApartAdminBundle:TransferRequest')->findBy(array('transfer_request_state' => 4));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:TransferRequests:view_requests.html.twig', array(
            'outstanding_transfer_requests' => $outstanding_transfer_requests,
            'incomplete_transfer_requests' => $incomplete_transfer_requests,
            'completed_transfer_requests' => $completed_transfer_requests,
            ));
   
    }

}
