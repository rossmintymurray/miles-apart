<?php
// src/MilesApart/PublicBundle/Controller/ShopController.php

namespace MilesApart\PublicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Session\Session;
use MilesApart\PublicBundle\Entity\Search;
use MilesApart\AdminBundle\Entity\Customer;
use MilesApart\AdminBundle\Entity\PersonalCustomer;
use MilesApart\AdminBundle\Entity\ProductQuestion;
use MilesApart\AdminBundle\Entity\ProductReview;
use MilesApart\PublicBundle\Form\AskQuestionType;
use MilesApart\PublicBundle\Form\MainSearchType;
use MilesApart\PublicBundle\Form\ProductReviewType;
use Symfony\Component\HttpFoundation\JsonResponse;
 use FOS\UserBundle\Model\UserInterface;

class ShopController extends Controller
{
    public function shopAction($specific_category=null, $sub_category=null, $main_category= null, $page = null, $attributes = null, Request $request)
    {
        //Check for the category and set the level accordingly.
        if ($specific_category != null) {
            $level = 3;
            $category_slug = $specific_category;
            
        } else if ($sub_category != null) {
            $level = 2;
            $category_slug = $sub_category;
           
        } else if ($main_category != null) {
           
            $category_slug = $main_category;
            $level = 1;
        } else {
            $category_slug = null;
            $level = 0;
        }

        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        //Set up the session.
        $session = $request->getSession();
        $attributes =  $request->query->all();

        if($level != 0) {

            //Find category from the db.
            $category = $em->getRepository('MilesApartAdminBundle:Category')->findOneBy(array('category_slug'=>$category_slug), array('category_display_order' => 'ASC'));
          
            

            $session->set("category_level", $level);
            $session->set("category_slug", $category->getCategorySlug());
        } else {
            //Find category from the db.
            $category = $em->getRepository('MilesApartAdminBundle:Category')->findBy(array('category_type'=>1), array('category_display_order' => 'ASC'));
          
        }

        //Check if category exists
        if ($level == 3) {
            

            //Set the three tier variables for slug and name.
            $specific_category_name = $category->getCategoryName();
            $specific_category_slug = $category->getCategorySlug();
            $sub_category_name = $category->getParent()->getCategoryName();
            $sub_category_slug = $category->getParent()->getCategorySlug();
            $main_category_name = $category->getParent()->getParent()->getCategoryName();
            $main_category_slug = $category->getParent()->getParent()->getCategorySlug();
            

            // Add pages to breadcrumb.
            $breadcrumbs->addItem("Homepage", $this->get("router")->generate("miles_apart_public_homepage"));
            $breadcrumbs->addItem($main_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> null, 'specific_category'=> null )));
            $breadcrumbs->addItem($sub_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> $sub_category_slug, 'specific_category'=> null  )));
            $breadcrumbs->addItem($specific_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> $sub_category_slug, 'specific_category'=> $specific_category_slug )));
            
            $products = true;

            //Check if products per page and order by is set
            $per_page = $session->get("per_page", false);
            $order_by = $session->get("order_by", false);
            
            

        } else if ($level == 2) {

            //Set the main and sub category, reducing the parent level
            $specific_category_name = null;
            $specific_category_slug = null;
            $sub_category_name = $category->getCategoryName();
            $sub_category_slug = $category->getCategorySlug();
            $main_category_name = $category->getParent()->getCategoryName();
            $main_category_slug = $category->getParent()->getCategorySlug();
            

            // Add pages to breadcrumb.
            $breadcrumbs->addItem("Homepage", $this->get("router")->generate("miles_apart_public_homepage"));
            $breadcrumbs->addItem($main_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> null, 'specific_category'=> null )));
            $breadcrumbs->addItem($sub_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> $sub_category_slug, 'specific_category'=> null  )));

            $products = false;
            $per_page = $session->get("per_page", false);
            $order_by = $session->get("order_by", false);

        } else if ($level == 1) {

            $specific_category_name = null;
            $specific_category_slug = null;
            $sub_category_name = null;
            $sub_category_slug = null;
            $main_category_name = $category->getCategoryName();
            $main_category_slug = $category->getCategorySlug();

            // Add pages to breadcrumb.
            $breadcrumbs->addItem("Homepage", $this->get("router")->generate("miles_apart_public_homepage"));
            $breadcrumbs->addItem($main_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> null, 'specific_category'=> null )));
        
            $products = false;
            $per_page = $session->get("per_page", false);
            $order_by = $session->get("order_by", false);

        } else if ($level == 0) {

            $specific_category_name = null;
            $specific_category_slug = null;
            $sub_category_name = null;
            $sub_category_slug = null;
            $main_category_name = null;
            $main_category_slug = null;

            // Add pages to breadcrumb.
            $breadcrumbs->addItem("Homepage", $this->get("router")->generate("miles_apart_public_homepage"));
            
            $products = false;
            $per_page = $session->get("per_page", false);
            $order_by = $session->get("order_by", false);
        }

        $view_type =  $session->get("view_type");
    	
        return $this->render('MilesApartPublicBundle:Shop:shop.html.twig',
    	  	array(
                'specific_category_slug'=>$specific_category_slug,
                'sub_category_slug'=>$sub_category_slug,
                'main_category_slug'=>$main_category_slug,
                
                'specific_category_name'=>$specific_category_name,
                'sub_category_name'=>$sub_category_name,
                'main_category_name'=>$main_category_name,
    	  		
                'level'=> $level,
                'products' => $products,
                'category' => $category,
                'page'=> $page,

                'per_page' => $per_page,
                'order_by' => $order_by,

                'attributes'=> $attributes,

                'viewtype' => $view_type, 
    	  	));
    }



    public function getThumbnailsAction()
    {
        //Get the entity manager.
        $em = $this->getDoctrine()->getManager();

       
        //Get the products.
        $entities = $em->getRepository('MilesApartAdminBundle:Product')->findall();



        return $this->render(
            'MilesApartPublicBundle:Shop:thumbnails.html.twig',
            array('entities' => $entities)
        );
    }

    /* Code to show categories on shop page */
    public function categoriesdisplayareaAction($level, $category)
    {
        if($level != 0){
            //Get the entity manager.
            $em = $this->getDoctrine()->getManager();

            $category = $em->merge($category);
        }

        return $this->render('MilesApartPublicBundle:Shop:categories_display_area.html.twig', array(
            'level' => $level,
            'category' => $category,
            ));
    }

    /* Code to show products on shop page */
    public function productsdisplayareaAction($specific_category, $products_per_page, $attributes=null, $page=null, Request $request)
    {
        $session = $request->getSession();

        //Set up attributes 
        //If attributes is null (i.e. we came here from AJAx call)
        if($attributes == null){
            //Set attributes to the get data from the request
            $attributes = $request->request->all();
        }

        //Get order by
        if($session->has("order_by_query")) {
            $order_by_query = $session->get("order_by_query");
        } else {
            $order_by_query = null;
        }

        //Make array for querying DB
        //Set up parameters array 
        $parameters = array();
        //For each get parameter, push to params array
        foreach($attributes as $key => $value){
            foreach($value as $k => $v) {
                array_push($parameters, $v);
            }
        }

        //Set up session
        $session = $request->getSession();

        //Get the entity manager.
        $em = $this->getDoctrine()->getManager();

        //If the specific category is a string, get the object form the DB
        if(is_string($specific_category)) {
            //Get the category form the database
            $specific_category = $em->getRepository('MilesApartAdminBundle:Category')->findOneBy(array('category_slug' => $specific_category));

            //Get the products, using the specific category and parameters
            $products = $em->getRepository('MilesApartAdminBundle:Product')->findWebProductsByCategoryWithParams($specific_category->getCategorySlug(), $parameters, $order_by_query);
        
        //If the category is and object, we dont need to get the object from the DB
        } else {
            //Merge the cat
            $specific_category = $em->merge($specific_category);

            //Cehck if there are attributes
            if($attributes != NULL) {


                //Get the products, using the specific category and parameters
                $products = $em->getRepository('MilesApartAdminBundle:Product')->findWebProductsByCategoryWithParams($specific_category->getCategorySlug(), $parameters, $order_by_query);

            //If there are no attributes
            } else {

                //Get the products, using the specific category
                $products = $em->getRepository('MilesApartAdminBundle:Product')->findWebProductsByCategory($specific_category->getCategorySlug(), $order_by_query);

            }
        }
        
        //Set the specific category in the session
        $session->set("specific_category", $specific_category);
        


        //Remove products with no stock
        $saleable_products = array();
        foreach($products as $product) {
            if($product->getCurrentStockLevel() > 0) {
                array_push($saleable_products, $product);
            }
        }

        //Get the total number of products
        $product_count = count($saleable_products);

        //Set up pagerfanta
        $adapter = new ArrayAdapter($saleable_products);
        
        //Pass adapter to pagerfanta
        $pager =  new Pagerfanta($adapter);
        
        //Check if products per page is set
        if(!$session->get("per_page")) {
            $products_per_page = 12;

        } else {
            $products_per_page = $session->get("per_page");
        }

        //Set the number of results
        $pager->setMaxPerPage($products_per_page);

        //Set current page if not set
        if (!$page)    
            $page = 1;

        //Set page in the session.
        $session->set('page', $page);
        try  {
            $pager->setCurrentPage($page);
        }
            catch(NotValidCurrentPageException $e) {
            throw new NotFoundHttpException('Illegal page');
        }

        //Create the routeParams variable for pagerfanta
        $routeParams = array(
            'main_category' => $specific_category->getParent()->getParent()->getCategorySlug(),
            'sub_category' => $specific_category->getParent()->getCategorySlug(),
            'specific_category' => $specific_category->getCategorySlug()
            );

        //Add each attribute to routeParams
        if($attributes != null) {
            foreach($attributes as $key => $attribute) {
                foreach($attribute as $k => $param)
                    $routeParams[$key][$k] = $param;
                
            }
        } 


        return $this->render('MilesApartPublicBundle:Shop:products_display_area.html.twig', array(
            'pager' => $pager,
            'specific_category' =>$specific_category,
            'product_count' => $product_count,
            'attributes' => $attributes,
            'parameters' => $parameters,
            'routeParams' => $routeParams
            ));
    }

    /* Code to show products on shop page */
    public function searchresultsproductsdisplayareaAction($search_string, $products_per_page, $attributes=null, $page=null, Request $request)
    {
        $session = $request->getSession();

        //Set up attributes 
        //If attributes is null (i.e. we came here from AJAx call)
        if($attributes == null){
            //Set attributes to the get data from the request
            $attributes = $request->request->all();
        }

        //Get order by
        if($session->has("order_by_query")) {
            $order_by_query = $session->get("order_by_query");
        } else {
            $order_by_query = null;
        }

        //Make array for querying DB
        //Set up parameters array 
        $parameters = array();
        //For each get parameter, push to params array
        foreach($attributes as $key => $value){
            foreach($value as $k => $v) {
                array_push($parameters, $v);
            }
        }

        //Set up session
        $session = $request->getSession();

        //Get the entity manager.
        $em = $this->getDoctrine()->getManager();

        //If the specific category is a string, get the object form the DB
        if(is_string($specific_category)) {
            //Get the category form the database
            $specific_category = $em->getRepository('MilesApartAdminBundle:Category')->findOneBy(array('category_slug' => $specific_category));

            //Get the products, using the specific category and parameters
            $products = $em->getRepository('MilesApartAdminBundle:Product')->findWebProductsByCategoryWithParams($specific_category->getCategorySlug(), $parameters, $order_by_query);
        
        //If the category is and object, we dont need to get the object from the DB
        } else {
            //Merge the cat
            $specific_category = $em->merge($specific_category);

            //Cehck if there are attributes
            if($attributes != NULL) {


                //Get the products, using the specific category and parameters
                $products = $em->getRepository('MilesApartAdminBundle:Product')->findWebProductsByCategoryWithParams($specific_category->getCategorySlug(), $parameters, $order_by_query);

            //If there are no attributes
            } else {

                //Get the products, using the specific category
                $products = $em->getRepository('MilesApartAdminBundle:Product')->findWebProductsByCategory($specific_category->getCategorySlug(), $order_by_query);

            }
        }
        
        //Set the specific category in the session
        $session->set("specific_category", $specific_category);

        //Remove products with no stock
        $saleable_products = array();
        foreach($products as $product) {
            if($product->getCurrentStockLevel() > 0) {
                array_push($saleable_products, $product);
            }
        }

        //Get the total number of products
        $product_count = count($saleable_products);

        //Set up pagerfanta
        $adapter = new ArrayAdapter($saleable_products);
        
        //Pass adapter to pagerfanta
        $pager =  new Pagerfanta($adapter);
        
        //Check if products per page is set
        if(!$session->get("per_page")) {
            $products_per_page = 12;

        } else {
            $products_per_page = $session->get("per_page");
        }

        //Set the number of results
        $pager->setMaxPerPage($products_per_page);

        //Set current page if not set
        if (!$page)    
            $page = 1;

        //Set page in the session.
        $session->set('page', $page);
        try  {
            $pager->setCurrentPage($page);
        }
            catch(NotValidCurrentPageException $e) {
            throw new NotFoundHttpException('Illegal page');
        }

        //Create the routeParams variable for pagerfanta
        $routeParams = array(
            'main_category' => $specific_category->getParent()->getParent()->getCategorySlug(),
            'sub_category' => $specific_category->getParent()->getCategorySlug(),
            'specific_category' => $specific_category->getCategorySlug()
            );

        //Add each attribute to routeParams
        if($attributes != null) {
            foreach($attributes as $key => $attribute) {
                foreach($attribute as $k => $param)
                    $routeParams[$key][$k] = $param;
                
            }
        } 
            
        return $this->render('MilesApartPublicBundle:Shop:search_results_product_display_area.html.twig', array(
            'pager' => $pager,
            'specific_category' =>$specific_category,
            'product_count' => $product_count,
            'attributes' => $attributes,
            'parameters' => $parameters,
            'routeParams' => $routeParams,
            'search_string' => $search_string
            ));
    }

    public function getCategoriesOwningProducts($products)
    {
       
        //Set up the categories array.
        $categories = array();

        //Iterate over the products, 
        foreach($products as $product) {

            //Ensure product is in stock
            if($product->getCurrentStockLevel() > 0) {
                //Iterate over the categories of each product
                foreach ($product->getCategory() as $category) {

                    //Push the category to the array.
                    array_push($categories, $category->getId());
                }
            }
        }
        $unique = array_unique($categories);
        
        //Get the category objects.
        $em = $this->getDoctrine()->getManager();

        $cats = array();

        foreach($categories as $category) {
            $cat = $em->getRepository('MilesApartAdminBundle:Category')->findById($category);
            
            array_push($cats, $cat);
        }
    
        return $cats;
        
    }

    public function getAttributesOwningProductsAction($specific_category, $attributes = null, Request $request)
    {
         //Set up session
        $session = $request->getSession();

         //Get the entity manager.
        $em = $this->getDoctrine()->getManager();
        $specific_category = $em->merge($specific_category);

        $session->set("specific_category", $specific_category);
        //Get the products, using the specific category
        $products = $em->getRepository('MilesApartAdminBundle:Product')->findWebProductsByCategory($specific_category->getCategorySlug());
       
        //Set up the categories array.
        $attribute_values_array = array();
        $attributes_array = array();

        $attribute_to_add = true;
        $attribute_value_to_add = true;

        //Iterate over the products, 
        foreach($products as $product) {

            //Ensure product is in stock
            if($product->getCurrentStockLevel() > 0) {


                //Iterate over each attribute of the product
                foreach ($product->getAttributeValue() as $product_attribute) {
                    $attribute_value_to_add = true;
                    //for each attribute that exists in the array

                    if (array_key_exists($product_attribute->getAttribute()->getAttributeName(), $attribute_values_array)) {
                        foreach ($attribute_values_array[$product_attribute->getAttribute()->getAttributeName()] as $attribute_value) {

                            //Check if the attribute exists in the attributes array
                            if ($product_attribute == $attribute_value) {

                                //If it exists, set to add to false
                                $attribute_value_to_add = false;
                            }
                        }
                    }

                    //If it doesn't exist, add the attribute to the array and the attribute value
                    //Push the attribute to the array.
                    if ($attribute_value_to_add == true) {

                        //Check if the index exists in the array
                        if (array_key_exists($product_attribute->getAttribute()->getAttributeName(), $attribute_values_array)) {

                            //The array key (attribute) exists, so add the attribute value to it
                            array_push($attribute_values_array[$product_attribute->getAttribute()->getAttributeName()], $product_attribute);
                        } else {
                            $attribute_values_array[$product_attribute->getAttribute()->getAttributeName()][0] = $product_attribute;
                        }
                    }
                }
            }
        }

        //Sort the arrays
        //ksort($attribute_values_array);

        $keys = array_keys($attribute_values_array);

        foreach($keys as $key) {
            //in this case FUNCTION_NAME would be cmp
            usort($attribute_values_array[$key], array($this, "cmp")); 
            //usort($attribute_values_array[$key], SORT_ASC);  

        }
         
        return $this->render('MilesApartPublicBundle:Shop:attributes_filter.html.twig', array(
            'attributes_array' => $attributes_array,
            'attribute_values_array' => $attribute_values_array,
            'specific_category_slug' => $specific_category->getCategorySlug(),
            'sub_category_slug' => $specific_category->getParent()->getCategorySlug(),
            'main_category_slug' => $specific_category->getParent()->getParent()->getCategorySlug(),
            'attributes' => $attributes
            ));
    }

    public function getSearchAttributesOwningProductsAction($search_string, $attributes = null, Request $request)
    {
        //Set up session
        $session = $request->getSession();

        //Get the entity manager.
        $em = $this->getDoctrine()->getManager();
        
        //Find the products that match the search string
        $products = $em->getRepository('MilesApartAdminBundle:Product')->publicSearch($search_string);
       
        //Set up the categories array.
        $attribute_values_array = array();
        $attributes_array = array();

        $attribute_to_add = true;
        $attribute_value_to_add = true;

        //Iterate over the products, 
        foreach($products as $product) {

            //Ensure product is in stock
            if($product->getCurrentStockLevel() > 0) {


                //Iterate over each attribute of the product
                foreach ($product->getAttributeValue() as $product_attribute) {
                    $attribute_value_to_add = true;
                    //for each attribute that exists in the array

                    if (array_key_exists($product_attribute->getAttribute()->getAttributeName(), $attribute_values_array)) {
                        foreach ($attribute_values_array[$product_attribute->getAttribute()->getAttributeName()] as $attribute_value) {

                            //Check if the attribute exists in the attributes array
                            if ($product_attribute == $attribute_value) {

                                //If it exists, set to add to false
                                $attribute_value_to_add = false;


                            } else {
                                //If it doesn't exist, add the attribute to the array and the attribute value
                                //Push the attribute to the array.


                            }


                        }
                    }

                    if ($attribute_value_to_add == true) {

                        //Check if the index exists in the array
                        if (array_key_exists($product_attribute->getAttribute()->getAttributeName(), $attribute_values_array)) {

                            //The array key (attribute) exists, so add the attribute value to it
                            array_push($attribute_values_array[$product_attribute->getAttribute()->getAttributeName()], $product_attribute);
                        } else {
                            $attribute_values_array[$product_attribute->getAttribute()->getAttributeName()][0] = $product_attribute;
                        }
                    }
                }
            }
        }
       
        //Sort the arrays

        /*$sortArray = array(); 

        foreach($attribute_values_array as $person){ 
            foreach($person as $key=>$value){ 
                if(!isset($sortArray[$key])){ 
                    $sortArray[$key] = array(); 
                } 
                $sortArray[$key][] = $value; 
            } 
        } 

        $orderby = "name"; //change this to whatever key you want from the array 
*/
        //array_multisort($attribute_values_array); 


        //ksort($attribute_values_array);

      

        $keys = array_keys($attribute_values_array);

        foreach($keys as $key) {
            //in this case FUNCTION_NAME would be cmp
            usort($attribute_values_array[$key], array($this, "cmp")); 
            //usort($attribute_values_array[$key], SORT_ASC);  

                 
        }
        
    
         
        return $this->render('MilesApartPublicBundle:Shop:search_filter.html.twig', array(
            'attributes_array' => $attributes_array,
            'attribute_values_array' => $attribute_values_array,
           
            'attributes' => $attributes
            ));
    }

    public static function cmp($a, $b) 
    {
        return strcmp($a->getAttributeValue(), $b->getAttributeValue());
    }



    public function productpageAction($slug=null, Request $request)
    {
         $em = $this->getDoctrine()->getManager();
        //Find the data required for breadcrumb.
        //Find the category by slug.
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneBy(array('product_slug'=>$slug));
        
        //Get the category that was clicked.
        $session = $request->getSession();

        $category_slug = $session->get("category_slug");   
        $category_level = $session->get("category_level");

        //If there is no category clicked, get the defaut and the category that was cliked is the third level
        if(!$category_slug OR $category_level != 3) {
            $specific_category_name = $product->getProductDefaultCategory()->getCategoryName();
            $sub_category_name = $product->getProductDefaultCategory()->getParent()->getCategoryName();
            $main_category_name = $product->getProductDefaultCategory()->getParent()->getParent()->getCategoryName();

            $specific_category_slug = $product->getProductDefaultCategory()->getCategorySlug();
            $sub_category_slug = $product->getProductDefaultCategory()->getParent()->getCategorySlug();
            $main_category_slug = $product->getProductDefaultCategory()->getParent()->getParent()->getCategorySlug();
        } else {

            //There is a category slug so find the upper categories
            $category = $em->getRepository('MilesApartAdminBundle:Category')->findOneBy(array('category_slug'=>$category_slug));

            $specific_category_name = $category->getCategoryName();
            $sub_category_name = $category->getParent()->getCategoryName();
            $main_category_name = $category->getParent()->getParent()->getCategoryName();

            $specific_category_slug = $category->getCategorySlug();
            $sub_category_slug = $category->getParent()->getCategorySlug();
            $main_category_slug = $category->getParent()->getParent()->getCategorySlug();

        }
        
        
        //Get the nex cat and so on.

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Homepage", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem($main_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> null, 'specific_category'=> null )));
        $breadcrumbs->addItem($sub_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> $sub_category_slug, 'specific_category'=> null  )));

        $breadcrumbs->addItem($specific_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> $sub_category_slug, 'specific_category'=> $specific_category_slug )));

        $breadcrumbs->addItem($product->getProductName(), $this->get("router")->generate("miles_apart_public_product_page", array ('slug'=> $slug, 'main_category'=>  $main_category_slug, 'sub_category'=> $sub_category_slug, 'specific_category'=> $specific_category_slug )));



        $postage_options = $this->getPostageOptions($product);
        //Get products from the database.
       
       //Get the product question form 
        $entity = new ProductQuestion();

        //Set the product 
        $entity->setProduct($product);
        
        $form = $this->createProductQuestionForm($entity);

        //If user is logged in, assign the email address
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {

        } else {
            $form->get('question_name')->setData($this->container->get('security.context')->getToken()->getUser()->getCustomer()->getCustomerFullName());
            $form->get('question_email')->setData($this->container->get('security.context')->getToken()->getUser()->getCustomer()->getCustomerEmailAddress());
        }

        return $this->render('MilesApartPublicBundle:Shop:product_page.html.twig', array(
            'slug'=>$slug,
            'entity'=>$product,
            'postage_options' => $postage_options,
            'ask_question_form' => $form->createView()
                  
        ));
    }


    public function productreviewAction($id=null, Request $request)
    {
         $em = $this->getDoctrine()->getManager();
        //Find the data required for breadcrumb.
        //Find the category by slug.
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($id);
        
        $slug = $product->getProductSlug();
        //Get the category that was clicked.
        $session = $request->getSession();

        $category_slug = $session->get("category_slug");   
        $category_level = $session->get("category_level");

        //If there is no category clicked, get the defaut and the category that was cliked is the third level
        if(!$category_slug OR $category_level != 3) {
            $specific_category_name = $product->getProductDefaultCategory()->getCategoryName();
            $sub_category_name = $product->getProductDefaultCategory()->getParent()->getCategoryName();
            $main_category_name = $product->getProductDefaultCategory()->getParent()->getParent()->getCategoryName();

            $specific_category_slug = $product->getProductDefaultCategory()->getCategorySlug();
            $sub_category_slug = $product->getProductDefaultCategory()->getParent()->getCategorySlug();
            $main_category_slug = $product->getProductDefaultCategory()->getParent()->getParent()->getCategorySlug();
        } else {

            //There is a category slug so find the upper categories
            $category = $em->getRepository('MilesApartAdminBundle:Category')->findOneBy(array('category_slug'=>$category_slug));

            $specific_category_name = $category->getCategoryName();
            $sub_category_name = $category->getParent()->getCategoryName();
            $main_category_name = $category->getParent()->getParent()->getCategoryName();

            $specific_category_slug = $category->getCategorySlug();
            $sub_category_slug = $category->getParent()->getCategorySlug();
            $main_category_slug = $category->getParent()->getParent()->getCategorySlug();

        }
        
        
        //Get the nex cat and so on.

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Homepage", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem($main_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> null, 'specific_category'=> null )));
        $breadcrumbs->addItem($sub_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> $sub_category_slug, 'specific_category'=> null  )));

        $breadcrumbs->addItem($specific_category_name, $this->get("router")->generate("miles_apart_public_shop", array ('main_category'=>  $main_category_slug, 'sub_category'=> $sub_category_slug, 'specific_category'=> $specific_category_slug )));

        $breadcrumbs->addItem($product->getProductName(), $this->get("router")->generate("miles_apart_public_product_page", array ('slug'=> $slug, 'main_category'=>  $main_category_slug, 'sub_category'=> $sub_category_slug, 'specific_category'=> $specific_category_slug )));

        $breadcrumbs->addItem("Leave Review", $this->get("router")->generate("miles_apart_public_product_review", array ('id' => $id)));

       
       //Get the product question form 
        $entity = new ProductReview();

        //Set the product 
        $entity->setProduct($product);
        
        $form = $this->createProductReviewForm($entity);

        //If user is logged in, assign the email address
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {

        } else {
            $form->get('question_name')->setData($this->container->get('security.context')->getToken()->getUser()->getCustomer()->getCustomerFullName());
            $form->get('question_email')->setData($this->container->get('security.context')->getToken()->getUser()->getCustomer()->getCustomerEmailAddress());
        }

        return $this->render('MilesApartPublicBundle:Shop:product_review_page.html.twig', array(
            'entity'=>$product,
            'review_form' => $form->createView()
                  
        ));
    }


    /**
    * Creates a form to create a new product review.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createProductReviewForm(ProductReview $entity)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ProductReviewType($entityManager), $entity, array(
            'action' => $this->generateUrl('miles_apart_public_product_review_submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Leave review', 'attr' => array(
                        'class' => 'btn btn-primary small-12')));

        return $form;
    }


    /**
    * Creates a form to create a new product question.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createProductQuestionForm(ProductQuestion $entity)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(new AskQuestionType($entityManager), $entity, array(
            'action' => $this->generateUrl('miles_apart_public_product_ask_product_question'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Ask question', 'attr' => array(
                        'class' => 'btn btn-primary small-12')));

        return $form;
    }

    //Function to ask product question
    public function askproductquestionAction(Request $request)
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

        $name = $response["name"];
        $email = $response["email"];
        $question = $response["question"];
        $productId = $response["productId"];

        $em = $this->getDoctrine()->getManager();
        

        //Validate the fields 


        //Get the product
        $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($productId);

        //Create the customer 
        //Check if the customer exists
        $question_customer = $em->getRepository('MilesApartAdminBundle:Customer')->findByLetters(null, $email, null);
        if(count($question_customer) > 0) {
            $question_customer = $question_customer[0];
        } else {
            //Figure out the first names and surname
            $names = explode(" ", $name);
            $arrayKeys = array_keys($names);
            
            // Fetch last array key
            $lastArrayKey = array_pop($arrayKeys);
            
            //Set up the last name variable
            $first_name = "";
            //iterate over array
            foreach($names as $k => $v) {
                if($k == $lastArrayKey) {
                    //during array iteration this condition states the last element.
                    break;
                }
                $first_name = $first_name . " ". $v;
            }
            $surname = $names[$lastArrayKey];
            //Create new personal customer and set values
            $personal_customer = new PersonalCustomer();

            //Set the variables of the object
            $personal_customer->setPersonalCustomerFirstName($first_name);
            $personal_customer->setPersonalCustomerSurname($surname);
            $personal_customer->setPersonalCustomerEmailAddress($email);
            $em->persist($personal_customer);
            //Create new customer and set values
            $question_customer = new Customer();
            //Assign values to the customer 
            $question_customer->setPersonalCustomer($personal_customer);
            $personal_customer->setCustomer($question_customer);
            $em->persist($question_customer);
        }

        //Create the question in the database
        $product_question = new ProductQuestion();
        //Set values
        $product_question->setCustomer($question_customer);
        $product_question->setProduct($product);
        $product_question->setProductQuestionText($question);
        $em->persist($product_question);
        $em->flush();


        //Send an email to say that the question has been received 
        $confirm_email = $this->sendCustomerQuestionConfirmationEmail($product_question, $email);

        //Send an email to say that the question has been received
        $staff_confirm_email = $this->sendCustomerQuestionStaffNotificationEmail($product_question, $email);



        $response = array(
            "success" => $confirm_email,
            );

        return new JsonResponse($response);
        
    }

    function sendCustomerQuestionConfirmationEmail($product_question, $email_address)
    {
        //Set up the mailer
        $mailer = $this->container->get('swiftmailer.mailer.weborders_mailer');
        
        //Get the supplier email address.
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Miles Apart Question Confirmation')
            ->setFrom(array('customersupport@miles-apart.com' => 'Miles Apart'))
            ->setTo($email_address)
            ->setBody(
                $this->renderView(
                    'MilesApartPublicBundle:Emails:ask_question_confirmation.html.twig',
                    array('product_question' => $product_question)
                    
                )

            )
        ;

        //Avoid localhost issues
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        //Send the email
        $mailer->send($message);       

        return true;
    }

    function sendCustomerQuestionStaffNotificationEmail($product_question, $email_address)
    {
        //Set up the mailer
        $mailer = $this->container->get('swiftmailer.mailer.weborders_mailer');

        //Get the supplier email address.
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Miles Apart New Question Notification')
            ->setFrom(array('wesite@miles-apart.com' => 'Miles Apart'))
            ->setTo(array('customersupport@miles-apart.com' => 'Miles Apart'))
            ->setBody(
                $this->renderView(
                    'MilesApartPublicBundle:Emails:ask_question_staff_notification.html.twig',
                    array('product_question' => $product_question)

                )

            )
        ;

        //Avoid localhost issues
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        //Send the email
        $mailer->send($message);

        return true;
    }


    //Function to get the array of postage band prices for a given product. 
    public function getPostageOptions($product) {

        //Query the database for the results
        $em = $this->getDoctrine()->getManager();

        //Find the postage band that matchs the products dimensions
        $postage_band = $em->getRepository('MilesApartAdminBundle:PostageBand')->findPostageBandBySizes($product->getProductWidth(), $product->getProductHeight(), $product->getProductDepth(), $product->getProductWeight());

        //Iterate over the postage options
        foreach($postage_band[0]->getPostageBandDispatchLogistics() as $postage_option) {
            
            //If first class
            if($postage_option->getPostageType()->getId() == 1) {
                $first_class_postage = $postage_option->getPostageBandPrice();
            
            //If second class
            } else if($postage_option->getPostageType()->getId() == 2) {
                $second_class_postage = $postage_option->getPostageBandPrice();
            }
        }
        
        //Check if product value is over £30
        if($product->getCurrentPriceDecimal() > 30) {
            //Get the value of second class postage
            $first_class_postage = $first_class_postage - $second_class_postage;
            $second_class_postage = "Free";
        } 

        return array(
            'first_class_postage' => $first_class_postage,
            'second_class_postage' => $second_class_postage,
            'postage_band_id' => $postage_band[0]->getId(),
        );
    }

     public function searchresultsAction(Request $request, $attributes = null, $products_per_page=null, $page = 1)
    {
         //Set up session
        $session = $request->getSession();

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        
        //Create the form for handling search string.
        $search_string = $request->query->get('query');

        // Add search page to breadcrumb.
        $breadcrumbs->addItem("Search Results for '".$search_string."'", $this->get("router")->generate("miles_apart_public_search_results"));

        $em = $this->getDoctrine()->getManager();
        //Find the products that match the search string
        $products = $em->getRepository('MilesApartAdminBundle:Product')->publicSearch($search_string);

        //Remove products with no stock
        $saleable_products = array();
        foreach($products as $product) {
            if($product->getCurrentStockLevel() > 0) {
                array_push($saleable_products, $product);
            }
        }

        $per_page = $session->get("per_page", false);
        $order_by = $session->get("order_by", false);

        return $this->render('MilesApartPublicBundle:Shop:search_results.html.twig', array(
            
            'search_string' => $search_string,
            'per_page' => $per_page,
            'attributes' => $attributes,
            'results_count' => count($saleable_products),
            'order_by' => $order_by,
            'page' => $page


        ));

       

    }

    public function searchresultsdisplayareaAction(Request $request, $attributes = null, $products_per_page=null, $search_string, $page) 
    {
        //Set up session
        $session = $request->getSession();

        //Set up attributes 
        //If attributes is null (i.e. we came here from AJAx call)
        if($attributes == null){
            //Set attributes to the get data from the request
            $attributes = $request->request->all();
        }

        //Get order by
        if($session->has("order_by_query")) {
            $order_by_query = $session->get("order_by_query");
        } else {
            $order_by_query = null;
        }

        //Make array for querying DB
        //Set up parameters array 
        $parameters = array();
        //For each get parameter, push to params array
        foreach($attributes as $key => $value){
            foreach($value as $k => $v) {
                array_push($parameters, $v);
            }
        }

        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Find the products that match the search string
        $products = $em->getRepository('MilesApartAdminBundle:Product')->findWebProductsBySearchWithParams($search_string, $parameters, $order_by_query);

        //Remove products with no stock
        $saleable_products = array();
        foreach($products as $product) {
            if($product->getCurrentStockLevel() > 0) {
                array_push($saleable_products, $product);
            }
        }

        //Set up pagerfanta
        $adapter = new ArrayAdapter($saleable_products);
        
        //Pass adapter to pagerfanta
        $pager =  new Pagerfanta($adapter);
        
        $product_count = count($saleable_products);
        //Check if products per page is set
        if(!$session->get("per_page")) {
            $products_per_page = 12;

        } else {
            $products_per_page = $session->get("per_page");
        }

        //Set the number of results
        $pager->setMaxPerPage($products_per_page);


        //Set current page if not set
        
        if (!$page)    
            $page = 1;

        //Set page in the session.
        $session->set('page', $page);
            try  {
                $pager->setCurrentPage($page);
            }
            catch(NotValidCurrentPageException $e) {
              throw new NotFoundHttpException('Illegal page');
        }

        $per_page = $session->get("per_page", false);
        $order_by = $session->get("order_by", false);

        return $this->render('MilesApartPublicBundle:Shop:search_results_product_display_area.html.twig', array(
            
            'search_string' => $search_string,
            'pager' => $pager,
            'per_page' => $per_page,
            'order_by' => $order_by,
            'page' => $page, 
            'attributes' => $attributes,
            'product_count' => $product_count,

        ));
    }

    /**
    * Creates a form to create search box.
    *
    * @param  $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createSearchForm(Search $entity)
    {
        $form = $this->createForm(new MainSearchType(), $entity, array(
            'action' => $this->generateUrl('miles_apart_public_search_results'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        return $form;
    }

    public function settingsviewtypeAction(Request $request, $view_type)
    {
        
        //Get the category that was clicked.
        $session = $request->getSession();

        $session->set("view_type", $view_type);


        $response = new JsonResponse($view_type);
        return $response;
    }

    public function settingsperpageAction(Request $request, $per_page)
    {
        
        //Get the category that was clicked.
        $session = $request->getSession();

        $session->set("per_page", $per_page);

        $response = new JsonResponse(true);
        return $response;
    }

    public function settingsorderbyAction(Request $request, $order_by)
    {
        //Translate the ordered by into query 

        //p.product_name ASC
        if($order_by == "a_to_z") {
            $order_by_query = "p.product_name ASC";
        
        //pp.price_value ASC
        } else if($order_by == "low_to_high") {
            $order_by_query = "pp.product_price_value ASC";
        
        //pp.price_value DESC
        } else if($order_by == "high_to_low") {
            $order_by_query = "pp.product_price_value DESC";
        }
        
        

        //Get the category that was clicked.
        $session = $request->getSession();

        $session->set("order_by", $order_by);
        $session->set("order_by_query", $order_by_query);

    

        $response = new JsonResponse(true);
        return $response;
    }
}