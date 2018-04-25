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
use MilesApart\AdminBundle\Entity\PurchaseOrderProduct;
use MilesApart\AdminBundle\Entity\StocktakeProduct;
use MilesApart\AdminBundle\Entity\Product;
use MilesApart\AdminBundle\Entity\ProductGroup;
use MilesApart\AdminBundle\Entity\PrintRequest;
use MilesApart\AdminBundle\Entity\ProductPrice;
use MilesApart\AdminBundle\Entity\ProductSupplier;
use MilesApart\AdminBundle\Entity\PurchaseOrder;
use MilesApart\AdminBundle\Entity\TransferRequest;
use MilesApart\AdminBundle\Entity\ProductTransferRequest;
use MilesApart\AdminBundle\Entity\ProductGroupTransferRequest;
use MilesApart\AdminBundle\Entity\BusinessPremises;
use MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct;
use MilesApart\StaffBundle\Form\TransferRequests\TransferRequestType;
use MilesApart\StaffBundle\Form\TransferRequests\ProductTransferRequestType;
use MilesApart\StaffBundle\Form\Helpers\AddProductToListType;

class HelperController extends Controller
{
    /*************************************************
    * Helper controller assists the functions and pages in all menu areas.
    *************************************************/

    public function addProductToListAction($function_variable, $barcode, $search_query = null, $product_supplier_code = null) 
    {
        
        $entity = new Product();

        $form = $this->createAddProductToListForm($entity);
        $em = $this->getDoctrine()->getManager();

        //If the barcode is completed
        if($barcode != null) {

            //Check if the barcode starts with letters (and is therefore a product group)
          
            $string_test = trim($barcode);//removes any spaces from beginning of the string
            if(ctype_alpha($string_test[0])) {
               //Barcode is for product group
                //Get the product group
                
                $product_group = $em->getRepository('MilesApartAdminBundle:ProductGroup')->findOneById(substr($barcode, 3));
                if ($function_variable == "TransferRequest") {
                    
                    $response = $this->forward('MilesApartStaffBundle:Helper:addTransferRequestSingleProductGroup', array(
                        'product_group'  => $product_group,
                    ));
                    
                    //$response->headers->add(array('found' => $found, 'single' => $single));
                
                    return $response; 
                } 

            } else { 
                //Barcode has no letters so is for product
                $entity = $em->getRepository('MilesApartAdminBundle:Product')->findBy(array('product_barcode'=> $barcode));
            }
            
        
            //If there are multiple products with the same barcode.
            if (count($entity) == 0) {
                $found = false;
                $single = true;
            } elseif (count($entity) > 1) {
                $found = true;
                $single = false;
            } else {
                $found = true;
                $single = true;
            }
        
        //If the search query is completed
        } else if($product_supplier_code != null) {
            
            $entity = $em->getRepository('MilesApartAdminBundle:Product')->findBy(array('product_supplier_code'=> $product_supplier_code));

            //If there are multiple products with the same barcode.
            if (count($entity) == 0) {
                $found = false;
                $single = true;
            } elseif (count($entity) > 1) {
                $found = true;
                $single = false;
            } else {
                $found = true;
                $single = true;
            }

        } else if(isset($search_query)) {
           
            $entity = $em->getRepository('MilesApartAdminBundle:Product')->findBy(array('product_name'=> $search_query));
        }  

        if(isset($entity)) {
            
            if (!$entity) {
               
               $product_id = "False";
            } else {
                
                $product_id = $entity[0]->getId();
            }
        } else {
            //Show new product form
            $product_id = "false";
        }
        
        //If the product has been found, add product transfer request
        if ($found == true) {
            if ($single == true) {
                //If only one product found, add it to the transfer request
                
                   //$product_transfer_request = $this->addProductToTransferRequest($entity);
                      
                //Add a single product and get response depending on the function_variable.
                if ($function_variable == "TransferRequest") {
                   
                    
                    $response = $this->forward('MilesApartStaffBundle:Helper:addTransferRequestSingleProduct', array(
                        'product'  => $entity[0],
                        
                    ));
                    
                    $response->headers->add(array('found' => $found, 'single' => $single));
                    
                    
                    return $response; 
                    
                } 

                if ($function_variable == "SeasonalStorageBox") {
                    
                    
                    $response = $this->forward('MilesApartStaffBundle:Helper:addSeasonalStorageBoxSingleProduct', array(
                        'product'  => $entity[0],
                        
                    ));
                    
                    $response->headers->add(array('found' => $found, 'single' => $single));
                    
                    
                    return $response; 
                    
                } 

                if ($function_variable == "PrintRequest") {
                   
                    
                    $response = $this->forward('MilesApartStaffBundle:Helper:addPrintRequestSingleProduct', array(
                        'product'  => $entity[0],
                        
                    ));
                    
                    $response->headers->add(array('found' => $found, 'single' => $single));
                    
                    
                    return $response; 
                    
                } 

                if ($function_variable == "StocktakeProduct") {
                    
                    
                    $response = $this->forward('MilesApartStaffBundle:Helper:addStocktakeProductSingleProduct', array(
                        'product'  => $entity[0],
                        
                    ));
                    
                    $response->headers->add(array('found' => $found, 'single' => $single));
                    
                   
                    return $response; 
                    
                } 

                if ($function_variable == "PurchaseOrderProduct") {
                    
                    $response = $this->forward('MilesApartStaffBundle:Helper:addPurchaseOrderProductSingleProduct', array(
                        'product'  => $entity[0],
                        
                    ));
                    
                    $response->headers->add(array('found' => $found, 'single' => $single));
                    
                    
                    return $response; 
                    
                } 
                    
            } else {
                //Otherwise, show the table of multiple products so selection can be made
                //Iterate over each product found and add to response array
                $response_array = array(
                        'found'=> $found,
                        'single' => $single,
                        'products' => [],
                        );
                foreach($entity as $key => $value) {
                    
                    //Check for supplier 
                    if($value->getDefaultProductSupplier()!= null) {
                        $supplier_name = $value->getDefaultProductSupplier()->getSupplier()->getSupplierName();
                    } else {
                        $supplier_name = "Unknown";
                    }
                    

                    $response = array(     
                        'product_name' => $value->getProductName(), 
                        'product_price'=>$value->getCurrentPriceDisplay(),
                        'product_supplier_code'=> $value->getProductSupplierCode(),
                        'product_barcode' => $value->getProductBarcode(),
                        'supplier_name' => $supplier_name,            
                        'product_id' => $value->getId(),

                    );
                    array_push($response_array['products'], $response);
                }
                
                return new JsonResponse(
            $response_array 
            );
            }

        } elseif ($found == false) {
            //The product has not been found so show the red box.
            $response = array('product' => $entity, 'found'=> $found, 'single' => $single, 'search_query' => $search_query);


            return new JsonResponse(
            $response 
            );
            
        }   
        
    }

