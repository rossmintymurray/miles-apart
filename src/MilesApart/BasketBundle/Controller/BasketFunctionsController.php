<?php

namespace MilesApart\BasketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MilesApart\BasketBundle\Entity\Basket;
use MilesApart\BasketBundle\Entity\BasketProduct;
use MilesApart\AdminBundle\Entity\CustomerWishList;
use MilesApart\AdminBundle\Entity\CustomerWishListProduct;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
class BasketFunctionsController extends Controller
{
    //Add from shop page (if user has ajax disabled
    public function shopaddAction($id, $specific_category_slug = null, $sub_category_slug=null, $main_category_slug=null)
    {
    	//Get the product and add it to the session
    
        //Get product from the database.
        $em = $this->getDoctrine()->getManager();
		
		//Check if basket is set the in the session.
        $session = new Session();
       
        if ($session->has('basket')) {
        	
            //Get the basket from the session
        	$basket = $session->get('basket');

            $basket = $em->merge($basket);
        
        } else {
        	
            //Basket does not exist so create new.
        	$basket = new Basket();

            //Set the session ID so abandoned baskets can be recovered.
        	$basket->setSessionId($session->getId());

            $em->persist($basket);
        }
        $logger = $this->get('logger');
        

        $exists = "";
        $count = count($basket->getPurchasingBasketProduct());
        //Check if any products exists in the basket 
        //Set existing to 0.
        $exists_in_basket = false;
        if ($count > 0) {
            
        $logger->info('I just got the logger count 1 or more');
            
            

            //For each basket product in the basket
	        foreach($basket->getPurchasingBasketProduct() as $key => $basket_product_existing) {
		         
                //Check if the product id of each item in the basket matches the one we are adding.
		        if ($basket_product_existing->getProduct()->getId() == $id) {
		        	
                    $logger->info('I just got the logger match');
                    //Product is in basket so add qty..
                    $new_qty = $basket_product_existing->getBasketProductQuantity() + 1;
		        	$basket_product_existing->setBasketProductQuantity($new_qty);
		        	
                    $exists_in_basket = true;
                    //$em->persist($basket_product_existing);
                //Product is not in the basket
                } 

            }
        }

        if ($exists_in_basket == false) {
            $logger->info('I just got the logger no match');

            $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($id);

            //Create new basket product.
            $basket_product = new BasketProduct();
            $basket_product->setBasket($basket);
            //Set the product of the basket product.
            $basket_product->setProduct($product);
            $basket_product->setBasketProductQuantity(1);

            //Product is not in basket so add it..
            $basket->addBasketProduct($basket_product);
                   
                   
		        
		}


        
        
        
       
        $logger->info('I just got the logger persist');
        //Persist to database.
       
      

        $em->flush();

        //Save to session.
        //$session->remove('basket');
        $session->set('basket', $basket);

        //Show the flash message with success
        $this->get('session')->getFlashBag()->set('public-success', 'Item has been added to the basket');
        
        $page = $session->get('page');

        //$session->remove('basket');
        //$session->invalidate();
        return $this->redirect($this->generateUrl('miles_apart_public_shop', array('main_category' => $main_category_slug, 'sub_category' => $sub_category_slug, 'specific_category' => $specific_category_slug, 'page' => $page )));
       
    }

    public function productaddAction($id)
    {
        //Get the product and add it to the session
    
        //Get product from the database.
        $em = $this->getDoctrine()->getManager();
        
        //Check if basket is set the in the session.
        $session = new Session();
       
        if ($session->has('basket')) {
            
            //Get the basket from the session
            $basket = $session->get('basket');

            $basket = $em->merge($basket);
        
        } else {
            
            //Basket does not exist so create new.
            $basket = new Basket();

            //Set the session ID so abandoned baskets can be recovered.
            $basket->setSessionId($session->getId());

            $em->persist($basket);
        }
        $logger = $this->get('logger');
        

        $exists = "";
        $count = count($basket->getPurchasingBasketProduct());
        //Check if any products exists in the basket 
        //Set existing to 0.
        $exists_in_basket = false;
        if ($count > 0) {
            
        $logger->info('I just got the logger couunt 1 or more');
            
            

            //For each basket product in the basket
            foreach($basket->getPurchasingBasketProduct() as $key => $basket_product_existing) {
                 
                //Check if the product id of each item in the basket matches the one we are adding.
                if ($basket_product_existing->getProduct()->getId() == $id) {
                    
                    $logger->info('I just got the logger match');
                    //Product is in basket so add qty..
                    $new_qty = $basket_product_existing->getBasketProductQuantity() + 1;
                    $basket_product_existing->setBasketProductQuantity($new_qty);
                    
                    $exists_in_basket = true;
                    $product = $basket_product_existing->getProduct();
                    //$em->persist($basket_product_existing);
                //Product is not in the basket
                } 

            }
        }

        if ($exists_in_basket == false) {
            $logger->info('I just got the logger no match');

            $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($id);

            //Create new basket product.
            $basket_product = new BasketProduct();
            $basket_product->setBasket($basket);
            //Set the product of the basket product.
            $basket_product->setProduct($product);
            $basket_product->setBasketAddedProductQuantity($basket_product->getBasketAddedProductQuantity() +1);

            //Product is not in basket so add it..
            $basket->addBasketProduct($basket_product);
                   
                   
                
        }


        
        
        
       
        $logger->info('I just got the logger persist');
        //Persist to database.
       
      

        $em->flush();

        //Save to session.
        //$session->remove('basket');
        $session->set('basket', $basket);

        //Show the flash message with success
        $this->get('session')->getFlashBag()->set('public-success', 'Item has been added to the basket');


        //$session->remove('basket');
        //$session->invalidate();
        return $this->redirect($this->generateUrl('miles_apart_public_product_page', array('slug' => $product->getProductSlug())));
       
    }


