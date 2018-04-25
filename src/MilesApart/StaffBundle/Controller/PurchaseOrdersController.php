<?php
// src/MilesApart/StaffBundle/Controller/PurchaseOrdersController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Session\Session;

use MilesApart\AdminBundle\Entity\PurchaseOrder;
use MilesApart\AdminBundle\Entity\PurchaseOrderProduct;
use MilesApart\AdminBundle\Entity\ProductPrice;
use MilesApart\AdminBundle\Entity\Product;
use MilesApart\AdminBundle\Entity\ProductCost;
use MilesApart\AdminBundle\Entity\Supplier;
use MilesApart\AdminBundle\Entity\ProductSupplier;
use MilesApart\StaffBundle\Entity\PurchaseOrders\PurchaseOrderConfirmationManualInput;

use MilesApart\StaffBundle\Entity\PurchaseOrders\SelectSupplier;
use MilesApart\StaffBundle\Entity\PurchaseOrders\PurchaseOrderCSVFile;
use MilesApart\StaffBundle\Form\PurchaseOrders\PurchaseOrderCSVFileType;
use MilesApart\StaffBundle\Form\PurchaseOrders\AddProductToPurchaseOrderType;
use MilesApart\StaffBundle\Form\PurchaseOrders\SelectSupplierType;
use MilesApart\StaffBundle\Form\PurchaseOrders\PurchaseOrderConfirmationSupplierSelectType;
use MilesApart\StaffBundle\Form\PurchaseOrders\PurchaseOrderConfirmationManualInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Reader\ArrayReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\Writer\ArrayWriter;
use Ddeboer\DataImport\Writer\CsvWriter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;



