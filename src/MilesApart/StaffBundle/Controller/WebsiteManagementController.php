<?php
// src/MilesApart/StaffBundle/Controller/WebsiteManagementController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class WebsiteManagementController extends Controller
{
    /*************************************************
    * Website management controller displays the functions and pages in website management menu area.
    *************************************************/

    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Website Management Notifications", $this->get("router")->generate("staff-website-management_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:WebsiteManagement:notifications.html.twig');
   
    }

    public function editpageAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Edit Page", $this->get("router")->generate("staff-website-management_edit-page"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:WebsiteManagement:edit_page.html.twig');
   
    }

    public function emptycacheAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Empty Cache", $this->get("router")->generate("staff-website-management_empty-cache"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:WebsiteManagement:empty_cache.html.twig');
   
    }

}