    /**
    * Creates a form to create a new product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAddProductToListForm(Product $entity)
    {
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(new AddProductToListType($em), $entity, array(
            'action' => $this->generateUrl('staff-transfer-requests_request-products-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal add_to_product_list_form')
        ));

       
        $form->add('submit', 'submit', array('label' => 'Add', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
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
                'product_price'=> $value->getCurrentPriceDisplay(),
                'product_supplier_code'=> $value->getProductSupplierCode(),
                'product_barcode' => $value->getProductBarcode(),
                'supplier_name' => $value->getDefaultProductSupplier()->getSupplier()->getSupplierName(),            
                'product_id' => $value->getId(),

            );

            array_push($jsonContentArray['products'], $jsonContent);
        }
        
        $response = new JsonResponse($jsonContentArray);
        return $response;
    }

    public function addproducttolistmultipleproductsselectsubmitAction($function_variable, $selection_string)
    {
        $em = $this->getDoctrine()->getManager();
      
       //Explode connection string into array of product ids
       $products = explode('-', $selection_string);

       $count = count($products);

       $product_array = array();

       foreach ($products as $key => $product_id) {

            //Check what function to call
            if ($function_variable == "TransferRequest") {
               
                //Get the product entity from the database
                $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('id'=>$product_id));
                
                //Add new product transfer request for the product
                $product_list_item = $this->addProductToTransferRequestAction($product, false);
            }

            if ($function_variable == "SeasonalStorageBox") {
                //Get the product entity from the database
                $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('id'=>$product_id));
                
                //Add new product seasonal storage box for the product
                $product_list_item = $this->addProductToSeasonalStorageBoxAction($product, false);
            }
        
           

            array_push($product_array, $product_list_item);
        } 

foreach ($product_array as $key => $product) {

    

}
       
       return new JsonResponse(
                array('product_list' => $product_array)
            );


   }


    function addTransferRequestSingleProductAction(Product $product)
    {
        
        //Get transfer request
        $session = new Session();
        $transfer_request = $session->get('transfer_request');
        $em = $this->getDoctrine()->getManager();
        
        //Get transfer request by id
        $transfer_request = $em->merge($transfer_request);
        
        
        //Find product transfer request with the $transfer request and $entity[]->getId that match.
        $existing_product_transfer_request = $em->getRepository('MilesApartAdminBundle:ProductTransferRequest')->findOneBy(array('transfer_request' => $transfer_request, 'product' => $product));
        
        if ($existing_product_transfer_request) {
           
            $duplicate = true;
            $response = array(
                'duplicate' => true,
                'product_name' => $existing_product_transfer_request->getProduct()->getProductName(), 
                'product_qty' => $existing_product_transfer_request->getProductTransferRequestQty(), 
                'product_id' => $existing_product_transfer_request->getId(),
                );
            return new JsonResponse(
                $response 
            );

        } else {
            
            $response = $this->forward('MilesApartStaffBundle:Helper:addProductToTransferRequest', array(
                'product'  => $product,
                'returnJson' => true,
                        
            ));
        }

       
        return $response ;
        
    }

    function addProductToTransferRequestAction(Product $product, $returnJson) 
    {
        
        $em = $this->getDoctrine()->getManager();

        $product = $em->merge($product);

       
        $session = new Session();
        $transfer_request = $session->get('transfer_request');

        $transfer_request = $em->merge($transfer_request);
        
        //If all is ok, add the product to the product transfer request table
        //New product tarnsfer request
        $product_transfer_request = new ProductTransferRequest();

        //Set the product
        $product_transfer_request->setProduct($product);
        
        //Set the transfer                                                 
        $product_transfer_request->setTransferRequest($transfer_request);

        $product_transfer_request->setProductTransferRequestQty(6);
        //$product_transfer_request->setProductTransferRequestState(1);

        //Persist row to the database.
        
        $em->persist($product_transfer_request);
       
        $em->flush();
        

        $response = array(
            'product_name' => $product_transfer_request->getProduct()->getProductName(), 
            'product_stock_qty'=>'NA', 
            'product_price'=> $product_transfer_request->getProduct()->getCurrentPriceDisplay(), 
            'product_supplier_code'=> $product_transfer_request->getProduct()->getProductSupplierCode(),
            'product_barcode' => $product_transfer_request->getProduct()->getProductBarcode(),
            'supplier_name' => $product_transfer_request->getProduct()->getDefaultProductSupplier()->getSupplier()->getSupplierName(),
            'product_qty'=> $product_transfer_request->getProductTransferRequestQty(), 
            'product_id' => $product_transfer_request->getId(),
            'prod_id' => $product_transfer_request->getProduct()->getId(),
            'found' => true,
            'single' => true,
            
        );
        
      
        
        if ($returnJson == true) {
            return new JsonResponse(
                $response 
            );
        } else {
            return $response;
        }
            
        
    }

    function addTransferRequestSingleProductGroupAction(ProductGroup $product_group)
    {
        //Get transfer request
        $session = new Session();
        $transfer_request = $session->get('transfer_request');
        $em = $this->getDoctrine()->getManager();
        
        //Get transfer request by id
        $transfer_request = $em->merge($transfer_request);
        
        //Find product transfer request with the $transfer request and $entity[]->getId that match.
        $existing_product_group_transfer_request = $em->getRepository('MilesApartAdminBundle:ProductGroupTransferRequest')->findOneBy(array('transfer_request' => $transfer_request, 'product_group' => $product_group));
        if ($existing_product_group_transfer_request) {
           
            $duplicate = true;
            $response = array(
                'duplicate' => true,
                'product_name' => $existing_product_group_transfer_request->getProductGroup()->getProductGroupName(), 
                'product_qty' => $existing_product_group_transfer_request->getProductGroupTransferRequestQty(), 
                'product_id' => $existing_product_group_transfer_request->getId(),
                );
            return new JsonResponse(
                $response 
            );

        } else {
            $response = $this->forward('MilesApartStaffBundle:Helper:addProductGroupToTransferRequest', array(
                'product_group'  => $product_group,
                'returnJson' => true,
                        
            ));
        }

       
        return $response ;
        
    }

    function addProductGroupToTransferRequestAction(ProductGroup $product_group, $returnJson) 
    {   
        $em = $this->getDoctrine()->getManager();

        $product_group = $em->merge($product_group);

        $session = new Session();
        $transfer_request = $session->get('transfer_request');

        $transfer_request = $em->merge($transfer_request);
        
        //If all is ok, add the product to the product transfer request table
        //New product tarnsfer request
        $product_group_transfer_request = new ProductGroupTransferRequest();

        //Set the product
        $product_group_transfer_request->setProductGroup($product_group);
        
        //Set the transfer                                                 
        $product_group_transfer_request->setTransferRequest($transfer_request);

        $product_group_transfer_request->setProductGroupTransferRequestQty(6);
        //$product_transfer_request->setProductTransferRequestState(1);

        //Persist row to the database.
        $em->persist($product_group_transfer_request);
       
        $em->flush();

        $response = array(
            'product_name' => $product_group_transfer_request->getProductGroup()->getProductGroupName(), 
            'product_stock_qty'=>'NA', 
            'product_price'=> $product_group_transfer_request->getProductGroup()->getProductGroupDefaultPriceDisplay(), 
            'product_supplier_code'=> null,
            'product_barcode' => $product_group_transfer_request->getProductGroup()->getProductGroupBarcode(),
            'supplier_name' => null,
            'product_qty'=> $product_group_transfer_request->getProductGroupTransferRequestQty(), 
            'product_id' => $product_group_transfer_request->getId(),
            'prod_id' => $product_group_transfer_request->getProductGroup()->getId(),
            'found' => true,
            'single' => true,
        );
        
       
        
        if ($returnJson == true) {
            return new JsonResponse(
                $response 
            );
        } else {
            return $response;
        }
                 
    }

    function addPrintRequestSingleProductAction(Product $product)
    {

        
        $em = $this->getDoctrine()->getManager();
        
        
        
        //Find product transfer request with the $transfer request and $entity[]->getId that match.
        $existing_print_request = $em->getRepository('MilesApartAdminBundle:PrintRequest')->findOneBy(array('print_request_printed' => 0, 'product' => $product));
        
        if ($existing_print_request) {
           
            $duplicate = true;
            $response = array(
                'duplicate' => true,
                'product_name' => $existing_product_transfer_request->getProduct()->getProductName(), 
                'product_qty' => $existing_product_transfer_request->getProductTransferRequestQty(), 
                'product_id' => $existing_product_transfer_request->getId(),
                );
            return new JsonResponse(
                $response 
            );

        } else {
            
            $response = $this->forward('MilesApartStaffBundle:Helper:addProductToPrintRequest', array(
                'product'  => $product,
                'returnJson' => true,
                        
            ));
        }

        
        return $response ;
        
    }



    function addProductToPrintRequestAction(Product $product, $returnJson) 
    {
        $session = new Session();
        $price_request_business_premises = $session->get('price_request_business_premises');
        $print_request_type = $session->get('print_request_type');

        $em = $this->getDoctrine()->getManager();

        $product = $em->merge($product);
        $price_request_business_premises = $em->merge($price_request_business_premises);
        $print_request_type = $em->merge($print_request_type);

        
        //If all is ok, add the product to the product transfer request table

        //New product tarnsfer request
        $print_request = new PrintRequest();

        //Set the product
        $print_request->setProduct($product);

        //Set the business premises
        $print_request->setBusinessPremises($price_request_business_premises);

        //Set the print price type
        $print_request->setPrintRequestType($print_request_type);

        $print_request->setPrintRequestQty(1);
        //$product_transfer_request->setProductTransferRequestState(1);

        //Persist row to the database.
        
        $em->persist($print_request);
        
        $em->flush();
        

        //Set up short name and subtitle
        if($print_request->getProduct()->getShortName() != NULL) {
            $short_name = $print_request->getProduct()->getShortName();
        } else {
            $short_name = NULL;
        }

        if($print_request->getProduct()->getPrintSubtitle() != NULL) {
            $subtitle = $print_request->getProduct()->getPrintSubtitle();
        } else {
            $subtitle = NULL;
        }

        $response = array(
            'product_name' => $print_request->getProduct()->getProductName(), 
            'product_stock_qty'=>'NA', 
            'product_price'=> $print_request->getProduct()->getCurrentPriceDisplay(), 
            'product_supplier_code'=> $print_request->getProduct()->getProductSupplierCode(),
            'product_barcode' => $print_request->getProduct()->getProductBarcode(),
            'supplier_name' => $print_request->getProduct()->getDefaultProductSupplier()->getSupplier()->getSupplierName(),
            'product_qty'=> $print_request->getPrintRequestQty(), 
            'product_id' => $print_request->getId(),
            'prod_id' => $print_request->getProduct()->getId(),
            'found' => true,
            'single' => true,
            'short_name' => $short_name,
            'subtitle' => $subtitle
            
        );
        

        
        if ($returnJson == true) {
            return new JsonResponse(
                $response 
            );
        } else {
            return $response;
        }
            
        
    }

    function addStocktakeProductSingleProductAction(Product $product)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        //Find product transfer request with the $transfer request and $entity[]->getId that match.

        //Get the session data to check if product exists for this stocktake and shelf.
        $session = new Session();

        $stocktake = $session->get('stocktake');
        $stock_location_shelf = $session->get('stock_location_shelf');

        $stocktake = $em->merge($stocktake);
        $stock_location_shelf = $em->merge($stock_location_shelf);
            
        $existing_stocktake_product = $em->getRepository('MilesApartAdminBundle:StocktakeProduct')->findOneBy(array('product' => $product, 'stock_location_shelf' => $stock_location_shelf, 'stocktake' => $stocktake));
       
        if ($existing_stocktake_product) {
            
            $duplicate = true;
            $response = array(
                'duplicate' => true,
                'product_name' => $existing_stocktake_product->getProduct()->getProductName(), 
                'product_qty' => $existing_stocktake_product->getStocktakeProductQty(), 
                'product_id' => $existing_stocktake_product->getId(),
                );
            return new JsonResponse(
                $response 
            );

        } else {
            
            $response = $this->forward('MilesApartStaffBundle:Helper:addProductToStocktakeProduct', array(
                'product'  => $product,
                'returnJson' => true,
                        
            ));
        }

        
        return $response ;
        
    }

    function addProductToStocktakeProductAction(Product $product, $returnJson) 
    {
        //Get the shelf from the session.
        $session = new Session();
        $stock_location_shelf = $session->get('stock_location_shelf');
        $stocktake = $session->get('stocktake');

        
        $em = $this->getDoctrine()->getManager();

        $product = $em->merge($product);
        $stock_location_shelf = $em->merge($stock_location_shelf);
        $stocktake = $em->merge($stocktake);

       
        //If all is ok, add the product to the product transfer request table

        //New product tarnsfer request
        $stocktake_product = new StocktakeProduct();

        //Set the stocktake 
        $stocktake_product->setStocktake($stocktake);

        //Set the product
        $stocktake_product->setProduct($product);

        //Set the stock location shelf
        $stocktake_product->setStockLocationShelf($stock_location_shelf);

        //Set the default qty
        $stocktake_product->setStocktakeProductQty(0);

        //Persist row to the database.
        
        $em->persist($stocktake_product);
        
        $em->flush();
        
        //Check supplier is set
        if($stocktake_product->getProduct()->getDefaultProductSupplierObject() != null) {
            $sup = $stocktake_product->getProduct()->getDefaultProductSupplierObject()->getSupplierShortName();
        } else {
            $sup = null;
        }

        $response = array(
            'product_name' => $stocktake_product->getProduct()->getProductName(), 
            'product_stock_qty'=>'NA', 
            'product_price'=> $stocktake_product->getProduct()->getCurrentPriceDisplay(), 
            'product_supplier_code'=> $stocktake_product->getProduct()->getProductSupplierCode(),
            'product_barcode' => $stocktake_product->getProduct()->getProductBarcode(),
            'supplier_name' => $sup,
            'product_qty'=> $stocktake_product->getStocktakeProductQty(), 
            'product_id' => $stocktake_product->getId(),
            'prod_id' => $stocktake_product->getProduct()->getId(),
            'found' => true,
            'single' => true,         
        );
       
        
        
        if ($returnJson == true) {
            return new JsonResponse(
                $response 
            );
        } else {
            return $response;
        }
            
        
    }

    function addPurchaseOrderProductSingleProductAction(Product $product)
    {
    
        
        $em = $this->getDoctrine()->getManager();
        
        //Find product transfer request with the $transfer request and $entity[]->getId that match.

        //Get the product
        $em = $this->getDoctrine()->getManager();
        //Get the supplier of the product
        $product = $em->merge($product);

    
        $supplier_variable = $product->getDefaultProductSupplierObject();
        //Check if there is an incomplete purchase order for this supplier
        
        $purchase_order = $em->getRepository('MilesApartAdminBundle:PurchaseOrder')->findBy(array('supplier' => $supplier_variable, 'purchase_order_state' => 1));
       

        if($purchase_order != NULL) {
            
            //Check if the product exists in the existing purchase order
            $existing_purchase_order_product = $em->getRepository('MilesApartAdminBundle:PurchaseOrderProduct')->findOneBy(array('product' => $product, 'purchase_order' => $purchase_order));
        
            if ($existing_purchase_order_product) {
               
                $duplicate = true;
                $response = array(
                    'duplicate' => true,
                    'product_name' => $existing_purchase_order_product->getProduct()->getProductName(), 
                    'product_qty' => $existing_purchase_order_product->getPurchaseOrderProductQuantity(), 
                    'product_id' => $existing_purchase_order_product->getId(),
                    );
                return new JsonResponse(
                    $response 
                );

            } else {
                //Set teh session
                $session = new Session();
                $session->set('purchase_order', $purchase_order);

                
                $response = $this->forward('MilesApartStaffBundle:Helper:addProductToPurchaseOrderProduct', array(
                    'product'  => $product,
                    'returnJson' => true,
                            
                ));
            }

        } else {
            
            //If not, create a new purchase order
            $purchase_order = new PurchaseOrder();
            
            //Set the supplier of the new purchase order
            $purchase_order->setSupplier($product->getDefaultProductSupplierObject());
            
            
            $purchase_order_state = $em->getRepository('MilesApartAdminBundle:PurchaseOrderState')->findOneBy(array('id' => 1));
            $purchase_order->setPurchaseOrderState($purchase_order_state);
           
            //Set teh session
            $session = new Session();
            $session->set('purchase_order', $purchase_order);

            $em->persist($purchase_order);
            $em->flush();
            $response = $this->forward('MilesApartStaffBundle:Helper:addProductToPurchaseOrderProduct', array(
                    'product'  => $product,
                    'returnJson' => true,
                            
                ));
        }

       

        
        
        return $response ;
        
    }

    function addProductToPurchaseOrderProductAction(Product $product, $returnJson) 
    {
    

        //Get the shelf from the session.
        $session = new Session();
        $purchase_order = $session->get('purchase_order');
       
        $em = $this->getDoctrine()->getManager();

        $product = $em->merge($product);
        
        //Check if purchase order is an array
        if (is_array($purchase_order)) { 
            $purchase_order = $em->merge($purchase_order[0]);
        } else {
            $purchase_order = $em->merge($purchase_order);
        }

        
        //If all is ok, add the product to the product transfer request table

        //New product tarnsfer request
        $purchase_order_product = new PurchaseOrderProduct();

        //Set the purchase_order
        $purchase_order_product->setPurchaseOrder($purchase_order);

        //Set the product
        $purchase_order_product->setProduct($product);


        //Set the default qty
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
               $order_qty = $purchase_order_product->getProduct()->getProductOuterQuantity();
            }
        }

        $purchase_order_product->setPurchaseOrderProductQuantity($order_qty);

        //Persist row to the database.
        
        $em->persist($purchase_order_product);
        
        $em->flush();
        

        $response = array(
            'product_name' => $purchase_order_product->getProduct()->getProductName(), 
            'product_stock_qty'=>'NA', 
            'product_price'=> $purchase_order_product->getProduct()->getCurrentPriceDisplay(),
            'product_cost' => $purchase_order_product->getProduct()->getCurrentCostDecimal(),
            'product_supplier_code'=> $purchase_order_product->getProduct()->getProductSupplierCode(),
            'product_barcode' => $purchase_order_product->getProduct()->getProductBarcode(),
            'supplier_name' => $purchase_order_product->getProduct()->getDefaultProductSupplierObject()->getSupplierName(),
            'product_qty'=> $purchase_order_product->getPurchaseOrderProductQuantity(), 
            'product_id' => $purchase_order_product->getId(),
            'prod_id' => $purchase_order_product->getProduct()->getId(),
            'table_id' => $purchase_order_product->getPurchaseOrder()->getId(),
            'product_outers_quantity' => $purchase_order_product->getPurchaseOrderProductOuters(),
            'product_inners_quantity' => $purchase_order_product->getPurchaseOrderProductInners(),
            'found' => true,
            'single' => true,
            
        );
        
       
        
        if ($returnJson == true) {
            return new JsonResponse(
                $response 
            );
        } else {
            return $response;
        }
            
        
    }

    function addSeasonalStorageBoxSingleProductAction(Product $product)
    {
        
        //Get transfer request
        $session = new Session();
        $seasonal_storage_box = $session->get('seasonal_storage_box');
        $em = $this->getDoctrine()->getManager();
        
        //Get transfer request by id
        $seasonal_storage_box = $em->merge($seasonal_storage_box);
        
        
        //Find product transfer request with the $transfer request and $entity[]->getId that match.
        $existing_seasonal_storage_box_product = $em->getRepository('MilesApartAdminBundle:SeasonalStorageBoxProduct')->findOneBy(array('seasonal_storage_box' => $seasonal_storage_box, 'product' => $product));
        
        if ($existing_seasonal_storage_box_product) {
            
            $duplicate = true;
            $response = array(
                'duplicate' => true,
                'product_name' => $existing_seasonal_storage_box_product->getProduct()->getProductName(), 
                'product_qty' => $existing_seasonal_storage_box_product->getSeasonalStorageBoxProductQty(), 
                'product_id' => $existing_seasonal_storage_box_product->getId(),
                );
            return new JsonResponse(
                $response 
            );

        } else {
           
            $response = $this->forward('MilesApartStaffBundle:Helper:addProductToSeasonalStorageBox', array(
                'product'  => $product,
                'returnJson' => true,
                        
            ));
        }

       
        return $response ;
        
    }

    function addProductToSeasonalStorageBoxAction(Product $product, $returnJson) 
    {
        
        $em = $this->getDoctrine()->getManager();

        $product = $em->merge($product);

        
        $session = new Session();
        $seasonal_storage_box = $session->get('seasonal_storage_box');

        $seasonal_storage_box = $em->merge($seasonal_storage_box);
        
        //Get the season forn the box code
        $season_code = substr($seasonal_storage_box->getSeasonalStorageBoxCode(), 0, 2);

        //Get the season entity
        $season = $em->getRepository('MilesApartAdminBundle:Season')->findOneBy(array('season_code' => $season_code));
        
        $match = false;

        $product->removeSeason($season);
        $product->addSeason($season);
        


        //If all is ok, add the product to the product transfer request table
        //New product tarnsfer request
        $seasonal_storage_box_product = new SeasonalStorageBoxProduct();

        //Set the product
        $seasonal_storage_box_product->setProduct($product);
        
        //Set the transfer                                                 
        $seasonal_storage_box_product->setSeasonalStorageBox($seasonal_storage_box);

        $seasonal_storage_box_product->setSeasonalStorageBoxProductQty(4);
        //$product_transfer_request->setProductTransferRequestState(1);

        //Persist row to the database.
       
        $em->persist($seasonal_storage_box_product);
        
        $em->flush();
        

        $response = array(
            'product_name' => $seasonal_storage_box_product->getProduct()->getProductName(), 
            'product_stock_qty'=>'NA', 
            'product_price'=> $seasonal_storage_box_product->getProduct()->getCurrentPriceDisplay(), 
            'product_supplier_code'=> $seasonal_storage_box_product->getProduct()->getProductSupplierCode(),
            'product_barcode' => $seasonal_storage_box_product->getProduct()->getProductBarcode(),
            'supplier_name' => $seasonal_storage_box_product->getProduct()->getDefaultProductSupplierObject()->getSupplierName(),
            'product_qty'=> $seasonal_storage_box_product->getSeasonalStorageBoxProductQty(), 
            'product_id' => $seasonal_storage_box_product->getId(),
            'prod_id' => $seasonal_storage_box_product->getProduct()->getId(),
            'found' => true,
            'single' => true,
            
        );
       
        
        
        if ($returnJson == true) {
            return new JsonResponse(
                $response 
            );
        } else {
            return $response;
        }
            
        
    }

}