class PurchaseOrdersController extends Controller
{
    /*************************************************
    * Purchase orders controller displays the functions and pages in purchase orders menu area.
    *************************************************/

    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Purchase Order Notifications", $this->get("router")->generate("staff-purchase-orders_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:notifications.html.twig');
   
    }

    public function addproducttopurchaseorderAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Purchase Orders", $this->get("router")->generate("staff-purchase-orders_notifications"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Add Product To Purchase Order", $this->get("router")->generate("staff-purchase-orders_add-product-to-purchase-order"));
        
        //Create the form to add products.
        $entity = new Product();
        $form = $this->createAddProductToPurchaseOrderForm($entity);

        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        //Check for existing purchase orders for all suppliers (all that have not been sent)
        $purchase_order_products = $em->getRepository('MilesApartAdminBundle:PurchaseOrderProduct')->findExistingPurchaseOrderProducts();
        $purchase_orders = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findBy(array('purchase_order_state' => 1), array('purchase_order_date_created' => 'DESC'));
        
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:add_product_to_purchase_order.html.twig', array( 
            'purchase_orders' => $purchase_orders,
            'purchase_order_products' => $purchase_order_products,
            'form' => $form->createView(),
            'entity'=>$entity,
            'submitted'=> FALSE,

            ));
   
    }

     /**
    * Creates a form to create a new product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAddProductToPurchaseOrderForm(Product $entity)
    {
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(new AddProductToPurchaseOrderType($em), $entity, array(
            'action' => $this->generateUrl('staff-purchase-orders_add-product-to-purchase-order-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal add_product_to_list_form')
        ));

       
        $form->add('submit', 'submit', array('label' => 'Add', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-3 col-xs-12')));

        return $form;
    }

    function addproducttopurchaseorderfindproductAction(Request $request) {
       $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Get the barcode and search query from the request
        $product_supplier_code = $response["product_supplier_code"];
        $product_search_string = $response["product_search_string"];


        $em = $this->getDoctrine()->getManager();
        if($product_supplier_code != null || $product_supplier_code != "") {
            $products = $em->getRepository('MilesApartAdminBundle:Product')->findProductsFromSupplierCodeText($product_supplier_code);
        } else {
            $products = $em->getRepository('MilesApartAdminBundle:Product')->findByLetters($product_search_string);
        }

        $serialized = array();
        // Serialize products
        foreach($products as $product){ 

            if($product->getDefaultProductSupplierObject() != null) {
                $supplier = $product->getDefaultProductSupplierObject()->getSupplierShortName();
            } else {
                $supplier = "Unknown";
            }
            
            $serializedEntity = array(
                'product_name' => $product->getProductName(),
                'product_supplier_code' => $product->getProductSupplierCode(),
                'product_supplier' => $supplier,
                'product_price' => $product->getCurrentPrice(),
                'product_barcode' => $product->getProductBarcode()
                );

            array_push($serialized, $serializedEntity);
        }

        //Return results to js
        $response = array(
            "success" => true,
            "products" => $serialized,
        );
        
        return new JsonResponse(
                    $response 
        );
            

    }

    function addproducttopurchaseordersubmitAction(Request $request) {
        //Set the function variable.
        $function_variable = "PurchaseOrderProduct";
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Get the barcode and search query from the request
        $barcode = $response["barcode"];

        //Call add product to list from helper class.
        $response = $this->forward('MilesApartStaffBundle:Helper:addProductToList', array(
            'function_variable'  => $function_variable,
            'barcode' => $barcode,
            
        ));
        
        return $response;
    }

    public function addproducttopurchaseordernewqtyAction(Request $request) 
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
        $purchase_order_product_id = $response["product_id"];

        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $purchase_order_product = $em->getRepository('MilesApartAdminBundle:PurchaseOrderProduct')->findOneBy(array('id' => $purchase_order_product_id));

        //Set the product
        $purchase_order_product->setPurchaseOrderProductQuantity($new_qty);

        //Persist row to the database.
        $em->persist($purchase_order_product);
        $em->flush();

        $response = array("success" => true);
        return new JsonResponse(
                    $response 
                );
    }

    public function addproducttopurchaseordersubmitnewproductAction(Request $request) 
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
        $new_product_qty = $response["new_product_qty"];
        $new_product_supplier_code = $response["new_product_supplier_code"];
        $new_product_price = $response["new_product_price"];
        $new_product_supplier_id = $response["new_product_supplier_id"];
        $logger = $this->get('logger');
        $logger->info('I just got the logger add 0');
        //Get the supplier
        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneBy(array('id' => $new_product_supplier_id));
$logger->info('I just got the logger add 2');
        //Set the session for next add.
        $session = new Session();
        $session->set('new_product_supplier', $supplier);
$logger->info('I just got the logger add 3');
        //Create new product in the database with the product name and barcode.
        $product = new Product();

        $product->setProductName($new_product_name);
        $product->setProductBarcode($new_product_barcode);
        $product->setProductSupplierCode($new_product_supplier_code);

        if ($new_product_price) {
            $product_price = new ProductPrice();
         
            $product_price->setProductPriceValue($new_product_price);
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

$logger->info('I just got the logger add 4');
        //PRODUCT HAS NOW BEEN ADDED, NOW ADD TO PURCHSE ORDER.
        //Check if purchase order exists for the supplier of the new product.
        $purchase_order = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findOneBy(array('supplier' => $supplier, 'purchase_order_state' => 1));
        
        if($purchase_order != NULL) {
            
            $logger->info('I just got the logger add 5');

        } else {
            //Get the purchase order state.
            $purchase_order_state = $em->getRepository('MilesApartAdminBundle:PurchaseOrderState')->findOneBy(array('id' => 1));
        
            //Purchase order does not exist, create new, then add this product to it.
$logger->info('I just got the logger add 6');
            $purchase_order = new PurchaseOrder();
            $purchase_order->setSupplier($supplier);
            $purchase_order->setPurchaseOrderState($purchase_order_state);
$logger->info('I just got the logger add 67');
            $em->persist($purchase_order);

$logger->info('I just got the logger add 7');
        }
$logger->info('I just got the logger add 8');
        //Purchase order exists, just add this product to it.
            $purchase_order_product = new PurchaseOrderProduct();

            $purchase_order_product->setProduct($product);
            $purchase_order_product->setPurchaseOrder($purchase_order);
            $purchase_order_product->setPurchaseOrderProductQuantity($new_product_qty);
            
        $em->persist($purchase_order_product);
        //Persist the database
        
        $em->flush();

        //Then return success.

        $response = array(
                    'product_name' => $purchase_order_product->getProduct()->getProductName(), 
                    'product_stock_qty'=>'NA', 
                    'product_price'=>$purchase_order_product->getProduct()->getCurrentPrice(), 
                    'product_supplier_code'=> $purchase_order_product->getProduct()->getProductSupplierCode(),
                    'product_barcode' => $purchase_order_product->getProduct()->getProductBarcode(),
                    'supplier_name' => $supplier->getSupplierName(),
                    'product_qty'=> $purchase_order_product->getPurchaseOrderProductQuantity(), 
                    'product_id' => $purchase_order_product->getId(),
                    'success'=> true,
                    'table_id' => $purchase_order_product->getPurchaseOrder()->getId(),
                    );


                return new JsonResponse(
                    $response 
                );


            
    }

    public function newsupplierpurchaseorderAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Purchase Orders", $this->get("router")->generate("staff-purchase-orders_notifications"));
       
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Supplier Purchase Order", $this->get("router")->generate("staff-purchase-orders_new-supplier-purchase-order"));
        
        //Create the form and load.
        $entity = new SelectSupplier();
        $form = $this->createSelectSupplierForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:select_purchase_order_supplier.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
            
        ));

   
    }

    /**
    * Creates a form to select a supplier entity.
    *
    * @param Supplier $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createSelectSupplierForm(SelectSupplier $entity)
    {
        $form = $this->createForm(new SelectSupplierType(), $entity, array(
            'action' => $this->generateUrl('staff-purchase-orders_select-supplier-for-purchase-order'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal'),
        ));

        
        return $form;
    }

    public function selectsupplierforpurchaseorderprocessAction(Request $request, $supplier_id = null) 
    {
        if ($supplier_id == null) {
           //Get the $_POST array.
            if ($request->isXMLHttpRequest()) {
                $response = $_POST;
                //$response = new JsonResponse($r);
            } else {

                $response = "false";
            }

            //Set the new qty and the id of the product transfer request
            $supplier_id = $response["supplier_id"];
        }

        $em = $this->getDoctrine()->getManager();
        //Get the supplier
        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneBy(array('id' => $supplier_id));
        
        //Get products by this supplier
        $product_suppliers = $em->getRepository('MilesApartAdminBundle:ProductSupplier')->findProductSuppliersNotDiscontinued($supplier_id);

        //Get existing purchase orders and their contents.
        $purchase_orders = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findBy(array('supplier' => $supplier, 'purchase_order_state' => 1));


        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:purchase_order_display_products.html.twig', array(
            'product_suppliers' => $product_suppliers,
            'purchase_orders' => $purchase_orders,
        ));
   
    }

    public function addproducttopurchaseordershoppingbasketAction(Request $request) 
    {
       //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

         $logger = $this->get('logger');
        $logger->info('I just got the logger add 0');

        //Set the new qty and the id of the product transfer request
        $product_id = $response["id"];

        $em = $this->getDoctrine()->getManager();
        //GEt the supplier
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('id' => $product_id));
        
        //Set the qty to add to the purchase order.
        //Check the supplier minimum order format
        //Check it is set
        
        $order_qty = 1;
        if($product->getDefaultProductSupplierObject()->getSupplierMinimumOrderFormat() != NULL) {
            //Minimum is outer
            if($product->getDefaultProductSupplierObject()->getSupplierMinimumOrderFormat()->getSupplierMinimumOrderFormatName() == "Outer") {
                
                //If the inner qty is 0 add an outer
                if($product->getProductOuterQuantity() != 0 || $product->getProductOuterQuantity() != NULL) {
                    $order_qty = $product->getProductOuterQuantity();
                } else if($product->getProductInnerQuantity() != 0 || $product->getProductInnerQuantity() != NULL) {
                    $order_qty = $product->getProductInnerQuantity();
                } else {
                    $order_qty = 1;
                }
            
            //Minimum is inner
            } else if($product->getDefaultProductSupplierObject()->getSupplierMinimumOrderFormat()->getSupplierMinimumOrderFormatName() == "Inner") {
                 
                //If the inner qty is 0 add an outer
                if($product->getProductInnerQuantity() != 0 || $product->getProductInnerQuantity() != NULL) {
                    $order_qty = $product->getProductInnerQuantity();
                } else if($product->getProductOuterQuantity() != 0 || $product->getProductOuterQuantity() != NULL) {
                    $order_qty = $product->getProductOuterQuantity();
                } else {
                    $order_qty = 1;
                }
            
            //Minimum is single   
            } else if ($product->getDefaultProductSupplierObject()->getSupplierMinimumOrderFormat()->getSupplierMinimumOrderFormatName() == "Single") {
                $order_qty = 1;
            }
        
        //No minimum set
        } else {
            if ($product->getProductOuterQuantity() == NULL) {
                $order_qty = 1;
            } else {
                $product->getProductOuterQuantity();
            }
        }
        
        

        //Get products by this supplier
        $product_suppliers = $em->getRepository('MilesApartAdminBundle:ProductSupplier')->findBy(array('product' => $product));

        $logger->info('I just got the logger add 1');

        //Check if there is a purchase order for this supplier.
        //Get existing purchase orders and their contents.
        $purchase_order = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findOneBy(array('supplier' => $product->getDefaultProductSupplierObject(), 'purchase_order_state' => 1));

        $logger->info('I just got the logger add 2');
        //If no purchase order is found, create one.
        if ($purchase_order == NULL) {

            //Get purchase order state
            $purchase_order_state = $em->getRepository('MilesApartAdminBundle:PurchaseOrderState')->findOneBy(array('id' => 1));

            $purchase_order = new PurchaseOrder();

            //Set the supplier & state
            $purchase_order->setSupplier($product->getDefaultProductSupplierObject());
            $purchase_order->setPurchaseOrderState($purchase_order_state);
            $logger->info('I just got the logger add 3');
            //Persist changes to the database.
            $em->persist($purchase_order);
            $em->flush();
            $logger->info('I just got the logger add 4');

            //Tell JS to create new table/
            $new_table = TRUE;
        } else {
            $new_table = FALSE;
        }
        $logger->info('I just got the logger add 45');
        //Add the product to the purchase order
        //Check if product already exists in PO
        $purchase_order_product = $em->getRepository('MilesApartAdminBundle:PurchaseOrderProduct')->findOneBy(array('purchase_order' => $purchase_order, 'product' => $product));
        $logger->info('I just got the logger add 5');

        //If existing, add another carton to qty.
        if ($purchase_order_product != NULL) {
            $logger->info('I just got the logger add 55');
            //Check if outer quantity
            
            $new_qty = $purchase_order_product->getPurchaseOrderProductQuantity() + $order_qty;
            $purchase_order_product->setPurchaseOrderProductQuantity($new_qty);
            $logger->info('I just got the logger add 6');

            //Set so JS knows 
            $existing_product = TRUE;
        //If it doesn't exist, create new.
        } else {
            $logger->info('I just got the logger add 67');
            $purchase_order_product = new PurchaseOrderProduct();
$logger->info('I just got the logger add 7');

$logger->info('I just got the logger add 7');
$logger->info($purchase_order->getId());
$logger->info($order_qty);

            $purchase_order_product->setProduct($product);
            $purchase_order_product->setPurchaseOrder($purchase_order);
            $purchase_order_product->setPurchaseOrderProductQuantity($order_qty);
            $logger->info('I just got the logger add 8');
            $em->persist($purchase_order_product);
            $logger->info('I just got the logger add 9');
            $existing_product = FALSE;
        }

        $em->flush();
        $logger->info('I just got the logger add 10');

        //Set up the response array.
        $response = array(
            'product_name' => $product->getProductName(), 
            'current_cost'=> $product->getCurrentCostDisplay(), 
            'product_supplier_code'=> $product->getProductSupplierCode(),
            'product_barcode' => $product->getProductBarcode(),
            'supplier_name' => $product->getDefaultProductSupplier(),
            'product_qty'=> $purchase_order_product->getPurchaseOrderProductQuantity(), 
            'product_id' => $purchase_order_product->getId(),
            'prod_id' => $product->getId(),
            'new_table' => $new_table,
            'existing_product' => $existing_product, 
            'purchase_order_date_created' => date_format($purchase_order->getPurchaseOrderDateCreated(),"d M Y g:i a"),
            'difference_to_minimum_value' => $purchase_order->getPurchaseOrderMinimumOrderValueDifferenceDisplay(),
            'purchase_order_current_total_display' => $purchase_order->getPurchaseOrderCurrentTotalDisplay(),
            'purchase_order_product_outers' => $purchase_order_product->getPurchaseOrderProductOuters(),
            'purchase_order_product_inners' => $purchase_order_product->getPurchaseOrderProductInners(),
            'purchase_order_id' => $purchase_order_product->getPurchaseOrder()->getId(),
        );
        //Render the page from template
        return new JsonResponse(
                $response 
            );
   
    }

    public function minusproductfrompurchaseordershoppingbasketAction(Request $request) {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

         $logger = $this->get('logger');
        $logger->info('I just got the logger add 0');

        //Set the new qty and the id of the product transfer request
        $purchase_order_product_id = $response["id"];

        $em = $this->getDoctrine()->getManager();
        //GEt the supplier
        $purchase_order_product = $em->getRepository('MilesApartAdminBundle:PurchaseOrderProduct')->findOneBy(array('id' => $purchase_order_product_id));
        
        //Set the qty to minus from the purchase order.
        $order_qty = 1;
        if($purchase_order_product->getProduct()->getDefaultProductSupplierObject()->getSupplierMinimumOrderFormat() != NULL) {
            //Minimum is outer
            if($purchase_order_product->getProduct()->getDefaultProductSupplierObject()->getSupplierMinimumOrderFormat()->getSupplierMinimumOrderFormatName() == "Outer") {
                
                //If the inner qty is 0 add an outer
                if($purchase_order_product->getProduct()->getProductOuterQuantity() != 0 || $purchase_order_product->getProduct()->getProductOuterQuantity() != NULL) {
                    $order_qty = $purchase_order_product->getProduct()->getProductOuterQuantity();
                } else if($purchase_order_product->getProduct()->getProductInnerQuantity() != 0 || $purchase_order_product->getProduct()->getProductInnerQuantity() != NULL) {
                    $order_qty = $purchase_order_product->getProduct()->getProductInnerQuantity();
                } else {
                    $order_qty = 1;
                }
            
            //Minimum is inner
            } else if($purchase_order_product->getProduct()->getDefaultProductSupplierObject()->getSupplierMinimumOrderFormat()->getSupplierMinimumOrderFormatName() == "Inner") {
                 
                //If the inner qty is 0 add an outer
                if($purchase_order_product->getProduct()->getProductInnerQuantity() != 0 || $purchase_order_product->getProduct()->getProductInnerQuantity() != NULL) {
                    $order_qty = $purchase_order_product->getProduct()->getProductInnerQuantity();
                } else if($purchase_order_product->getProduct()->getProductOuterQuantity() != 0 || $purchase_order_product->getProduct()->getProductOuterQuantity() != NULL) {
                    $order_qty = $purchase_order_product->getProduct()->getProductOuterQuantity();
                } else {
                    $order_qty = 1;
                }
            
            //Minimum is single   
            } else if ($purchase_order_product->getProduct()->getDefaultProductSupplierObject()->getSupplierMinimumOrderFormat()->getSupplierMinimumOrderFormatName() == "Single") {
                $order_qty = 1;
            }
        
        //No minimum set
        } else {
            if ($purchase_order_product->getProduct()->getProductOuterQuantity() == NULL) {
                $order_qty = 1;
            } else {
                $purchase_order_product->getProduct()->getProductOuterQuantity();
            }
        }

        $new_qty = $purchase_order_product->getPurchaseOrderProductQuantity() - $order_qty;
        $purchase_order_product->setPurchaseOrderProductQuantity($new_qty);

        //Check if the qty is 0 or less, if so, disable from the purchase order (deactivate the row, exceopt for plus button)


        $em->persist($purchase_order_product);
        $em->flush();
        
        //Return the new quantities to the table.
        $response = array(
            'product_id' => $purchase_order_product->getId(),
            'product_qty'=> $purchase_order_product->getPurchaseOrderProductQuantity(), 
            'purchase_order_product_outers' => $purchase_order_product->getPurchaseOrderProductOuters(),
            'purchase_order_product_inners' => $purchase_order_product->getPurchaseOrderProductInners(),
            'difference_to_minimum_value' => $purchase_order_product->getPurchaseOrder()->getPurchaseOrderMinimumOrderValueDifferenceDisplay(),
            'purchase_order_current_total_display' => $purchase_order_product->getPurchaseOrder()->getPurchaseOrderCurrentTotalDisplay(),
        );
        //Render the page from template
        return new JsonResponse(
                $response 
            );

        
    }

    public function deleteproductfrompurchaseordershoppingbasketAction(Request $request) {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $logger = $this->get('logger');
        $logger->info('I just got the logger add 0');

        //Set the new qty and the id of the product transfer request
        $purchase_order_product_id = $response["id"];

        $em = $this->getDoctrine()->getManager();
        //GEt the supplier
        $purchase_order_product = $em->getRepository('MilesApartAdminBundle:PurchaseOrderProduct')->findOneBy(array('id' => $purchase_order_product_id));
        
        $purchase_order = $purchase_order_product->getPurchaseOrder();

        $purchase_order->removePurchaseOrderProduct($purchase_order_product);
        $purchase_order_product->setPurchaseOrder(null);

        //Check if the qty is 0 or less, if so, disable from the purchase order (deactivate the row, exceopt for plus button)


        $em->persist($purchase_order_product);
        $em->flush();
        
        //Return the new quantities to the table.
        $response = array(
            'removed' => true,
            'product_id' => $purchase_order_product->getId(),
            'prod_id' => $purchase_order_product->getProduct()->getId(),
            'difference_to_minimum_value' => $purchase_order->getPurchaseOrderMinimumOrderValueDifferenceDisplay(),
            'purchase_order_current_total_display' => $purchase_order->getPurchaseOrderCurrentTotalDisplay(),
        );
        //Render the page from template
        return new JsonResponse(
                $response 
            );

        
    }


    public function purchaseorderproductmoreinfoAction(Request $request) {

        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Set the new qty and the id of the product transfer request
        $product_id = $response["id"];

        $em = $this->getDoctrine()->getManager();
        //GEt the supplier
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('id' => $product_id));
        
        //Set up the response array.
        $response = array(
            'product_name' => $product->getProductName(), 
            'current_cost'=> $product->getCurrentCostDisplay(), 
            'product_supplier_code'=> $product->getProductSupplierCode(),
            'product_barcode' => $product->getProductBarcode(),
            'supplier_name' => $product->getDefaultProductSupplier(),
        );
        
        //Render the page from template
        return new JsonResponse(
                $response 
            );
    }

    public function sendpurchaseorderAction($id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Purchase Orders", $this->get("router")->generate("staff-purchase-orders_notifications"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Send Purchase Order");
        
        $em = $this->getDoctrine()->getManager();
        //Get the purchase order
        $purchase_order = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findOneById($id);

        //CHECK THAT THE ORDER HAS NOT BEEN SENT
        if($purchase_order->getPurchaseOrderState()->getPurchaseOrderState() == "Sent") {

            //Show the flash message with error
            $this->get('session')->getFlashBag()->set('admin-error', 'The purchase order has already been sent.');

            //Render the page from template
            return $this->render('MilesApartStaffBundle:PurchaseOrders:send_purchase_order.html.twig', array(
                'purchase_order' => $purchase_order,
            ));
   
        }

        //CHECK THAT THE ORDER total value is above the minimum
        if($purchase_order->getSupplier()->getSupplierMinimumOrderValue() != NULL) {
            if($purchase_order->getPurchaseOrderCurrentTotal() < $purchase_order->getSupplier()->getSupplierMinimumOrderValue()) {

                //Show the flash message with error
                $this->get('session')->getFlashBag()->set('admin-error', 'The purchase order is not up to the required minimum.');

                //Render the page from template
                return $this->render('MilesApartStaffBundle:PurchaseOrders:send_purchase_order.html.twig', array(
                    'purchase_order' => $purchase_order,
                ));
            }
        }

        //Define email for use to know if fax needs to be sent.
        $email = FALSE;
        $fax = FALSE;
        $email_send = FALSE;
        //Check if there is an ordering email address for the supplier
        if ($purchase_order->getSupplier()->getSupplierOrderingEmail() != NULL) {

            $email = TRUE;

            //Email the purchase order.
            //Create and send the email.
            $email_send = $this->sendPurchaseOrderEmail($purchase_order);

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The purchase order has been marked as sent.');

        //Check if there is any email address for the supplier.
        } else if ($purchase_order->getSupplier()->getSupplierInfoEmail() != NULL) {

            $email = TRUE;

            //Email the purchase order.
            $email_send = $this->sendPurchaseOrderEmail($purchase_order);

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The purchase order has been marked as sent.');


        //Check if there is a fax number.
        } else if ($purchase_order->getSupplier()->getSupplierFax() != NULL) {
            $fax = TRUE;
            //Print the purchase order. Remember to append fax number.
            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-warning', 'The purchase order needs to be faxed to the supplier. Please print then fax to ' .$purchase_order->getSupplier()->getSupplierFax(). '.');

        //The purchase order needs to be displayed.
        } else {
            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-warning', 'The purchase order needs to be phoned through to the supplier. Please call ' .$purchase_order->getSupplier()->getSupplierPhone(). '.');


        }

        //Set purchase order as sent.
        $purchase_order_state = $em->getRepository('MilesApartAdminBundle:PurchaseOrderState')->findOneBy(array('id' => 2));
        $purchase_order->setPurchaseOrderState($purchase_order_state);

        //Persist changes to the database.
        $em->persist($purchase_order);
        $em->flush();

        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:send_purchase_order.html.twig', array(
            'purchase_order' => $purchase_order,
        ));
   
    }

    public function sendpurchaseorderpostAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Set the new qty and the id of the product transfer request
        $purchase_order_id = $response["id"];
        
        $em = $this->getDoctrine()->getManager();
        //Get the purchase order
        $purchase_order = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findOneBy(array('id' => $purchase_order_id));

        //Define email for use to know if fax needs to be sent.
        $email = FALSE;
        $fax = FALSE;
        $email_send = FALSE;
        //Check if there is an ordering email address for the supplier
        if ($purchase_order->getSupplier()->getSupplierOrderingEmail() != NULL) {

            $email = TRUE;

            //Email the purchase order.
            //Create and send the email.
            $email_send = $this->sendPurchaseOrderEmail($purchase_order);


        //Check if there is any email address for the supplier.
        } else if ($purchase_order->getSupplier()->getSupplierInfoEmail() != NULL) {

            $email = TRUE;

            //Email the purchase order.
            $email_send = $this->sendPurchaseOrderEmail($purchase_order);

        //Check if there is a fax number.
        } else if ($purchase_order->getSupplier()->getSupplierFax() != NULL) {
            $fax = TRUE;
            //Print the purchase order. Remember to append fax number.
            
        //The purchase order needs to be displayed.
        } else {


        }

        //Set purchase order as sent.
        $purchase_order_state = $em->getRepository('MilesApartAdminBundle:PurchaseOrderState')->findOneBy(array('id' => 2));
        $purchase_order->setPurchaseOrderState($purchase_order_state);

        //Persist changes to the database.
        $em->persist($purchase_order);
        $em->flush();

        //Show the flash message with success
        $this->get('session')->getFlashBag()->set('admin-notice', 'The purchase order has been marked as sent.');

        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:send_purchase_order.html.twig', array(
            'purchase_order' => $purchase_order,
            'fax' => $fax,
            'email' => $email,
            'email_send' => $email_send,
        ));
   
    }

    function printpurchaseorderAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        //Get the purchase order
        $purchase_order = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findOneBy(array('id' => $id));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:print_purchase_order.html.twig', array(
            'purchase_order' => $purchase_order,
        ));
   
    }


    function sendPurchaseOrderEmail($purchase_order)
    {
        //Set up the mailer
        $mailer = $this->container->get('swiftmailer.mailer.weborders_mailer');

        //Set the email address.
        if ($purchase_order->getSupplier()->getSupplierOrderingEmail() != NULL) {
            $email_address = $purchase_order->getSupplier()->getSupplierOrderingEmail();
        } else if ($purchase_order->getSupplier()->getSupplierInfoEmail() != NULL) {
            $email_address = $purchase_order->getSupplier()->getSupplierInfoEmail();
        } else {
            return false;
        }

        
        //Get the supplier email address.
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Purchase Order from Miles Apart, Wiltshire, SP4 0AB - A/C ' . $purchase_order->getSupplier()->getSupplierAccountNumber())
            ->setFrom(array('purchaseorders@miles-apart.com' => 'Miles Apart Purchasing'))
            ->setTo($email_address)
            ->setCc('purchaseorders@miles-apart.com')
            ->setBody(
                $this->renderView(
                    'MilesApartStaffBundle:PurchaseOrders:purchase_order_email.html.twig',
                    array('purchase_order' => $purchase_order)
                )

            )
        ;

        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        $mailer->send($message);
        

        return true;
    }

    public function reviewpurchaseordersAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Purchase Orders", $this->get("router")->generate("staff-purchase-orders_notifications"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Review Purchase Orders", $this->get("router")->generate("staff-purchase-orders_review-purchase-orders"));
        
        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $outstanding_purchase_orders = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findBy(array('purchase_order_state' => 2), array('purchase_order_date_created' => 'DESC'));
        $incomplete_purchase_orders = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findBy(array('purchase_order_state' => 1), array('purchase_order_date_created' => 'DESC'));
        $completed_purchase_orders = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findBy(array('purchase_order_state' => 3), array('purchase_order_date_created' => 'DESC'));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:review_purchase_orders.html.twig', array(
            'outstanding_purchase_orders' => $outstanding_purchase_orders,
            'incomplete_purchase_orders' => $incomplete_purchase_orders,
            'completed_purchase_orders' => $completed_purchase_orders,
            ));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:review_purchase_orders.html.twig');
   
    }

    public function viewpurchaseorderdetailsAction($purchase_order_id)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Purchase Orders", $this->get("router")->generate("staff-purchase-orders_notifications"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Review Purchase Orders", $this->get("router")->generate("staff-purchase-orders_review-purchase-orders"));
        
        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $purchase_order = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->getPurchaseOrderById($purchase_order_id);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:view_purchase_order_details.html.twig', array(
            'purchase_order' => $purchase_order,
            ));
    }

    public function processpurchaseorderconfirmationAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Purchase Orders", $this->get("router")->generate("staff-purchase-orders_notifications"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Purchase Order Confirmation", $this->get("router")->generate("staff-purchase-orders_process-purchase-order-confirmation"));
    
        $entity = new SelectSupplier();

        $form = $this->createPurchaseOrderConfirmationSupplierSelectForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:process_purchase_order_confirmation.html.twig', array(
            'submitted'=> false,
            'entity' => $entity,
            'form'   => $form->createView(),
            ));
    }

    /**
    * Creates a form to create a suppler select entity.
    *
    * @param CSVFile $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPurchaseOrderConfirmationSupplierSelectForm(SelectSupplier $entity)
    {
        $form = $this->createForm(new PurchaseOrderConfirmationSupplierSelectType(), $entity, array(
            'action' => $this->generateUrl('staff-products_process-purchase-order-supplier-select'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        return $form;
    }

    public function processpurchaseorderconfirmationsupplierselectAction(Request $request, $supplier_id = null) 
    {
        if ($supplier_id == null) {
           //Get the $_POST array.
            if ($request->isXMLHttpRequest()) {
                $response = $_POST;
                //$response = new JsonResponse($r);
            } else {

                $response = "false";
            }

            //Set the new qty and the id of the product transfer request
            $supplier_id = $response["supplier_id"];
        }

        $em = $this->getDoctrine()->getManager();
        //Get the supplier
        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneBy(array('id' => $supplier_id));
        
        //Set the supplier in the array
        $session = new Session();
        $session->set('supplier_id', $supplier->getId());

        //Get existing purchase orders and their contents.
        $purchase_orders = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findBy(array('supplier' => $supplier, 'purchase_order_state' => 2));


        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:purchase_order_confirmation_display_unconfirmed.html.twig', array(
            'purchase_orders' => $purchase_orders,
        ));
   
    }

    public function processpurchaseorderconfirmationuploadcsvAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Purchase Orders", $this->get("router")->generate("staff-purchase-orders_notifications"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Purchase Order Confirmation", $this->get("router")->generate("staff-purchase-orders_process-purchase-order-confirmation"));

         // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Upload CSV", $this->get("router")->generate("staff-purchase-orders_process-purchase-order-confirmation"));
        
        $entity = new PurchaseOrderCSVFile();

        $form = $this->createPurchaseOrderCSVFileForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:purchase_order_confirmation_upload_csv.html.twig', array(
            'submitted'=> false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CSVFile entity.
    *
    * @param CSVFile $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPurchaseOrderCSVFileForm(PurchaseOrderCSVFile $entity)
    {
        $form = $this->createForm(new PurchaseOrderCSVFileType(), $entity, array(
            'action' => $this->generateUrl('staff-products_process-purchase-order-csv'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Upload', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    
    /**
     * Process the upload file
     */

    /*Test for CSV upload - code works to read csv and insert into array.*/ 
    public function processpurchaseordercsvAction(Request $request)
    {

        $entity = new PurchaseOrderCSVFile();
        $form = $this->createPurchaseOrderCSVFileForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $outcome = "true";
            $data = $form->getData();
        } else { 
            $outcome = "false";
            $data="";
        }

        $entity->preupload();
        $entity->upload();
        $csvFilePath = 'purchase-order-uploads/' . $entity->getPurchaseOrderCSVFilePath();

        $csvArray = $this->newcsvimport($request, $csvFilePath);

        return $this->render('MilesApartStaffBundle:PurchaseOrders:process_purchase_order_csv.html.twig', array(
            
            'csvArray'=> $csvArray,
            'outcome'=> $outcome,
            'data'=> $data,
            'csvFilePath'=>$csvFilePath
        ));

    }


    public function newcsvimport(Request $request, $csvFilePath)
    {
        // Create and configure the reader
        $file = new \SplFileObject($csvFilePath);
        $csvReader = new CsvReader($file);
        

        /*$csvReader = new ArrayReader(array(
            'products' => array(
                0 => array(
                    'name' => 'some name',
                    'price' => '€12,16',
                ),
                1 => array(
                    'name' => 'some name',
                    'price' => '€12,16',
                )
            ))
        );
        */

        // Tell the reader that the first row in the CSV file contains column headers
        $csvReader->setHeaderRowNumber(0);

        // Create the workflow from the reader
        $workflow = new Workflow($csvReader);

        $csvArray = array();

        $arrayWriter = new ArrayWriter($csvArray);
        $workflow->addWriter($arrayWriter);

        /*$file = new \SplFileObject('test/output.csv', 'w');
        $writer = new CsvWriter($file);
        $workflow->addWriter($writer);
        */
        

        // Process the workflow
        $upload = $workflow->process();

        return $csvArray;
    }

    function cmp($a, $b)
    {
        return strcmp($a->getPurchaseOrderProduct()->getProduct(), $b->getPurchaseOrderProduct()->getProduct());
    }


    public function processpurchaseorderconfirmationmanualinputAction($id)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Purchase Orders", $this->get("router")->generate("staff-purchase-orders_notifications"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process Purchase Order Confirmation", $this->get("router")->generate("staff-purchase-orders_process-purchase-order-confirmation"));

         // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Manual Input", $this->get("router")->generate("staff-purchase-orders_process-purchase-order-confirmation"));

        //Split the supplier ids
        $id_array = explode("-", $id);

        //Get purchase orders that have been selected
        $em = $this->getDoctrine()->getManager();
        //Get the supplier
        $purchase_orders =  $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findById($id_array);
        
        //Set purchase_orders in session
        $session = new Session();
        $session->set('purchase_orders', $purchase_orders);

        //Order the purchase order prdocts alphabetically
        sort($purchase_orders);

        $entity = new PurchaseOrder();

        $form = $this->createPurchaseOrderConfirmationManualInputForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:PurchaseOrders:purchase_order_confirmation_manual_input.html.twig', array(
            'submitted'=> false,
            'entity' => $entity,
            'form'   => $form->createView(),
            'purchase_orders' => $purchase_orders
        ));
    }

    /**
    * Creates a form to create a Manual input entity.
    *
    * @param  $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPurchaseOrderConfirmationManualInputForm(PurchaseOrder $entity)
    {
        $form = $this->createForm(new PurchaseOrderConfirmationManualInputType(), $entity, array(
            'action' => $this->generateUrl('staff-products_process-purchase-order-confirmation-manual-input-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Confirm Purchase Order', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    

    public function suppliercodecheckAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {
            $response = "false";
        }

        $session = new Session();

        //Set the new qty and the id of the product transfer request
        $productSupplierCode = $response["productCode"];
        $supplierId = $session->get('supplier_id');

        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findBySupplierCode($productSupplierCode, $supplierId);

        //Set the data to be returned
        if($product != null) {
            $response = array(
                "success" => true,
                "product_name" => $product[0]->getProductName(),
                "product_barcode" => $product[0]->getProductBarcode(),
                "product_outer_quantity" => $product[0]->getProductOuterQuantity(),
                "product_inner_quantity" => $product[0]->getProductInnerQuantity(),
                "product_cost" => $product[0]->getCurrentCostDecimal(),
            );
        } else {
            $response = array(
                "success" => false
            );
        }

        return new JsonResponse(
                    $response 
                );
    }


    //Add new purchase order and mark any existing ones (and their products) as complete
    public function processpurchaseorderconfirmationmanualinputsubmitAction(Request $request)
    {
        //Set up the entity manager 
        $em = $this->getDoctrine()->getManager();

        //First, the new purchase order (additions)
        $purchase_order = new PurchaseOrder();
        $form = $this->createPurchaseOrderConfirmationManualInputForm($purchase_order);

        $session = new Session();
        $supplierId = $session->get('supplier_id');
        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneById($supplierId);

        $form->handleRequest($request);

        if ($form->isValid()) {
            //Set each purchase order product to confirmed
            foreach($form->getData()->getPurchaseOrderProduct()->getValues() as $purchase_order_product) {
                //Check that the product exists in the database
                $product = $em->getRepository('MilesApartAdminBundle:Product')->findBySupplierCode($purchase_order_product['product_supplier_code'], $supplierId);

                //If it exists
                if($product) {
                    //Assign product and purchase order to purchase order product 
                    //Create new purchase orderproduct 
                    $new_purchase_order_product = new PurchaseOrderProduct();

                    //Assign the values from the form
                    $new_purchase_order_product->setProduct($product);
                    $new_purchase_order_product->setPurchaseOrder($purchase_order);
                    $new_purchase_order_product->setPurchaseOrderQuantity($purchase_order_product['purchase_order_product_quantity']);
                    //Check if the product cost has changed
                    //Check if unit cost entered 
                    if($purchase_order_product['product_cost'] != null) {
                        $product_cost = $purchase_order_product['product_cost'];
                    //Or total cost
                    } else {
                        $product_cost = ($purchase_order_product['total_cost'] / $purchase_order_product['purchase_order_product_quantity']) * 100;
                        
                        $product_cost = ceil($product_cost);

                        $product_cost = number_format(($product_cost/100), 2);
                    }
                    
                    //Compare new cost with existing
                    if($product->getCurrentCostDecimal() != $product_cost) {
                        //Create new product cost and assign it to the product supplier 
                        //Get product supplier 
                        $product_supplier = $product->getCurrentCost()->getProductSupplier();
                        $new_cost = new ProductCost();
                        $new_cost->setProductCostValue($product_cost);
                        $new_cost->setProductSupplier($product_supplier);
                        $new_cost->setProductCostValidFrom(date("Y-m-d"));
                        $new_cost->setProductCostIsSpecial(false);
                    }


                //No product matches the supplier code so create new one
                } else {
                    //Add the new product, then assign
                    $new_product = new Product();
                    $new_product->setProductSupplierCode($purchase_order_product['product_supplier_code']);
                    $new_product->setProductName($purchase_order_product['product_name']);
                    $new_product->setProductBarcode($purchase_order_product['product_barcode']);
                    $new_product->setProductOuterQuantity($purchase_order_product['product_outer_quantity']);
                    $new_product->setProductInnerQuantity($purchase_order_product['product_inner_quantity']);

                    //Create new product supplier
                    $new_product_supplier = new ProductSupplier();
                    $new_product_supplier->setProduct($product);
                    $new_product_supplier->setSupplier($supplier);
                    $new_product_supplier->setDefaultSupplier(true);

                    //Create new product cost
                    $new_cost = new ProductCost();
                    $new_cost->setProductCostValue($product_cost);
                    $new_cost->setProductSupplier($new_product_supplier);
                    $new_cost->setProductCostValidFrom(date("Y-m-d"));
                    $new_cost->setProductCostIsSpecial(false);

                }

                //Set the purchase order product to confirmed
                $new_purchase_order_product->setConfirmed(true);

            }

            //Set the purchase order as confirmed 
            $purchase_order->setPurchaseOrderState(
                 $em->getRepository('MilesApartAdminBundle:PurchaseOrderState')->findOneById(3)
                 );


           //Insert the new PO into the database
            $em->persist($purchase_order);
            $em->flush();
       
            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The purchase order has been confirmed.');

            //Render the page from template
            return $this->render('MilesApartStaffBundle:PurchaseOrders:purchase_order_confirmation_manual_input_submit.html.twig', array(
                'submitted'=> false,
                'entity' => $entity,
                'form'   => $form->createView(),
                
            ));

        //Form validation has failed 
        } else {
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem with the form.');
            
            //Render the page from template
            return $this->render('MilesApartStaffBundle:PurchaseOrders:purchase_order_confirmation_manual_input.html.twig', array(
                'submitted'=> true,
                'entity' => $purchase_order,
                'form'   => $form->createView(),
                'purchase_orders' => $session->get('purchase_orders'),
            ));
        }
    }
}   



