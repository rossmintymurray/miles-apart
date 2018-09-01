<?php
// src/MilesApart/StaffBundle/Controller/AmazonController.php

namespace MilesApart\SellerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use CaponicaAmazonMwsComplete\ClientPool\MwsClientPool;
use CaponicaAmazonMwsComplete\ClientPool\MwsClientPoolConfig;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerBuilder;

use MilesApart\AdminBundle\Entity\BusinessCustomer;
use MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative;
use MilesApart\AdminBundle\Entity\BusinessCustomerRepresentativeCustomerOrder;
use MilesApart\AdminBundle\Entity\PersonalCustomer;
use MilesApart\AdminBundle\Entity\CustomerOrder;
use MilesApart\AdminBundle\Entity\Customer;
use MilesApart\AdminBundle\Entity\CustomerAddress;
use MilesApart\AdminBundle\Entity\CustomerOrderProduct;
use MilesApart\SellerBundle\Entity\AmazonOrder;
use MilesApart\AdminBundle\Entity\Product;
use MilesApart\AdminBundle\Entity\ProductFeature;
use MilesApart\AdminBundle\Entity\Brand;
use MilesApart\SellerBundle\Form\NewProductModalType;

use MilesApart\SellerBundle\Form\AmazonPriceCheckType;

class AmazonController extends Controller
{
    /*************************************************
    * Suppliers controller displays the functions and pages in suppliers menu area.
    *************************************************/

    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Sellers Notifications", $this->get("router")->generate("miles_apart_seller_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartSellerBundle:Amazon:notifications.html.twig', array(
            
        ));
   
    }

    /*************************************************
    *
    * Functions that call the XML files and make the upload with Amazon
    *
    *************************************************/
    /************** Get Amazon Orders *********************/
    public function getamazonordersAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Sellers Notifications", $this->get("router")->generate("miles_apart_seller_notifications"));
       
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Get Amazon Orders", $this->get("router")->generate("miles_apart_seller_get-amazon-orders"));
              
        $orders = $this->getNewOrders();

        if($orders != null) {
            $orders_output = $orders->getListOrdersResult()->getOrders();
            $output = $this->saveNewOrders($orders);
            if($output != null) {
                $output_output = $output->getListOrderItemsResult()->getOrderItems();
            } else {
                $output_output = null;
            }
        } else {
            $orders_output = null;
            $output_output = null;
        }

        return $this->render('MilesApartSellerBundle:Amazon:get_amazon_orders.html.twig', array(
            'orders' => $orders_output,
            'output' => $output_output,
            
        ));
    }

    /************** Price Check *********************/
    public function amazontotalpriceAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Sellers Notifications", $this->get("router")->generate("miles_apart_seller_notifications"));
        // Add pick & pack to breadcrumb.
        
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Price Check", $this->get("router")->generate("miles_apart_seller_amazon-total-price"));
              
        $form = $this->createAmazonPriceCheckForm();


        //Render the page from template
        return $this->render('MilesApartSellerBundle:Amazon:amazon_price_check.html.twig', array(
            'submitted' => false,
            'form'   => $form->createView(),
        ));
    }

     /**
    * Creates a form to product entity by barcode.
    *
    * @param ProductType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAmazonPriceCheckForm()
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new AmazonPriceCheckType(), null, array(
            'action' => $this->generateUrl('miles_apart_seller_amazon-price-check-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Check price', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-3 col-xs-12')));

        return $form;
    }
    

    /**
     * Finds and displays a Product entity.
     *
     */
    public function amazontotalpricesubmitAction(Request $request)
    {
       
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("Price Check", $this->get("router")->generate("staff-products_price-check"));
        $breadcrumbs->addItem("Price Result");
        
        $defaultData = array('product_barcode' => '');
       
        $form = $this->createAmazonPriceCheckForm();

      
           
        $form->submit($request->request->get($form->getName()));

        $barcode = $form["product_barcode"]->getData();


        $asin = $this->getAsinFromBarcode($barcode);
        if(!$asin) {
            return $this->render('MilesApartSellerBundle:Amazon:amazon_price_check.html.twig', array(
            'price' => false,
            'shipping' => false,
            'total' => false,
            'asin' => false,
            'submitted' => false,
            'form'   => $form->createView(),
            'product' => false,
        ));
        }
        $price = $this->getAmazonPriceCheck($asin); 
        $shipping = $this->getAmazonShippingCheck($asin);
        $total = $this->getAmazonPriceCheck($asin) + $this->getAmazonShippingCheck($asin);

        $product = $this->getProductObjectFromBarcode($barcode);
         $form = $this->createAmazonPriceCheckForm();

        return $this->render('MilesApartSellerBundle:Amazon:amazon_price_check.html.twig', array(
            'price' => $price,
            'shipping' => $shipping,
            'total' => $total,
            'asin' => $asin,
            'submitted' => false,
            'form'   => $form->createView(),
            'product' => $product,
        ));
    }

 //Get the Product for any given barcode
    public function amazonproductmodalAction(Request $request)
    {
        $match = false;
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }
$logger = $this->get('logger');
        $logger->info('I just got the logger tr ');
        //Set the new price and the product id
        $barcode = $response["barcode"];
        $submitUrl = $response["submitUrl"];
        $functionName = $response["functionName"];
        $variablePrepend = $response["variablePrepend"];
        $logger->info('I just got the logger br-- ' . $barcode);
$logger->info('I just got the logger br ');
        $em = $this->getDoctrine()->getManager();
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $logger->info('I just got the logger cr ');
        $mwsProductClientPackUk = $mwsClientPoolUk->getProductClientPack();
