<?php
// src/MilesApart/AdminBundle/Controller/PageController.php

namespace MilesApart\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MilesApart\AdminBundle\Entity\Supplier;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use MilesApart\AdminBundle\Entity\SupplierRepresentative;

class PageController extends Controller
{
    /*************************************************
    * Page controller displays the overview pages (top level) of each site area.
    *************************************************/

    //Admin homepage 
    public function indexAction($page=null)
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:index.html.twig');
    }

    //Supplier homepage
    public function supplierAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Supplier", $this->get("router")->generate("miles_apart_admin_supplier"));

        
        ////Render the page from template sending the suppliers and representative object arrays
        return $this->render('MilesApartAdminBundle:Page:supplier.html.twig');
       
    }

    
    //Pick pack overview
    public function customerorderAction()
    {   
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Customer Order", $this->get("router")->generate("miles_apart_admin_customerorder"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:customerorder.html.twig');
    }

    //Products overview
    public function productAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Product", $this->get("router")->generate("miles_apart_admin_product"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:product.html.twig');
    }

    //Products overview
    public function taxonomyAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Category", $this->get("router")->generate("miles_apart_admin_category"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:taxonomy.html.twig');
    }

    //Customers overview
    public function customerAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Customer", $this->get("router")->generate("miles_apart_admin_customer"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:customer.html.twig');
    }

    //Customers overview
    public function transferrequestAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Transfer Request", $this->get("router")->generate("miles_apart_admin_transferrequest"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:transferrequest.html.twig');
    }

    //Customers overview
    public function campaignAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Campaign", $this->get("router")->generate("miles_apart_admin_campaign"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:campaign.html.twig');
    }

    //Finances overview
    public function financeAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Finance", $this->get("router")->generate("miles_apart_admin_finance"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:finance.html.twig');
    }

    //Business overview
    public function businessAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Business", $this->get("router")->generate("miles_apart_admin_business"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:business.html.twig');
    }

    //Goods in overview
    public function goodsinAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Goods In", $this->get("router")->generate("miles_apart_admin_goodsin"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:goodsin.html.twig');
    }

    //HR overview
    public function hrAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("HR", $this->get("router")->generate("miles_apart_admin_hr"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:hr.html.twig');
    }

    //Procurement overview
    public function procurementAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Procurement", $this->get("router")->generate("miles_apart_admin_procurement"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:procurement.html.twig');
    }

    //We management overview
    public function webmanagementAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        //Add home to breadcrumb
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        //Add current page to breadcrumb
        $breadcrumbs->addItem("Web Management", $this->get("router")->generate("miles_apart_admin_webmanagement"));

        //Render the page from template
        return $this->render('MilesApartAdminBundle:Page:webmanagement.html.twig');
    }

    
}
?>