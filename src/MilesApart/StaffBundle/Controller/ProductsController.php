<?php
// src/MilesApart/StaffBundle/Controller/ProductsController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\Product;
use MilesApart\AdminBundle\Entity\ProductAnswer;
use MilesApart\AdminBundle\Entity\ProductGroup;
use MilesApart\AdminBundle\Entity\ProductPrice;
use MilesApart\AdminBundle\Entity\ProductCost;
use MilesApart\StaffBundle\Entity\Products\ProductListCSVFile;
use MilesApart\StaffBundle\Entity\Products\PackUpSeasonal;
use MilesApart\StaffBundle\Entity\Products\SearchProduct;
use MilesApart\AdminBundle\Entity\SeasonalStorageBox;
use MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct;
use MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox;
use MilesApart\AdminBundle\Entity\StocktakeProduct;
use MilesApart\AdminBundle\Entity\ProductSupplier;
use MilesApart\AdminBundle\Entity\BusinessPremises;
use MilesApart\AdminBundle\Entity\PrintRequest;
use MilesApart\AdminBundle\Entity\Stocktake;
use MilesApart\AdminBundle\Entity\ProductImage;
use MilesApart\AdminBundle\Entity\Season;

use MilesApart\StaffBundle\Form\Products\ProductImageUploadType;
use MilesApart\AdminBundle\Form\ProductImageType;
use MilesApart\StaffBundle\Form\Products\PriceCheckType;
use MilesApart\StaffBundle\Form\Products\NewProductType;
use MilesApart\StaffBundle\Form\Products\NewProductPriceType;
use MilesApart\StaffBundle\Form\Products\NewProductCostType;
use MilesApart\StaffBundle\Form\Products\ProductListCSVFileType;
use MilesApart\StaffBundle\Form\Products\PackUpSeasonalSelectType;
use MilesApart\StaffBundle\Form\Products\SeasonalStorageBoxType;
use MilesApart\StaffBundle\Form\Products\AddProductToSeasonalStorageBoxType;
use MilesApart\StaffBundle\Form\Products\AddProductToReturnsType;
use MilesApart\StaffBundle\Form\Products\FindProductType;
use MilesApart\StaffBundle\Form\Products\NewPriceType;
use MilesApart\StaffBundle\Form\Products\ViewSeasonalStorageBoxType;
use MilesApart\StaffBundle\Form\Products\StoredSeasonType;
use MilesApart\StaffBundle\Form\Products\NewProductGroupType;
use MilesApart\StaffBundle\Form\Products\AnswerProductQuestionType;

use MilesApart\StaffBundle\Form\Products\EditProduct\EditProductType;

use MilesApart\StaffBundle\Form\Helpers\AddProductToListType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Reader\ArrayReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\Writer\ArrayWriter;
use Ddeboer\DataImport\Writer\CsvWriter;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;
use Ddeboer\DataImport\ItemConverter\MappingItemConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Ddeboer\DataImport\Filter\CallbackFilter;


class ProductsController extends Controller
{
    /*************************************************
    * Products controller for product site area.
    *************************************************/

    public function notificationsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add product notifications to breadcrumb.
        $breadcrumbs->addItem("Product notifications", $this->get("router")->generate("staff-products_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:notifications.html.twig');
   
    }

    //Price Check action
    public function pricecheckAction(Request $request)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add price check to breadcrumb.
        $breadcrumbs->addItem("Price Check", $this->get("router")->generate("staff-products_price-check"));

        $defaultData = array('product_barcode' => '');

        $entity = new Product();
        $form = $this->createPriceCheckForm($entity);


        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:price_check.html.twig', array(
            'submitted' => false,
            'product_not_found' => false,
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
    private function createPriceCheckForm(Product $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PriceCheckType(), $entity, array(
            'action' => $this->generateUrl('staff-products_price-check-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Check price', 'attr' => array(
                        'class' => 'btn btn-primary col-xs-12 col-md-offset-4 col-md-3')));

        return $form;
    }
    

    /**
     * Finds and displays a Product entity.
     *
     */
    public function pricechecksubmitAction(Request $request)
    {
       
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("Price Check", $this->get("router")->generate("staff-products_price-check"));
        $breadcrumbs->addItem("Price Result");
        
        $defaultData = array('product_barcode' => '');
        $entity = new Product();
        $form = $this->createPriceCheckForm($entity);

      
           
        $form->submit($request->request->get($form->getName()));

        $barcode = $form["product_barcode"]->getData();


        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Product')->findBy(array('product_barcode'=> $barcode));

        if (!$entity) {

            //Check the inner barcode.
            $entity = $em->getRepository('MilesApartAdminBundle:Product')->findBy(array('product_inner_barcode'=> $barcode));

            if (!$entity) {

                //Check the outer barcode.
                $entity = $em->getRepository('MilesApartAdminBundle:Product')->findBy(array('product_outer_barcode'=> $barcode));

                if (!$entity) {
                    return $this->render('MilesApartStaffBundle:Products:price_check.html.twig', array(
                        'submitted' => false,
                        'product_not_found' => true,
                        'form'   => $form->createView(),
                    ));
                } 
                
            }
        }

        if ($entity) {

            //Set the product session.
            $session = new Session();
            $session->set('price_check_product', $entity[0]);
        }

        $product_id = $entity[0]->getId();
        //Get suppliers from db
        $current_price = $this->getDoctrine()
        //Identify repository
        ->getRepository('MilesApartAdminBundle:ProductPrice')
        //Call getAllSuppliers method from repository
        ->getCurrentPrice($product_id);
        
        
        $entity2 = new Product();
        $form2 = $this->createPriceCheckForm($entity2);

        //Set up new price form.
        $entity3 = new ProductPrice();
        $form3 = $this->createNewPriceForm($entity3);

        //Set new price variables
        $entity3->setProduct($entity[0]);
        $entity3->setProductPriceValidFrom(new \DateTime('now'));

        return $this->render('MilesApartStaffBundle:Products:show_price.html.twig', array(
            'entity'      => $entity,
            'submitted' => true,
             'form'   => $form2->createView(),
             'form2'   => $form3->createView(),
                   ));
    }

    /**
    * Creates a form to add new product price to product.
    *
    * @param ProductPriceType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createNewPriceForm(ProductPrice $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new NewPriceType(), $entity, array(
            'action' => $this->generateUrl('staff-products_new-price-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Update price', 'attr' => array(
                        'class' => 'btn btn-primary col-xs-12 col-md-offset-4 col-md-3')));

        return $form;
    }

    /**
     * Finds and adds new price.
     *
     */
    public function newpricesubmitAction(Request $request)
    {
       
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("Price Check", $this->get("router")->generate("staff-products_price-check"));
        $breadcrumbs->addItem("Price Result");
        
        $new_price = new ProductPrice;
        $form = $this->createNewPriceForm($new_price);

         //Get the product from the session.
        $session = new Session();
        $product = $session->get('price_check_product');
        
        $em = $this->getDoctrine()->getManager();
        $product = $em->merge($product);

        $new_price->setProductPriceValidFrom(new \DateTime('now'));    
        $form->handleRequest($request);

        if ($form->isValid()) {
            //Set the product and valid from
            
            $new_price->setProduct($product);  
            $em->persist($new_price);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Your form was submitted successfully. Thank you!');
          
            return $this->redirect($this->generateUrl('staff-products_price-check'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
            return $this->redirect($this->generateUrl('staff-products_price-check'));
        }


        
    }


  
    //Print prices and labels
    public function printpricesandlabelsAction(Request $request)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add price check to breadcrumb.
        $breadcrumbs->addItem("Print Prices & Labels", $this->get("router")->generate("staff-products_print-prices-and-labels"));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:print_prices_and_labels.html.twig'
        );
    }

    //Print requested prices
    public function printrequestedpricesAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add price check to breadcrumb.
        $breadcrumbs->addItem("Print Requested Prices", $this->get("router")->generate("staff-products_print-requested-prices"));

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();

        //Find all print requests that have not been printed - it doesn;t matter what shop or type they are.
        $print_request_types = $em->getRepository('MilesApartAdminBundle:PrintRequestType')->getUnprintedPrintRequestTypes();


        //If they do exists, populate a table with their details
        return $this->render('MilesApartStaffBundle:Products:print_requested_prices.html.twig', array(
            
            'print_request_types'=>$print_request_types,
            
            
        ));
    }

       
    //Execute the printing of prices.
    public function printoutstandingpricesAction($price_type)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add price check to breadcrumb.
        $breadcrumbs->addItem("Print Requested Prices", $this->get("router")->generate("staff-products_print-requested-prices"));

        //Set up the entity manager
        $em = $this->getDoctrine()->getManager();
        
        //Find all print requests that have not been printed - it doesn;t matter what shop or type they are.
        $print_request_types = $em->getRepository('MilesApartAdminBundle:PrintRequestType')->getUnprintedPrintRequestTypes($price_type);

        //Iterate over the prices and update print request printed to true.
        foreach($print_request_types as $print_request_type) {
            foreach($print_request_type->getPrintRequest() as $print_request) {
                $print_request->setPrintRequestPrinted(TRUE);
            }
        }

        //$em->flush();
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:print_outstanding_prices.html.twig', array(
            'print_request_types'=>$print_request_types, 
            
        ));
    }


    //New price section
    public function newpriceAction(Request $request)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add price check to breadcrumb.
        $breadcrumbs->addItem("New Price", $this->get("router")->generate("staff-products_new-price"));


        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:new_price.html.twig', array(
            'submitted' => false,
            ));
    }

     /**
    * Creates a form to product entity by barcode.
    *
    * @param ProductType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
   /* private function createPriceCheckForm(Product $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PriceCheckType(), $entity, array(
            'action' => $this->generateUrl('staff-products_price-check-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Check price', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-3')));

        return $form;
    }*/
    

    /**
     * Finds and displays a Product entity.
     *
     */
    /*public function newpricesubmitAction(Request $request)
    {
       
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("New Price", $this->get("router")->generate("staff-products_new-price"));
        $breadcrumbs->addItem("New Price Result");
        
        /*$defaultData = array('product_barcode' => '');
        $entity = new Product();
        $form = $this->createPriceCheckForm($entity);

      
           
        $form->submit($request->request->get($form->getName()));

        $barcode = $form["product_barcode"]->getData();


        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Product')->findBy(array('product_barcode'=> $barcode));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find product.');
        }

        $product_id = $entity[0]->getId();
        //Get suppliers from db
        $current_price = $this->getDoctrine()
        //Identify repository
        ->getRepository('MilesApartAdminBundle:ProductPrice')
        //Call getAllSuppliers method from repository
        ->getCurrentPrice($product_id);
        
        
        $entity2 = new Product();
        $form2 = $this->createPriceCheckForm($entity2);

        return $this->render('MilesApartStaffBundle:Products:new_price_confirm.html.twig', array(
            'submitted' => false,
            ));

    }*/

    public function updatepriceAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $logger = $this->get('logger');
        $logger->info('LOG'.$response["product_id"]);
        $logger->info('I just got the logger add update price');
        //Set the new price and the product id
        //$logger->info($response["new_price"]);
        $new_price = floatval($response["new_price"]);
        $logger->info($new_price);
        $product_id = $response["product_id"];

        $em = $this->getDoctrine()->getManager();
        
        //Get product by id
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($product_id);

        //Create new product price
        $new_product_price = new ProductPrice();

        //Set the price value
        $logger->info('I just got the logger add 1');
        $new_product_price->setProductPriceValue($new_price);
        $logger->info('I just got the logger add 2');
        $new_product_price->setProductPriceValidFrom(new \DateTime());
        $logger->info('I just got the logger add 3');
        //Set the product.
        $new_product_price->setProduct($product);

        $product->addProductPrice($new_product_price);
        //Persist row to the database.
        $em->persist($product);
        $em->flush();

        $response = array("success" => true);
        return new JsonResponse(
                    $response 
                );
    }

    public function updateshortnameandsubtitleAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $logger = $this->get('logger');
        $logger->info('I just got the logger add update price');
         $logger->info($response["product_id"]);
        //Set the new price and the product id
        //$logger->info($response["new_price"]);
        $logger->info($response["short_name"]);
        $logger->info($response["subtitle"]);

       
        $logger->info('I just got the logger add update price 2');
        $short_name = $response["short_name"];
        $subtitle = $response["subtitle"];
        $logger->info('I just got the logger add update price 3');
        $product_id = $response["product_id"];

        $em = $this->getDoctrine()->getManager();
        
        //Get product by id
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($product_id);
$logger->info('I just got the logger add update price 4');
        $product->setShortName($short_name);
        $product->setPrintSubtitle($subtitle);
        //Persist row to the database.
        $em->persist($product);
        $em->flush();

        $response = array("success" => true);
        return new JsonResponse(
                    $response 
                );
    }

    public function updatesupplierAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $logger = $this->get('logger');
       
        $logger->info('I just got the logger add update price 2');
        $supplier_id = $response["supplier"];
        $product_id = $response["product_id"];

        $em = $this->getDoctrine()->getManager();
        
        //Get product by id
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($product_id);
        //Get supplier by id
        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneById($supplier_id);
