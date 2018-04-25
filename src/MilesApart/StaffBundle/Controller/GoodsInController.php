<?php
// src/MilesApart/StaffBundle/Controller/GoodsInController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

use MilesApart\AdminBundle\Entity\SupplierDelivery;
use MilesApart\AdminBundle\Entity\SupplierDeliveryProduct;
use MilesApart\AdminBundle\Entity\StockLocationShelfProductSent;
use MilesApart\AdminBundle\Entity\ProductPrice;

use MilesApart\StaffBundle\Form\GoodsIn\NewDeliveryProductType;
use MilesApart\StaffBundle\Form\GoodsIn\NewDeliveryType;
use MilesApart\StaffBundle\Form\GoodsIn\BookInDeliveryType;
use MilesApart\StaffBundle\Form\GoodsIn\StoreDeliveryType;
use MilesApart\StaffBundle\Form\GoodsIn\ProcessSupplierDeliveryProductType;


class GoodsInController extends Controller
{
    /*************************************************
    * Categories controller displays the functions and pages in categories menu area.
    *************************************************/

    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Goods In Notifications", $this->get("router")->generate("staff-goods-in_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:GoodsIn:notifications.html.twig');
    }

    public function newdeliverynoteAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Delivery Note", $this->get("router")->generate("staff-goods-in_new-delivery-note"));

        $em = $this->getDoctrine()->getManager();
        $existing_supplier_deliveries = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findBy(array('delivered_datetime'=>null));
        
        $entity = new SupplierDelivery();

        //Set supplier delivery delivered date to today.
        $now = new \DateTime();
        $entity->setDeliveredDatetime($now);

        $form = $this->createCreateNewDeliveryNoteForm($entity);

        return $this->render('MilesApartStaffBundle:GoodsIn:new_delivery_note.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
            'existing_supplier_deliveries' => $existing_supplier_deliveries,
        ));

    }    

    public function newdeliverynotesubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Delivery Note", $this->get("router")->generate("staff-goods-in_new-delivery-note"));

        //Handle the form, add the new delivery to the databse, then get the id and forward to add product to delivery action
        $entity = new SupplierDelivery();

        $form = $this->createCreateNewDeliveryNoteForm($entity);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isValid()) {
            
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New delivery has been booked in successfully.');

            return $this->redirect($this->generateUrl('staff-goods-in_add-products-to-delivery-note', array('supplier_delivery_id' => $entity->getId())));
        } else {
            $existing_supplier_deliveries = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findBy(array('time'=>null));
        
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:GoodsIn:new_delivery_note.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
            'existing_supplier_deliveries' => $existing_supplier_deliveries,
        ));
    }

       

    

    /**
    * Creates a form to create a new supplier product.
    *
    * @param SupplierProduct $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateNewDeliveryNoteForm(SupplierDelivery $entity)
    {
        $form = $this->createForm(new NewDeliveryType(), $entity, array(
            'action' => $this->generateUrl('staff-goods-in_new-delivery-note-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }

    public function addproductstodeliverynoteAction($supplier_delivery_id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Add products to delivery note" );
        

        //Get supplier delivery so existsing products can be show.
        $em = $this->getDoctrine()->getManager();

        $supplier_delivery = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findOneById($supplier_delivery_id);


        $entity = new SupplierDeliveryProduct();

        $form = $this->createCreateNewDeliveryNoteProductForm($entity, $supplier_delivery_id);

        return $this->render('MilesApartStaffBundle:GoodsIn:new_delivery_note_product.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
            'supplier_delivery_id' => $supplier_delivery_id,
            'supplier_delivery' => $supplier_delivery,
            
        ));

        
        
    }

    /**
    * Creates a form to create a new product to delivery note.
    *
    * @param SupplierDeliveryProduct $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateNewDeliveryNoteProductForm(SupplierDeliveryProduct $entity, $supplier_delivery_id)
    {
        $form = $this->createForm(new NewDeliveryProductType(), $entity, array(
            'action' => $this->generateUrl('staff-goods-in_add-products-to-delivery-note-submit', array('supplier_delivery_id' => $supplier_delivery_id)),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }

    public function addproductstodeliverynoteprocessAction(Request $request, $supplier_delivery_id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Delivery");
        
        $entity = new SupplierDeliveryProduct();
        $form = $this->createCreateNewDeliveryNoteProductForm($entity, $supplier_delivery_id);

        $form->submit($request->request->get($form->getName()));

        $product_supplier_code = $form["product_supplier_code"]->getData();
        $supplier_delivery_note_qty = $form["supplier_delivery_note_qty"]->getData();

        $em = $this->getDoctrine()->getManager();

        $supplier_delivery = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findOneById($supplier_delivery_id);


        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_supplier_code'=> $product_supplier_code, ));

        if (!$product) {

            $this->get('session')->getFlashBag()->set('admin-error', 'The product code does not exist in the database.');
                    
            return $this->render('MilesApartStaffBundle:GoodsIn:new_delivery_note_product.html.twig', array(
                'submitted' => false,
                'form'   => $form->createView(),
                'supplier_delivery_id' => $supplier_delivery_id,
                'supplier_delivery' => $supplier_delivery,
            ));
        } 

        //Add the supplier delivery product to the database
        //Set the product
        $entity->setProduct($product);
        $entity->setSupplierDelivery($supplier_delivery);
        $entity->setSupplierDeliveryNoteQty($supplier_delivery_note_qty);
        $em->persist($entity);
        $em->flush();
                
        $this->get('session')->getFlashBag()->set('admin-notice', $supplier_delivery_note_qty. ' of product "' . $product->getProductName() . '" have been added to the delivery note.');
            
        $entity2 = new SupplierDeliveryProduct();
        $form2 = $this->createCreateNewDeliveryNoteProductForm($entity2, $supplier_delivery_id);

      

        return $this->render('MilesApartStaffBundle:GoodsIn:new_delivery_note_product.html.twig', array(
            'entity3'      => $entity,
            'submitted' => true,
             'form'   => $form2->createView(),
             'supplier_delivery_id' => $supplier_delivery_id,
             'supplier_delivery' => $supplier_delivery,
             
                   ));
        
    }


    public function completedeliverynoteAction($supplier_delivery_id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Delivery");
        
        //Check if this delivery has a completed delivery note.
        $em = $this->getDoctrine()->getManager();

        $supplier_delivery = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findOneById($supplier_delivery_id);

        if($supplier_delivery) {
            $supplier_delivery->setSupplierDeliveryNoteComplete(true);

            $em->flush();
            $this->get('session')->getFlashBag()->set('admin-notice','Delivery note has been marked as complete');
       
            //If it doesn't, redirect to add product to delivery note,
            return $this->redirect($this->generateUrl('staff-goods-in_view-delivery-note', array('supplier_delivery_id' => $supplier_delivery_id)));
       
        }

         $this->get('session')->getFlashBag()->set('admin-error','Delivery note cannot be found');
       

        //If it doesn't, redirect to add product to delivery note,
            return $this->redirect($this->generateUrl('staff-goods-in_view-deliveries'));
       
    }

    public function viewdeliverynoteAction($supplier_delivery_id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View delivery note");
        
        //Check if this delivery has a completed delivery note.
        $em = $this->getDoctrine()->getManager();

        $supplier_delivery = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findOneById($supplier_delivery_id);

        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:GoodsIn:view_delivery_note.html.twig', array(
            'supplier_delivery' => $supplier_delivery,
            ));
       
    }

    //
    public function processdeliveryAction($supplier_delivery_id) 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Delivery");
        
        //Check if this delivery has a completed delivery note.
        $em = $this->getDoctrine()->getManager();

        $supplier_delivery = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findOneById($supplier_delivery_id);

        if($supplier_delivery->getSupplierDeliveryNoteComplete() == false) {

            //If it doesn't, redirect to add product to delivery note,
            return $this->redirect($this->generateUrl('staff-goods-in_add-products-to-delivery-note', array('supplier_delivery_id' => $supplier_delivery_id)));
        }

        //If it doesn't, redirect to add product to delivery note,

        $entity = new SupplierDeliveryProduct();

        $form = $this->createProcessSupplierDeliveryProductForm($entity, $supplier_delivery->getId());

        //If it does, carry on

        //Render the page from template
        return $this->render('MilesApartStaffBundle:GoodsIn:process_delivery.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
            'supplier_delivery' => $supplier_delivery,
            ));
    }


    /**
    * Creates a form to create a new supplier product.
    *
    * @param SupplierProduct $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createProcessSupplierDeliveryProductForm(SupplierDeliveryProduct $entity, $supplier_delivery_id)
    {
        $form = $this->createForm(new ProcessSupplierDeliveryProductType(), $entity, array(
            'action' => $this->generateUrl('staff-goods-in_process-delivery-submit', array('supplier_delivery_id' => $supplier_delivery_id)),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }

     //
    public function processdeliverysubmitAction(Request $request, $supplier_delivery_id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Delivery");
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }
        
        //Set the new price and the product id
        $product_barcode = $response["barcode"];

        //Get the delivery
        $em = $this->getDoctrine()->getManager();

        $supplier_delivery = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findOneById($supplier_delivery_id);

        //Get the product
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_barcode' => $product_barcode));

        if (!$product) {

            //Check the inner barcode.
            $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_inner_barcode'=> $product_barcode));

            if (!$product) {

                //Check the outer barcode.
                $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_outer_barcode'=> $product_barcode));
 
                
            }
        }
        //Check that the product is on the delivery note
        $exists = false;
        $delivered_product = null;

        foreach($supplier_delivery->getSupplierDeliveryProduct() as $delivery_product) {

            if($delivery_product->getProduct()->getId() == $product->getId()) {
                $exists = true;
                $delivered_product = $delivery_product;
            }
        }

        //Product does exist
        if($exists == true) {
            //Render the page from template
            return $this->render('MilesApartStaffBundle:GoodsIn:process_delivery_product_display.html.twig', array(
                'delivered_product' => $delivered_product,
            ));

        //Product does not exist
        } else {
            return false;
        }

        //If it does, carry on

        
    }

    //
    public function processdeliverysubmitconfirmAction(Request $request, $supplier_delivery_id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Delivery");
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        
        
        $supplier_delivery_product_id = $response["supplier_delivery_product_id"];
        $delivered_qty_unit = $response["delivered_qty_unit"];
        $delivered_qty_inners = $response["delivered_qty_inners"];
        $delivered_qty_outers = $response["delivered_qty_outers"];
        $new_price = $response["new_price"];
        $store_qty_unit = $response["store_qty_unit"];
        $store_qty_inners = $response["store_qty_inners"];
        $store_qty_outers = $response["store_qty_outers"];

        //Get the delivery product
        $em = $this->getDoctrine()->getManager();

        $supplier_delivery_product = $em->getRepository('MilesApartAdminBundle:SupplierDeliveryProduct')->findOneById($supplier_delivery_product_id);


        //Add the delivered qty to the database
        //Check if the outers is set
        if($delivered_qty_outers == "") {
            if($delivered_qty_inners == "") {
                if($delivered_qty_unit == "") {
                    //Qty not set so it is equal to delivery note qty
                    $supplier_delivery_product->setSupplierDeliveryQtyDelivered($supplier_delivery_product->getSupplierDeliveryNoteQty());

                } else {
                    $supplier_delivery_product->setSupplierDeliveryQtyDelivered($delivered_qty_unit);
                }
            } else {
                    $supplier_delivery_product->setSupplierDeliveryQtyDelivered($delivered_qty_inners * $supplier_delivery_product->getProduct()->getProductInnerQuantity());
            }
        } else {
            $supplier_delivery_product->setSupplierDeliveryQtyDelivered($delivered_qty_outers * $supplier_delivery_product->getProduct()->getProductOuterQuantity());
        }


        //Add the store qty to the database
        //Check if the outers is set
        if($store_qty_outers == "") {
            if($store_qty_inners == "") {
                if($store_qty_unit == "") {
                    //Qty not set so it is equal to 0
                    $supplier_delivery_product->setSupplierDeliveryQtyToStore(0);

                } else {
                    $supplier_delivery_product->setSupplierDeliveryQtyToStore($store_qty_unit);
                }
            } else {
                    $supplier_delivery_product->setSupplierDeliveryQtyToStore($store_qty_inners * $supplier_delivery_product->getProduct()->getProductInnerQuantity());
            }
        } else {
            $supplier_delivery_product->setSupplierDeliveryQtyToStore($store_qty_outers * $supplier_delivery_product->getProduct()->getProductOuterQuantity());
        }

        //Update price
        if($new_price != "") {
            $price = new ProductPrice;
            $price->setProductPriceValue($new_price);
            $price->setProductPriceValidFrom(new \DateTime('now'));
            $price->setProduct($supplier_delivery_product->getProduct());  
            $em->persist($price);
        }


        $em->flush();
        //Add the store qty to the database

        $response = array(
            "success" => true,
            "qty_remaining" => $supplier_delivery_product->getSupplierDeliveryQtyRemaining(),
        );
        
        return new JsonResponse(
                    $response 
                );
        

        //If there is a new price, update the price

    }


    public function processdeliverycompleteAction($supplier_delivery_id)
    {
        
        //Check that the supplier delivery has been completed
        $em = $this->getDoctrine()->getManager();

        $supplier_delivery = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findOneById($supplier_delivery_id);

        //Get the list of products that have not been delivered 
        $all_delivered = true;

        foreach($supplier_delivery->getSupplierDeliveryProduct() as $product) {

            if($product->getSupplierDeliveryQtyRemaining() > 0) {
                $all_delivered = false;
            }
        }

        if($all_delivered == false) {

            $response = array(
                "success" => false,
            );
        
        
        } else {
            //Set the delivery as complete in the db
            $response = array(
                "success" => true,
            );
        }

        return new JsonResponse(
                    $response 
                );

    }

    public function processdeliverycompletesendemailAction($supplier_delivery_id)
    {
        //Check that the supplier delivery has been completed
        $em = $this->getDoctrine()->getManager();

        $supplier_delivery = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findOneById($supplier_delivery_id);

       
        //Set up the undelivered array 
        $undelivered_array = array();

        //Get the list of products that have not been delivered 
        foreach($supplier_delivery->getSupplierDeliveryProduct() as $product) {
            if($product->getSupplierDeliveryQtyRemaining() > 0) {
                array_push($undelivered_array, $product);
            }
        }
        $logger = $this->get('logger');
        $logger->info('I just got the logger add update pricea');
        if(count($undelivered_array > 0)) {
$logger->info('I just got the logger add update priceb');

            //Call the email send
            $email = $this->sendShortagesEmail($supplier_delivery, $undelivered_array);
$logger->info('I just got the logger add update pricec');
$logger->info($email);
            if($email == true) {
                $response = array(
                    "success" => true,
                    "undelivered_array" => $undelivered_array,
                );
            } else {
                $response = array(
                "success" => false,
            );
            }
            
        
        
        } else {
            $response = array(
                "success" => false,
            );
        }

        return new JsonResponse(
                    $response 
                );

    
    }

    function sendShortagesEmail($supplier_delivery, $undelivered_array)
    {
        //Set up the mailer
        $mailer = $this->container->get('swiftmailer.mailer.goodsin');

        $logger = $this->get('logger');
        $logger->info('I just got the logger add update pricetip');
        //Set the email address.
        if ($supplier_delivery->getSupplier()->getSupplierOrderingEmail() != NULL) {
            $email_address = $supplier_delivery->getSupplier()->getSupplierOrderingEmail();
        } else if ($supplier_delivery->getSupplier()->getSupplierInfoEmail() != NULL) {
            $email_address = $supplier_delivery->getSupplier()->getSupplierInfoEmail();
        } else {
            return false;
        }

        $logger->info('I just got the logger add update price');
        
        //Get the supplier email address.
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Notification of shortages on delivery '. $supplier_delivery->getSupplierDeliveryNoteNumber())
            ->setFrom(array('purchaseorders@miles-apart.com' => 'Miles Apart Purchasing Department'))
            ->setTo($email_address)
            ->setCc('purchaseorders@miles-apart.com')
            ->setBody(
                $this->renderView(
                    'MilesApartStaffBundle:GoodsIn:shortages_email.html.twig',
                    array('undelivered_array' => $undelivered_array,
                        'supplier_delivery' => $supplier_delivery,
                        )
                )

            )
        ;

        $logger->info('I just got the logger add update price2');

        //Avoid localhost issues
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        $mailer->send($message);
        $logger->info('I just got the logger add update price3');

        return true;
    }

    public function bookindeliveryAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Book In Delivery", $this->get("router")->generate("staff-goods-in_book-in-delivery"));

        $entity = new SupplierDelivery();
        //Set supplier delivery delivered date to today.
        $now = new \DateTime();
        $entity->setBookedInDate($now);

        $form = $this->createCreateBookInDeliveryForm($entity);

        return $this->render('MilesApartStaffBundle:GoodsIn:book_in_delivery.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }   

    public function bookindeliverysubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Book In Delivery", $this->get("router")->generate("staff-goods-in_book-in-delivery"));

        $entity = new SupplierDelivery();
        $form = $this->createCreateBookInDeliveryForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New delivery has been booked in successfully.');

            return $this->redirect($this->generateUrl('staff-goods-in_book-in-delivery'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:GoodsIn:book_in_delivery.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a book in new supplier delivery.
    *
    * @param SupplierDelivery $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateBookInDeliveryForm(SupplierDelivery $entity)
    {
        $form = $this->createForm(new BookInDeliveryType(), $entity, array(
            'action' => $this->generateUrl('staff-goods-in_book-in-delivery-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }

    public function storedeliveriesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Store Delivery", $this->get("router")->generate("staff-goods-in_store-deliveries"));
        
        $em = $this->getDoctrine()->getManager();
        
        $supplier_deliveries = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findAll();

        return $this->render('MilesApartStaffBundle:GoodsIn:store_delivery_view_deliveries.html.twig', array(
           
            'supplier_deliveries' => $supplier_deliveries,
            
        ));
    }


    public function storedeliverydeliveryselectedAction($supplier_delivery_id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Store Delivery", $this->get("router")->generate("staff-goods-in_store-deliveries"));

        //Get the supplier delivery
        $em = $this->getDoctrine()->getManager();
        $supplier_delivery = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findOneById($supplier_delivery_id);

        //Set the supplier delivery in the session
        $session = new Session();
        $session->set('supplier_delivery', $supplier_delivery);

        return $this->render('MilesApartStaffBundle:GoodsIn:store_delivery.html.twig', array(
           'supplier_delivery' => $supplier_delivery,
           
            
        ));
    }

    /**
    * Creates a form to create a new stock location product sent.
    *
    * @param StockLocationProductSent $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateStockLocationProductSentForm(StockLocationShelfProductSent $entity)
    {
        $form = $this->createForm(new StoreDeliveryType(), $entity, array(
            'action' => $this->generateUrl('staff-goods-in_store-delivery-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function storedeliverysubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Store Delivery", $this->get("router")->generate("staff-goods-in_store-delivery"));

        $entity = new StockLocationShelfProductSent();
        $form = $this->createCreateStockLocationProductSentForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Product has been stored successfully.');

            return $this->redirect($this->generateUrl('staff-goods-in_store-delivery'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:GoodsIn:store_delivery.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    function storedeliveryprocessAction(Request $request) 
    {
        //Get the product barcode from the request
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $product_barcode = $response["barcode"];
        $logger = $this->get('logger');
        $logger->info('I just got the logger add update price');

        //Get the shelf from the session.
        $session = new Session();
        $stock_location_shelf = $session->get('stock_location_shelf');
        $supplier_delivery = $session->get('supplier_delivery');

$logger->info('I just got the logger add update price2');

$logger->info('I just got the logger add update price23');

        //Get em
        $em = $this->getDoctrine()->getManager();

        $stock_location_shelf = $em->merge($stock_location_shelf);

        //Get supplier delivery product from database
        $supplier_delivery_product = $em->getRepository('MilesApartAdminBundle:SupplierDeliveryProduct')->findProductByBarcode($product_barcode, $supplier_delivery->getId());


        //Check that the qty to store is not more than the remaining qty to store
        //Get the qty already stored 
        $supplier_delivery_product_stored = $em->getRepository('MilesApartAdminBundle:StockLocationShelfProductSent')->findOneBy(array('supplier_delivery_product' => $supplier_delivery_product));
        //Check there was a result 
        if(count($supplier_delivery_product_stored) > 0) {
            if($supplier_delivery_product[0]->getSupplierDeliveryQtyToStore() == $supplier_delivery_product_stored->getStockLocationShelfProductSentQty()) {
                
                //Set the response to tell js that the product cannot be stored, as it already has been
                $response = array(
                    'found' => true,
                    'already_stored' => true,
                    'already_stored_shelf_code' => $supplier_delivery_product_stored->getStockLocationShelf()->getStockLocationShelfCode(),
                    'stored_success' => false,

                    
                );
           
                return new JsonResponse(
                    $response 
                );
            }
        }
        //If all is ok, add the product to the product stored table

        //New product tarnsfer request
        $stock_location_shelf_product_sent = new StockLocationShelfProductSent();

        //Set the product
        $stock_location_shelf_product_sent->setSupplierDeliveryProduct($supplier_delivery_product[0]);

        //Set the stock location shelf
        $stock_location_shelf_product_sent->setStockLocationShelf($stock_location_shelf);

        //Set the default qty
        $stock_location_shelf_product_sent->setStockLocationShelfProductSentQty($supplier_delivery_product[0]->getSupplierDeliveryQtyToStore());

        //Persist row to the database.
        
        $em->persist($stock_location_shelf_product_sent);
        
        $em->flush();
        $logger->info('I just got the logger add update price');

        $response = array(
            'found' => true,
            'alreadey_stored' => false,
            'stored_success' => true,
            'supplier_delivery_product_id' => $supplier_delivery_product[0]->getId(),
            
        );
       
        $this->container->get('logger')->info('Local variables', get_defined_vars());
        
        
        return new JsonResponse(
            $response 
        );
       
            
        
    }

    public function viewdeliveriesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Deliveries", $this->get("router")->generate("staff-goods-in_view-deliveries"));

        $em = $this->getDoctrine()->getManager();
        
        $supplier_deliveries = $em->getRepository('MilesApartAdminBundle:SupplierDelivery')->findAll();

        return $this->render('MilesApartStaffBundle:GoodsIn:view_deliveries.html.twig', array(
           
            'supplier_deliveries' => $supplier_deliveries,
            
        ));
    }   

    public function selectstorageshelfAction(Request $request)
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Get the barcode and search query from the request
        $shelf_barcode = $response["barcode"];

        //Get the shelf location object
        $em = $this->getDoctrine()->getManager();
        $stock_location_shelf = $em->getRepository('MilesApartAdminBundle:StockLocationShelf')->findOneBy(array('stock_location_shelf_code'=> $shelf_barcode));

        //Set the session.
        $session = new Session();
        $session->set('stock_location_shelf', $stock_location_shelf);
        
        $response = array(     
                        'response' => true,
                    );

        $response = new JsonResponse($response);
        return $response;
    
    }

   

}