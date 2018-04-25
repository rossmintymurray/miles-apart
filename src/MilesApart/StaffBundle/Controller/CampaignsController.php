<?php
// src/MilesApart/StaffBundle/Controller/CampaignsController.php

namespace MilesApart\StaffBundle\Controller;

use MilesApart\StaffBundle\Form\Campaigns\CampaignType;
use MilesApart\StaffBundle\Form\Campaigns\PromotionType;
use MilesApart\StaffBundle\Form\Campaigns\PromotionSingleType;
use MilesApart\AdminBundle\Form\PromotionTypeType;
use MilesApart\AdminBundle\Form\CampaignTypeType;
use MilesApart\AdminBundle\Form\TrafficSourceTypeType;
use MilesApart\AdminBundle\Form\TrafficSourceType;

use MilesApart\AdminBundle\Entity\Campaign;
use MilesApart\AdminBundle\Entity\Promotion;
use MilesApart\AdminBundle\Entity\PromotionType as PType;
use MilesApart\AdminBundle\Entity\CampaignType as CType;
use MilesApart\AdminBundle\Entity\TrafficSource;
use MilesApart\AdminBundle\Entity\TrafficSourceType as TSType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\Common\Collections\ArrayCollection;

class CampaignsController extends Controller
{
    /*************************************************
    * Campaigns controller displays the functions and pages in campaigns menu area.
    *************************************************/