    public function ajaxaddAction(Request $request)
    {
        $session = $request->getSession();
        
        //Get the product and add it to the session
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            //Load the post array into response variable
            $response = $_POST;
        
        //If there is no post data
        } else {
            //Set response to false
            $response = "false";
        }

        $id = $response["product_id"];
       
        //Get product from the database.
        $em = $this->getDoctrine()->getManager();
        
    
        if ($session->has('basket')) {
            
            //Get the basket from the session
            $basket = $session->get('basket');

            $basket = $em->merge($basket);
        
        } else {
            
            //Basket does not exist so create new.
            $basket = new Basket();

            //Set the session ID so abandoned baskets can be recovered.
            $basket->setSessionId($session->getId());

            $em->persist($basket);
        }
        
        

        $exists = "";
        $count = count($basket->getPurchasingBasketProduct());
        //Check if any products exists in the basket 
        //Set existing to 0.
        $exists_in_basket = false;
        if ($count > 0) {
            
        
            
            

            //For each basket product in the basket
            foreach($basket->getPurchasingBasketProduct() as $key => $basket_product_existing) {
                 
                //Check if the product id of each item in the basket matches the one we are adding.
                if ($basket_product_existing->getProduct()->getId() == $id) {

                    //The product is in the basket
                    $exists_in_basket = true;

                    //Check stock of product
                    if ($basket_product_existing->getProduct()->getCurrentStockLevel() - $basket_product_existing->getBasketProductQuantity() > 0) {
                        //Product is in basket so add qty..
                        $new_qty = $basket_product_existing->getBasketAddedProductQuantity() + 1;
                        $basket_product_existing->setBasketAddedProductQuantity($new_qty);


                        //$em->persist($basket_product_existing);

                        //Set the return data
                        $product_id = $basket_product_existing->getProduct()->getId();
                        $product_name = $basket_product_existing->getProduct()->getProductMarketingName();
                        $product_price = $basket_product_existing->getProduct()->getCurrentPriceDecimal();
                        $product_quantity = $basket_product_existing->getBasketProductQuantity();
                        $current_stock_level = $basket_product_existing->getProduct()->getCurrentStockLevelMinusBasket();
                    } else {
                        //Set that there is not enough stock for the warning message
                        //Set the return data
                        $product_id = FALSE;
                        $product_name = FALSE;
                        $product_price = FALSE;
                        $product_quantity = FALSE;
                        $current_stock_level = -1;
                    }

                }

            }
        }

        if ($exists_in_basket == false) {
            
            $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($id);

            //Check stock level
            if($product->getCurrentStockLevel() > 0) {
                //Create new basket product.
                $basket_product = new BasketProduct();
                $basket_product->setBasket($basket);
                //Set the product of the basket product.
                $basket_product->setProduct($product);
                $basket_product->setBasketAddedProductQuantity(1);

                //Product is not in basket so add it..
                $basket->addBasketProduct($basket_product);

                //Set the return data
                $product_id = $basket_product->getProduct()->getId();
                $product_name = $basket_product->getProduct()->getProductMarketingName();
                $product_price = $basket_product->getProduct()->getCurrentPriceDecimal();
                $product_quantity = $basket_product->getBasketProductQuantity();
                $current_stock_level = $product->getCurrentStockLevelMinusBasket();
            } else {
                //Set that there is not enough stock for the warning message
                //Set the return data
                $product_id = FALSE;
                $product_name = FALSE;
                $product_price = FALSE;
                $product_quantity = FALSE;
                $current_stock_level = -1;
            }
        }


        //Persist to database.
       
      

        $em->flush();

        //Save to session.
        //$session->remove('basket');
        $session->set('basket', $basket);