$logger->info('I just got the logger add update price 4');

        //Create new supplier product
        $product_supplier = new ProductSupplier();
        $product_supplier->setSupplier($supplier);
        $product_supplier->setProduct($product);
        $product_supplier->setDefaultSupplier(TRUE);
        
        //Persist row to the database.
        $em->persist($product);
        $em->flush();

        $response = array("success" => true);
        return new JsonResponse(
                    $response 
                );
    }
    
    /*//////////////////////////////////////////////////////
    ////////////////// New product section /////////////////
    /////////////////////////////////////////////////////*/
    
    //New product action
    public function newproductAction(Request $request)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add price check to breadcrumb.
        $breadcrumbs->addItem("New Product", $this->get("router")->generate("staff-products_new-product"));
        $logger = $this->get('logger');
        $logger->info('I just got the logger tr ');

        $entity = new Product();
         $logger->info('I just got the logger tr 1');
        $form = $this->createCreateForm($entity);
 $logger->info('I just got the logger tr 2');
        return $this->render('MilesApartStaffBundle:Products:new_product.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
 $logger->info('I just got the logger tr 3');
    }    

    /**
    * Creates a form to create a new product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Product $entity)
    {
        $form = $this->createForm(new NewProductType(), $entity, array(
            'action' => $this->generateUrl('staff-products_new-product-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    


    /**
     * Creates new Product.
     *
     */
    public function newproductsubmitAction(Request $request)
    {
       
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("New Product", $this->get("router")->generate("staff-products_new-product"));
        $breadcrumbs->addItem("New Product Submitted");
        
        $entity = new Product();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        $datetime = new \DateTime();

        if ($form->isValid()) {

            //Assign each submitted cost to this product
            foreach($form->get('product_price')->getData() as $price) {
                $price->setProduct($entity);
                $price->setProductPriceValidFrom($datetime);
                $entity->removeProductPrice($price);
                $entity->addProductPrice($price);
                
            }

            //Assign each submitted cost to this product
            foreach($form->get('product_supplier')->getData() as $sup) {
                $sup->setProduct($entity);
                $sup->setDefaultSupplier(1);
                $entity->removeProductSupplier($sup);
                $entity->addProductSupplier($sup);
            }

            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New product has been added successfully.');

            return $this->redirect($this->generateUrl('staff-products_new-product', array('id' => $entity->getId())));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Products:new_product.html.twig', array(
            'submitted'=> $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    
    /***********************************
     *
     * CSV IMPORT FUNCTIONALITY - for product list
     *
     **********************************/

    /**
     * Page to select the upload file
     */
    public function importproductlistcsvAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));

        //Get the product list CSV class
        $entity = new ProductListCSVFile();

        //Create the form 
        $form = $this->createProductListCSVFileForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:import_product_list_csv.html.twig', array(
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
    private function createProductListCSVFileForm(ProductListCSVFile $entity)
    {
        //Build the form
        $form = $this->createForm(new ProductListCSVFileType(), $entity, array(
            'action' => $this->generateUrl('staff-products_process-product-list-csv'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        //Add the submit button
        $form->add('submit', 'submit', array('label' => 'Upload', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-12')));

        //Return the form class
        return $form;
    }


    /****************************************************
    * Process for uploading CSV and putting into array, prior to being mapped
    ****************************************************/
    public function processproductlistcsvAction(Request $request)
    {

        $entity = new ProductListCSVFile();
        $form = $this->createProductListCSVFileForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $outcome = "true";
            $data = $form->getData();
        } else { 
            $outcome = "false";
            $data="Fail";
        }

        $entity->preupload();
        $entity->upload();
        $supplier = $entity->getSupplier();
        $session = new Session();
        $session->set('csvSupplier', $supplier);
        $csvFilePath = 'product-list-uploads/' . $entity->getProductListCSVFilePath();

        $csvArray = $this->newcsvimport($request, $csvFilePath);

        return $this->render('MilesApartStaffBundle:Products:process_product_list_csv.html.twig', array(
            
            'csvArray'=> $csvArray,
            'outcome'=> $outcome,
            'supplier'=> $supplier,
            'csvFilePath'=>$csvFilePath
        ));

    }


    public function newcsvimport(Request $request, $csvFilePath)
    {

        // Create and configure the reader
        $file = new \SplFileObject($csvFilePath);
        $csvReader = new CsvReader($file);

        // Tell the reader that the first row in the CSV file contains column headers
        $csvReader->setHeaderRowNumber(0);

        // Create the workflow from the reader
        $workflow = new Workflow($csvReader);

        $csvArray = array();

        $arrayWriter = new ArrayWriter($csvArray);
        $workflow->addWriter($arrayWriter);

        // Process the workflow
        $upload = $workflow->process();

        $session = new Session();
        $session->set('csvArray', $csvArray);

        return $csvArray;
        
    }

    /****************************************************
    * Process for iterating over CSV and adding to DB (called after I map the fields)
    ****************************************************/
    public function dedupeproductlistcsvAction(Request $request)
    {   //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        //Get the session and supplier enitiy from session and merge with em.
        $session = new Session();
        $supplier = $session->get('csvSupplier');
        $supplier = $em->merge($supplier);
        
        //Set the supplier ID
        $supplierId = $supplier->getId();

        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = array('ok'=>$_POST);
        } else {
            $response = "false";
        }
        
        //Get the original array from the session.
        $csvArray = $session->get('csvArray');

        //Initiate array reader.
        $arrayReader = new ArrayReader($csvArray);

        //Create the workflow.
        $workflow = new Workflow($arrayReader);

        //Create the output array
        $csvArray2 = array();

        //Start writer and workflow
        $arrayWriter = new ArrayWriter($csvArray2);
        $workflow->addWriter($arrayWriter);

        //Set up the converter for mapping.
        $converter = new MappingItemConverter();

        //For each column selected to import, map the array to the DB.
        for ($i = 0;$i < count($response['ok']['columns']); ++$i){ 
            $columnName = $response['ok']['columns'][$i][0];
            $csvHeader = $response['ok']['columns'][$i][1];
            $converter->addMapping($csvHeader, $columnName);
        }

        //Add mapping to the workflow.
        $workflow->addItemConverter($converter);

        
        //Find the product name CSV header and ensure there are no blanks.
        //$filter = new CallbackFilter(function ($input) {
            
            //return ('' != $input['Description']);
        //});
        
        //$workflow->addFilter($filter);

        $first_response = $workflow->process();
       
        $count = count($csvArray2);
        $count2 = count($csvArray);

        //Remove the extra columns from array (that have not been selected for import)
        for($r = 0; $r < count($csvArray2); ++$r)  {
            $max = count($csvArray2[$r]);
            $min = $max - $i;
            $found = "found";
            $csvArray2[$r] = array_slice($csvArray2[$r], $min, $max);
            
        }

        //Set the session to the modified CSV Array.
        $csvArray = $csvArray2;
        $session->set('csvArray', $csvArray);
        
        /* Check if either the supplier product code, barcode or 
        product name exists in the DB for each of the products. */

        //Create duplicates array.
        $duplicates = [];

        //Create the array to store values to check product name duplicates.
        $records = [];

        //Define printout (for testing)
        $printout = '';
        $test = 'empty';
        $added = 0;
        $notAdded = 0;

        //Change counters
        $price_change_count = 0;
        $cost_change_count = 0;
        $inner_barcode_change_count = 0;
        $outer_barcode_change_count = 0;
        $inner_quantity_change_count = 0;
        $outer_quantity_change_count = 0;
        $weight_change_count = 0;
        $height_change_count = 0;
        $depth_change_count = 0;
        $width_change_count = 0;
        $product_pack_quantity_change_count = 0;

        $change = false;
        $barcode_match = false;

        //Iterate over csvArray.
        for($row = 0; $row < $count; ++$row){
           
            //Set the "toAdd" value used to know if the row should be added on the first import(not a duplicate)
            $toAdd = true;

            //Get the products
            $product = new Product();
            
            $repository = $em->getRepository('MilesApartAdminBundle:Product');

            try {
            /*////////////Barcode////////////*/
            //Check if barcode exists
            if (isset($csvArray2[$row]['product_barcode'])) {
                //Set the query value
                $value = $csvArray2[$row]['product_barcode'];

                //Call the repository query to check for existing barcode
                $product_barcode = $repository->findProductByBarcode($value);
                
                //If a barcode has been matched
                if (!$product_barcode)
                {
                    //No match
                    $printout .= "does not exist" .$value;
                } else {
                    //The product has been matched
                    $existing_product = $product_barcode;
                    //Add to duplicates
                    array_push($duplicates, $product_barcode);

                    if (isset($csvArray2[$row]['product_cost'])) {
                        //Get cost for comarison.
                        $import_product_cost = $csvArray2[$row]['product_cost'];
                    } else {
                        $import_product_cost = NULL;
                    }

                    //Remove from csvArray
                    //unset($csvArray2[$row]);

                    //Set to not import
                    $toAdd = false;

                    //Set barcode match to true (for adding missing data)
                    $barcode_match = true;

                    //Add to printout (for testing)
                    $printout .= "does exist" .$value;
                }

            //If barcode does not exist
            } else {
                $printout .= "<h2>Barcode is not selected</h2>";
            }
        }  catch (Exception $e) {
        $printout .= "<h2>Barcode fail</h2>";
        }

        try {
            /*/////////////Product Name///////////*/
            if (isset($csvArray2[$row]['product_name'])) {
                //Set the query value
                $value = $csvArray2[$row]['product_name'];

                //Call the repository query to check for existing name
                $product_name = $repository->findProductByName($value);

                //If a name has been matched
                if (!$product_name)
                {
                    //No match
                    $printout .= "does not exist" .$value;
                } else {
                    //The product has been matched
                    $existing_product = $product_name;
                    //Add to duplicates
                    array_push($duplicates, $product_name);

                    if (isset($csvArray2[$row]['product_cost'])) {
                        //Get cost for comarison.
                        $import_product_cost = $csvArray2[$row]['product_cost'];
                    } else {
                        $import_product_cost = NULL;
                    }


                    //Remove from CSV array
                    //unset($csvArray2[$row]);

                    //Set to not import
                    $toAdd = false;

                    //Add to printout (for testing)
                    $printout .= "does exist" .$value;

                }

                //Check if the product name is a duplicate (within csv file)
                /*if(!in_array($csvArray2[$row]['product_name'], $records) && $toAdd) {
                    $records[] = $csvArray2[$row]['product_name'];
                } else {
                    $csvArray2[$row]['product_name'] = $csvArray2[$row]['product_name'] . " - Dupe " .$row;
                }*/

            //If name does not exist
            } else {
                $printout .= "Name is not selected";
            }

        }  catch (Exception $e) {

            $printout .= "Product name fail";
        }
             
        try {
            /*////////////Product supplier code////////////*/
            if (isset($csvArray2[$row]['product_supplier_code'])) {
                //Set the query value
                $value = $csvArray2[$row]['product_supplier_code'];

                //Call the repository query to check for existing supplier code for the set supplier
                $product_supplier_code = $repository->findBySupplierCode($value, $supplierId);

                //If a supplier code has been matched
                if (!$product_supplier_code)
                {
                    //No match
                    $printout .= "does not exist" .$value;
                } else {
                    //The product has been matched
                    $existing_product = $product_supplier_code;
                    //Add to duplicates
                    array_push($duplicates, $product_supplier_code);

                    if (isset($csvArray2[$row]['product_cost'])) {
                        //Get cost for comarison.
                        $import_product_cost = $csvArray2[$row]['product_cost'];
                    } else {
                        $import_product_cost = NULL;
                    }

                    
                    //Remove from CSV array
                    //unset($csvArray2[$row]);

                    //Set to not import
                    $toAdd = FALSE;

                    //Add to printout (for testing)
                    $printout .= "does exist" .$value;
                }
          
            //If supplier code does not exist
            } else {
                $printout .= "Product supplier code is not selected";
            }
            
        }  catch (Exception $e) {
            $printout .= "<h2>Supplier code fail</h2>";
        }
            

            //Check if toAdd is true and add to database.
            if($toAdd) {

                try {
                   $em = $this->getDoctrine()->getManager();
                    $product = new Product();
                     
                    //Set the supplier 
                    $product_supplier = new ProductSupplier();
                    $product_supplier->setSupplier($supplier);
                    $product_supplier->setDefaultSupplier(1);
                    $product_supplier->setProduct($product);
                   
                    $em->persist($product_supplier);
                    
                    //Iterate over the columns of the csv file using the mapped column headers
                    foreach ($csvArray2[$row] as $key => $value) {
                        //Convert the array key to setter name
                        $word = str_replace('_', ' ', $key) ;
                        $words = ucwords($word);
                        $setter ="set". str_replace(' ', '', $words) ;

                        //If this array alue is the description, fix capitalisation on the text.
                        if ($setter == "setProductName") {
                            $value = strtolower($value);
                            $value = ucwords($value);
                            
                        }

                        //if ($setter == "setProductBarcode") {
                            //$value = str_replace(' ', '', $value);
                        //}

                        //Chcek if price, if so create new price and add
                        if ($setter == "setProductCost") {
                            
                            $product_cost = new ProductCost();
                            $product_cost->setProductCostValue($value);
                            $product_cost->setProductCostIsSpecial(0);
                            $product_cost->setProductSupplier($product_supplier);
                            $product_cost->setProductCostValidFrom(new \Datetime);
                            
                            $em->persist($product_cost);
                            
                        } else if ($setter == "setProductPrice") {
                            $product_price = new ProductPrice();
                            $product_price->setProductPriceValue($value);
                            $product_price->setProductPriceIsSpecial(0);
                            $product_price->setProduct($product);
                            $product_price->setProductPriceValidFrom(new \Datetime);
                            
                            $em->persist($product_price);
                        } else {
                            //Assign setters of to each of the values
                            $product->$setter($value);
                        }   
                    }
                    
                    //Persist row to the database.
                   
                    $em->persist($product);
                    
                    ++$added;
                } catch (Exception $e) {
                    //This row was not added to the database
                    
                    
                }
            //Product is not added as toadd is false.  
            $current_cost = null;  

            //Else, if the product is in the DB, check for new data that can be added to the DB (toAdd is false)
            } else { 

                //Check that the barcode matches, only then add new data to the row. 
                if($barcode_match == true) {

                    //Check if the cost has changed, if so add new cost to db
                    //Find the cost for this product from the db
                    //Check the importing cost is not null
                    if ($import_product_cost != NULL) {
                        
                        //Ceck if the existing cost is null
                        if ($existing_product[0]->getCurrentCost() != null) {

                            if($existing_product[0]->getCurrentCost() != $import_product_cost) {

                                //Create new cost in DB
                                $new_cost = new ProductCost();
                                $new_cost->setProductCostValue($import_product_cost);
                                $new_cost->setProductCostIsSpecial(0);
                                $new_cost->setProductSupplier($existing_product[0]->getDefaultProductSupplier());
                                $new_cost->setProductCostValidFrom(new \DateTime('now'));
                                        
                                $em->persist($new_cost);
                                

                                $cost_change_count++;
                                $change = true;
                            }

                        //Existing product has no cost assigned so add the new one
                        } else {

                            //Create new product cost
                            $new_cost = new ProductCost();
                            $new_cost->setProductCostValue($import_product_cost);
                            $new_cost->setProductCostIsSpecial(0);
                            $new_cost->setProductSupplier($existing_product[0]->getDefaultProductSupplier());
                            $new_cost->setProductCostValidFrom(new \DateTime('now'));
                                    
                            $em->persist($new_cost);

                            $cost_change_count++;
                            $change = true;
                        }
                    }

                    //Check if the inner barcode has changed, if so add new barcode to db
                    //Make sure the barcode is not null or empty
                    if(isset($csvArray2[$row]['product_inner_barcode']) && $csvArray2[$row]['product_inner_barcode'] != "") {
                    
                        //Find the inner barcode for this product from the db
                        if ($existing_product[0]->getProductInnerBarcode() != $csvArray2[$row]['product_inner_barcode']) {
                            //Set the new barcode in the db.
                            $existing_product[0]->setProductInnerBarcode($csvArray2[$row]['product_inner_barcode']);

                            //Persist to save the changes.
                            $em->persist($existing_product[0]);
                            
                            //Update change count
                            $inner_barcode_change_count++;
                            $change = true;
                        }
                    }
                    //Check if the outer barcode has changed, if so add new cost to db
                    //Make sure the barcode is not null or empty
                    if(isset($csvArray2[$row]['product_outer_barcode']) && $csvArray2[$row]['product_outer_barcode'] != "") {

                        //Find the outer barcode for this product from the db
                        if ($existing_product[0]->getProductOuterBarcode() != $csvArray2[$row]['product_outer_barcode']) {
                           
                           //Set the new barcode in the db.
                            $existing_product[0]->setProductOuterBarcode($csvArray2[$row]['product_outer_barcode']);

                            //Persist to save the changes.
                            $em->persist($existing_product[0]);
                            
                            //Update change count
                            $outer_barcode_change_count++;
                            $change = true;
                        }
                    }

                    //Check if the inner quantity has changed, if so add new cost to db
                    //Make sure the quantity is not null or empty
                    if(isset($csvArray2[$row]['product_inner_quantity']) && $csvArray2[$row]['product_inner_quantity'] != "") {

                        //Find the inner quantity for this product from the db
                        if ($existing_product[0]->getProductInnerQuantity() != $csvArray2[$row]['product_inner_quantity']) {
                           
                           //Set the new quantity in the db.
                            $existing_product[0]->setProductInnerQuantity($csvArray2[$row]['product_inner_quantity']);

                            //Persist to save the changes.
                            $em->persist($existing_product[0]);
                            
                            //Update change count
                            $inner_quantity_change_count++;
                            $change = true;
                        }
                    }
                    //Check if the outer quantity has changed, if so add new cost to db
                    //Make sure the quantity is not null or empty
                    if(isset($csvArray2[$row]['product_outer_quantity']) && $csvArray2[$row]['product_outer_quantity'] != "") {

                        //Find the outer quantity for this product from the db
                        if ($existing_product[0]->getProductOuterQuantity() != $csvArray2[$row]['product_outer_quantity']) {
                           
                           //Set the new quantity in the db.
                            $existing_product[0]->setProductOuterQuantity($csvArray2[$row]['product_outer_quantity']);

                            //Persist to save the changes.
                            $em->persist($existing_product[0]);
                            
                            //Update change count
                            $outer_quantity_change_count++;
                            $change = true;
                        }
                    }


                    //Check if the weight has changed, if so add new cost to db
                    //Make sure the weight is not null or empty
                    if(isset($csvArray2[$row]['product_weight']) && $csvArray2[$row]['product_weight'] != "") {

                        //Find the weight for this product from the db
                        if ($existing_product[0]->getProductWeight() != $csvArray2[$row]['product_weight']) {
                           
                           //Set the weight in the db.
                            $existing_product[0]->setProductWeight($csvArray2[$row]['product_weight']);

                            //Persist to save the changes.
                            $em->persist($existing_product[0]);
                            
                            //Update change count
                            $weight_change_count++;
                            $change = true;
                        }
                    }

                    //Check if the height has changed, if so add new cost to db
                    //Make sure the height is not null or empty
                    if(isset($csvArray2[$row]['product_height']) && $csvArray2[$row]['product_height'] != "") {

                        //Find the height for this product from the db
                        if ($existing_product[0]->getProductHeight() != $csvArray2[$row]['product_height']) {
                           
                           //Set the new height in the db.
                            $existing_product[0]->setProductHeight($csvArray2[$row]['product_height']);

                            //Persist to save the changes.
                            $em->persist($existing_product[0]);
                            
                            //Update change count
                            $height_change_count++;
                            $change = true;
                        }
                    }


                    //Check if the depth has changed, if so add new cost to db
                    //Make sure the height is not null or empty
                    if(isset($csvArray2[$row]['product_depth']) && $csvArray2[$row]['product_depth'] != "") {

                        //Find the depth for this product from the db
                        if ($existing_product[0]->getProductDepth() != $csvArray2[$row]['product_depth']) {
                           
                           //Set the new depth in the db.
                            $existing_product[0]->setProductDepth($csvArray2[$row]['product_depth']);

                            //Persist to save the changes.
                            $em->persist($existing_product[0]);
                            
                            //Update change count
                            $depth_change_count++;
                            $change = true;
                        }
                    }

                    //Check if the width has changed, if so add new cost to db
                    //Make sure the width is not null or empty
                    if(isset($csvArray2[$row]['product_width']) && $csvArray2[$row]['product_width'] != "") {

                        //Find the width for this product from the db
                        if ($existing_product[0]->getProductWidth() != $csvArray2[$row]['product_width']) {
                           
                           //Set the new width in the db.
                            $existing_product[0]->setProductWidth($csvArray2[$row]['product_width']);

                            //Persist to save the changes.
                            $em->persist($existing_product[0]);
                            
                            //Update change count
                            $width_change_count++;
                            $change = true;
                        }
                    }

                    //Check if the product pack quantity has changed, if so add new cost to db
                    //Make sure the product pack quantity is not null or empty
                    if(isset($csvArray2[$row]['product_pack_quantity']) && $csvArray2[$row]['product_pack_quantity'] != "") {

                        //Find the product pack quantity for this product from the db
                        if ($existing_product[0]->getProductPackQuantity() != $csvArray2[$row]['product_pack_quantity']) {
                           
                           //Set the new product pack quantity in the db.
                            $existing_product[0]->setProductPackQuantity($csvArray2[$row]['product_pack_quantity']);

                            //Persist to save the changes.
                            $em->persist($existing_product[0]);
                            
                            //Update change count
                            $product_pack_quantity_change_count++;
                            $change = true;
                        }
                    }



                //End of barcode match if
                }
                ++$notAdded ;
            }
            /*////////////Price/cost////////////*/

            //For each duplicate remove from array and put into duplicates array
            $printout .= "Test";

            
        //End of CSV iteration.
        }

        //Check the dupes for price changes - iterate over dupes and compare prices, if different, add new.

        if ($added > 0 || $change == true) {
            $em->flush();
            $em->clear();
            $flash = "Products have been added/updated";
        } else if ($notAdded > 0) {
            $flash = "No products have been added";
        }

        $count = count($csvArray2);


        //Return results data to template for ajax page section.
         //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:csv_upload_response.html.twig', array (
            
            'csvArray' => $csvArray,
            'csvArray2'=> $csvArray2, 
            'duplicates'=> $duplicates,
            'supplierId'=> $supplierId,
            'test' => $test,
            'flash' => $flash,
            'added' => $added,
            'notAdded' => $notAdded,
            'row' => $row,
            'printout' => $printout,
            'count' => $count,
            'count2' => $count2,
            'first_response' => $first_response,
            'price_change_count' => $price_change_count,
            'cost_change_count' => $cost_change_count,
            'inner_barcode_change_count' => $inner_barcode_change_count,
            'outer_barcode_change_count' => $outer_barcode_change_count,
            'inner_quantity_change_count' => $inner_quantity_change_count,
            'outer_quantity_change_count' => $outer_quantity_change_count,
            'weight_change_count' => $weight_change_count,
            'depth_change_count' => $depth_change_count,
            'height_change_count' => $height_change_count,
            'width_change_count' => $width_change_count,
            'product_pack_quantity_change_count' => $product_pack_quantity_change_count,
            
        ));
      
    }
    
    

    
    public function viewstockoutsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Stockouts", $this->get("router")->generate("staff-products_view-stockouts"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:view_stockouts.html.twig');
   
    }

    public function startstocktakeAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Start stocktake", $this->get("router")->generate("staff-products_start-stocktake"));
        
        //Check if existing stocktake (incoplete).
        $em = $this->getDoctrine()->getManager();
        $stocktake = $em->getRepository('MilesApartAdminBundle:Stocktake')->findOneBy(array('stocktake_completed_date'=> NULL), array('stocktake_start_date' => 'DESC'));

        //If no esxisting, create new.
        if ($stocktake == NULL) {
            $stocktake = new Stocktake();

            $stocktake->setStocktakeStartDate(new \Date());
        }
        //Set the stocktake session.
        $session = new Session();
        $session->set('stocktake', $stocktake);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:start_stocktake.html.twig', array (
            'stocktake' => $stocktake,
            ));
        


    }

    public function startstocktakenewAction() 
    {
        
        $em = $this->getDoctrine()->getManager();

        //Create new stocktake
        $stocktake = new Stocktake();
        
        //Set the stocktake session.
        $session = new Session();
        $session->set('stocktake', $stocktake);

        $em->persist($stocktake);
        $em->flush();

        $response = $this->forward('MilesApartStaffBundle:Products:startstocktake', array(
            
        ));

        return $response;


    }

    public function addstocktakeproductsubmitAction(Request $request)
    {
        //Set the function variable.
        $function_variable = "StocktakeProduct";
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Get the barcode and search query from the request
        $barcode = $response["barcode"];
        $search_query = $response["search_query"];

        //Call add product to list from helper class.
        $response = $this->forward('MilesApartStaffBundle:Helper:addProductToList', array(
            'function_variable'  => $function_variable,
            'barcode' => $barcode,
            'search_query' => $search_query,
        ));
        
        return $response;
    }

    public function selectstocktakeshelfAction(Request $request)
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

        $stocktake = $session->get('stocktake');
        $stocktake = $em->merge($stocktake);

        //Check if products already exist in an incomplete transfer request
        $stocktake_products = $em->getRepository('MilesApartAdminBundle:StocktakeProduct')->findBy(array('stocktake'=>$stocktake, 'stock_location_shelf'=>$stock_location_shelf), array('id' => 'DESC'));

        //Set up the array to be returned 
        $jsonContentArray = array('products' => []);

        foreach ($stocktake_products as $key => $value) {

            //Get supplier if it exists
            if($value->getProduct()->getDefaultProductSupplier() != null) {
                $sup = $value->getProduct()->getDefaultProductSupplier()->getSupplier()->getSupplierShortName();
            } else {
                $sup = null;
            }
            $jsonContent = array(
                'product_name' => $value->getProduct()->getProductName(), 
                'product_price'=> $value->getProduct()->getCurrentPriceDisplay(),
                'product_supplier_code'=> $value->getProduct()->getProductSupplierCode(),
                'product_barcode' => $value->getProduct()->getProductBarcode(),
                'supplier_name' => $sup,            
                'product_id' => $value->getProduct()->getId(),
                'stocktake_product_qty' => $value->getStocktakeProductQty(),
                'prod_id'=> $value->getId(),
            );

            array_push($jsonContentArray['products'], $jsonContent);
        }
        
        $response = array(     
                        'stock_location_shelf_code' => $stock_location_shelf->getStockLocationShelfCode(), 
                        'stock_location_name'=> $stock_location_shelf->getStockLocation()->getStockLocationName(),
                        'business_premises_name'=> $stock_location_shelf->getStockLocation()->getBusinessPremises()->getBusinessPremisesName(),
                        'stocktake_products' => $jsonContentArray,
                    );

        $response = new JsonResponse($response);
        return $response;
    
    }


    public function stocktakeproductnewqtyAction(Request $request) 
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
        $stocktake_product_id = $response["product_id"];

        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $stocktake_product = $em->getRepository('MilesApartAdminBundle:StocktakeProduct')->findOneBy(array('id' => $stocktake_product_id));

        //Set the product
        $stocktake_product->setStocktakeProductQty($new_qty);

        //Persist row to the database.
        $em->persist($stocktake_product);
        $em->flush();

        $response = array("success" => true);
        return new JsonResponse(
                    $response 
                );
    }

    public function addstocktakeproductsubmitnewproductAction(Request $request) 
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

        $em->persist($product);

        //Get stocktake and stock location shelf
        $session = new Session();
        $stocktake = $session->get('stocktake');
        $stock_location_shelf = $session->get('stock_location_shelf');

        $stocktake = $em->merge($stocktake); 
        $stock_location_shelf = $em->merge($stock_location_shelf); 

        //Then create new product transfer request, adding the quantity
        $stocktake_product = new StocktakeProduct();

        $stocktake_product->setProduct($product);
        $stocktake_product->setStocktake($stocktake);
        $stocktake_product->setStockLocationShelf($stock_location_shelf);
        $stocktake_product->setStocktakeProductQty($new_product_qty);

        $em->persist($stocktake_product);
        //Persist the database

        
        $em->flush();

        //Then return success.

        $response = array(
                    'product_name' => $stocktake_product->getProduct()->getProductName(), 
                    'product_stock_qty'=>'NA', 
                    'product_price'=>$stocktake_product->getProduct()->getCurrentPrice(), 
                    'product_supplier_code'=> $stocktake_product->getProduct()->getProductSupplierCode(),
                    'product_barcode' => $stocktake_product->getProduct()->getProductBarcode(),
                    'supplier_name' => $stocktake_product->getProduct()->getDefaultProductSupplier(),
                    'product_qty'=> $stocktake_product->getStocktakeProductQty(), 
                    'product_id' => $stocktake_product->getId(),
                    'success'=> true);


                return new JsonResponse(
                    $response 
                );


            
    }

    //Code to handle processing of returns
    public function viewstocktakesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View stocktakes", $this->get("router")->generate("staff-products_view-stocktakes"));
        
        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:view_stocktakes.html.twig', array(
           
            ));
   
    }

    public function completestocktakeAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Complete stocktake", $this->get("router")->generate("staff-products_complete-stocktake"));
        
        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $stocktake = $em->getRepository('MilesApartAdminBundle:Stocktake')->findOneBy(array('stocktake_completed_date'=> NULL), array('stocktake_start_date' => 'DESC'));

        $stocktake_unique = $this->stocktakeUnique($stocktake);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:complete_stocktake.html.twig', array(
            'stocktake' => $stocktake,
            'stocktake_unique' => $stocktake_unique
            ));
   
    }

    public function confirmcompletestocktakeAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Complete stocktake confirm", $this->get("router")->generate("staff-products_confirm-stocktake-completion"));
        
        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $stocktake = $em->getRepository('MilesApartAdminBundle:Stocktake')->findOneBy(array('stocktake_completed_date'=> NULL), array('stocktake_start_date' => 'DESC'));

        //Get array to upload to Amazon
        $stocktake_unique = $this->stocktakeUnique($stocktake);

        //Send the array to Amazon Upload script