    public function notificationsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Campaign Notifications", $this->get("router")->generate("staff-campaigns_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:notifications.html.twig');
   
    }

    public function newcampaignAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Campaign", $this->get("router")->generate("staff-campaigns_new-campaign"));
        
        $entity = new Campaign();
        $form   = $this->createCampaignForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:new_campaign.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
   
    }

    /**
    * Creates a new campaign form.
    *
    * @param CampaignType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCampaignForm(Campaign $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new CampaignType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_create-new-campaign'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Create Campaign', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function createnewcampaignAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new Campaign();
        $form = $this->createCampaignForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //Assign each submitted promotion
            foreach($form->get('promotion')->getData() as $promotion) {
                $promotion->setCampaign($entity);
                $entity->removePromotion($promotion);
                $entity->addPromotion($promotion); 
            }

            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New campaign has been created successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_new-campaign'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:new_campaign.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Campaign entity.
     *
     */
    public function editcampaignAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the entity from the database
        $entity = $em->getRepository('MilesApartAdminBundle:Campaign')->find($id);

        //If the campaign can't be found, show error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
        }

        //Get the from 
        $editForm = $this->createEditCampaignForm($entity);
        
        //Render the response
        return $this->render('MilesApartStaffBundle:Campaigns:edit_campaign.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           
        ));
    }

    /**
    * Creates a form to edit a Campaign entity.
    *
    * @param Campaign $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditCampaignForm(Campaign $entity)
    {
        $form = $this->createForm(new CampaignType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_update-campaign', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Updates an existing Campaign entity.
     *
     */
    public function updatecampaignAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the existing campaign from database
        $entity = $em->getRepository('MilesApartAdminBundle:Campaign')->find($id);

        //If the campaign can be found, throw error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
        }

        //Put the existing relations into arraycollection so they can be checked.
        //Promtions
        $originalPromotion = new ArrayCollection();

        // Create an ArrayCollection of the current promotion objects in the database
        foreach ($entity->getPromotion() as $promotion) {
            $originalPromotion->add($promotion);
        }
        
        //Create the form 
        $editForm = $this->createEditCampaignForm($entity);
        //Hand le request data
        $editForm->handleRequest($request);

        //If the form is valid
        if ($editForm->isValid()) {
            
            //Remove the relationship between the Campaign and Promotion if they have been deleted
            foreach ($originalPromotion as $promotion) {
                if (false === $entity->getPromotion()->contains($promotion)) {
                    // remove the Task from the Tag
                    $promotion->getCampaign()->removePromotion($promotion);

                    //Remove the relationship
                    $promotion->setCampaign(null);

                    //Save changes to promotion
                    $em->persist($promotion);
                }
            }

            //Assign each submitted promotion to this campaign
            foreach($editForm->get('promotion')->getData() as $promotion) {
                $promotion->setCampaign($entity);
                $entity->removePromotion($promotion);
                $entity->addPromotion($promotion);
            }

            //Save changes to the campaign entity
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The campaign was updated successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_view-campaigns'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:edit_campiagn.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
          
        ));
    }


    public function viewcampaignsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Campaigns", $this->get("router")->generate("staff-campaigns_view-campaigns"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:Campaign')->findAll();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:view_campaigns.html.twig', array(
            'entities' => $entities,
            ));
   
    }

    public function newpromotionAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Promotion", $this->get("router")->generate("staff-campaigns_new-promotion"));
        
        $entity = new Promotion();
        $form   = $this->createPromotionForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:new_promotion.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
   
    }

    /**
    * Creates a new promotion form.
    *
    * @param PromotionType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPromotionForm(Promotion $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PromotionSingleType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_create-new-promotion'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Create Promotion', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function createnewpromotionAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new Promotion();
        $form = $this->createPromotionForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            //Get entity manager
            $em = $this->getDoctrine()->getManager();

            //Save the entity
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New promotion has been created successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_new-promotion'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:new_promotion.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Promotion entity.
     *
     */
    public function editpromotionAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the entity from the database
        $entity = $em->getRepository('MilesApartAdminBundle:Promotion')->find($id);

        //If the promotion can't be found, show error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Promotion entity.');
        }

        //Get the from 
        $editForm = $this->createEditPromotionForm($entity);
        
        //Render the response
        return $this->render('MilesApartStaffBundle:Campaigns:edit_promotion.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           
        ));
    }

    /**
    * Creates a form to edit a Promotion entity.
    *
    * @param Promotion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditPromotionForm(Promotion $entity)
    {
        $form = $this->createForm(new PromotionSingleType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_update-promotion', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Updates an existing Promotion entity.
     *
     */
    public function updatepromotionAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the existing campaign from database
        $entity = $em->getRepository('MilesApartAdminBundle:Promotion')->find($id);

        //If the campaign can be found, throw error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Promotion entity.');
        }
        
        //Create the form 
        $editForm = $this->createEditPromotionForm($entity);
        //Handle request data
        $editForm->handleRequest($request);

        //If the form is valid
        if ($editForm->isValid()) {

            //Save changes to the promotion entity
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The promotion was updated successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_view-promotions'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:edit_promotion.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
          
        ));
    }


    public function viewpromotionsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Promotions", $this->get("router")->generate("staff-campaigns_view-promotions"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:Promotion')->findAll();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:view_promotions.html.twig', array(
            'entities' => $entities,
            ));
   
    }

    public function analysecampaignsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Analyse Campaign", $this->get("router")->generate("staff-campaigns_analyse-campaigns"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:analyse_campaigns.html.twig');
   
    }

    /*
    *
    *
    * Code to add data required to set up campaigns
    *
    *
    */
    //Traffic source type
     public function newtrafficsourcetypeAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Traffic Source Type", $this->get("router")->generate("staff-campaigns_new-traffic-source-type"));
        
        $entity = new TSType();
        $form   = $this->createTrafficSourceTypeForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:new_traffic_source_type.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
   
    }

    /**
    * Creates a new traffic source type form.
    *
    * @param TrafficSourceTypeType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createTrafficSourceTypeForm(TSType $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new TrafficSourceTypeType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_create-new-traffic-source-type'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Create Traffic Source Type', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function createnewtrafficsourcetypeAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new TSType();
        $form = $this->createTrafficSourceTypeForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New traffic sourc type has been created successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_new-traffic-source-type'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:new_traffic_source_type.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Traffic Source Type entity.
     *
     */
    public function edittrafficsourcetypeAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the entity from the database
        $entity = $em->getRepository('MilesApartAdminBundle:TrafficSourceType')->find($id);

        //If the campaign can't be found, show error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find traffic source type entity.');
        }

        //Get the from 
        $editForm = $this->createEditTrafficSourceTypeForm($entity);
        
        //Render the response
        return $this->render('MilesApartStaffBundle:Campaigns:edit_traffic_source_type.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           
        ));
    }

    /**
    * Creates a form to edit a TrafficSourceType entity.
    *
    * @param TrafficSourceType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditTrafficSourceTypeForm(TSType $entity)
    {
        $form = $this->createForm(new TrafficSourceTypeType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_update-traffic-source-type', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Updates an existing TrafficSourceType entity.
     *
     */
    public function updatetrafficsourcetypeAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the existing traffic source type from database
        $entity = $em->getRepository('MilesApartAdminBundle:TrafficSourceType')->find($id);

        //If the traffic source type can be found, throw error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find traffic source type entity.');
        }
        
        //Create the form 
        $editForm = $this->createEditTrafficSourceTypeForm($entity);
        //Hand le request data
        $editForm->handleRequest($request);

        //If the form is valid
        if ($editForm->isValid()) {

            //Save changes to the campaign entity
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The traffic source type was updated successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_view-traffic-source-types'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:edit_traffic_source_type.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
          
        ));
    }


    public function viewtrafficsourcetypesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Traffic Source Types", $this->get("router")->generate("staff-campaigns_view-traffic-source-types"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:TrafficSourceType')->findAll();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:view_traffic_source_types.html.twig', array(
            'entities' => $entities,
            ));
   
    }

    //Traffic Source
     public function newtrafficsourceAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Traffic Source", $this->get("router")->generate("staff-campaigns_new-traffic-source"));
        
        $entity = new TrafficSource();
        $form   = $this->createTrafficSourceForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:new_traffic_source.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
   
    }

    /**
    * Creates a new traffic source form.
    *
    * @param TrafficSourceType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createTrafficSourceForm(TrafficSource $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new TrafficSourceType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_create-new-traffic-source'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Create Traffic Source', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function createnewtrafficsourceAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new TrafficSource();
        $form = $this->createTrafficSourceForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New traffic source has been created successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_new-traffic-source'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:new_campaign_traffic_source.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Traffic Source entity.
     *
     */
    public function edittrafficsourceAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the entity from the database
        $entity = $em->getRepository('MilesApartAdminBundle:TrafficSource')->find($id);

        //If the campaign can't be found, show error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find traffic source entity.');
        }

        //Get the from 
        $editForm = $this->createEditTrafficSourceForm($entity);
        
        //Render the response
        return $this->render('MilesApartStaffBundle:Campaigns:edit_traffic_source.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           
        ));
    }

    /**
    * Creates a form to edit a TrafficSource entity.
    *
    * @param TrafficSource $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditTrafficSourceForm(TrafficSource $entity)
    {
        $form = $this->createForm(new TrafficSourceType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_update-traffic-source', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Updates an existing TrafficSource entity.
     *
     */
    public function updatetrafficsourceAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the existing traffic source from database
        $entity = $em->getRepository('MilesApartAdminBundle:TrafficSource')->find($id);

        //If the traffic source can be found, throw error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find traffic source entity.');
        }
        
        //Create the form 
        $editForm = $this->createEditTrafficSourceForm($entity);
        //Hand le request data
        $editForm->handleRequest($request);

        //If the form is valid
        if ($editForm->isValid()) {

            //Save changes to the campaign entity
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The traffic source was updated successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_view-traffic-sources'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:edit_traffic_source.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
          
        ));
    }


    public function viewtrafficsourcesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Traffic Sources", $this->get("router")->generate("staff-campaigns_view-traffic-sources"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:TrafficSource')->findAll();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:view_traffic_sources.html.twig', array(
            'entities' => $entities,
            ));
   
    }

    //Promotion Type
     public function newpromotiontypeAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Promotion Type", $this->get("router")->generate("staff-campaigns_new-promotion-type"));
        
        $entity = new PType();
        $form   = $this->createPromotionTypeForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:new_promotion_type.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
   
    }

    /**
    * Creates a new promotion type form.
    *
    * @param PromotionType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPromotionTypeForm(PType $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PromotionTypeType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_create-new-promotion-type'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Create Promotion Type', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function createnewpromotiontypeAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new PType();
        $form = $this->createPromotionTypeForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New promotion type has been created successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_new-promotion-type'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:new_promotion_type.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Promotion Type entity.
     *
     */
    public function editpromotiontypeAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the entity from the database
        $entity = $em->getRepository('MilesApartAdminBundle:PromotionType')->find($id);

        //If the promotion type can't be found, show error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find promotion type entity.');
        }

        //Get the from 
        $editForm = $this->createEditPromotionTypeForm($entity);
        
        //Render the response
        return $this->render('MilesApartStaffBundle:Campaigns:edit_promotion_type.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           
        ));
    }

    /**
    * Creates a form to edit a PromotionType entity.
    *
    * @param PromotionType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditPromotionTypeForm(PType $entity)
    {
        $form = $this->createForm(new PromotionTypeType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_update-promotion-type', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Updates an existing PromotionType entity.
     *
     */
    public function updatepromotiontypeAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the existing promotion type from database
        $entity = $em->getRepository('MilesApartAdminBundle:PromotionType')->find($id);

        //If the promotion type can be found, throw error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find promotion type entity.');
        }
        
        //Create the form 
        $editForm = $this->createEditPromotionTypeForm($entity);
        //Hand le request data
        $editForm->handleRequest($request);

        //If the form is valid
        if ($editForm->isValid()) {

            //Save changes to the campaign entity
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The promotion type was updated successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_view-promotion-types'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:edit_promotion_type.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
          
        ));
    }


    public function viewpromotiontypesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Promotion Types", $this->get("router")->generate("staff-campaigns_view-promotion-types"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:PromotionType')->findAll();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:view_promotion_types.html.twig', array(
            'entities' => $entities,
            ));
   
    }

    //Campaign Type
     public function newcampaigntypeAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Campaign Type", $this->get("router")->generate("staff-campaigns_new-campaign-type"));
        
        $entity = new CType();
        $form   = $this->createCampaignTypeForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:new_campaign_type.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
   
    }

    /**
    * Creates a new campaign type form.
    *
    * @param CampaignType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCampaignTypeForm(CType $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new CampaignTypeType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_create-new-campaign-type'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Create Campaign Type', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function createnewcampaigntypeAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new CType();
        $form = $this->createCampaignTypeForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New campaign type has been created successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_new-campaign-type'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:new_campaign_type.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Campaign Type entity.
     *
     */
    public function editcampaigntypeAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the entity from the database
        $entity = $em->getRepository('MilesApartAdminBundle:CampaignType')->find($id);

        //If the campaign type can't be found, show error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find campaign type entity.');
        }

        //Get the from 
        $editForm = $this->createEditCampaignTypeForm($entity);
        
        //Render the response
        return $this->render('MilesApartStaffBundle:Campaigns:edit_campaign_type.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           
        ));
    }

    /**
    * Creates a form to edit a CampaignType entity.
    *
    * @param CampaignType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditCampaignTypeForm(CType $entity)
    {
        $form = $this->createForm(new CampaignTypeType(), $entity, array(
            'action' => $this->generateUrl('staff-campaigns_update-campaign-type', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Updates an existing CampaignType entity.
     *
     */
    public function updatecampaigntypeAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the existing campaign type from database
        $entity = $em->getRepository('MilesApartAdminBundle:CampaignType')->find($id);

        //If the campaign type can be found, throw error.
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find campaign type entity.');
        }
        
        //Create the form 
        $editForm = $this->createEditCampaignTypeForm($entity);
        //Hand le request data
        $editForm->handleRequest($request);

        //If the form is valid
        if ($editForm->isValid()) {

            //Save changes to the campaign type entity
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The campaign type was updated successfully.');

            return $this->redirect($this->generateUrl('staff-campaigns_view-campaign-types'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Campaigns:edit_campaign_type.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
          
        ));
    }


    public function viewcampaigntypesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Campaign Types", $this->get("router")->generate("staff-campaigns_view-campaign-types"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:CampaignType')->findAll();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Campaigns:view_campaign_types.html.twig', array(
            'entities' => $entities,
            ));
   
    }

     //
    public function vanityurlcheckAction(Request $request) 
    {
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }
        
        //Set the new price and the product id
        $vanity_url = $response["vanityURL"];

        //Get the delivery
        $em = $this->getDoctrine()->getManager();

        //Query the database to see if there is an existing promotion that uses the vanity URL
        $existing_promotion = $em->getRepository('MilesApartAdminBundle:Promotion')->findOneBy(array('vanity_url_append_text' => $vanity_url));

        if($existing_promotion != null) {
            $available = false;
        } else {
            $available = true;
        }

        $response = array(
            "available" => $available
        );

        return new JsonResponse($response);

        
    }

}