$logger->info('I just got the logger dr ');
        $mwsResponse = $mwsProductClientPackUk->callGetMatchingProductForId('EAN', $barcode);

        $logger->info('I just got the logger tr 5555 ');
        //Check if products were found 
       
        if(count($mwsResponse->getGetMatchingProductForIdResult()[0]->getProducts()) > 0) {
            
            //Product was found so 
            //Set up the form (using standard product form)
            //New product action
            $product = new Product();
        

            
$logger->info('I just got the logger tr 235235');
$logger->info($mwsResponse->getRawXml());
            //Handle the xml data
            $xml = preg_replace('~(</?|\s)([a-z0-9_]+):~is', '$1$2_', $mwsResponse->getRawXml());
            $logger->info($xml);

            $xml = new \SimpleXMLElement(stripslashes($xml));

$logger->info('I just got the logger tr 235235');
            $logger->info($xml);
            //No fault
            $body = $xml->xpath("//*[local-name()='GetMatchingProductForIdResponse']");

$logger->info('I just got the logger tr rt5');
            $allocated = TRUE;
            $array = json_decode(json_encode($body), TRUE); 

$logger->info('I just got the logger tr rt6');

            //Check if we have single producs or array of products
            if(array_key_exists('GetMatchingProductForIdResult', $array[0])) {
$logger->info('I just got the logger tr got1');
                if(array_key_exists('Products', $array[0]['GetMatchingProductForIdResult'])) {
$logger->info('I just got the logger tr got2');
                    if(array_key_exists('Product', $array[0]['GetMatchingProductForIdResult']['Products'])) {
$logger->info('I just got the logger tr got3');
                        //if(count($array[0]['GetMatchingProductForIdResult']['Products']) > 1) {
$logger->info('I just got the logger tr got4');
                            //if(array_key_exists(0, $array[0]['GetMatchingProductForIdResult']['Products'])) {
    $logger->info('I just got the logger tr got5');
                                $amazon_product_array = $array[0]['GetMatchingProductForIdResult']['Products']['Product']['AttributeSets'];
                                $match = true;
                                $logger->info('I just got the logger tr got6');
                            //} else {
                                //$match = false;
                            //}
                        //} else {
                            //$match = false;
                        //}
                    } else {
                        $match = false;
                    }
                } else if(array_key_exists('Product', $array[0]['GetMatchingProductForIdResult'])) {
                    if(array_key_exists('AttributeSets', $array[0]['GetMatchingProductForIdResult']['Product'])) {
                        $logger->info('I just got the logger tr got7');
                        $amazon_product_array = $array[0]['GetMatchingProductForIdResult']['Product']['AttributeSets'];
                        $match = true;
                        $logger->info('I just got the logger tr got8');
                    } else {
                        $match = false;
                    }
                } else {
                    $logger->info('I just got the logger tr got9');
                    $match = false;
                }
            } else {
                $match = FALSE;
            }
$logger->info('I just got the logger tr got10');
            
            if($match) {
$logger->info('I just got the logger tr 1');
                //Asign values returned from Amazon into the form
                $product->setProductName($amazon_product_array["ns2_ItemAttributes"]['ns2_Title']);
                $product->setProductBarcode($barcode);
                $logger->info('I just got the logger tr 12');
                //Convert height from inches to mm
                if(isset($amazon_product_array["ns2_ItemAttributes"]['ns2_PackageDimensions']['ns2_Height'])) {
                    $height_inches = $amazon_product_array["ns2_ItemAttributes"]['ns2_PackageDimensions']['ns2_Height'];
                    $height_inches = floatval($height_inches);
                    $height_mm = $height_inches/0.039370;
                    $product->setProductDepth($height_mm);
                }
$logger->info('I just got the logger tr 13');
                //Convert length from inches to mm
                if(isset($amazon_product_array["ns2_ItemAttributes"]['ns2_PackageDimensions']['ns2_Length'])) {
                    $length_inches = $amazon_product_array["ns2_ItemAttributes"]['ns2_PackageDimensions']['ns2_Length'];
                    $length_inches = floatval($length_inches);
                    $length_mm = $length_inches/0.039370;
                    $product->setProductHeight($length_mm);
                }
$logger->info('I just got the logger tr 14');
                //Convert width from inches to mm
                if(isset($amazon_product_array["ns2_ItemAttributes"]['ns2_PackageDimensions']['ns2_Width'])) {
                    $width_inches = $amazon_product_array["ns2_ItemAttributes"]['ns2_PackageDimensions']['ns2_Width'];
                    $width_inches = floatval($width_inches);
                    $width_mm = $width_inches/0.039370;
                    $product->setProductWidth($width_mm);
                }
$logger->info('I just got the logger tr 15');

                //Convert weight from ounces to grams
                if(isset($amazon_product_array["ns2_ItemAttributes"]['ns2_PackageDimensions']['ns2_Weight'])) {
                    $weight_pounds = $amazon_product_array["ns2_ItemAttributes"]['ns2_PackageDimensions']['ns2_Weight'];
                    $logger->info('I just got the logger tr 1516');
                    $weight_pounds = floatval($weight_pounds);
                    $logger->info('I just got the logger tr 1616');
                    $weight_grams = $weight_pounds/0.0022046;
                    $logger->info('I just got the logger tr 1716');
                    $product->setProductWeight($weight_grams);
                    $logger->info('I just got the logger tr 16');
                }

                //Check feature exists
                if(array_key_exists('ns2_Feature', $amazon_product_array["ns2_ItemAttributes"])) {
                    //Iterate over the featurs, adding each one
                    if(is_array($amazon_product_array["ns2_ItemAttributes"]["ns2_Feature"])) {
                        foreach($amazon_product_array["ns2_ItemAttributes"]["ns2_Feature"] as $amazon_feature) {
                            $logger->info('I just got the logger tr 17');
                            $product_feature = new ProductFeature();
                            $product_feature->setProductFeatureText($amazon_feature);
                            $product_feature->setProduct($product);
                            $product->addProductFeature($product_feature);
                            $em->persist($product_feature);
                            $logger->info('I just got the logger tr 18');
                        } 
                    } else {
                        //If just a single feature
                        $product_feature = new ProductFeature();
                        $product_feature->setProductFeatureText($amazon_product_array["ns2_ItemAttributes"]["ns2_Feature"]);
                        $product_feature->setProduct($product);
                        $product->addProductFeature($product_feature);
                        $em->persist($product_feature);
                        $logger->info('I just got the logger tr 18other');
                    }
                }

                //Set the brand
                //Check if it exists
                $logger->info('I just got the logger tr 19');
                if(isset($amazon_product_array["ns2_ItemAttributes"]['ns2_Brand'])) {
                    $logger->info('I just got the logger tr 119');
                    $brand = $em->getRepository('MilesApartAdminBundle:Brand')->findOneBy(
                        array( 'brand_name' => $amazon_product_array["ns2_ItemAttributes"]['ns2_Brand'])
                    );
                
                    //Add new brand to tha database
                    $logger->info('I just got the logger tr 2');
                    if(!$brand) {
                        $logger->info('I just got the logger tr 23');
                        //Create new brand 
                        $brand = new Brand();
                        $logger->info('I just got the logger tr 24');
                        //Set brand name 
                        $brand->setBrandName($amazon_product_array["ns2_ItemAttributes"]['ns2_Brand']);
                        $logger->info('I just got the logger tr 26');
                        //Persist to database 
                        $em->persist($brand);
                        $logger->info('I just got the logger tr 27');
                        $em->flush();
                        $logger->info('I just got the logger tr 25');
                    }

                    //Set the product brand
                    $product->setBrand($brand); 
                    $logger->info('I just got the logger tr 2');
                }
                

                //Create the form 
                $form = $this->createAmazonProductModalForm($product);
$logger->info('I just got the logger tr 3');
                //Return the modal html
                $html = $this->renderView('MilesApartSellerBundle:Amazon:amazon_product_modal.html.twig', array(
                    'form'   => $form->createView(),
                    //'product' => $product,
                    'submitted' => false,
                    'mws' => $mwsResponse->getRawXml(),
                    'image' => $amazon_product_array["ns2_ItemAttributes"]['ns2_SmallImage']['ns2_URL'],
                    'barcode' => $barcode,
                    'product_name' => $amazon_product_array["ns2_ItemAttributes"]['ns2_Title'],
                    'submitUrl' => $submitUrl,
                    'functionName' => $functionName,
                    'variablePrepend' => $variablePrepend
                ));
            $logger->info('I just got the logger tr 4');
            } else {
                
                $html = false;
            
            }

        } else {
            $html = false;
        }


        $response = array("match" => $match, "html" => $html);
        
        return new JsonResponse(
                    $response 
                );
    
    }

    /**
    * Creates a form to create a new product from amazon details.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAmazonProductModalForm(Product $product)
    {
        $form = $this->createForm(new NewProductModalType(), $product, array(
            'action' => $this->generateUrl('miles_apart_seller_amazon-product-modal-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function amazonproductmodalsubmitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Create the form 
        $entity = new Product();
        $form = $this->createAmazonProductModalForm($entity);

        $form->handleRequest($request);

        $logger = $this->get('logger');
        $logger->info('I just got the logger tFJHGFFJHGFr ');

        //Checj that the form passes validation
        if ($form->isValid()) {

            //Assign each submitted cost to this product
            foreach($form->get('product_price')->getData() as $price) {
                $price->setProduct($entity);
                //Checj if valid from date is set.
                if($price->getProductPriceValidFrom() != null) {

                } else {
                    $price->setProductPriceValidFrom(new \DateTime());
                }
                $entity->removeProductPrice($price);
                $entity->addProductPrice($price);
            }

            //Assign each submitted cost to this product
            foreach($form->get('product_supplier')->getData() as $sup) {
                $sup->setProduct($entity);
                $entity->removeProductSupplier($sup);
                $entity->addProductSupplier($sup);
            }

            //Assign each submitted cost to this product
            foreach($form->get('product_feature')->getData() as $feature) {
                $feature->addProduct($entity);
                $entity->removeProductFeature($feature);
                $entity->addProductFeature($feature);
            }

            //Assign each submitted cost to this product
            foreach($form->get('attribute_value')->getData() as $attribute) {
                
                //MAKE CALL TO CHECK IF TAG EXISTS IN THE DATABASE FIRST
                //Find the entity that matches the photo tag text
                $existing_attribute = $em->getRepository('MilesApartAdminBundle:AttributeValue')->findOneBy(array('attribute_value' => $attribute->getAttributeValue(), 'attribute' => $attribute->getAttribute()->getId()));
                if (!$existing_attribute) {
                    $attribute->addProduct($entity);
                    $entity->removeAttributeValue($attribute);
                    $entity->addAttributeValue($attribute);
                } else {
                    $entity->removeAttributeValue($attribute);
                    //IF IT DOES EXIST, THEN WE ASSIGN ITS ID TO THE PRODUCT 
                    $existing_attribute->addProduct($entity);
                    $entity->addAttributeValue($existing_attribute);
                }
            }

            //Assign each submitted cost to this product
            foreach($form->get('keyword')->getData() as $keyword) {
                $keyword->addProduct($entity);
                $entity->removeKeyword($keyword);
                $entity->addKeyword($keyword);
            }

            //Assign each submitted photo tag to this photo
            foreach($form->get('keyword')->getData() as $keyword) {
                //MAKE CALL TO CHECK IF KEYWORD EXISTS IN THE DATABASE FIRST
                //Find the entity that matches the keyword text
                $existing_keyword = $em->getRepository('MilesApartAdminBundle:Keyword')->findOneBy(array('keyword_word' => $keyword->getKeywordWord()));
                if (!$existing_keyword) {
                    //ONLY IF IT DOESNT EXIST DO WE ADD IT
                    $keyword->addProduct($entity);
                    $entity->removeKeyword($keyword);
                    $entity->addKeyword($keyword);
                } else {

                    $entity->removeKeyword($keyword);

                    //IF IT DOES IXIST, THEN WE ASSIGN ITS ID TO THE PHOTO AND IGNORE THE $PHOTO_TAG VARIABLE
                    $existing_keyword->addProduct($entity);
                    $entity->addKeyword($existing_keyword);
                }
            }

            //Assign each category to this product
            foreach($form->get('category')->getData() as $category) {

                //MAKE CALL TO CHECK IF TAG EXISTS IN THE DATABASE FIRST
                //Find the entity that matches the photo tag text
                $existing_category = $em->getRepository('MilesApartAdminBundle:Category')->findOneBy(array('id' => $category->getId()));

                if (!$existing_category) {
            
                    //ONLY IF IT DOESNT EXIST DO WE ADD IT
                    $category->addProduct($entity);
                    $entity->removeCategory($category);
                    $entity->addCategory($category);
                } else {

                    $entity->removeCategory($category);

                    //IF IT DOES IXIST, THEN WE ASSIGN ITS ID TO THE PHOTO AND IGNORE THE $PHOTO_TAG VARIABLE
                    $existing_category->addProduct($entity);
                    $entity->addCategory($existing_category);
                } 
            }

            //Assign each submitted cost to this product
            foreach($form->get('season')->getData() as $season) {
                $season->addProduct($entity);
                $entity->removeSeason($season);
                $entity->addSeason($season);
            }

            //Assign each submitted cost to this product
            foreach($form->get('product_image')->getData() as $product_image) {
                $product_image->setProduct($entity);
                $entity->removeProductImage($product_image);
                $entity->addProductImage($product_image);  
            }
            $em->persist($entity);
            $em->flush();

            return new JsonResponse(array('status'=>'success', 'editUrl' => $this->generateUrl('staff-products_view-product', array('id' => $entity->getId()))));
        } else {
            return new JsonResponse(array('status'=>'fail-validation'));
        }
    }
    
    //Get the Product for any given barcode
    public function getProductFromBarcode($barcode)
    {
        
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsProductClientPackUk = $mwsClientPoolUk->getProductClientPack();

        $mwsResponse = $mwsProductClientPackUk->callGetMatchingProductForId('EAN', $barcode);

        
        //Check if products were found 
        if(count($mwsResponse->getGetMatchingProductForIdResult()[0]->getProducts()) > 0) {
            //Product were found so serialize
            $serializer = SerializerBuilder::create()->build();
            $json = $serializer->serialize($mwsResponse, 'json');
            return $json;
        } else {
            return false;
        }
    
    }

     //Get the Product for any given barcode
    public function getProductObjectFromBarcode($barcode)
    {
        
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsProductClientPackUk = $mwsClientPoolUk->getProductClientPack();

        $mwsResponse = $mwsProductClientPackUk->callGetMatchingProductForId('EAN', $barcode);

        
        //Check if products were found 
        if(count($mwsResponse->getGetMatchingProductForIdResult()[0]->getProducts()) > 0) {
           return $mwsResponse;
        } else {
            return false;
        }
    
    }
    //Get the ASIN for any given barcode
    public function getAsinFromBarcode($barcode)
    {
        
        $mwsResponse = $this->getProductObjectFromBarcode($barcode);

        //Check iff products were found 
        if(count($mwsResponse->getGetMatchingProductForIdResult()) > 0){
            if(count($mwsResponse->getGetMatchingProductForIdResult()[0]->getProducts()) > 0) {
                return $mwsResponse->getGetMatchingProductForIdResult()[0]->getProducts()->getProduct()[0]->getIdentifiers()->getMarketplaceASIN()->getASIN();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Get the lowest Amazon price for any given ASIN
    public function getAmazonPriceCheck($asin) 
    {
          
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsProductClientPackUk = $mwsClientPoolUk->getProductClientPack();
        
        $mwsResponse = $mwsProductClientPackUk->callGetCompetitivePricingForASIN($asin);

        //Render the page from template
        return $mwsResponse->getGetCompetitivePricingForASINResult()[0]->getProduct()->getCompetitivePricing()->getCompetitivePrices()->getCompetitivePrice()[0]->getPrice()->getLandedPrice()->getAmount();
    //return $mwsResponse->getGetCompetitivePricingForASINResult();
    }

    //Get the lowest Amazon shipping cost for any given ASIN
    public function getAmazonShippingCheck($asin) 
    {

        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsProductClientPackUk = $mwsClientPoolUk->getProductClientPack();
        
        $mwsResponse = $mwsProductClientPackUk->callGetCompetitivePricingForASIN($asin);

        //Render the page from template
        return $mwsResponse->getGetCompetitivePricingForASINResult()[0]->getProduct()->getCompetitivePricing()->getCompetitivePrices()->getCompetitivePrice()[0]->getPrice()->getShipping()->getAmount();
    }
    /************** End of Price Check *********************/
   
    
    public function getproductinfoAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Set the new price and the product id
        $barcode = $response["barcode"];
        
        //Get ASIN
        $product = $this->getProductFromBarcode($barcode);

        /*if(count($product->getGetMatchingProductForIdResult()[0]->getProducts()) > 0) {
            $match = true;
            $found_product = $product->getGetMatchingProductForIdResult()[0]->getProducts()->getProduct()[0];
        } else {
            $match = false;
            $found_product = false;
        }

        ladybug_dump($product);
*/
        //this->get('ladybug')->log($product);
        $response = array("match" => true, "product" => $product);
        
        return new JsonResponse(
                    $response 
                );
       
    }


   /******************* Order management *********************/
    //Function to upload prices, products and quantities from stocktake products
    public function getNewOrders() 
    {
        //Get the Amazon setup
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsOrdersClientPack = $mwsClientPoolUk->getOrderClientPack();

        $em = $this->getDoctrine()->getManager();

        //Get most recent amazon order from the MA database
        $amazon_order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findLatestAmazonOrder();

        //If there are no amazon orders yet.
        if($amazon_order == null) {

            //Set start date to arbitary date in the past
            $start_date = new \DateTime();
            $start_date->setDate(2001, 1, 1);
        } else {
            //Set the start date as the date the last order was imported
            $start_date = $amazon_order[0]->getCustomerOrderDateCreated();
           
        }

        //Set the end date as today
        $end_date = new \DateTime();
        //Remove 2 minutes
        $end_date->modify('-3 minute');
    

        $mwsResponse = $mwsOrdersClientPack->callListOrdersByCreateDate($start_date, $end_date);

        return $mwsResponse;
    }

    public function getNewOrderContents($amazon_order_id) 
    {
        //Get the Amazon setup
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsOrdersClientPack = $mwsClientPoolUk->getOrderClientPack();

        $mwsResponse = $mwsOrdersClientPack->callListOrderItems($amazon_order_id);

        return $mwsResponse;
    }

    //Map new orders and save to MA database
    public function saveNewOrders($orders) 
    {
        $em = $this->getDoctrine()->getManager();

        //Iterate over the orders 
        foreach($orders->getListOrdersResult()->getOrders() as $order) {
            //Check if the order exists in the MA DB
            $existing_amazon_order = $em->getRepository('MilesApartSellerBundle:AmazonOrder')->findOneBy(array('amazon_order_id' => $order->getAmazonOrderId()));

            if($existing_amazon_order != null) {
                //Update the order
            } else {


            
                //Split name into first name and surname
                $arr = explode(' ', trim($order->getBuyerName()));

                $amazon_first_name = ucfirst($arr[0]);
                $amazon_surname = "";
                foreach($arr as $key => $name) {
                    if($key >= 1) {
                        $amazon_surname .= ucfirst($name);
                    }
                }

                //Create new customer  object
                $customer = new Customer();

                //Set up the variables


                //Create new personal or business customer object
                if($order->getIsBusinessOrder() != true){
                    $business_customer = new BusinessCustomer();

                    $business_customer_representative = new BusinessCustomerRepresentative();

                    //Set up the variables
                    $business_customer_representative->setBusinessCustomerRepresentativeFirstName($amazon_first_name);
                    $business_customer_representative->setBusinessCustomerRepresentativeSurname($amazon_surname);
                    $business_customer->addBusinessCustomerRepresentative($business_customer_representative);
                    $customer->setBusinessCustomer($business_customer);
                } else {
                    $personal_customer = new PersonalCustomer();

                    //Set up the variables
                    $personal_customer->setCustomer($customer);
                    $personal_customer->setPersonalCustomerFirstName($amazon_first_name);
                    $personal_customer->setPersonalCustomerSurname($amazon_surname);
                    $personal_customer->setPersonalCustomerEmailAddress($order->getBuyerEmail());
                    $customer->setPersonalCustomer($personal_customer);

                }

                //Create new customer address object
                $customer_address = new CustomerAddress();

                //Set up the variables
                $customer_address->setCustomer($customer);
                $customer_address->setCustomerAddressContactFirstName($amazon_first_name);
                $customer_address->setCustomerAddressContactSurname($amazon_surname);
                $customer_address->setCustomerAddressLine1($order->getShippingAddress()->getAddressLine1());
                $customer_address->setCustomerAddressLine2($order->getShippingAddress()->getAddressLine2());
                $customer_address->setCustomerAddressTown($order->getShippingAddress()->getCity());
                $customer_address->setCustomerAddressCounty($order->getShippingAddress()->getStateOrRegion());
                $customer_address->setCustomerAddressPostcode($order->getShippingAddress()->getPostalCode());
                $customer_address->setCustomerAddressCountry($order->getShippingAddress()->getCountryCode());

                $customer->addCustomerAddress($customer_address);
                //Create new customer order object
                $customer_order = new CustomerOrder();

                //Set up the variables
                $customer_order->setCustomer($customer);
                $customer_order->setDeliveryAddress($customer_address);
                $customer_order->setCustomerOrderTotalPricePaid($order->getOrderTotal()->getAmount());
                $customer_order->setCustomerOrderSource(
                    $em->getRepository('MilesApartAdminBundle:CustomerOrderSource')->findOneBy(
                    array( 'id' => 2 )
                ));
                //Check the order status and insert into the DB as appropriate
                if($order->getOrderStatus() == "Pending") {
                    $customer_order->setCustomerOrderState(
                    $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                        array( 'id' => 1 )
                    ));
                } else if($order->getOrderStatus() == "Unshipped") {
                    $customer_order->setCustomerOrderState(
                    $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                        array( 'id' => 2 )
                    ));
                } else if($order->getOrderStatus() == "Shipped") {
                    $customer_order->setCustomerOrderState(
                    $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                        array( 'id' => 6 )
                    ));
                } else if($order->getOrderStatus() == "Cancelled") {
                    $customer_order->setCustomerOrderState(
                    $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                        array( 'id' => 9)
                    ));
                }
                $customer->addCustomerOrder($customer_order);
                //Get the order contents
                $order_contents = $this->getNewOrderContents($order->getAmazonOrderId());
                //Set delivery total variable.
                $amazon_delivery_total_price = 0.00;
                //For each product in the order
                foreach($order_contents->getListOrderItemsResult()->getOrderItems() as $order_product) {

                    //Get the product from the DB
                    $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($order_product->getSellerSKU());

                    //Create new customer order object
                    $customer_order_product = new CustomerOrderProduct();
                    
                    //Set up the variables
                    $customer_order_product->setCustomerOrder($customer_order);
                    $customer_order_product->setProduct($product);
                    $customer_order_product->setCustomerOrderProductQuantity($order_product->getQuantityOrdered());

                    $customer_order->addCustomerOrderProduct($customer_order_product);

                    //Update the delivery total
                    $amazon_delivery_total_price = $amazon_delivery_total_price + $order_product->getShippingPrice()->getAmount();
                }

                //Update the total shipping price on customer order
                $customer_order->setCustomerOrderShippingPaid($amazon_delivery_total_price);

                //Create amazon order object
                $amazon_order = new AmazonOrder();

                //Set up the variables
                $amazon_order->setAmazonOrderId($order->getAmazonOrderId());
                $amazon_order->setPurchaseDate(new \DateTime($order->getPurchaseDate(), new \DateTimeZone('Europe/London')));
                $amazon_order->setLastUpdateDate(new \DateTime($order->getLastUpdateDate(), new \DateTimeZone('Europe/London')));
                $amazon_order->setMarketplaceId($order->getMarketplaceId());
                $amazon_order->setCustomerOrder($customer_order);

                $customer_order->setAmazonOrder($amazon_order);

                //Set up the postage band details
                //Query the model with the order, height width, depth and weight
                $postage_band = $em->getRepository('MilesApartAdminBundle:PostageBand')->findPostageBandBySizes($customer_order->getCustomerOrderLargestWidth(), $customer_order->getCustomerOrderLargestHeight(), $customer_order->getCustomerOrderLargestDepth(), $customer_order->getCustomerOrderTotalWeight());


                $customer_order->setDeliveryOption(
                    $em->getRepository('MilesApartAdminBundle:PostageBandDispatchLogistics')->findOneBy(array('postage_band' => $postage_band[0]->getId(), 'postage_type' => 2))
                );


                //Persist the order and return success or failure
                $em->persist($customer_order);
                $em->flush();
                
            }
            return $order_contents;

        }
    }

    /******************* Feed creation for order acknowledgement *********************/
    //Function to upload acknowledgement of order (after an order has been downloaded and  added to the MA DB)
    public function uploadAmazonOrderAcknowledgement($amazon_order_id, $miles_apart_order_id) 
    {
        //Get the Amazon setup
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsFeedandReportClientPackUk = $mwsClientPoolUk->getFeedAndReportClientPack();

        //Create the product feed and save on the server
        $acknowledgementFeedFile = $this->getAmazonOrderAcknowledgementFeed($amazon_order_id, $miles_apart_order_id);
       
        //Set up the product upload 
        $feedType = "_POST_ORDER_ACKNOWLEDGEMENT_DATA_";

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web/order_acknowledgement_feed.xml');

        $file = fopen($webPath, 'r');
        $mwsResponse1 = $mwsFeedandReportClientPackUk->callSubmitFeed($feedType, $file);

        //Return the response
        $response = new JsonResponse(array($mwsResponse1));
        return $response;
    }

    /******************* Feed creation for order fulfilment *********************/
    //Function to upload acknowledgement of order dispatch
    public function uploadAmazonOrderFulfillment($amazon_order_id) 
    {
        //Get the Amazon setup
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsFeedandReportClientPackUk = $mwsClientPoolUk->getFeedAndReportClientPack();

        //Create the product feed and save on the server
        $fulfillmentFeedFile = $this->getAmazonOrderFulfillmentFeed($amazon_order_id);
       
        //Set up the product upload 
        $feedType = "_POST_ORDER_FULFILLMENT_DATA_";

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web/order_fulfillment_feed.xml');

        $file = fopen($webPath, 'r');
        $mwsResponse1 = $mwsFeedandReportClientPackUk->callSubmitFeed($feedType, $file);

        //Return the response
        $response = new JsonResponse(array($mwsResponse1));
        return $response;
    }


    /******************* Stocktake upload *********************/
    //Function to upload prices, products and quantities from stocktake products
    public function uploadAmazonProductArrayAction($stocktake_unique) 
    {
        //Get the Amazon setup
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsFeedandReportClientPackUk = $mwsClientPoolUk->getFeedAndReportClientPack();

        //Create the product feed and save on the server
        $productFeedFile = $this->getAmazonProductFeedContentFromArray($stocktake_unique);

        //Create the inventory feed and save on the server
        $inventoryFeedFile = $this->getAmazonInventoryFeedContentFromArray($stocktake_unique);

        //Create the inventory feed and save on the server
        $priceFeedFile = $this->getAmazonPricingFeedContentFromArray($stocktake_unique);
       
        //Set up the product upload 
        $feedType = "_POST_PRODUCT_DATA_";

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web/product_upload.xml');

        $file = fopen($webPath, 'r');
        $mwsResponse1 = $mwsFeedandReportClientPackUk->callSubmitFeed($feedType, $file);

        //Set up the inventory upload 
        $feedType = "_POST_INVENTORY_AVAILABILITY_DATA_";

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web/inventory_upload.xml');

        $file = fopen($webPath, 'r');
        $mwsResponse2 = $mwsFeedandReportClientPackUk->callSubmitFeed($feedType, $file);

        //Set up the inventory upload 
        $feedType = "_POST_PRODUCT_PRICING_DATA_";

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web/price_upload.xml');

        $file = fopen($webPath, 'r');
        $mwsResponse2 = $mwsFeedandReportClientPackUk->callSubmitFeed($feedType, $file);
        
        //Pause, then check for submission success
        //sleep(300);

        //$productFeedResponse = $this->getAmazonFeedSubmissionResponse();
        //Set up the product upload response
        //$feedType = "_POST_PRODUCT_DATA_";

        //$appPath = $this->container->getParameter('kernel.root_dir');
        //$webPath = realpath($appPath . '/../web/get_feed_submission_request.xml');

        //$file = fopen($webPath, 'r');

        //$mwsResponse4 = $mwsFeedandReportClientPackUk->callGetFeedSubmissionResult($mwsResponse1->getSubmitFeedResult()->getFeedSubmissionInfo()->getFeedSubmissionId());

        //ladybug_dump($mwsResponse4);

        //Return the response
        $response = new JsonResponse(array($mwsResponse1));
        return $response;
    }

    //Add inventory qty data to Amazon for single product
    public function uploadAmazonProductNewQtyAction($customer_order_product) 
    {
        //Get the Amazon setup
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsFeedandReportClientPackUk = $mwsClientPoolUk->getFeedAndReportClientPack();

        //Create the product feed and save on the server
        $productFeedFile = $this->getAmazonInventoryFeedContentForProduct($customer_order_product->getProduct());

        //Set up the inventory upload 
        $feedType = "_POST_INVENTORY_AVAILABILITY_DATA_";

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web/inventory_update.xml');

        $file = fopen($webPath, 'r');
        $mwsResponse2 = $mwsFeedandReportClientPackUk->callSubmitFeed($feedType, $file);

        //Return the response
        $response = new JsonResponse(array($mwsResponse2));
        return $response;
    }

    //Add inventory qty data to Amazon for multiple products
    public function uploadAmazonMultipleProductNewQtyAction($customer_order_products) 
    {
        //Get the Amazon setup
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsFeedandReportClientPackUk = $mwsClientPoolUk->getFeedAndReportClientPack();

        //Create the product feed and save on the server
        $productFeedFile = $this->getAmazonInventoryFeedContentForMultipleProducts($customer_order_products);

        //Set up the inventory upload 
        $feedType = "_POST_INVENTORY_AVAILABILITY_DATA_";

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web/inventory_update.xml');

        $file = fopen($webPath, 'r');
        //$mwsResponse2 = $mwsFeedandReportClientPackUk->callSubmitFeed($feedType, $file);
    
        //ladybug_dump($mwsResponse2);

        //Return the response
        $response = new JsonResponse(array($mwsResponse2));
        return $response;
    }

    /*************************************************
    *
    * Functions to create the XML files used for interacting with Amazon API
    *
    *************************************************/
    public function getAmazonProductFeedContentFromArray($stocktake_array) 
    {
        //Creates XML string and XML document using the DOM 
        $dom = new \DomDocument('1.0', 'UTF-8'); 

        //add root
        $root = $dom->appendChild($dom->createElement('AmazonEnvelope'));

        $attr = $dom->createAttribute('xmlns:xsi');
        $attr->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $attr2 = $dom->createAttribute('xsi:noNamespaceSchemaLocation');
        $attr2->appendChild($dom->createTextNode('amzn-envelope.xsd'));

        $root->appendChild($attr);
        $root->appendChild($attr2);

        //Add header element
        $header = $dom->createElement('Header');

        //Append header to root
        $root->appendChild($header);

        //Add doc version
        $doc_version = $dom->createElement('DocumentVersion');
        $doc_version->appendChild($dom->createTextNode('1.0'));
        $header->appendchild($doc_version);

        //Add Merch ident
        $merch_ident = $dom->createElement('MerchantIdentifier');
        $merch_ident->appendChild($dom->createTextNode('A38J5HCNU7X658'));
        $header->appendchild($merch_ident);
        
        //Add message type
        $message_type = $dom->createElement('MessageType');

        //Append header to root
        $root->appendChild($message_type);
        $message_type->appendChild($dom->createTextNode('Product'));

        //Add purge & replace
        $purge = $dom->createElement('PurgeAndReplace');

        //Append header to root
        $root->appendChild($purge);
        $purge->appendChild($dom->createTextNode('false'));

        //Set MessageId - this will auto increment 
        $message_id = 1;
        //Iterate over the stocktake array
        foreach($stocktake_array as $product) {

            //Add message type
            $message = $dom->createElement('Message');

            //Append header to root
            $root->appendChild($message);
            
            //Add Merch ident
            $MessageID = $dom->createElement('MessageID');
            $MessageID->appendChild($dom->createTextNode($message_id));
            $message->appendchild($MessageID);

            //Add Merch ident
            $OperationType = $dom->createElement('OperationType');
            $OperationType->appendChild($dom->createTextNode('Update'));
            $message->appendchild($OperationType);

            //Add Merch ident
            $Product = $dom->createElement('Product');
            $message->appendchild($Product);

            //Add product SKU
            $SKU = $dom->createElement('SKU');
            $SKU->appendChild($dom->createTextNode($product['product']->getProduct()->getId()));
            $Product->appendchild($SKU);

            //Add StandardProductID
            $StandardProductID = $dom->createElement('StandardProductID');
            $Product->appendchild($StandardProductID);

            //Add Type
            $Type = $dom->createElement('Type');
            $Type->appendChild($dom->createTextNode('ASIN'));
            $StandardProductID->appendchild($Type);

            //Add Value
            $Value = $dom->createElement('Value');
            $Value->appendChild($dom->createTextNode($this->getAsinFromBarcode($product['product']->getProduct()->getProductBarcode())));
            $StandardProductID->appendchild($Value);
    
            //Add condition
            $Condition = $dom->createElement('Condition');
            $Product->appendchild($Condition);

            //Add condition
            $ConditionType = $dom->createElement('ConditionType');
            $ConditionType->appendChild($dom->createTextNode('New'));
            $Condition->appendchild($ConditionType);

            //Increment the message id
            $message_id++;
        }
         

        $dom->formatOutput = true; // set the formatOutput attribute of domDocument to true

        // save XML as string or file 
        $test1 = $dom->saveXML(); // put string in test1
        $dom->save('product_upload.xml'); // save as file

        return 'product_upload.xml';

    } 

    public function getAmazonPricingFeedContentFromArray($stocktake_array) 
    {
        //Creates XML string and XML document using the DOM 
        $dom = new \DomDocument('1.0', 'UTF-8'); 

        //add root
        $root = $dom->appendChild($dom->createElement('AmazonEnvelope'));

        $attr = $dom->createAttribute('xmlns:xsi');
        $attr->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $attr2 = $dom->createAttribute('xsi:noNamespaceSchemaLocation');
        $attr2->appendChild($dom->createTextNode('amzn-envelope.xsd'));

        $root->appendChild($attr);
        $root->appendChild($attr2);


        //Add header element
        $header = $dom->createElement('Header');

        //Append header to root
        $root->appendChild($header);

        //Add doc version
        $doc_version = $dom->createElement('DocumentVersion');
        $doc_version->appendChild($dom->createTextNode('1.0'));
        $header->appendchild($doc_version);

        //Add Merch ident
        $merch_ident = $dom->createElement('MerchantIdentifier');
        $merch_ident->appendChild($dom->createTextNode('A38J5HCNU7X658'));
        $header->appendchild($merch_ident);
        
        //Add message type
        $message_type = $dom->createElement('MessageType');

        //Append header to root
        $root->appendChild($message_type);
        $message_type->appendChild($dom->createTextNode('Price'));

        //Set MessageId - this will auto increment 
        $message_id = 1;

        //Iterate over the stocktake array
        foreach($stocktake_array as $product) {
            
            //Add message type
            $message = $dom->createElement('Message');

            //Append header to root
            $root->appendChild($message);

            //Add Message id
            $MessageID = $dom->createElement('MessageID');
            $MessageID->appendChild($dom->createTextNode($message_id));
            $message->appendchild($MessageID);

            //Add Operation type
            $OperationType = $dom->createElement('OperationType');
            $OperationType->appendChild($dom->createTextNode('Update'));
            $message->appendchild($OperationType);

            //Add Price
            $Price = $dom->createElement('Price');
            $message->appendchild($Price);

            //Add product SKU
            $SKU = $dom->createElement('SKU');
            $SKU->appendChild($dom->createTextNode($product['product']->getProduct()->getId()));
            $Price->appendchild($SKU);

            //Add StandardPrice
            $StandardPrice = $dom->createElement('StandardPrice');
            $StandardPrice->appendChild($dom->createTextNode($product['product']->getProduct()->getCurrentPriceDecimal()));
            $Price->appendchild($StandardPrice);

            //Add Currency attribute to standard price
            $attr3 = $dom->createAttribute('currency');
            $attr3->appendChild($dom->createTextNode('GBP'));
            $StandardPrice->appendChild($attr3);

            //Increment the message id
            $message_id++;
        }

        $dom->formatOutput = true; // set the formatOutput attribute of domDocument to true

        // save XML as string or file 
        $test1 = $dom->saveXML(); // put string in test1
        $dom->save('price_upload.xml'); // save as file

        return 'price_upload.xml';

    }

    public function getAmazonInventoryFeedContentFromArray($stocktake_array) 
    {
        //Creates XML string and XML document using the DOM 
        $dom = new \DomDocument('1.0', 'UTF-8'); 

        //add root
        $root = $dom->appendChild($dom->createElement('AmazonEnvelope'));

        $attr = $dom->createAttribute('xmlns:xsi');
        $attr->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $attr2 = $dom->createAttribute('xsi:noNamespaceSchemaLocation');
        $attr2->appendChild($dom->createTextNode('amzn-envelope.xsd'));

        $root->appendChild($attr);
        $root->appendChild($attr2);

        //Add header element
        $header = $dom->createElement('Header');

        //Append header to root
        $root->appendChild($header);

        //Add doc version
        $doc_version = $dom->createElement('DocumentVersion');
        $doc_version->appendChild($dom->createTextNode('1.0'));
        $header->appendchild($doc_version);

        //Add Merch ident
        $merch_ident = $dom->createElement('MerchantIdentifier');
        $merch_ident->appendChild($dom->createTextNode('A38J5HCNU7X658'));
        $header->appendchild($merch_ident);
        
        //Add message type
        $message_type = $dom->createElement('MessageType');

        //Append header to root
        $root->appendChild($message_type);
        $message_type->appendChild($dom->createTextNode('Inventory'));

        //Set MessageId - this will auto increment 
        $message_id = 1;

        //Iterate over the stocktake array
        foreach($stocktake_array as $product) {
            
            //Add message type
            $message = $dom->createElement('Message');

            //Append header to root
            $root->appendChild($message);

            //Add Merch ident
            $MessageID = $dom->createElement('MessageID');
            $MessageID->appendChild($dom->createTextNode($message_id));
            $message->appendchild($MessageID);

            //Add Merch ident
            $OperationType = $dom->createElement('OperationType');
            $OperationType->appendChild($dom->createTextNode('Update'));
            $message->appendchild($OperationType);

            //Add Merch ident
            $Inventory = $dom->createElement('Inventory');
            $message->appendchild($Inventory);

            //Add product SKU
            $SKU = $dom->createElement('SKU');
            $SKU->appendChild($dom->createTextNode($product['product']->getProduct()->getId()));
            $Inventory->appendchild($SKU);

            //Add product qty
            $Quantity = $dom->createElement('Quantity');
            $Quantity->appendChild($dom->createTextNode($product['qty']));
            $Inventory->appendchild($Quantity);

            //Add product FulfillmentLatency
            $FulfillmentLatency = $dom->createElement('FulfillmentLatency');
            $FulfillmentLatency->appendChild($dom->createTextNode('1'));
            $Inventory->appendchild($FulfillmentLatency);

            $message_id++;
        }

        $dom->formatOutput = true; // set the formatOutput attribute of domDocument to true

        // save XML as string or file 
        $test1 = $dom->saveXML(); // put string in test1
        $dom->save('inventory_upload.xml'); // save as file

        return 'inventory_upload.xml';

    }


    public function getAmazonInventoryFeedContentForProduct($product) 
    {
        //Creates XML string and XML document using the DOM 
        $dom = new \DomDocument('1.0', 'UTF-8'); 

        //add root
        $root = $dom->appendChild($dom->createElement('AmazonEnvelope'));

        $attr = $dom->createAttribute('xmlns:xsi');
        $attr->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $attr2 = $dom->createAttribute('xsi:noNamespaceSchemaLocation');
        $attr2->appendChild($dom->createTextNode('amzn-envelope.xsd'));

        $root->appendChild($attr);
        $root->appendChild($attr2);

        //Add header element
        $header = $dom->createElement('Header');

        //Append header to root
        $root->appendChild($header);

        //Add doc version
        $doc_version = $dom->createElement('DocumentVersion');
        $doc_version->appendChild($dom->createTextNode('1.0'));
        $header->appendchild($doc_version);

        //Add Merch ident
        $merch_ident = $dom->createElement('MerchantIdentifier');
        $merch_ident->appendChild($dom->createTextNode('A38J5HCNU7X658'));
        $header->appendchild($merch_ident);
        
        //Add message type
        $message_type = $dom->createElement('MessageType');

        //Append header to root
        $root->appendChild($message_type);
        $message_type->appendChild($dom->createTextNode('Inventory'));

        //Add message type
        $message = $dom->createElement('Message');

        //Append header to root
        $root->appendChild($message);

        //Add Merch ident
        $MessageID = $dom->createElement('MessageID');
        $MessageID->appendChild($dom->createTextNode('1'));
        $message->appendchild($MessageID);

        //Add Merch ident
        $OperationType = $dom->createElement('OperationType');
        $OperationType->appendChild($dom->createTextNode('Update'));
        $message->appendchild($OperationType);

        //Add Merch ident
        $Inventory = $dom->createElement('Inventory');
        $message->appendchild($Inventory);

        //Add product SKU
        $SKU = $dom->createElement('SKU');
        $SKU->appendChild($dom->createTextNode($product->getId()));
        $Inventory->appendchild($SKU);

        //Add product qty
        $Quantity = $dom->createElement('Quantity');
        $Quantity->appendChild($dom->createTextNode($product->getCurrentStockLevel()));
        $Inventory->appendchild($Quantity);

        //Add product FulfillmentLatency
        $FulfillmentLatency = $dom->createElement('FulfillmentLatency');
        $FulfillmentLatency->appendChild($dom->createTextNode('1'));
        $Inventory->appendchild($FulfillmentLatency);

        $dom->formatOutput = true; // set the formatOutput attribute of domDocument to true

        // save XML as string or file 
        $test1 = $dom->saveXML(); // put string in test1
        $dom->save('inventory_update.xml'); // save as file

        return 'inventory_update.xml';

    }

    public function getAmazonInventoryFeedContentForMultipleProducts($customer_order_products = null, $product_delivery = null) 
    {

        //Creates XML string and XML document using the DOM 
        $dom = new \DomDocument('1.0', 'UTF-8'); 

        //add root
        $root = $dom->appendChild($dom->createElement('AmazonEnvelope'));

        $attr = $dom->createAttribute('xmlns:xsi');
        $attr->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $attr2 = $dom->createAttribute('xsi:noNamespaceSchemaLocation');
        $attr2->appendChild($dom->createTextNode('amzn-envelope.xsd'));

        $root->appendChild($attr);
        $root->appendChild($attr2);

        //Add header element
        $header = $dom->createElement('Header');

        //Append header to root
        $root->appendChild($header);

        //Add doc version
        $doc_version = $dom->createElement('DocumentVersion');
        $doc_version->appendChild($dom->createTextNode('1.0'));
        $header->appendchild($doc_version);

        //Add Merch ident
        $merch_ident = $dom->createElement('MerchantIdentifier');
        $merch_ident->appendChild($dom->createTextNode('A38J5HCNU7X658'));
        $header->appendchild($merch_ident);
        
        //Add message type
        $message_type = $dom->createElement('MessageType');

        //Append header to root
        $root->appendChild($message_type);
        $message_type->appendChild($dom->createTextNode('Inventory'));

        //Set MessageId - this will auto increment 
        $message_id = 1;

        //Iterate over the stocktake array
        foreach($customer_order_products as $products) {

            $product = $products->getProduct();
            
            //Add message type
            $message = $dom->createElement('Message');

            //Append header to root
            $root->appendChild($message);

            //Add Merch ident
            $MessageID = $dom->createElement('MessageID');
            $MessageID->appendChild($dom->createTextNode($message_id));
            $message->appendchild($MessageID);

            //Add Merch ident
            $OperationType = $dom->createElement('OperationType');
            $OperationType->appendChild($dom->createTextNode('Update'));
            $message->appendchild($OperationType);

            //Add Merch ident
            $Inventory = $dom->createElement('Inventory');
            $message->appendchild($Inventory);

            //Add product SKU
            $SKU = $dom->createElement('SKU');
            $SKU->appendChild($dom->createTextNode($product->getId()));
            $Inventory->appendchild($SKU);

            //Add product qty
            $Quantity = $dom->createElement('Quantity');
            $Quantity->appendChild($dom->createTextNode($product->getCurrentStockLevel()));
            $Inventory->appendchild($Quantity);

            //Add product FulfillmentLatency
            $FulfillmentLatency = $dom->createElement('FulfillmentLatency');
            $FulfillmentLatency->appendChild($dom->createTextNode('1'));
            $Inventory->appendchild($FulfillmentLatency);

            $message_id++;
        }

        $dom->formatOutput = true; // set the formatOutput attribute of domDocument to true

        // save XML as string or file 
        $test1 = $dom->saveXML(); // put string in test1
        $dom->save('inventory_update.xml'); // save as file

        return 'inventory_update.xml';

    }

    public function getAmazonFeedSubmissionResponse($transaction_id) 
    {
        //Creates XML string and XML document using the DOM 
        $dom = new \DomDocument('1.0', 'UTF-8'); 

        //add root
        $root = $dom->appendChild($dom->createElement('AmazonEnvelope'));

        $attr = $dom->createAttribute('xmlns:xsi');
        $attr->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $attr2 = $dom->createAttribute('xsi:noNamespaceSchemaLocation');
        $attr2->appendChild($dom->createTextNode('amzn-envelope.xsd'));

        $root->appendChild($attr);
        $root->appendChild($attr2);

        //Add header element
        $header = $dom->createElement('Header');

        //Append header to root
        $root->appendChild($header);

        //Add doc version
        $doc_version = $dom->createElement('DocumentVersion');
        $doc_version->appendChild($dom->createTextNode('1.0'));
        $header->appendchild($doc_version);

        //Add Merch ident
        $merch_ident = $dom->createElement('MerchantIdentifier');
        $merch_ident->appendChild($dom->createTextNode('A38J5HCNU7X658'));
        $header->appendchild($merch_ident);
        
        //Add message type
        $message_type = $dom->createElement('MessageType');

        //Append header to root
        $root->appendChild($message_type);
        $message_type->appendChild($dom->createTextNode('ProcessingReport'));

        //Add message
        $message = $dom->createElement('Message');
        $root->appendChild($message);
        
        //Add MessageID
        $MessageID = $dom->createElement('MessageID');
        $MessageID->appendChild($dom->createTextNode('1'));
        $message->appendChild($MessageID);

        //Add ProcessingReport
        $ProcessingReport = $dom->createElement('ProcessingReport');
        $message->appendChild($ProcessingReport);

        //Add DocumentTransactionID
        $DocumentTransactionID = $dom->createElement('DocumentTransactionID');
        $MessageID->appendChild($dom->createTextNode($transaction_id));
        $ProcessingReport->appendChild($DocumentTransactionID);
   
        $dom->formatOutput = true; // set the formatOutput attribute of domDocument to true

        // save XML as string or file 
        $test1 = $dom->saveXML(); // put string in test1
        $dom->save('get_feed_submission_request.xml'); // save as file

        return 'get_feed_submission_request.xml';
    }

    public function getAmazonOrderAcknowledgementFeed($amazon_order_id, $miles_apart_order_id) 
    {
        //Creates XML string and XML document using the DOM 
        $dom = new \DomDocument('1.0', 'UTF-8'); 

        //add root
        $root = $dom->appendChild($dom->createElement('AmazonEnvelope'));

        $attr = $dom->createAttribute('xmlns:xsi');
        $attr->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $attr2 = $dom->createAttribute('xsi:noNamespaceSchemaLocation');
        $attr2->appendChild($dom->createTextNode('amzn-envelope.xsd'));

        $root->appendChild($attr);
        $root->appendChild($attr2);

        //Add header element
        $header = $dom->createElement('Header');

        //Append header to root
        $root->appendChild($header);

        //Add doc version
        $doc_version = $dom->createElement('DocumentVersion');
        $doc_version->appendChild($dom->createTextNode('1.0'));
        $header->appendchild($doc_version);

        //Add Merch ident
        $merch_ident = $dom->createElement('MerchantIdentifier');
        $merch_ident->appendChild($dom->createTextNode('A38J5HCNU7X658'));
        $header->appendchild($merch_ident);
        
        //Add message type
        $message_type = $dom->createElement('MessageType');

        //Append header to root
        $root->appendChild($message_type);
        $message_type->appendChild($dom->createTextNode('OrderAcknowledgement'));

        //Add message
        $message = $dom->createElement('Message');
        $root->appendChild($message);
        
        //Add MessageID
        $MessageID = $dom->createElement('MessageID');
        $MessageID->appendChild($dom->createTextNode('1'));
        $message->appendChild($MessageID);

        //Add OrderAcknowledgement
        $OrderAcknowledgement = $dom->createElement('OrderAcknowledgement');
        $message->appendChild($OrderAcknowledgement);

        //Add AmazonOrderID
        $AmazonOrderID = $dom->createElement('AmazonOrderID');
        $AmazonOrderID->appendChild($dom->createTextNode($amazon_order_id));
        $OrderAcknowledgement->appendChild($AmazonOrderID);

        //Add MilesApartOrderId
        $MilesApartOrderId = $dom->createElement('MerchantOrderID');
        $MilesApartOrderId->appendChild($dom->createTextNode($miles_apart_order_id));
        $OrderAcknowledgement->appendChild($MilesApartOrderId);

        //Add StatusCode
        $SuccessCode = $dom->createElement('StatusCode');
        $SuccessCode->appendChild($dom->createTextNode('Success'));
        $OrderAcknowledgement->appendChild($SuccessCode);

        $dom->formatOutput = true; // set the formatOutput attribute of domDocument to true

        // save XML as string or file 
        $test1 = $dom->saveXML(); // put string in test1
        $dom->save('order_acknowledgement_feed.xml'); // save as file

        return 'order_acknowledgement_feed.xml';
    }

    public function getAmazonOrderFulfillmentFeed($amazon_order) 
    {
        //Creates XML string and XML document using the DOM 
        $dom = new \DomDocument('1.0', 'UTF-8'); 

        //add root
        $root = $dom->appendChild($dom->createElement('AmazonEnvelope'));

        $attr = $dom->createAttribute('xmlns:xsi');
        $attr->appendChild($dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));

        $attr2 = $dom->createAttribute('xsi:noNamespaceSchemaLocation');
        $attr2->appendChild($dom->createTextNode('amzn-envelope.xsd'));

        $root->appendChild($attr);
        $root->appendChild($attr2);

        //Add header element
        $header = $dom->createElement('Header');

        //Append header to root
        $root->appendChild($header);

        //Add doc version
        $doc_version = $dom->createElement('DocumentVersion');
        $doc_version->appendChild($dom->createTextNode('1.0'));
        $header->appendchild($doc_version);

        //Add Merch ident
        $merch_ident = $dom->createElement('MerchantIdentifier');
        $merch_ident->appendChild($dom->createTextNode('A38J5HCNU7X658'));
        $header->appendchild($merch_ident);
        
        //Add message type
        $message_type = $dom->createElement('MessageType');

        //Append header to root
        $root->appendChild($message_type);
        $message_type->appendChild($dom->createTextNode('OrderFulfillment'));

        //Add message
        $message = $dom->createElement('Message');
        $root->appendChild($message);
        
        //Add MessageID
        $MessageID = $dom->createElement('MessageID');
        $MessageID->appendChild($dom->createTextNode('1'));
        $message->appendChild($MessageID);

        //Add OrderFulfillment
        $OrderFulfillment = $dom->createElement('OrderFulfillment');
        $message->appendChild($OrderFulfillment);

        //Add MilesApartOrderId
        $MilesApartOrderId = $dom->createElement('MerchantOrderID');
        $MilesApartOrderId->appendChild($dom->createTextNode($amazon_order->getCustomerOrder()->getId()));
        $OrderFulfillment->appendChild($MilesApartOrderId);

        //Add StatusCode
        $FulfillmentDate = $dom->createElement('FulfillmentDate');
        $FulfillmentDate->appendChild($dom->createTextNode($amazon_order->getCustomerOrder()->getId()));
        $OrderFulfillment->appendChild($FulfillmentDate);

        //Add FulfillmentData
        $FulfillmentData = $dom->createElement('FulfillmentData');
        $OrderFulfillment->appendChild($FulfillmentData);

        //Add CarrierCode
        $CarrierCode = $dom->createElement('CarrierCode');
        $CarrierCode->appendChild("Royal Mail");
        $FulfillmentData->appendChild($CarrierCode);

        //Add ShippingMethod
        $ShippingMethod = $dom->createElement('ShippingMethod');
        $ShippingMethod->appendChild("Second Class");
        $FulfillmentData->appendChild($ShippingMethod);

        $dom->formatOutput = true; // set the formatOutput attribute of domDocument to true

        // save XML as string or file 
        $test1 = $dom->saveXML(); // put string in test1
        $dom->save('order_fulfillment_feed.xml'); // save as file

        return 'order_fulfillment_feed.xml';
    }

    public function getAmazonFeesForPeriodAction($amazon_customer_orders) 
    {

        $value = count($amazon_customer_orders);
        return $value;
    }

    
}