//        $amazon_response = $this->forward('MilesApartSellerBundle:Amazon:uploadAmazonProductArray', array(
//            'stocktake_unique'  => $stocktake_unique,
//        ));

        //Update stocktake completed date
        $stocktake->setStocktakeCompletedDate(new \DateTime());

        $em->persist($stocktake);
        $em->flush();

        $amazon_response = true;

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:complete_stocktake_confirm.html.twig', array(
            'amazon_response' => $amazon_response
        ));
    }

    public function stocktakeUnique($stocktake) 
    {
        $stocktake_unique = array();

        //Iterate over the stocktake products 
        foreach($stocktake->getStocktakeProduct() as $stocktake_product) {
            //Check there are items in the stocktake unique array
            if(count($stocktake_unique) > 0) {

                //Iterate over the unique stocktake array
                foreach($stocktake_unique as $key => $unique_stocktake_product) {
                    //Check if there is a match
                    if($stocktake_product->getProduct()->getId() == $unique_stocktake_product['product']->getProduct()->getId()) {
                        //Update the quantity
                        $stocktake_unique[$key]['qty'] = $unique_stocktake_product['qty'] + $stocktake_product->getStocktakeProductQty();
                        
                    } else {
                        //Add the stocktake product to the unique stocktake
                        $stocktake_product_array = array('product' => $stocktake_product, 'qty' => $stocktake_product->getStocktakeProductQty());
                        array_push($stocktake_unique, $stocktake_product_array);
                    }
                }
            } else {
                //There are no items in the stocktake unique array, so add th item
                $stocktake_product_array = array('product' => $stocktake_product, 'qty' => $stocktake_product->getStocktakeProductQty());
                array_push($stocktake_unique, $stocktake_product_array);
            }
        }

        return $stocktake_unique;
    }
    

    public function packupseasonalAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add product notifications to breadcrumb.
        $breadcrumbs->addItem("Pack up seasonal", $this->get("router")->generate("staff-products_pack-up-seasonal"));
        
        $entity = new PackUpSeasonal();

        $form = $this->createPackUpSeasonalSelectForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:pack_up_seasonal.html.twig', array(
            'submitted'=> false,
            'entity' => $entity,
            'form'   => $form->createView(),
            ));
    }

    /**
    * Creates a form to create a PackUpSeasonal entity.
    *
    * @param PackUpSeasonal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPackUpSeasonalSelectForm(PackUpSeasonal $entity)
    {
        $form = $this->createForm(new PackUpSeasonalSelectType(), $entity, array(
            'action' => $this->generateUrl('staff-products_pack-up-seasonal-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Next', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-12')));

        return $form;
    }

    public function packupseasonalsubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add product notifications to breadcrumb.
        $breadcrumbs->addItem("Pack up seasonal", $this->get("router")->generate("staff-products_pack-up-seasonal"));

        
        $entity = new PackUpSeasonal();
        $form = $this->createPackUpSeasonalSelectForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $outcome = "true";
            $data = $form->getData();
        } else { 
            $outcome = "false";
            $data="Fail";
        }

       
        $season = $data->getSeason();
        $business_premises = $data->getBusinessPremises();

        $session = new Session();
        $session->set('season', $season);
        $session->set('business_premises', $business_premises);

        //Crete the box selection form
        $entity = new SeasonalStorageBox();

        $form = $this->createSeasonalStorageBoxSelectForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:pack_up_seasonal_selected.html.twig', array(
            'submitted'=> false,
            'season' => $season,
            'business_premises' => $business_premises,
            'form' => $form->createView(),
        ));

    }

    public function seasonalstorageboxselectsubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Request Products Premises Select", $this->get("router")->generate("staff-transfer-requests_request-products"));
        // Add pick & pack to breadcrumb.

        $entity = new SeasonalStorageBox();

        $form = $this->createSeasonalStorageBoxSelectForm($entity);
        $form->handleRequest($request);
        
        
        if ($form->isValid()) {
            $outcome = "true";
            $data = $form->getData();
        } else { 
            $outcome = "false";
            $data="Fail";
        }

        $barcode = $data->getSeasonalStorageBoxCode();

        $em = $this->getDoctrine()->getManager();
        $seasonal_storage_box = $em->getRepository('MilesApartAdminBundle:SeasonalStorageBox')->findOneBy(array('seasonal_storage_box_code'=> $barcode));

        //Set the box in the session.
        $session = new Session();
        $session->set('seasonal_storage_box', $seasonal_storage_box);

        //Get the products that are already stored in the seasonal storage box. NEED TO FIGURE OUT HOW TO DO THIS WHEN USING STOCKTAKE PRODUCT
        $seasonal_storage_box_products = $em->getRepository('MilesApartAdminBundle:SeasonalStorageBoxProduct')->findBy(array('seasonal_storage_box'=> $seasonal_storage_box));

        //Create the form to add products.
        $entity = new Product();
        $form = $this->createAddProductToSeasonalStorageBoxForm($entity);


        return $this->render('MilesApartStaffBundle:Products:add_product_to_seasonal_storage_box.html.twig', array(
            'seasonal_storage_box' => $seasonal_storage_box,
            'seasonal_storage_box_products' => $seasonal_storage_box_products,
            'form'   => $form->createView(),
            'submitted' => false,
            
        ));
    }

    


    /**
    * Creates a form to create a SeasonaStorageBox entity.
    *
    * @param SeasonalStorageBox $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createSeasonalStorageBoxSelectForm(SeasonalStorageBox $entity)
    {
        $form = $this->createForm(new SeasonalStorageBoxType(), $entity, array(
            'action' => $this->generateUrl('staff-products_seasonal-storage-box-select-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal', 'id' => 'milesapart_staffbundle_seasonalstoragebox_seasonal_storage_box_form')
        ));

        $form->add('submit', 'submit', array('label' => 'Next', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-12')));

        return $form;
    }

    public function addproducttoseasonalstorageboxAction(Request $request) 
    {
        //Set the function variable.
        $function_variable = "SeasonalStorageBox";
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Get the barcode and search query from the request
        $barcode = $response["barcode"];
        $search_query = $response["search_query"];

        //Call add product to list from helper class.
        $response = $this->forward('MilesApartStaffBundle:Helper:addProductToList', array(
            'function_variable'  => $function_variable,
            'barcode' => $barcode,
            'search_query' => $search_query,
        ));

        return $response;
         
    }

    public function addProductToSeasonalStorageBox($product_id) 
    {
        //Set up entity manager
        $em = $this->getDoctrine()->getManager();
        
        //Get product from the id
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($product_id);

        //Get seasonal storage box from the session
        $session = new Session();
        $seasonal_storage_box = $session->get('seasonal_storage_box');
        $seasonal_storage_box = $em->merge($seasonal_storage_box);

        //If all is ok, add the product to the product transfer request table
        //New product tarnsfer request
        $seasonal_storage_box_product = new SeasonalStorageBoxProduct();

        //Set the product
        $seasonal_storage_box_product->setProduct($product);
        
        //Set the saesonal storage box                                                 
        $seasonal_storage_box_product->setSeasonalStorageBox($seasonal_storage_box);

        //Set the default quantity
        $seasonal_storage_box_product->setSeasonalStorageBoxProductQty(1);

        //Persist row to the database.
        $em->persist($seasonal_storage_box_product);
        $em->flush();

        //Set up the array for AJAX response
        $response = array(     
                        'product_name' => $product->getProductName(), 
                        'product_price'=> $product->getCurrentPrice(),
                        'product_supplier_code'=> $product->getProductSupplierCode(),
                        'product_barcode' => $product->getProductBarcode(),
                        'supplier_name' => $product->getDefaultProductSupplier(),            
                        'product_id' => $product->getId(),
                        'stocktake_product_qty' => $seasonal_storage_box_product->getStocktakeProductQty(),
                        'stocktake_product_id' => $seasonal_storage_box_product->getId(),
                    );
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

    //For adding to seasonal storage box if more than one match (from checkbox).
    public function seasonalstorageboxmultipleproductsselectsubmitAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('I just got the logger tr 0');
        //Set the function variable
        $function_variable = "SeasonalStorageBox";

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

    //Update the quantity of a product in a seasonal storage box
    public function seasonalstorageboxproductnewqtyAction(Request $request) 
    {
       
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {

            //Load the post array into response variable
            $response = $_POST;
        
        //If there is no post data
        } else {
            
            //Set response to false
            $response = "false";
        }

       
        //Set the new qty and the id of the seasonal storage product (stocktake product) id
        $new_qty = $response["new_qty"];
        $seasonal_storage_box_product_id = $response["product_id"];
        
        //Load the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get stocktake product by id
        $seasonal_storage_box_product = $em->getRepository('MilesApartAdminBundle:SeasonalStorageBoxProduct')->findOneBy(array('id' => $seasonal_storage_box_product_id));

        //Set the new quantity
        $seasonal_storage_box_product->setSeasonalStorageBoxProductQty($new_qty);
        
        //Persist row to the database.
        $em->persist($seasonal_storage_box_product);

        //Flush changes
        $em->flush();

        //Return the response with success value
        $response = array("success" => true);
        return new JsonResponse(
                    $response 
                );
    }

     public function seasonalstorageboxproductnewproductAction(Request $request) 
    {

        //Get the $_POST array
        if ($request->isXMLHttpRequest()) {

            //Load the post array into response variable
            $response = $_POST;
            
        //If there is no post data
        } else {
            
            //Set response to false
            $response = "false";
        }

        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        //Get the variables from the response variable
        $new_product_name = $response["new_product_name"];
        $new_product_barcode = $response["new_product_barcode"];
        $new_seasonal_storage_box_product_qty = $response["new_product_qty"];
        $new_seasonal_storage_box_product_supplier_code = $response["new_product_supplier_code"];
        $new_seasonal_storage_box_product_price = $response["new_product_price"];
        $new_product_supplier_id = $response["new_product_supplier_id"];
         
        //Get the supplier entity by the id
        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneBy(array('id' => $new_product_supplier_id));
 
        //Set the session for next add (so supplier will automatically selected)
        $session = new Session();

        //Set new product supplier session
        $session->set('new_product_supplier', $supplier);
 
        //Get selected seasonal storage box from session (so the new product can be assigned to it.)
        $seasonal_storage_box = $session->get('seasonal_storage_box');

        //Merge so it can be used for persistence
        $seasonal_storage_box = $em->merge($seasonal_storage_box);
        
        //Create new product in the database with the product name and barcode.
        $product = new Product();

        //Set new product values
        $product->setProductName($new_product_name);
        $product->setProductBarcode($new_product_barcode);
        $product->setProductSupplierCode($new_seasonal_storage_box_product_supplier_code);

        //Check if a price has been submitted
        if ($new_seasonal_storage_box_product_price) {
            //Price has been submitted, so create new product price entity
            $product_price = new ProductPrice();
            $product_price->setProductPriceValue($new_seasonal_storage_box_product_price);
            $product_price->setProduct($product);
            $product_price->setProductPriceValidFrom(new \DateTime());

            //Persist product price
            $em->persist($product_price);
        
        //Add the product price to the new product
        $product->addProductPrice($product_price);
        }

        //Add supplier details.
        $product_supplier = new ProductSupplier();
        $product_supplier->setDefaultSupplier(true);
        $product_supplier->setSupplier($supplier);
        $product_supplier->setProduct($product);

        //Add the season to the product.
        //Get the season forn the box code
        $season_code = substr($seasonal_storage_box->getSeasonalStorageBoxCode(), 0, 2);

        //Get the season entity
        $season = $em->getRepository('MilesApartAdminBundle:Season')->findOneBy(array('season_code' => $season_code));

        $product->removeSeason($season);
        $product->addSeason($season);

        //Persist changes to database
        $em->persist($product);
        $em->persist($product_supplier);

        //Then create new stocktake product entity that links the product with the seasonal storage box, adding the quantity
        $seasonal_storage_box_product = new SeasonalStorageBoxProduct();

        $seasonal_storage_box_product->setProduct($product);
        $seasonal_storage_box_product->setSeasonalStorageBox($seasonal_storage_box);
        $seasonal_storage_box_product->setSeasonalStorageBoxProductQty($new_seasonal_storage_box_product_qty);

        $em->persist($seasonal_storage_box_product);
        //Persist the database

        $em->flush();

        //Then return success.

        $response = array(
                    'product_name' => $seasonal_storage_box_product->getProduct()->getProductName(), 
                    'product_stock_qty'=>'NA', 
                    'product_price'=>$seasonal_storage_box_product->getProduct()->getCurrentPrice(), 
                    'product_supplier_code'=> $seasonal_storage_box_product->getProduct()->getProductSupplierCode(),
                    'product_barcode' => $seasonal_storage_box_product->getProduct()->getProductBarcode(),
                    'supplier_name' => $seasonal_storage_box_product->getProduct()->getDefaultProductSupplier(),
                    'product_qty'=> $seasonal_storage_box_product->getSeasonalStorageBoxProductQty(), 
                    'stocktake_product_id' => $seasonal_storage_box_product->getId(),
                    'success'=> true);


                return new JsonResponse(
                    $response 
                );


            
    }






    /**
    * Creates a form to create a new product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAddProductToSeasonalStorageBoxForm(Product $entity)
    {
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(new AddProductToSeasonalStorageBoxType($em), $entity, array(
            'action' => $this->generateUrl('staff-products_add-product-to-seasonal-storage-box-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

       
        $form->add('submit', 'submit', array('label' => 'Add', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }
    
     public function newseasonalstorageboxAction() 
    {
        //Get the business premises and season from session
        $session = new Session();
        $season = $session->get('season');
        $business_premises = $session->get('business_premises');

        $em = $this->getDoctrine()->getManager();

        $season = $em->merge($season);
        $business_premises = $em->merge($business_premises);
        //Get the next box number from the session 
        //Get count of boxes in DB where season and business premises match those in session
        $entities = $em->getRepository('MilesApartAdminBundle:SeasonalStorageBox')->findBy(array('business_premises'=> $business_premises, 'season'=>$season));

        $box_number = count($entities) + 1;

        //Generate the code 
        $new_box_code = $season->getSeasonCode() . "-" . $business_premises->getBusinessPremisesCode() . "-" . $box_number;

        $entity = new SeasonalStorageBox();

        
        $entity->setSeason($season);
        $entity->setBusinessPremises($business_premises);
        $entity->setSeasonalStorageBoxCode($new_box_code);

        $em->persist($entity);
        $em->flush();
        $em->clear();

        //Genertate the barcode image 

        $response = array(
            'new_box_code' => $new_box_code,   
        );

        return new JsonResponse(
            $response 
        );

    }

    public function viewseasonalboxcontentsAction()
    {

        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add product notifications to breadcrumb.
        $breadcrumbs->addItem("Pack up seasonal", $this->get("router")->generate("staff-products_pack-up-seasonal"));
        
        $entity = new SeasonalStorageBox();

        $form = $this->createViewSeasonalBoxForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:view_seasonal_box_contents.html.twig', array(
            'submitted'=> false,
            'entity' => $entity,
            'form'   => $form->createView(),
            ));
    }



    /**
    * Creates a form to create a PackUpSeasonal entity.
    *
    * @param PackUpSeasonal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createViewSeasonalBoxForm(SeasonalStorageBox $entity)
    {
        $form = $this->createForm(new ViewSeasonalStorageBoxType(), $entity, array(
            'action' => $this->generateUrl('staff-products_view-seasonal-box-contents-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Next', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-12')));

        return $form;
    }

    public function viewseasonalboxcontentssubmitAction(Request $request) 
    {
        //Get the $_POST array
        if ($request->isXMLHttpRequest()) {

            //Load the post array into response variable
            $response = $_POST;
            
        //If there is no post data
        } else {
            
            //Set response to false
            $response = "false";
        }

        $box_barcode = $response["box_code"];

        //Get the box 
        $em = $this->getDoctrine()->getManager();
        $seasonal_storage_box = $em->getRepository('MilesApartAdminBundle:SeasonalStorageBox')->findOneBy(array("seasonal_storage_box_code" => $box_barcode));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:view_seasonal_box_contents_submit.html.twig', array(
            'seasonal_storage_box'=> $seasonal_storage_box,

        ));

    }

    public function emptyseasonalstorageboxAction(Request $request)
    {
        //Get the $_POST array
        if ($request->isXMLHttpRequest()) {

            //Load the post array into response variable
            $response = $_POST;
            
        //If there is no post data
        } else {
            
            //Set response to false
            $response = "false";
        }

        $box_barcode = $response["box_code"];

        //Get the box 
        $em = $this->getDoctrine()->getManager();
        $seasonal_storage_box = $em->getRepository('MilesApartAdminBundle:SeasonalStorageBox')->findOneBy(array("seasonal_storage_box_code" => $box_barcode));

        //Code to delete all of the stocktake product that matches the box code.
        



        //Set up the array for AJAX response
        $response = array(     
                        'success' => true, 

                    );


        return new JsonResponse(
                    $response 
                );

    }

    public function addstocktakeseasonalstorageboxAction(Request $request) {
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        //Set up entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the barcode and search query from the request
        $box_barcode = $response["barcode"];

        //Get seasonal storage box
        $seasonal_storage_box = $em->getRepository('MilesApartAdminBundle:SeasonalStorageBox')->findOneBy(array("seasonal_storage_box_code" => $box_barcode));
        
        //Get the session
        $session = new Session();

        //Get stock location shelf from the session and merge
        $stock_location_shelf = $session->get('stock_location_shelf');
        $stock_location_shelf = $em->merge($stock_location_shelf);

        //Get stocktake from the session and merge
        $stocktake = $session->get('stocktake');
        $stocktake = $em->merge($stocktake);

        //Create new stocktake seasonal storage box
        $stocktake_seasonal_storage_box = new StocktakeSeasonalStorageBox();
        $stocktake_seasonal_storage_box->setStocktake($stocktake);
        $stocktake_seasonal_storage_box->setStockLocationShelf($stock_location_shelf);
        $stocktake_seasonal_storage_box->setSeasonalStorageBox($seasonal_storage_box);

        //Persist changes
        $em->persist($stocktake_seasonal_storage_box);
        $em->flush();

        //Set up the array to be returned 
        $jsonContentArray = array('products' => []);
        
        $response = array(     
                        'seasonal_storage_box_code' => $seasonal_storage_box->getSeasonalStorageBoxCode(), 
                        
                    );

        $response = new JsonResponse($response);
        return $response;
    
    
    }

    public function storedseasonproductsAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add product notifications to breadcrumb.
        $breadcrumbs->addItem("Pack up seasonal", $this->get("router")->generate("staff-products_pack-up-seasonal"));
        
        $entity = new SeasonalStorageBox();

        $form = $this->createSeasonSelectForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:stored_season_products.html.twig', array(
            'submitted'=> false,
            'entity' => $entity,
            'form'   => $form->createView(),
            ));
    }

    /**
    * Creates a form to create a PackUpSeasonal entity.
    *
    * @param PackUpSeasonal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createSeasonSelectForm(SeasonalStorageBox $entity)
    {
        $form = $this->createForm(new StoredSeasonType(), $entity, array(
            'action' => $this->generateUrl('staff-products_stored-season-products-display'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Display', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-12')));

        return $form;
    }

    public function storedseasonproductsdisplayAction(Request $request)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add product notifications to breadcrumb.
        $breadcrumbs->addItem("Pack up seasonal", $this->get("router")->generate("staff-products_pack-up-seasonal"));
        
        $entity = new SeasonalStorageBox();

        $form = $this->createSeasonSelectForm($entity);
        $form->handleRequest($request);

        $season_id = $form->get('season')->getData();

        //Get the box 
        $em = $this->getDoctrine()->getManager();
        $season = $em->getRepository('MilesApartAdminBundle:Season')->findOneById($season_id);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:stored_season_products_display.html.twig', array(
            'season'=> $season,

        ));

    }

    //Find product controllers
    public function findproductAction() 
    {

        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Find Products", $this->get("router")->generate("staff-products_find-product"));
        

        //Create the form to find products.
        $entity = new SearchProduct();
        $form = $this->createFindProductForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:find_product.html.twig', array(
            'submitted' => false,
            'form' => $form->createView(),
            ));
   
    }

    //Find product controllers
    public function findproductsubmitAction(Request $request) 
    {

        
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Find Products", $this->get("router")->generate("staff-products_find-product"));
        
        //Get the form so the search criteria can be accessed.
        $entity = new SearchProduct();
        $form = $this->createFindProductForm($entity);
        $form->handleRequest($request);

        $logger = $this->get('logger');
        $logger->info('yes1');

        //Check if form is valid
        if ($form->isValid()) {

            //Set up entity manager
            $em = $this->getDoctrine()->getManager();
            
        $logger->info('yes');
            //If barcode is entered, search products for product, inner and outer barcodes.
            if($form->get('product_barcode')->getData() != NULL && $form->get('product_barcode')->getData() != "") {

                $barcode = $form->get('product_barcode')->getData();
                $logger->info($barcode);
                //Call the db
                $products = $em->getRepository('MilesApartAdminBundle:Product')->findBy(array('product_barcode' => $barcode));
            

            //If product name has been entered, do a search on the name.
            } else if($form->get('product_name')->getData() != NULL && $form->get('product_name')->getData() != ""){
                //Call the db
                $products = $em->getRepository('MilesApartAdminBundle:Product')->findByLetters($form->get('product_name')->getData());


            //If the supplier AND product supplier code has been entered, match the two.
            } else if($form->get('product_supplier_code')->getData() != NULL && $form->get('product_supplier_code')->getData() != ""){
                //Call the db
                $products = $em->getRepository('MilesApartAdminBundle:Product')->findProductsFromSupplierCodeText($form->get('product_supplier_code')->getData());
            
            }

            //Set product count.
            $product_count = count($products);

            //IN each case RETURN multiple results and display in table (should include price, cost, stock and location, allowing product to be clicked to drill down onto the new edit page.
            

        } else {
            
            
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
            
            return $this->render('MilesApartStaffBundle:Products:find_product.html.twig', array(
        
                'submitted' => true,
               'form'   => $form->createView(),
            ));

        }
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:find_product_submit.html.twig', array(
            'form'   => $form->createView(),
            'submitted' => true,
            'products' => $products,
            'product_count' => $product_count,
            ));
   
    }


    /**
    * Creates a form to find a  product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createFindProductForm(SearchProduct $entity)
    {
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(new FindProductType($em), $entity, array(
            'action' => $this->generateUrl('staff-products_find-product-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

       
        $form->add('submit', 'submit', array('label' => 'Search', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     */
    public function editProductAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Edit Product", $this->get("router")->generate("staff-products_edit-product", array ('id'=> $id )));
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $editForm = $this->createEditProductForm($entity);
       

        return $this->render('MilesApartStaffBundle:Products:edit_product.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Product entity.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditProductForm(Product $entity)
    {
        $form = $this->createForm(new NewProductType(), $entity, array(
            'action' => $this->generateUrl('staff-products_edit-product-submit', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing Product entity.
     *
     */
    public function editProductSubmitAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Products", $this->get("router")->generate("staff-products_edit-product-submit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        
        $editForm = $this->createEditProductForm($entity);
        $editForm->handleRequest($request);

        $datetime = new \DateTime();

        if ($editForm->isValid()) {

            //Assign each submitted price of this product
            foreach($editForm->get('product_price')->getData() as $price) {
                $price->setProduct($entity);
                $price->setProductPriceValidFrom($datetime);
                $entity->removeProductPrice($price);
                $entity->addProductPrice($price);
                
            }

            /*//Assign each submitted cost to this product
            foreach($editForm->get('product_supplier')->getData() as $sup) {
                $sup->setProduct($entity);
                $sup->setDefaultSupplier(1);
                $entity->removeProductSupplier($sup);
                $entity->addProductSupplier($sup);
            }
*/
            //Assign each submitted cost to this product
            foreach($editForm->get('product_feature')->getData() as $feature) {
                $feature->setProduct($entity);
                $entity->removeProductFeature($feature);
                $entity->addProductFeature($feature);
                
            }

            //Assign each submitted cost to this product
            foreach($editForm->get('attribute_value')->getData() as $attribute) {
                $attribute->setProduct($entity);
                $entity->removeAttributeValue($attribute);
                $entity->addAttributeValue($attribute);
                
            }

            //Assign each submitted cost to this product
            foreach($editForm->get('keyword')->getData() as $keyword) {
                $keyword->setProduct($entity);
                $entity->removeKeyword($keyword);
                $entity->addKeyword($keyword);
                
            }

            //Assign each submitted cost to this product
            foreach($editForm->get('category')->getData() as $category) {
                $category->setProduct($entity);
                $entity->removeProductPrice($category);
                $entity->addProductPrice($category);
                
            }

            //Assign each submitted cost to this product
            foreach($editForm->get('season')->getData() as $season) {
                $season->setProduct($entity);
                $entity->removeProductPrice($season);
                $entity->addProductPrice($season);
                
            }


            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The product was updated successfully.');

            return $this->redirect($this->generateUrl('staff-products_edit-product', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Products:edit_product.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }


    /**
     * Displays a form to view product details.
     *
     */
    public function viewProductAction($id)
    {
        
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Find Products", $this->get("router")->generate("staff-products_find-product"));
        $breadcrumbs->addItem("View Product", $this->get("router")->generate("staff-products_view-product", array ('id'=> $id )));
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $form = $this->createViewProductForm($entity);

        $product_image = new ProductImage;

        //Create the image upload form
        //$image_form = $this->createImageUploadForm($product_image, $id);

       

        return $this->render('MilesApartStaffBundle:Products:view_product.html.twig', array(
            'submitted' => false,
            'product'      => $entity,
            'form'   => $form->createView(),
            //'image_form' => $image_form->createView(),
        ));
    }

    /**
     * Handles form for adding new product image and uploading file
     *
     */
    public function viewProductimageuploadAction($id, Request $request)
    {

        ini_set('memory_limit', '2024M');
        
        //Get the product
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('MilesApartAdminBundle:Product')->find($id);
        $files = $request->files;

        $directory = 'images/products';
        
         $logger = $this->get('logger');
        $logger->info('yes1');
    // $file will be an instance of Symfony\Component\HttpFoundation\File\UploadedFile
    foreach ($files as $uploadedFile) {
        
        $name = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION);

        $increment = ''; //start with no suffix

        $logger->info($extension);
        $logger->info($name);
        while(file_exists($directory."/".$name .$increment . '.' . $extension)) {
            $increment++;
        }

        
        $basename = $name .$increment . '.' . $extension;

        $basename = str_replace(' ', '_', $basename);

         $file = $uploadedFile->move($directory, $basename);
         $logger->info($file);
         $logger->info($directory);
         $logger->info($increment);
        //Set up the objcet
        $file_object = new ProductImage;

        $file_object->setProductImagePath($basename);
        $file_object->setProduct($product);
        $file_object->setProductImageIsMain(false);
        $file_object->setProductImageWebDisplay(false);
        
        $em->persist($file_object);
        $em->flush();

        
    }

    $response = True;
        //Return the response
        return new JsonResponse($response);
    }

 private function getErrorMessages(\Symfony\Component\Form\Form $form) {
    $errors = array();

    foreach ($form->getErrors() as $key => $error) {
        if ($form->isRoot()) {
            $errors['#'][] = $error->getMessage();
        } else {
            $errors[] = $error->getMessage();
        }
    }

    foreach ($form->all() as $child) {
        if (!$child->isValid()) {
            $errors[$child->getName()] = $this->getErrorMessages($child);
        }
    }

    return $errors;
}
    /**
    * Creates a form to create a Photo entity.
    *
    * @param Photo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    /*
    private function createImageUploadForm(ProductImage $entity, $id)
    {
        //Create the form from the builder type.
        $form = $this->createForm(new ProductImageUploadType($id), $entity, array(
            'action' => $this->generateUrl('staff-products_view-product_image-upload', array('id' => $id)),
            'method' => 'POST',
            'attr' => array('class' => 'dropzone')
        ));

       

        //Return form.
        return $form;
    }*/

     /**
    * Creates a form to create edit product entity.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createViewProductForm(Product $entity)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(new EditProductType($entityManager), $entity, array(
            'action' => $this->generateUrl('staff-products_view-product-submit', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal', 'novalidate' => 'novalidate')
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array(
                        'class' => 'btn btn-primary col-xs-12')));

        return $form;
    }

    /**
     * Edits an existing Product entity.
     *
     */
    public function viewProductSubmitAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Products", $this->get("router")->generate("staff-products_edit-product-submit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        
        $form = $this->createViewProductForm($entity);
        $form->handleRequest($request);

        $datetime = new \DateTime();

        if ($form->isValid()) {

            //Assign each submitted cost to this product
            foreach($form->get('product_price')->getData() as $price) {
                $price->setProduct($entity);
                //Checj if valid from date is set.
                if($price->getProductPriceValidFrom() != null) {

                } else {
                    $price->setProductPriceValidFrom($datetime);
                }
                
                $entity->removeProductPrice($price);
                $entity->addProductPrice($price);
                
            }

            //Assign each submitted cost to this product
            foreach($form->get('product_supplier')->getData() as $sup) {
                $sup->setProduct($entity);
                $entity->removeProductSupplier($sup);
                $entity->addProductSupplier($sup);

                //Create and assign the product cost to the product supplier
                
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


            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The product was updated successfully.');

            return $this->redirect($this->generateUrl('staff-products_view-product', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Products:view_product.html.twig', array(
            'submitted' => $submitted,
            'product'      => $entity,
            'form'   => $form->createView(),
        ));
    }
    /*******************************************************
    * Controller code for adding product to print list *****
    *******************************************************/

    //This shows the business preomises selection page
    public function addproducttoprintlistAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
       // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Print Prices Premises Select", $this->get("router")->generate("staff-products_add-product-to-print-list"));
        
        //Get business premises (retail) from db and send to the page.
        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();
        $business_premises = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->findBy(array('business_premises_type' => 1));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:add_product_to_print_list_business_premises_select.html.twig', array(
            'business_premises' => $business_premises,

            ));
   
    }

    //This shows the add products to print page
    public function addproducttoprintlistbusinesspremisesselectedAction($business_premises_slug) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Print Prices Premises Select", $this->get("router")->generate("staff-products_add-product-to-print-list"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Select Products", $this->get("router")->generate("staff-products_add-product-to-print-list-business-premises-selected", array ('business_premises_slug'=> $business_premises_slug )));

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();

        //Get the business premises
        $business_premises = new BusinessPremises(); 
        $business_premises = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->findOneBy(array('business_premises_slug'=>$business_premises_slug));

        //Set the business premsies in the session.
        $session = new Session();
        $session->set('price_request_business_premises', $business_premises);

        //Get the default price type.
        $print_request_type = $em->getRepository('MilesApartAdminBundle:PrintRequestType')->findOneBy(array('id'=>1));
        //Set the default price type in the session.
        $session->set('print_request_type', $print_request_type);

        //Create the form to add products.
        $entity = new Product();
        $form = $this->createPrintRequestProductForm($entity);

        //Check if products already exist in an incomplete(unprinted) state
        $entities = $em->getRepository('MilesApartAdminBundle:PrintRequest')->findBy(array('business_premises'=>$business_premises, 'print_request_printed' => null), array('print_request_date_created' => 'DESC'));

        $print_request_types = $em->getRepository('MilesApartAdminBundle:PrintRequestType')->findBy(array(), array('print_request_type_name' => 'ASC'));
        //If they do exists, populate a table with their details
        return $this->render('MilesApartStaffBundle:Products:add_product_to_print_list.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
            'entities'=>$entities,
            'business_premises' => $business_premises,
            'print_request_types' => $print_request_types
            
        ));
    }

    //This updates the session when the print request type is changed.
    public function updateprintrequesttypeAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $print_request_type_name = $response["printRequestId"];

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();

        //Get the business premises
        $print_request_type = $em->getRepository('MilesApartAdminBundle:PrintRequestType')->findOneBy(array('print_request_type_name'=>$print_request_type_name));

        //Set the business premsies in the session.
        $session = new Session();
        $session->set('print_request_type', $print_request_type);

        return new JsonResponse(
                    $response 
                );
    }

    /**
    * Creates a form to find a new product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPrintRequestProductForm(Product $entity)
    {
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(new AddProductToListType($em), $entity, array(
            'action' => $this->generateUrl('staff-products_add-product-to-print-list-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal add_product_to_list_form')
        ));

       
        $form->add('submit', 'submit', array('label' => 'Add', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }

    public function addproducttoprintlistsubmitAction(Request $request) 
    {
        //Set the function variable.
        $function_variable = "PrintRequest";
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

    public function addProductToPrintList($product_id) 
    {
        $em = $this->getDoctrine()->getManager();
        //Get product
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($product_id);

        //New print request
        $print_request = new PrintRequest();

        //Set the product
        $print_request->setProduct($product);
        
        //Set default qty to 1
        $print_request->setPrintRequestQty(1);

        //Persist row to the database.
        $em->persist($print_request);
        $em->flush();

        //Set up the array for AJAX response
        $response = array(     
                        'product_name' => $product->getProductName(), 
                        'product_price'=> $product->getCurrentPrice(),
                        'product_supplier_code'=> $product->getProductSupplierCode(),
                        'product_barcode' => $product->getProductBarcode(),
                        'supplier_name' => $product->getDefaultProductSupplier(),            
                        'product_id' => $product->getId(),
                        'product_qty' => $print_request->getProductTransferRequestQty(),
                        'product_id' => $print_request->getId(),
                    );
        return $response;
    }

   
    public function addproducttoprintlistmultipleproductsselectsubmitAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('I just got the logger tr 0');
        //Set the function variable
        $function_variable = "PrintRequest";

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


     public function addproducttoprintlistnewqtyAction(Request $request) 
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
        $print_request_id = $response["product_id"];

        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $print_request = $em->getRepository('MilesApartAdminBundle:PrintRequest')->findOneBy(array('id' => $print_request_id));

        //Set the product
        $print_request->setProductTransferRequestQty($new_qty);

        //Persist row to the database.
        $em->persist($print_request);
        $em->flush();

        $response = array("success" => true);
        return new JsonResponse(
                    $response 
                );
    }

     public function addproducttoprintlistnewproductAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        //Forward response to Helper function that adds product to the database, returning nw product object.
        /*$product = $this->forward('MilesApartStaffBundle:Helper:addNewProductToDatabaseFromAddList', array(
            'response'  => $response,
        ));

        $product = addNewProductToDatabaseFromAddList($response);
        */
        $add_product_service = $this->get('staff.add_product_from_add_to_list');

        $product = $add_product_service->addProductToDatabase($response);
        
        $logger = $this->get('logger');
       
        $logger->info('I just got the logger add update price');
        

        //Then create new product transfer request, adding the quantity
        $print_request = new PrintRequest();