        //Set basket totals for sending to JS

        $response = array(
            "success" => true,
            "product_id" => $product_id,
            "product_name" => $product_name,
            "product_price" => $product_price,
            "product_quantity" => $product_quantity,
            "basket_quantity" =>$basket->getBasketTotalQuantity(),
            "basket_value" =>$basket->getBasketTotalPriceDisplay(),
            "current_stock_level" => $current_stock_level,

            );

        return new JsonResponse($response);
        
    }

    /*
     * Minus qty 1 from a product in the basket
     */
    public function ajaxminusAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('I just got the logger count 1 or more');

        $session = $request->getSession();

        //Get the product and add it to the session
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            //Load the post array into response variable
            $response = $_POST;

            //If there is no post data
        } else {
            //Set response to false
            $response = "false";
        }

        $logger->info('I just got the logger count 2 or more');
        $id = $response["product_id"];
        $logger->info('I just got the logger count 2.5 or more');
        //Get product from the database.
        $em = $this->getDoctrine()->getManager();

        //Get the basket from the session
        if ($session->has('basket')) {
            $logger->info('I just got the logger count 3 or more');
            //Get the basket from the session
            $basket = $session->get('basket');
            $basket = $em->merge($basket);

        } else {
            $logger->info('I just got the logger count 4 or more');
            //Return false as there is no basket
            return new JsonResponse(
                array(
                    "success" => false
            ));
        }

        //Check if any products exists in the basket
        $line_count = count($basket->getBasketProduct());
        if ($line_count > 0) {
            //For each basket product in the basket
            foreach($basket->getBasketProduct() as $key => $basket_product_existing) {

                //Check if the product id of each item in the basket matches the one we are minusing.
                if ($basket_product_existing->getProduct()->getId() == $id) {

                    //The product is in the basket
                    $exists_in_basket = true;

                    //Product is in basket so minus qty..
                    $new_qty = $basket_product_existing->getBasketRemovedProductQuantity() + 1;

                    //If there are no more qty of this item, remove the item
                    if ($new_qty == 0) {
                        $logger->info('I just got the logger count 5 or more');
                        //Set the qty so it doesnt interfere with adding items to the basket
                        $basket_product_existing->setBasketRemovedProductQuantity($new_qty);

                        //Reduce count by 1
                        $line_count = $line_count - 1;
                        $logger->info('I just got the logger count 5.5 or more');
                    } else {
                        $logger->info('I just got the logger count 6 or more');
                        //Still some left in basket so just update qty
                        $basket_product_existing->setBasketRemovedProductQuantity($new_qty);
                    }

                    //If no more items in the basket, remove the session
                    if($line_count == 0){
                        $session->remove('basket');
                    } else {
                        //Save to session.
                        $session->set('basket', $basket);
                    }



                    //Persist to database.
                    $em->flush();
                    $logger->info('I just got the logger count 6.5 or more');
                    //Set the return data
                    $product_id = $basket_product_existing->getProduct()->getId();
                    $product_name = $basket_product_existing->getProduct()->getProductMarketingName();
                    $product_price = $basket_product_existing->getProduct()->getCurrentPriceDecimal();
                    $product_quantity = $basket_product_existing->getBasketProductQuantity();
                    $current_stock_level = $basket_product_existing->getProduct()->getCurrentStockLevelMinusBasket();
                }
            }
        } else {
            //If the product we are removing does not exist in the database
            //Set the return data
            $product_id = FALSE;
            $product_name = FALSE;
            $product_price = FALSE;
            $product_quantity = FALSE;
            $current_stock_level = -1;
        }

        //Set basket totals for sending to JS
        $response = array(
            "success" => true,
            "product_id" => $product_id,
            "product_name" => $product_name,
            "product_price" => $product_price,
            "product_quantity" => $product_quantity,
            "basket_quantity" =>$basket->getBasketTotalQuantity(),
            "basket_value" =>$basket->getBasketTotalPriceDisplay(),
            "current_stock_level" => $current_stock_level,
        );

        return new JsonResponse($response);
    }

    /*
     * Remove an entire line from the basket
     */
    public function ajaxdeleteAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('I just got the logger count 1 or more');
        $session = $request->getSession();

        //Get the product and add it to the session
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            //Load the post array into response variable
            $response = $_POST;

            //If there is no post data
        } else {
            //Set response to false
            $response = "false";
        }
        $logger->info('I just got the logger count 2 or more');
        $id = $response["product_id"];

        //Get product from the database.
        $em = $this->getDoctrine()->getManager();
        $logger->info('I just got the logger count 3 or more');
        //Check that the basket exists in the session
        if ($session->has('basket')) {

            //Get the basket from the session
            $basket = $session->get('basket');

            $basket = $em->merge($basket);

        } else {
            //Return false as there is no basket
            return new JsonResponse(
                array(
                    "success" => false
                ));
        }
        $logger->info('I just got the logger count 4 or more');
        //Check if any products exists in the basket
        $count = count($basket->getBasketProduct());
        if ($count > 0) {
            $logger->info('I just got the logger count 5.78 or more');
            //For each basket product in the basket
            foreach($basket->getBasketProduct() as $key => $basket_product_existing) {
                $logger->info('I just got the logger count 5.87 or more');
                //Check if the product id of each item in the basket matches the one we are adding.
                if ($basket_product_existing->getProduct()->getId() == $id) {
                    $logger->info('I just got the logger count 4.58 or more');
                    //The product is in the basket
                    $exists_in_basket = true;
                    $logger->info('I just got the logger count 4.59 or more');
                    //Set the qty so it doesnt interfere with adding items to the basket
                    $basket_product_existing->setBasketRemovedProductQuantity($basket_product_existing->getBasketAddedProductQuantity());

                    $logger->info('I just got the logger count 5 or more');
                    //If it is the last product in the basket, remove the basket
                    if($count - 1 == 0) {
                        $session->remove('basket');
                    } else {
                        $session->set('basket', $basket);
                    }
                    $logger->info('I just got the logger count 6 or more');
                    //Persist to database.
                    $em->flush();

                    //Set the return data
                    $product_id = $basket_product_existing->getProduct()->getId();
                    $product_name = $basket_product_existing->getProduct()->getProductMarketingName();
                    $product_price = $basket_product_existing->getProduct()->getCurrentPriceDecimal();
                    $product_quantity = $basket_product_existing->getBasketProductQuantity();
                    $current_stock_level = $basket_product_existing->getProduct()->getCurrentStockLevelMinusBasket();
                }
            }
        }

        //Set basket totals for sending to JS
        $response = array(
            "success" => true,
            "product_id" => $product_id,
            "product_name" => $product_name,
            "product_price" => $product_price,
            "product_quantity" => $product_quantity,
            "basket_quantity" =>$basket->getBasketTotalQuantity(),
            "basket_value" =>$basket->getBasketTotalPriceDisplay(),
            "current_stock_level" => $current_stock_level,
        );

        return new JsonResponse($response);
    }


    public function addRemovedBasketProduct($basket_product)
    {

    }

    //Basket empty from AJAX fiunction
    public function basketemptyAction()
    {
        //Get the product and add it to the session
        $session = new Session();
       
        if ($session->has('basket')) {

            //Set the products qty to 0
            //Get entity manager
            $em = $this->getDoctrine()->getManager();

            $basket = $session->get('basket');

            $basket = $em->merge($basket);

            //For each basket product set to 0
            foreach($basket->getBasketProduct() as $value) {
                $value->setBasketProductQuantity(0);
            }

            $em->flush();

            //Get the basket from the session
            $session->remove('basket');

            //Set up the response
            $response = array(
                "success" => true
            );

            return new JsonResponse($response);
        }
    }

     public function savetowishlistAction()
     {
        //Check that logged in user exists
        //Get user from security token
        $customer = $this->container->get('security.context')->getToken()->getUser()->getCustomer();
        
        //Get the basket contents from the session
        $session = new Session();
       
        //Check if the session basket exists
        if ($session->has('basket')) {

            $basket = $session->get('basket');
            
            //Iterate over each item in the basket and add to the logged in users wish list
            //Get entity manager
            $em = $this->getDoctrine()->getManager();

            //Merge the basket so we have access to it's methods
            $basket = $em->merge($basket);

            //Create new wish list/check for existsing
            $customer_wish_list = $em->getRepository('MilesApartAdminBundle:CustomerWishList')->findOneBy(array('customer' => $customer), array('customer_wish_list_date_created' => 'ASC'));
            
            if($customer_wish_list == null) {
                $customer_wish_list = new CustomerWishList();

                //Set the customer
                $customer_wish_list->setCustomer($customer);
            }

            //Iterate over the items in the basket
            foreach($basket->getBasketProduct() as $basket_product) {
                $customer_wish_list_product = new CustomerWishListProduct();
                $customer_wish_list_product->setProduct($basket_product->getProduct());
                $customer_wish_list_product->setCustomerWishlistProductPurchased(false);
                $customer_wish_list_product->setCustomerWishList($customer_wish_list);
               
                
                $customer_wish_list->addCustomerWishListProduct($customer_wish_list_product);
                $em->persist($customer_wish_list_product);
            }
            
            //Persist and flush the customer order
            $em->persist($customer_wish_list);
            $em->flush();

        }
        
        $response = array(
            "success" => true,
            );

        return new JsonResponse($response);
    }

}
