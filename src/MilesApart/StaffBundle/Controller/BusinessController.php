<?php
// src/MilesApart/StaffBundle/Controller/BusinessController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\BusinessPremises;
use MilesApart\AdminBundle\Entity\StockLocation;
use MilesApart\AdminBundle\Entity\StockLocationShelf;
use MilesApart\StaffBundle\Entity\Business\StockLocationShelfEntry;
use MilesApart\AdminBundle\Form\BusinessPremisesType;
use MilesApart\AdminBundle\Form\StockLocationType;
use MilesApart\StaffBundle\Form\Business\StockLocationShelfEntryType;



class BusinessController extends Controller
{
    /*************************************************
    * Business controller displays the functions and pages in business menu ar
    *************************************************/
    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Business Notifications", $this->get("router")->generate("staff-business_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Business:notifications.html.twig');
   
    }

    public function newbusinesspremisesAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Business Premises", $this->get("router")->generate("staff-business_new-business-premises"));
        
        $entity = new BusinessPremises();

        $form = $this->createCreateBusinessPremisesForm($entity);

        return $this->render('MilesApartStaffBundle:Business:new_business_premises.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));

    }    

    public function newbusinesspremisessubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Business Premises", $this->get("router")->generate("staff-business_new-business-premises"));
        
        $entity = new BusinessPremises();
        $form = $this->createCreateBusinessPremisesForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New business premises has been created successfully.');

            return $this->redirect($this->generateUrl('staff-business_new-business-premises'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Business:new_business_premises.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }



    /**
    * Creates a form to create a new product.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateBusinessPremisesForm(BusinessPremises $entity)
    {
        $form = $this->createForm(new BusinessPremisesType(), $entity, array(
            'action' => $this->generateUrl('staff-business_new-business-premises-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }


    public function editbusinesspremisesAction($id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        $breadcrumbs->addItem("View Premises", $this->get("router")->generate("staff-business_view-business-premises"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Edit Premises", $this->get("router")->generate("staff-business_edit-business-premises", array ('id'=> $id )));
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $edit_form = $this->createEditBusinessPremisesForm($entity);

        return $this->render('MilesApartStaffBundle:Business:edit_business_premises.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'edit_form'   => $edit_form->createView(),
        ));

    } 

    /**
    * Creates a form to edit a BusinessPremises entity.
    *
    * @param BusinessPremises $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditBusinessPremisesForm(BusinessPremises $entity)
    {
        $form = $this->createForm(new BusinessPremisesType(), $entity, array(
            'action' => $this->generateUrl('staff-business_edit-business-premises-submit', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing Business Premises entity.
     *
     */
    public function editBusinessPremisesSubmitAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Business", $this->get("router")->generate("staff-business_edit-business-premises-submit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BusinessPremises entity.');
        }

        
        $editForm = $this->createEditBusinessPremisesForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The business premises was updated successfully.');

            return $this->redirect($this->generateUrl('staff-business_edit-business-premises', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Business:edit_business_premises.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }



    public function viewbusinesspremisesAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Premises", $this->get("router")->generate("staff-business_view-business-premises"));

        //Get the products 
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->findAll();

        //Get the total number of suppliers
        $business_premises_count = count($entities);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Business:view_business_premises.html.twig', array(
            'entities' => $entities,
            'business_premises_count' => $business_premises_count,
            
            ));



    }


    public function newstocklocationAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Stock Location", $this->get("router")->generate("staff-business_new-stock-location"));
        
        $entity = new StockLocation();

        $form = $this->createCreateStockLocationForm($entity);

        return $this->render('MilesApartStaffBundle:Business:new_stock_location.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));

    }    

    public function newstocklocationsubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Stock Location", $this->get("router")->generate("staff-business_new-stock-location"));
        
        $entity = new StockLocation();
        $form = $this->createCreateStockLocationForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New stock location has been created successfully.');

            return $this->redirect($this->generateUrl('staff-business_new-stock-location'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Business:new_stock_location.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }



    /**
    * Creates a form to create a new stokc location.
    *
    * @param StockLocation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateStockLocationForm(StockLocation $entity)
    {
        $form = $this->createForm(new StockLocationType(), $entity, array(
            'action' => $this->generateUrl('staff-business_new-stock-location-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }


    public function editstocklocationAction($id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Edit Stock Location", $this->get("router")->generate("staff-business_edit-stock-location", array ('id'=> $id )));
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:StockLocation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find stock location entity.');
        }

        $edit_form = $this->createEditStockLocationForm($entity);

        return $this->render('MilesApartStaffBundle:Business:edit_stock_location.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'edit_form'   => $edit_form->createView(),
        ));

    } 

    /**
    * Creates a form to edit a StockLocation entity.
    *
    * @param StockLocation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditStockLocationForm(StockLocation $entity)
    {
        $form = $this->createForm(new StockLocationType(), $entity, array(
            'action' => $this->generateUrl('staff-business_edit-stock-location-submit', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing Business Premises entity.
     *
     */
    public function editStockLocationSubmitAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Edit Stock Location", $this->get("router")->generate("staff-business_edit-stock-location-submit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:StockLocation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StockLocation entity.');
        }

        
        $editForm = $this->createEditStockLocationForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The stock location was updated successfully.');

            return $this->redirect($this->generateUrl('staff-business_edit-stock-location', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Business:edit_stock_location.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }



    public function viewstocklocationAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Stock Location", $this->get("router")->generate("staff-business_view-stock-locations"));

        //Get the products 
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:StockLocation')->findAll();

        //Get the total number of suppliers
        $stock_locations_count = count($entities);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Business:view_stock_locations.html.twig', array(
            'entities' => $entities,
            'stock_locations_count' => $stock_locations_count,
            
            ));



    }



    public function newstocklocationshelfAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Stock Location Shelf", $this->get("router")->generate("staff-business_new-stock-location-shelf"));
        
        $entity = new StockLocationShelfEntry();

        $form = $this->createCreateStockLocationShelfForm($entity);

        return $this->render('MilesApartStaffBundle:Business:new_stock_location_shelf.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
            'product_location_shelves'=> null,
            'total_number_of_shelves' => null,
        ));

    }    

    public function newstocklocationshelfsubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Stock Location Shelf", $this->get("router")->generate("staff-business_new-stock-location-shelf"));
        
        $entity = new StockLocationShelfEntry();
        $form = $this->createCreateStockLocationShelfForm($entity);
        $form->handleRequest($request);
        $product_location_shelves = array();
        $total_number_of_shelves = null;
        if ($form->isValid()) {

            //Now the logic has to compute which shelves need to be added.

            //Get x and y values from the form
            $x_start = $form["stock_location_shelf_x_start"]->getData();
            $x_end = $form["stock_location_shelf_x_end"]->getData();
            $y_start = $form["stock_location_shelf_y_start"]->getData();
            $y_end = $form["stock_location_shelf_y_end"]->getData();
            $wall_code = $form["stock_location_shelf_wall"]->getData();
            $stock_location = $form["stock_location"]->getData();
            
            $em = $this->getDoctrine()->getManager();
            $stock_location = $em->merge($stock_location);

            //Create the array of all shelves required.
            $xn = $x_end - $x_start + 1;
            $yn = $y_end - $y_start + 1;

            $total_number_of_shelves = $xn * $yn;   
            
            //Put the new shelves into array
            //For each x
            for ($x = $x_start; $x <= $x_end; $x++) {
                //For each y
                for ($y = $y_start; $y <= $y_end; $y++) {
                    
                    //Create the shelf code
                    $shelf_code = $stock_location->getBusinessPremises()->getBusinessPremisesCode() . "-". $stock_location->getStockLocationCode() . "-" .$wall_code ."-X" .$x. "-Y" .$y;
                    array_push($product_location_shelves, $shelf_code);
                }
            }
            
            //Find any existing shelves that match the ones to be created.
            $not_added = array();
            $duplicates = 0;
            $added = 0;
            //Just try/catch adding all shelves in. Duplicates won't be added.
            for ($i = 0; $i < count($product_location_shelves); $i++) {
                //Insert into DB.
                
                //Cheack db for existing shelf code
                $existing_stock_location = $em->getRepository('MilesApartAdminBundle:StockLocationShelf')->findOneBy(array('stock_location_shelf_code'=>$product_location_shelves[$i]));
                //If there is no shelf code
                if ($existing_stock_location == null) {

                    //Create ne shelf
                    $entity = new StockLocationShelf();
                    //Set code and location
                    $entity->setStockLocationShelfCode($product_location_shelves[$i]);
                    $entity->setStockLocation($stock_location);

                    //Persist to the database
                    try {

                        $em->persist($entity);
                        $em->flush();

                        //Add to the added counter
                        $added = $added + 1;
                    } catch (Exception $e) {
                        $output =  'Caught exception: '.  $e->getMessage(). "\n";
                    }

                //Shelf exists
                } else {
                    //Add to the duplicates counter.
                    $duplicates = $duplicates + 1;
                }

                array_push($not_added, $product_location_shelves[$i]);

            }

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'Your form was submitted successfully. ' . $added . ' have been added, ' . $duplicates . ' duplicates were found.');
            /*
            //Persist ot the db.
            $em = $this->getDoctrine()->getManager();

            
            $em->persist($entity);
            //Get the submitted shelf code and append it to business premises and stock location codes
            $stock_location_shelf_code = $entity->getStockLocation()->getBusinessPremises()->getBusinessPremisesCode() . "-" . $entity->getStockLocation()->getStockLocationCode() . "-" . $entity->getStockLocationShelfWall() . "-" . $entity->getStockLocationShelfCode(); 

            $entity->setStockLocationShelfCode($stock_location_shelf_code);

            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New stock location shelf has been created successfully.');

            return $this->redirect($this->generateUrl('staff-business_new-stock-location-shelf'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
            */
        }

        return $this->render('MilesApartStaffBundle:Business:new_stock_location_shelf.html.twig', array(
            'submitted' => true,
            'entity' => $entity,
            'form'   => $form->createView(),
            'product_location_shelves'=> $product_location_shelves,
            'total_number_of_shelves' => $total_number_of_shelves,
            'not_added' => $not_added,
            'added' => $added,
            'duplicates' => $duplicates,
        ));
    }



    /**
    * Creates a form to create a new stock location shelf.
    *
    * @param StockLocationShelf $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateStockLocationShelfForm(StockLocationShelfEntry $entity)
    {
        $form = $this->createForm(new StockLocationShelfEntryType(), $entity, array(
            'action' => $this->generateUrl('staff-business_new-stock-location-shelf-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }


    public function editstocklocationshelfAction($id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Edit Stock Location Shelf", $this->get("router")->generate("staff-business_edit-stock-location-shelf", array ('id'=> $id )));
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:StockLocationShelf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StockLocationShelf entity.');
        }

        $edit_form = $this->createEditStockLocationShelfForm($entity);

        return $this->render('MilesApartStaffBundle:Business:edit_stock_location_shelf.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'edit_form'   => $edit_form->createView(),
        ));

    } 

    /**
    * Creates a form to edit a StockLocationShelf entity.
    *
    * @param StockLocationShelf $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditStockLocationShelfForm(StockLocationShelf $entity)
    {
        $form = $this->createForm(new StockLocationShelfType(), $entity, array(
            'action' => $this->generateUrl('staff-business_edit-stock-location-shelf-submit', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing StockLocationShelf entity.
     *
     */
    public function editStockLocationShelfSubmitAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Edit Stock Location Shelf", $this->get("router")->generate("staff-business_edit-stock-location-shelf-submit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:StockLocationShelf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StockLocationShelf entity.');
        }

        
        $editForm = $this->createEditStockLocationShelfForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The stock location shelf was updated successfully.');

            return $this->redirect($this->generateUrl('staff-business_edit-stock-location-shelf', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Business:edit_stock_location_shelf.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }



    public function viewstocklocationshelvesAction()
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Stock Location Shelves", $this->get("router")->generate("staff-business_view-stock-location-shelves"));

        //Get the products 
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:BusinessPremises')->findAll();
        $count = 0;
        foreach($entities as $bp) {
            foreach($bp->getStockLocation() as $sl) {
                foreach($sl->getStockLocationShelf() as $sls) {
            
                    if($sls->getStockLocationShelfCodePrinted() == false) {
                        $count++;
                    }
                }
            }
        }

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Business:view_stock_location_shelves.html.twig', array(
            'entities' => $entities,
            
            
            ));



    }

}
