<?php
// src/MilesApart/StaffBundle/Controller/CategoriesController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use MilesApart\StaffBundle\Form\Categories\CategoryType;
use MilesApart\AdminBundle\Entity\Category;
use MilesApart\AdminBundle\Entity\Brand;
use MilesApart\StaffBundle\Form\Categories\BrandType;
use MilesApart\AdminBundle\Entity\Season;
use MilesApart\StaffBundle\Form\Categories\SeasonType;

use Doctrine\Common\Collections\ArrayCollection;

class CategoriesController extends Controller
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
        $breadcrumbs->addItem("Category Notifications", $this->get("router")->generate("staff-categories_notifications"));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Categories:notifications.html.twig');
    }

    public function newcategoryAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Category", $this->get("router")->generate("staff-categories_new-category"));
        
        $entity = new Category();
        $form   = $this->createCategoryForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Categories:new_category.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));

        
    }

    /**
    * Creates a new category form.
    *
    * @param CategoryType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCategoryForm(Category $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('staff-categories_create-new-category'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Submit', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Creates a new Category entity.
     *
     */
    public function createnewcategoryAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new Category();
        $form = $this->createCategoryForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New category has been created successfully.');

            return $this->redirect($this->generateUrl('staff-categories_new-category'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Categories:new_category.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     */
    public function editcategoryAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createEditCategoryForm($entity);
        

        return $this->render('MilesApartStaffBundle:Categories:edit_category.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           
        ));
    }

    /**
    * Creates a form to edit a Category entity.
    *
    * @param Category $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditCategoryForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('staff-categories_edit-category-update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing Category entity.
     *
     */
    public function editcategoryupdateAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        
        $editForm = $this->createEditCategoryForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The category was updated successfully.');

            return $this->redirect($this->generateUrl('staff-categories_edit-category', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Categories:edit_category.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
          
        ));
    }


    public function viewcategoriesAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Categories", $this->get("router")->generate("staff-categories_view-categories"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:Category')->findAll();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Categories:view_categories.html.twig', array(
            'entities' => $entities,
        ));
    }

    
    public function newseasonAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add new season to breadcrumb.
        $breadcrumbs->addItem("New Season", $this->get("router")->generate("staff-categories_new-season"));
        
        $entity = new Season();
        $form   = $this->createSeasonForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Categories:new_season.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));

        
    }

    /**
    * Creates a new season form.
    *
    * @param SeasonType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createSeasonForm(Season $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new SeasonType(), $entity, array(
            'action' => $this->generateUrl('staff-categories_create-new-season'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Submit', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Creates a new Season entity.
     *
     */
    public function createnewseasonAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new Season();
        $form = $this->createSeasonForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New season has been created successfully.');

            return $this->redirect($this->generateUrl('staff-categories_new-season'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Categories:new_season.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Season entity.
     *
     */
    public function editseasonAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Season')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Season entity.');
        }

        $editForm = $this->createEditSeasonForm($entity);
        

        return $this->render('MilesApartStaffBundle:Categories:edit_season.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           
        ));
    }

    /**
    * Creates a form to edit a Season entity.
    *
    * @param Season $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditSeasonForm(Season $entity)
    {
        $form = $this->createForm(new SeasonType(), $entity, array(
            'action' => $this->generateUrl('staff-categories_edit-season-update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing Season entity.
     *
     */
    public function editseasonupdateAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Season')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Season entity.');
        }

        
        $editForm = $this->createEditSeasonForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The season was updated successfully.');

            return $this->redirect($this->generateUrl('staff-categories_edit-season', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Categories:edit_season.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
          
        ));
    }


    public function viewseasonsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Seasons", $this->get("router")->generate("staff-categories_view-seasons"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:Season')->findAll();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Categories:view_seasons.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function newbrandAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add new season to breadcrumb.
        $breadcrumbs->addItem("New Brand", $this->get("router")->generate("staff-categories_new-brand"));
        
        $entity = new Brand();
        $form   = $this->createBrandForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Categories:new_brand.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));

        
    }

    /**
    * Creates a new brand form.
    *
    * @param BrandType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createBrandForm(Brand $entity)
    {
         //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new BrandType(), $entity, array(
            'action' => $this->generateUrl('staff-categories_create-new-brand'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Submit', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Creates a new Brand entity.
     *
     */
    public function createnewbrandAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        

        $entity = new Brand();
        $form = $this->createBrandForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //Assign each submitted brand description paragraph
            foreach($form->get('brand_description_paragraph')->getData() as $bdp) {
                $bdp->setBrand($entity);
                $entity->removeBrandDescriptionParagraph($bdp);
                $entity->addBrandDescriptionParagraph($bdp); 
            }

            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New brand has been created successfully.');

            return $this->redirect($this->generateUrl('staff-categories_new-brand'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Categories:new_brand.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Brand entity.
     *
     */
    public function editbrandAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Brand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }

        $editForm = $this->createEditBrandForm($entity);
        

        return $this->render('MilesApartStaffBundle:Categories:edit_brand.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
           
        ));
    }

    /**
    * Creates a form to edit a Brand entity.
    *
    * @param Brand $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditBrandForm(Brand $entity)
    {
        $form = $this->createForm(new BrandType(), $entity, array(
            'action' => $this->generateUrl('staff-categories_edit-brand-update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    /**
     * Edits an existing Brand entity.
     *
     */
    public function editbrandupdateAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
       
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Brand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }

        //Put the existing relations into arraycollection so they can be checked.
        //Brand description paragraph
        $originalBrandDescriptionParagraph = new ArrayCollection();

        // Create an ArrayCollection of the current brand description paragraph objects in the database
        foreach ($entity->getBrandDescriptionParagraph() as $bdp) {
            $originalBrandDescriptionParagraph->add($bdp);
        }

        //Brand feature
        $originalBrandFeature = new ArrayCollection();

        // Create an ArrayCollection of the current brand feature objects in the database
        foreach ($entity->getBrandFeature() as $bf) {
            $originalBrandFeature->add($bf);
        }
        
        $editForm = $this->createEditBrandForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            // remove the relationship between the brand and brand description paragraph
            foreach ($originalBrandDescriptionParagraph as $bdp) {
                if (false === $entity->getBrandDescriptionParagraph()->contains($bdp)) {
                    // remove the Task from the Tag
                    $bdp->getBrand()->removeBrandDescriptionParagraph($bdp);

                    // if it was a many-to-one relationship, remove the relationship like this
                    //$pc->setDailyTakeBusinessPremises(null);

                    //$em->persist($pc);

                    // if you wanted to delete the Tag entirely, you can also do that
                    $em->remove($bdp);
                }
            }

            // remove the relationship between the brand and brand feature
            foreach ($originalBrandFeature as $bf) {
                if (false === $entity->getBrandFeature()->contains($bf)) {
                    // remove the Task from the Tag
                    $bdp->getBrand()->removeFeature($bf);

                    // if it was a many-to-one relationship, remove the relationship like this
                    //$pc->setDailyTakeBusinessPremises(null);

                    //$em->persist($pc);

                    // if you wanted to delete the Tag entirely, you can also do that
                    $em->remove($bf);
                }
            }

            //Assign each submitted brand description paragraph to this brand
            foreach($editForm->get('brand_description_paragraph')->getData() as $brand_description_paragraph) {
                $brand_description_paragraph->setBrand($entity);
                $entity->removeBrandDescriptionParagraph($brand_description_paragraph);
                $entity->addBrandDescriptionParagraph($brand_description_paragraph);
            }

            //Assign each submitted brand feature to this brand
            foreach($editForm->get('brand_feature')->getData() as $brand_feature) {
                $brand_feature->setBrand($entity);
                $entity->removeBrandFeature($brand_feature);
                $entity->addBrandFeature($brand_feature);
            }

            //Save changes to brand entity
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The brand was updated successfully.');

            return $this->redirect($this->generateUrl('staff-categories_view-brands'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Categories:edit_brand.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
          
        ));
    }


    public function viewbrandsAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Brands", $this->get("router")->generate("staff-categories_view-brands"));
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:Brand')->findAll();

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Categories:view_brands.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function categoryCreateSubmitAction($id = null) {
        
        $id = $id - 1;
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:Category')->findBy(array('category_type' => $id));
        

    return $this->render('MilesApartAdminBundle:Category:category_list.html.twig', array(
                'entities' => $entities,
                'id' => $id,
    ));
}

}