$logger->info('I just got the logger add update price2');
        $print_request->setProduct($product);
        $logger->info('I just got the logger add update price3');
        //$print_request->setPrintRequestType($transfer_request);
        $print_request->setPrintRequestQty(1);
$logger->info('I just got the logger add update price4');
        $print_request_type = $em->merge($session->get('print_request_type'));
$logger->info('I just got the logger add update price5');
        $print_request->setPrintRequestType($print_request_type);
$logger->info('I just got the logger add update price6');
        $business_premises = $em->merge($session->get('price_request_business_premises'));
        $logger->info('I just got the logger add update price7');
        $print_request->setBusinessPremises($business_premises);
        $logger->info('I just got the logger add update price8');
        $em->persist($print_request);
        $logger->info('I just got the logger add update price9');
        //Persist the database

        
        $em->flush();

        //Then return success.

        $response = array(
                    'product_name' => $print_request->getProduct()->getProductName(), 
                    'product_stock_qty'=>'NA', 
                    'product_price'=>$print_request->getProduct()->getCurrentPrice(), 
                    'product_supplier_code'=> $print_request->getProduct()->getProductSupplierCode(),
                    'product_barcode' => $print_request->getProduct()->getProductBarcode(),
                    'supplier_name' => $print_request->getProduct()->getDefaultProductSupplier(),
                    'product_qty'=> $print_request->getPrintRequestQty(), 
                    'product_id' => $print_request->getId(),
                    'success'=> true);


                return new JsonResponse(
                    $response 
                );


            
    }

    public function getoutstandingmediumpricelabelAction(Request $request) 
    {

        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $print_requests = $em->getRepository('MilesApartAdminBundle:PrintRequest')->findBy(array('print_request_type' => 7, 'print_request_printed' => NULL));

        //Create array of products
        $jsonContentArray = array('products' => []);

        foreach ($print_requests as $key => $value) {
            $jsonContent = array(
                'product_name' => $value->getProduct()->getProductName(), 
                'product_price'=> $value->getProduct()->getCurrentPrice(),
                'product_supplier_code'=> $value->getProduct()->getProductSupplierCode(),
                'product_barcode' => substr($value->getProduct()->getProductBarcode(), 0, 12),
                'supplier_name' => $value->getProduct()->getDefaultProductSupplier()->getSupplier()->getSupplierShortName(),            
                'product_id' => $value->getProduct()->getId(),
            );

            array_push($jsonContentArray['products'], $jsonContent);
        }
        
       

        $response = new JsonResponse($jsonContentArray);
        return $response;
    }

    public function getoutstandinghooklabelAction(Request $request) 
    {

        $em = $this->getDoctrine()->getManager();
        //Get transfer request by id
        $print_requests = $em->getRepository('MilesApartAdminBundle:PrintRequest')->findBy(array('print_request_type' => 26, 'print_request_printed' => NULL));

        
        //Create array of products
        $jsonContentArray = array('products' => []);

        foreach ($print_requests as $key => $value) {
            //Split the product name to two lines
            $name_line_1 = substr($value->getProduct()->getProductName(), 0, 18);
            $name_line_2 = substr($value->getProduct()->getProductName(), 18, 36);
            $name = $name_line_1 . "\n" . $name_line_2;


            $options = array(
                'code'   => substr($value->getProduct()->getProductBarcode(), 0, 12),
                'type'   => 'ean13',
                'format' => 'png',
                'width'  => 2,
                'height' => 30,
                'color'  => array(0, 0, 0),
            );

            $barcode =
                $this->get('sgk_barcode.generator')->generate($options);


            //Convert barcode to 64baseimage
            
            
           
            $jsonContent = array(
                'product_name' => $name, 
                'product_price'=> $value->getProduct()->getCurrentPriceDisplay(),
                'product_supplier_code'=> $value->getProduct()->getProductSupplierCode(),
                'product_barcode' => $barcode,
                'supplier_name' => $value->getProduct()->getDefaultProductSupplier()->getSupplier()->getSupplierShortName(),            
                'product_id' => $value->getProduct()->getId(),
            );

            array_push($jsonContentArray['products'], $jsonContent);

            //Set printed to true.
            $value->setPrintRequestPrinted(1);
            $em->persist($value);


        }
        
        $em->flush();
       

        $response = new JsonResponse($jsonContentArray);
        return $response;
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

    public function newproductimageAction(Request $request)
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }
        
        
        //Set up the new product image
        $entity = new ProductImage();

        //Set the default values for name. (Ensuring it is not a dupe)


        $form = $this->createNewProductImageForm($entity);
        $entity->setProductImageTitle("Test");
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

        } else {
            $error = true;
        }
              
        if ($response == "false") {
            return new JsonResponse("False");
        } else {
            return new JsonResponse("True");
        }
    }


    private function createNewProductImageForm(ProductImage $entity)
    {
        $form = $this->createForm(new ProductImageType(), $entity, array(
            'action' => $this->generateUrl('staff-products_new-product-image'),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    //Code to handle processing of returns
    public function processreturnsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Process returns", $this->get("router")->generate("staff-products_process-returns"));
        
        //Create the form to add products.
        $entity = new Product();
        $form = $this->createAddProductToReturnsForm($entity);


        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:process_returns.html.twig', array(
            'form'   => $form->createView(),
            'submitted' => false,
            ));
   
    }

    /**
    * Creates a form to create a add product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAddProductToReturnsForm(Product $entity)
    {
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(new AddProductToReturnsType($em), $entity, array(
            'action' => $this->generateUrl('staff-products_process-returns-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

       
        $form->add('submit', 'submit', array('label' => 'Add', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }

    //Code to handle processing of returns
    public function newproductgroupAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New product group", $this->get("router")->generate("staff-products_new-product-group"));
        
        //Create the form to add products.
        $entity = new ProductGroup();
        $form = $this->createNewProductGroupForm($entity);
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Products:new_product_group.html.twig', array(
            'form'   => $form->createView(),
            'submitted' => false,
            ));
   
    }

    /**
    * Creates a form to create a add product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createNewProductGroupForm(ProductGroup $entity)
    {
        
        
        $form = $this->createForm(new NewProductGroupType(), $entity, array(
            'action' => $this->generateUrl('staff-products_new-product-group-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

       
        $form->add('submit', 'submit', array('label' => 'Add', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12')));

        return $form;
    }

    //Code to handle processing of returns
    public function newproductgroupsubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New product group", $this->get("router")->generate("staff-products_new-product-group"));
        
        //Create the form to add products.
        $entity = new ProductGroup();
        $form = $this->createNewProductGroupForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Your form was submitted successfully. Thank you!');
          
            return $this->redirect($this->generateUrl('staff-products_new-product-group'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Products:new_product_group.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'submitted' => $submitted,
        ));
   
    }

    public function viewproductgroupsAction($page=null)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("View Product Groups", $this->get("router")->generate("staff-products_view-product-groups"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:ProductGroup')->findAll();

        //Set up pagerfanta
        $adapter = new ArrayAdapter($entities);
        
        //Pass adapter to pagerfanta
        $pager =  new Pagerfanta($adapter);
        //Set the number of results
        $pager->setMaxPerPage(300);

        //Set current page if not set
        if (!$page)    
            $page = 1;
            try  {
                $pager->setCurrentPage($page);
            }
            catch(NotValidCurrentPageException $e) {
              throw new NotFoundHttpException('Illegal page');
            }

        return $this->render('MilesApartStaffBundle:Products:view_product_groups.html.twig', array(
            'pager' => $pager,
        ));
    }

    public function addproductgrouptoprintrequestAction(Request $request) 
    {

        $session = new Session;
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $em = $this->getDoctrine()->getManager();

        $product_group_id = $response["productGroupId"];
        $print_request_type_id = $response["printRequestTypeId"];
        
        //Get the product group
        $product_group = $em->getRepository('MilesApartAdminBundle:ProductGroup')->findOneById($product_group_id);
        //Get the pprint request type
        $print_request_type = $em->getRepository('MilesApartAdminBundle:PrintRequestType')->findOneById($print_request_type_id);

        //Create the print request
        $print_request = new PrintRequest();

        $print_request->setProductGroup($product_group);
        //$print_request->setPrintRequestType($transfer_request);
        $print_request->setPrintRequestQty(1);

        $print_request->setPrintRequestType($print_request_type);

        //If business premises is set in session, set it here. Otherwise defult to westbury
        if($session->has('price_request_business_premises')) {
            $business_premises = $em->merge($session->get('price_request_business_premises'));
        } else {
            $business_premises = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->findOneById(5);
        }
        
        $print_request->setBusinessPremises($business_premises);
        $em->persist($print_request);
        //Persist the database

        
        $em->flush();

        //Then return success.
        $response = array('success'=> true);

        return new JsonResponse(
            $response 
        );
            
    }

    //Get the answer product question form 
    public function answerproductquestionAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('I just got the logger tr 1');
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

 $logger->info('I just got the logger tr 1');
        //Set the new price and the product id
        $question_id = $response["question_id"];
$logger->info('I just got the logger tr 1');
        //Get the em 
        $em = $this->getDoctrine()->getManager();
        $logger->info('I just got the logger tr 2');
        //Get the product 
        $em = $this->getDoctrine()->getManager();
        $product_question = $em->getRepository('MilesApartAdminBundle:ProductQuestion')->findOneById($question_id);
$logger->info('I just got the logger tr 23');
        //Create the form 

        $product_answer = new ProductAnswer();
        $logger->info('I just got the logger tr 23');
        $product_answer->setProductQuestion($product_question);
        $logger->info('I just got the logger tr 23');
        $form = $this->createAnswerProductQuestionForm($product_answer);
$logger->info('I just got the logger tr 3');
        //Return the modal html
        $html = $this->renderView('MilesApartStaffBundle:Products:answer_question_modal.html.twig', array(
            'form'   => $form->createView(),
            'product_question' => $product_question,
        ));

        $response = array("html" => $html, "success" => true);

        return new JsonResponse(
            $response 
        );
    }

    /**
    * Creates a form to create a add product answer.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createAnswerProductQuestionForm(ProductAnswer $entity)
    {
        
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new AnswerProductQuestionType($em), $entity, array(
            'action' => $this->generateUrl('staff-products_answer-question-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

       
        $form->add('submit', 'submit', array('label' => 'Answer', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4 col-xs-12', 'onclick' => 'submitAnswerQuestionModal();')));

        return $form;
    }

    //Code to handle the sumbission of the prodct answer
    public function answerproductquestionsubmitAction(Request $request) 
    {
        $logger = $this->get('logger');
        $logger->info('I just got the logger tr 1');
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

 $logger->info('I just got the logger tr 1');
        //Set the new price and the product id
        $question_id = $response["product_question_id"];
        $answer_text = $response["product_answer_text"];

$logger->info('I just got the logger tr 1');
        //Get the em 
        $em = $this->getDoctrine()->getManager();
        $logger->info('I just got the logger tr 2');
        //Get the product 
        $em = $this->getDoctrine()->getManager();


        //Create the form to add products.
        $product_answer = new ProductAnswer();
        $product_answer->setProductQuestion($em->getRepository('MilesApartAdminBundle:ProductQuestion')->findOneById($question_id));
        $product_answer->setProductAnswerText($answer_text);
        $product_answer->setAdminUser($this->get('security.context')->getToken()->getUser());

        $em->persist($product_answer);
        $em->flush();

        //Send the notification email
        $this->sendProductAnswerNotificationEmail($product_answer);

        $response = array("success" => true);

        return new JsonResponse(
            $response 
        );
   
    }

    function sendProductAnswerNotificationEmail($product_answer)
    {
        //Set up the mailer
        $mailer = $this->container->get('swiftmailer.mailer.weborders_mailer');

       //Get entity manager
        $em = $this->getDoctrine()->getManager();
        $product_answer = $em->merge($product_answer);

        $email_address = $product_answer->getProductQuestion()->getCustomer()->getCustomerEmailAddress();
        
        //Get the supplier email address.
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Miles Apart Product Answer Notification')
            ->setFrom(array('customersupport@miles-apart.com' => 'Miles Apart'))
            ->setTo($email_address)
            ->setBody(
                $this->renderView(
                    'MilesApartPublicBundle:Emails:product_answer_notification.html.twig',
                    array('product_answer' => $product_answer)
                    
                )

            )
        ;

        //Avoid localhost issues
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        //Send the email
        $mailer->send($message);       

        return true;
    }


    //Get the answer product question form 
    public function approvereviewAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('I just got the logger tr 1');
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

 $logger->info('I just got the logger tr 1');
        //Set the new price and the product id
        $review_id = $response["review_id"];
$logger->info('I just got the logger tr 1');
        //Get the em 
        $em = $this->getDoctrine()->getManager();
        $logger->info('I just got the logger tr 2');
        //Get the product 
        $em = $this->getDoctrine()->getManager();
        $product_review = $em->getRepository('MilesApartAdminBundle:ProductReview')->findOneById($review_id);
$logger->info('I just got the logger tr 23');
       
        //Return the modal html
        $html = $this->renderView('MilesApartStaffBundle:Products:approve_review_modal.html.twig', array(
           'product_review' => $product_review
        ));

        $response = array("html" => $html, "success" => true);

        return new JsonResponse(
            $response 
        );
    }

    //Code to handle the sumbission of the prodct answer
    public function approvereviewsubmitAction(Request $request) 
    {
        $logger = $this->get('logger');
        $logger->info('I just got the logger tr 1');
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

 $logger->info('I just got the logger tr 1');
        //Set the new price and the product id
        $review_id = $response["review_id"];

$logger->info('I just got the logger tr 1');
        //Get the em 
        $em = $this->getDoctrine()->getManager();
        $logger->info('I just got the logger tr 2');
        //Get the product 
        $em = $this->getDoctrine()->getManager();

        $product_review = $em->getRepository('MilesApartAdminBundle:ProductReview')->findOneById($review_id);
        $product_review->setProductReviewApproved(true);
        $product_review->setAdminUser($this->get('security.context')->getToken()->getUser());

        $em->flush();

        $response = array("success" => true);

        return new JsonResponse(
            $response 
        );
   
    }




}

















