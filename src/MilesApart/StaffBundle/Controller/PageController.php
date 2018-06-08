<?php
// src/MilesApart/StaffBundle/Controller/PageController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

use MilesApart\StaffBundle\Form\Products\PriceCheckType;
use MilesApart\StaffBundle\Form\Helpers\RecordReturnType;
use MilesApart\StaffBundle\Form\Helpers\RecordSaleType;
use MilesApart\AdminBundle\Entity\Product;
use MilesApart\AdminBundle\Entity\ShopSoldProduct;
use MilesApart\AdminBundle\Entity\ShopReturnedProduct;

class PageController extends Controller
{
    /*************************************************
    * Page controller displays the overview pages (top level) of each site area.
    *************************************************/

    //Staff homepage 
    public function indexAction($page=null)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));

        //Get unanswered questions 
        $em = $this->getDoctrine()->getManager();

        $unanswered_questions = $em->getRepository('MilesApartAdminBundle:ProductQuestion')->findUnansweredQuestions();

        $unapproved_reviews = $em->getRepository('MilesApartAdminBundle:ProductReview')->findUnapprovedReviews();

        //Get outstanding orders
        $outstanding_orders = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->getUnshippedCustomerOrders();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Page:index.html.twig', array(
            'unanswered_questions' => $unanswered_questions,
            'unapproved_reviews' => $unapproved_reviews,
            'outstanding_orders' => $outstanding_orders,
        ));
    }

    //Price check homepage 
    public function pricecheckAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add price check to breadcrumb.
        $breadcrumbs->addItem("Price Check", $this->get("router")->generate("miles_apart_staff_price_check"));


        $entity = new Product();
        $form = $this->createPriceCheckForm($entity);


        //Render the page from template
        return $this->render('MilesApartStaffBundle:Page:price_check.html.twig', array(
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
    private function createPriceCheckForm(Product $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PriceCheckType(), $entity, array(
            'action' => $this->generateUrl('miles_apart_staff_price_check_submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Check price', 'attr' => array(
                        'class' => 'btn btn-primary  col-md-offset-3 col-md-5 col-xs-12')));

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
        $breadcrumbs->addItem("Price Check", $this->get("router")->generate("miles_apart_staff_price_check"));
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

                    $this->get('session')->getFlashBag()->set('admin-error', 'The barcode does not exist in the database.');
                    
                    return $this->render('MilesApartStaffBundle:Page:price_check.html.twig', array(
                        'submitted' => false,
                        'form'   => $form->createView(),
                    ));
                } 
                
            }
        }

        
        $entity2 = new Product();
        $form2 = $this->createPriceCheckForm($entity2);

      

        return $this->render('MilesApartStaffBundle:Page:show_price.html.twig', array(
            'entity'      => $entity,
            'submitted' => true,
             'form'   => $form2->createView(),
             
                   ));
    }

    //Record sale homepage 
    public function recordsaleAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add record sale to breadcrumb.
        $breadcrumbs->addItem("Record Sale", $this->get("router")->generate("miles_apart_staff_record_sale"));


        $entity = new Product();
        $form = $this->createRecordSaleForm($entity);


        //Render the page from template
        return $this->render('MilesApartStaffBundle:Page:record_sale.html.twig', array(
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
    private function createRecordSaleForm(Product $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RecordSaleType(), $entity, array(
            'action' => $this->generateUrl('miles_apart_staff_record_sale_submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Record Sale', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-3 col-md-5 col-xs-12')));

        return $form;
    }

   
    /**
     * Finds and displays a Product entity.
     *
     */
    public function recordsalesubmitAction(Request $request)
    {
       
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("Record Sale", $this->get("router")->generate("miles_apart_staff_price_check"));
        
        
        $entity = new Product();
        $form = $this->createRecordSaleForm($entity);

           
        $form->submit($request->request->get($form->getName()));

        $barcode = $form["product_barcode"]->getData();


        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_barcode'=> $barcode));

        if (!$entity) {

            //Check the inner barcode.
            $entity = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_inner_barcode'=> $barcode));

            if (!$entity) {

                //Check the outer barcode.
                $entity = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_outer_barcode'=> $barcode));

                if (!$entity) {

                    $this->get('session')->getFlashBag()->set('admin-error', 'The barcode does not exist in the database, please write down the product name.');
                    
                    return $this->render('MilesApartStaffBundle:Page:record_sale.html.twig', array(
                        'submitted' => false,
                        'form'   => $form->createView(),
                    ));
                } 
                
            }
        }

        //Add the product to the sold product entity
        $sold_product = new ShopSoldProduct();
        $sold_product->setProduct($entity);
        $em->persist($sold_product);
        $em->flush();  
        
        //Update other channels
        //Amazon
        //Send the array to Amazon Upload script
        $amazon_response = $this->forward('MilesApartSellerBundle:Amazon:uploadAmazonProductNewQty', array(
            'product'  => $entity,
            
        ));
      

        $entity2 = new Product();
        $form2 = $this->createRecordSaleForm($entity2);

        $this->get('session')->getFlashBag()->set('admin-notice', 'The product "' . $entity->getProductName() . '" has been recorded as sold.');
                    

        return $this->render('MilesApartStaffBundle:Page:record_sale.html.twig', array(
            'entity'      => $entity,
            'submitted' => true,
             'form'   => $form2->createView(),
             
                   ));
    }




    public function recordreturnAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add record sale to breadcrumb.
        $breadcrumbs->addItem("Record Return", $this->get("router")->generate("miles_apart_staff_record_return"));


        $entity = new Product();
        $form = $this->createRecordReturnForm($entity);


        //Render the page from template
        return $this->render('MilesApartStaffBundle:Page:record_return.html.twig', array(
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
    private function createRecordReturnForm(Product $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RecordReturnType(), $entity, array(
            'action' => $this->generateUrl('miles_apart_staff_record_return_submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Record Return', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-3 col-md-5 col-xs-12')));

        return $form;
    }

   
    /**
     * Finds and displays a Product entity.
     *
     */
    public function recordreturnsubmitAction(Request $request)
    {
       
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("Record Return", $this->get("router")->generate("miles_apart_staff_record_return"));
        
        
        
        $entity = new Product();
        $form = $this->createRecordReturnForm($entity);

      
           
        $form->submit($request->request->get($form->getName()));

        $barcode = $form["product_barcode"]->getData();


        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_barcode'=> $barcode));

        if (!$entity) {

            //Check the inner barcode.
            $entity = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_inner_barcode'=> $barcode));

            if (!$entity) {

                //Check the outer barcode.
                $entity = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_outer_barcode'=> $barcode));

                if (!$entity) {

                    $this->get('session')->getFlashBag()->set('admin-error', 'The barcode does not exist in the database, there is no need to record the return.');
                    
                    return $this->render('MilesApartStaffBundle:Page:record_return.html.twig', array(
                        'submitted' => false,
                        'form'   => $form->createView(),
                    ));
                } 
                
            }
        }

        //Add the product to the sold product entity
        $returned_product = new ShopReturnedProduct();
        $returned_product->setProduct($entity);
        $em->persist($returned_product);
        $em->flush();  
        
        //Update other channels
        //Amazon
        //Send the array to Amazon Upload script
        $amazon_response = $this->forward('MilesApartSellerBundle:Amazon:uploadAmazonProductNewQty', array(
            'product'  => $entity,
            
        ));

      

        $entity2 = new Product();
        $form2 = $this->createRecordSaleForm($entity2);

        $this->get('session')->getFlashBag()->set('admin-notice', 'The product "' . $entity->getProductName() . '" has been recorded as returned.');
                    

        return $this->render('MilesApartStaffBundle:Page:record_return.html.twig', array(
            'entity'      => $entity,
            'submitted' => true,
             'form'   => $form2->createView(),
             
                   ));
    }
    
}
