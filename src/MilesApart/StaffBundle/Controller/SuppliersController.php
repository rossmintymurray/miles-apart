<?php
// src/MilesApart/StaffBundle/Controller/SuppliersController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

use MilesApart\AdminBundle\Entity\Supplier;
use MilesApart\AdminBundle\Form\SupplierType;
use MilesApart\StaffBundle\Form\Suppliers\FindSupplierType;

use MilesApart\AdminBundle\Entity\SupplierRepresentative;
use MilesApart\AdminBundle\Form\SupplierRepresentativeType;
use MilesApart\StaffBundle\Form\Suppliers\FindSupplierRepresentativeType;

class SuppliersController extends Controller
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
        $breadcrumbs->addItem("Supplier Notifications", $this->get("router")->generate("staff-suppliers_notifications"));
        
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Suppliers:notifications.html.twig', array(
           
            ));
   
    }


    public function getsupplieraddressAction(Request $request) 
    {
        
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $r = array('ok'=>$_POST);
            $supplier_id = 1;
        } else {
            $supplier_id = 2;
            $response = "false";
        }
        //Get the suppliers that have missing data 
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Supplier')->getSupplierAddress($supplier_id);
    
        /*$encoder = new JsonEncoder();
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes(array('supplier_account_number', 'supplier_phone', 'supplier_fax', 'supplier_ordering_email', 'supplier_info_email', 'supplier_website', 'supplier_type', 'supplier_representative', 'slug', 'product_supplier', 'supplier_discount', 'purchase_order', 'supplier_delivery', 'supplier_invoice'));
        $serializer = new Serializer(array($normalizer), array($encoder));

        $jsonContent = $serializer->serialize($entity, 'json');*/
        
        return new JsonResponse([
            $entity 
        ]);
   
    }

    public function newsupplierAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Supplier", $this->get("router")->generate("staff-suppliers_new-supplier"));
        
        $entity = new Supplier();

        $form = $this->createSupplierForm($entity);

        return $this->render('MilesApartStaffBundle:Suppliers:new_supplier.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));

    }    

    /**
    * Creates a form to create a new product.
    *
    * @param Supplier $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createSupplierForm(Supplier $entity)
    {
        $form = $this->createForm(new SupplierType(), $entity, array(
            'action' => $this->generateUrl('staff-suppliers_new-supplier-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function newsuppliersubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Supplier", $this->get("router")->generate("staff-suppliers_new-supplier"));
        
        $entity = new Supplier();
        $form = $this->createSupplierForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New supplier has been created successfully.');

            return $this->redirect($this->generateUrl('staff-suppliers_new-supplier'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Suppliers:new_supplier.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
/**
     * Displays a form to edit an existing Supplier entity.
     *
     */
    public function editSupplierAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Find Suppliers", $this->get("router")->generate("staff-suppliers_find-suppliers"));
        $breadcrumbs->addItem("Edit Supplier", $this->get("router")->generate("staff-suppliers_edit-supplier", array ('id'=> $id )));
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Supplier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Supplier entity.');
        }

        $editForm = $this->createEditSupplierForm($entity);
       

        return $this->render('MilesApartStaffBundle:Suppliers:edit_supplier.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Supplier entity.
    *
    * @param Supplier $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditSupplierForm(Supplier $entity)
    {
        $form = $this->createForm(new SupplierType(), $entity, array(
            'action' => $this->generateUrl('staff-suppliers_edit-supplier-submit', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing Supplier entity.
     *
     */
    public function editSupplierSubmitAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Supplier", $this->get("router")->generate("staff-suppliers_edit-supplier-submit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:Supplier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Supplier entity.');
        }

        
        $editForm = $this->createEditSupplierForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The supplier was updated successfully.');

            return $this->redirect($this->generateUrl('staff-suppliers_edit-supplier', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Suppliers:edit_supplier.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
       

    public function findsuppliersAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Find Suppliers", $this->get("router")->generate("staff-suppliers_find-suppliers"));
        
        //Get the suppliers 
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:Supplier')->findAll();
        //Get the total number of suppliers
        $supplier_count = count($entities);

        //Create the form to add products.
        $entity = new Supplier();
        $form = $this->createFindSupplierForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Suppliers:find_suppliers.html.twig', array(
            'entities' => $entities,
            'supplier_count' => $supplier_count,
            'submitted' => false,
            'form' => $form->createView(),
            ));
   
    }

     /**
    * Creates a form to find a Supplier entity.
    *
    * @param Supplier $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createFindSupplierForm(Supplier $entity)
    { 
        $form = $this->createForm(new FindSupplierType(), $entity, array(
            'action' => $this->generateUrl('staff-suppliers_find-supplier-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        

        return $form;
    }

    public function findsuppliersubmitAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $supplier_name = $response['searchString'];

        //Get the suppliers that have missing data 
        $em = $this->getDoctrine()->getManager();

        $suppliers = $this->getDoctrine()
                     ->getRepository('MilesApartAdminBundle:Supplier')
                     ->findByLetters($supplier_name);

        $supplier_count = count($suppliers);
        //Render the page from template*/
        return $this->render('MilesApartStaffBundle:Suppliers:find_suppliers_search.html.twig', array(
            'entities' => $suppliers,
            'supplier_count' => $supplier_count,
            ));
   
    }


    public function viewsupplierAction($id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Find Suppliers", $this->get("router")->generate("staff-suppliers_find-suppliers"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Supplier Details", $this->get("router")->generate("staff-suppliers_view-supplier", array('id'=>$id)));
        
        $em = $this->getDoctrine()->getManager();

        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneSupplierBy($id);
        

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Suppliers:view_supplier.html.twig', array(
            'supplier' => $supplier[0],
            ));
   
    }


     public function viewsupplierproductsAction($id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Find Suppliers", $this->get("router")->generate("staff-suppliers_find-suppliers"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("View Supplier Products", $this->get("router")->generate("staff-suppliers_view-supplier-products", array('id'=>$id)));
        
        $em = $this->getDoctrine()->getManager();

        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneBy(array('id' => $id));
        
        $products = $em->getRepository('MilesApartAdminBundle:Product')->findProductsBySupplier($id);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Suppliers:view_supplier_products.html.twig', array(
            'supplier' => $supplier,
            'products' => $products
            ));
   
    }



    public function newsupplierrepresentativeAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Representative", $this->get("router")->generate("staff-suppliers_new-supplier-representative"));
        
        $entity = new SupplierRepresentative();

        $form = $this->createSupplierRepresentativeForm($entity);

        return $this->render('MilesApartStaffBundle:Suppliers:new_supplier_representative.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));

    }    

    /**
    * Creates a form to create a new product.
    *
    * @param SupplierRepresentative $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createSupplierRepresentativeForm(SupplierRepresentative $entity)
    {
        $form = $this->createForm(new SupplierRepresentativeType(), $entity, array(
            'action' => $this->generateUrl('staff-suppliers_new-supplier-representative-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }

    public function newsupplierrepresentativesubmitAction(Request $request) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("New Supplier Representative", $this->get("router")->generate("staff-suppliers_new-supplier"));
        
        $entity = new SupplierRepresentative();
        $form = $this->createSupplierRepresentativeForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'New supplier representative has been created successfully.');

            return $this->redirect($this->generateUrl('staff-suppliers_new-supplier-representative'));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Suppliers:new_supplier_representative.html.twig', array(
            'submitted' => $submitted,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function findsupplierrepresentativesAction() 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Find Representatives", $this->get("router")->generate("staff-suppliers_find-supplier-representatives"));
        
        //Get the suppliers 
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MilesApartAdminBundle:SupplierRepresentative')->findAll();
        //Get the total number of suppliers
        $supplier_representative_count = count($entities);

        //Create the form to add products.
        $entity = new SupplierRepresentative();
        $form = $this->createFindSupplierRepresentativeForm($entity);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Suppliers:find_supplier_representatives.html.twig', array(
            'entities' => $entities,
            'supplier_representative_count' => $supplier_representative_count,
            'submitted' => false,
            'form' => $form->createView(),
            ));
    }

    /**
    * Creates a form to find a Supplier entity.
    *
    * @param SupplierRepresentative $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createFindSupplierRepresentativeForm(SupplierRepresentative $entity)
    { 
        $form = $this->createForm(new FindSupplierRepresentativeType(), $entity, array(
            'action' => $this->generateUrl('staff-suppliers_find-supplier-representative-submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        

        return $form;
    }

    public function findsupplierrepresentativesubmitAction(Request $request) 
    {
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }

        $supplier_representative_name = $response['searchString'];

        //Get the suppliers that have missing data 
        $em = $this->getDoctrine()->getManager();

        $supplier_representatives = $this->getDoctrine()
                     ->getRepository('MilesApartAdminBundle:SupplierRepresentative')
                     ->findByLetters($supplier_representative_name);

        $supplier_representative_count = count($supplier_representatives);
        //Render the page from template*/
        return $this->render('MilesApartStaffBundle:Suppliers:find_supplier_representatives_search.html.twig', array(
            'entities' => $supplier_representatives,
            'supplier_representative_count' => $supplier_representative_count,
            ));
   
    }

    /**
     * Displays a form to edit an existing Supplier entity.
     *
     */
    public function editSupplierRepresentativeAction($id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Edit Supplier Representative", $this->get("router")->generate("staff-suppliers_edit-supplier-representative", array ('id'=> $id )));
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:SupplierRepresentative')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Supplier Representative entity.');
        }

        $editForm = $this->createEditSupplierRepresentativeForm($entity);
       

        return $this->render('MilesApartStaffBundle:Suppliers:edit_supplier_representative.html.twig', array(
            'submitted' => false,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a SupplierRepresentative entity.
    *
    * @param SupplierRepresentative $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditSupplierRepresentativeForm(SupplierRepresentative $entity)
    {
        $form = $this->createForm(new SupplierRepresentativeType(), $entity, array(
            'action' => $this->generateUrl('staff-suppliers_edit-supplier-representative-submit', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array(
                        'class' => 'btn btn-primary col-md-offset-4 col-md-4')));

        return $form;
    }
    /**
     * Edits an existing Supplier entity.
     *
     */
    public function editSupplierRepresentativeSubmitAction(Request $request, $id)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_admin_homepage"));
        $breadcrumbs->addItem("Supplier", $this->get("router")->generate("staff-suppliers_edit-supplier-representative-submit", array ('id'=> $id )));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MilesApartAdminBundle:SupplierRepresentative')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SupplierRepresentative entity.');
        }

        
        $editForm = $this->createEditSupplierRepresentativeForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

             //Show the flash message with success
            $this->get('session')->getFlashBag()->set('admin-notice', 'The supplier representative was updated successfully.');

            return $this->redirect($this->generateUrl('staff-suppliers_edit-supplier-representative', array('id' => $id)));
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('admin-error', 'There has been a problem submitting the form, please check the details below.');
        }

        return $this->render('MilesApartStaffBundle:Suppliers:edit_supplier_representative.html.twig', array(
            'submitted' => $submitted,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    public function viewsupplierrepresentativeAction($id) 
    {
        //Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Supplier Representative", $this->get("router")->generate("staff-suppliers_view-supplier-representative", array('id'=>$id)));
        
        $em = $this->getDoctrine()->getManager();

        $supplier_representative = $em->getRepository('MilesApartAdminBundle:SupplierRepresentative')->findOneBy(array('id' => $id));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Suppliers:view_supplier_representative.html.twig', array(
            'supplier_representative' => $supplier_representative,
            ));
   
    }

    public function getsupplierslistAction() 
    {
        
        $em = $this->getDoctrine()->getManager();

        $suppliers = $em->getRepository('MilesApartAdminBundle:Supplier')->findAll();

        //Check if supplier is set in session.
        //Get transfer request
        $session = new Session();
        if ($session->get('new_product_supplier')) {
            $new_product_supplier = $session->get('new_product_supplier');

            $new_product_supplier = $em->merge($new_product_supplier);
            $new_product_supplier_id = $new_product_supplier->getId();
        } else {
            $new_product_supplier_id = null;
        }
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Suppliers:get_suppliers_list.html.twig', array(
            'suppliers' => $suppliers,
            'new_product_supplier_id' => $new_product_supplier_id,
            ));
   
    }

}
