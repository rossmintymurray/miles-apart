<?php

namespace MilesApart\BasketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MilesApart\BasketBundle\Entity\Basket;
use MilesApart\BasketBundle\Entity\BasketProduct;
use MilesApart\BasketBundle\Form\ExistingCustomerCheckoutType;
use MilesApart\BasketBundle\Form\ExistingCustomerCheckoutDeliveryType;
use MilesApart\BasketBundle\Form\NewCustomerCheckoutDeliveryType;
use MilesApart\BasketBundle\Form\PostOrderCreateAccountType;
use MilesApart\BasketBundle\Entity\NewCustomerCheckoutDelivery;
use Symfony\Component\HttpFoundation\Request;
use MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative;
use MilesApart\AdminBundle\Entity\BusinessCustomerRepresentativeCustomerOrder;
use MilesApart\AdminBundle\Entity\PersonalCustomer;
use MilesApart\AdminBundle\Entity\FosUser;
use MilesApart\AdminBundle\Entity\CustomerOrder;
use MilesApart\AdminBundle\Entity\Customer;
use MilesApart\AdminBundle\Entity\CustomerAddress;
use MilesApart\AdminBundle\Entity\CustomerOrderProduct;


use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Session\Session;
class CheckoutController extends Controller
{
    /*
    * Start Checkout Page
    */
     public function checkoutstartAction(Request $request)
    {
        //Get shop location settings from session 
        $session = $request->getSession();

        //Get the product and add it to the session
        if ($session->has('basket')) {
            //Get the basket from the session
            $basket = $session->get('basket');
        } else {
            //Forward to home as basket doesn't exist
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Get product from the database.
        $em = $this->getDoctrine()->getManager();
        //Merge the basket
        $basket = $em->merge($basket);

        //Check basket is purchasable
        $purchasable_basket = $this->getPurchasableBasket($basket);

        if($purchasable_basket) {
            //Adjustments have been made to the basket as items have been sold since they put it in their basket
            $basket = $purchasable_basket;

            //Set flashbage
            $this->get('session')->getFlashBag()->set('checkout-public-error', 'Our apologies, we have had to remove item(s) from your basket. your basket has been updated to reflect current available stock.');

            //Persist the new basket
            $em->flush();
        }
        
        //Create the new customer checkout and existing customer checkout forms
        $entity = new FosUser();

        //Create for for existing customer to login
        $existing_customer_checkout_form = $this->createExistingCustomerCheckoutForm($entity);

       //Render the page
        return $this->render('MilesApartBasketBundle::checkout_start.html.twig', array(
            'basket' => $basket,
            'form' => $existing_customer_checkout_form->createView(),
            'submitted' => false
        ));
    }

    /*
     *
     * Check basket can be purchased at this moment
     *
     */
    public function getPurchasableBasket($basket)
    {
        $purchasable_basket = NULL;

        foreach($basket->getBasketProduct() as $value) {
            $stock_offset = $value->getProduct()->getCurrentStockLevel() - $value->getBasketProductQuantity();

            if($stock_offset < 0) {
                //Adjust the basket quantity
                $value->setBasketProductQuantity($value->getBasketProductQuantity() + $stock_offset);

                $purchasable_basket = $basket;

            }
        }


        return $purchasable_basket;
    }

    /**
    * Creates a form to create an existing customer checkout form.
    *
    * @param PersonalCustomer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createExistingCustomerCheckoutForm(FosUser $entity)
    {
        $form = $this->createForm(new ExistingCustomerCheckoutType(), $entity, array(
            'action' => $this->generateUrl('miles_apart_basket_checkout_shipping'),
            'method' => 'POST',
            'attr' => array('class' => '')
        ));

        $form->add('submit', 'submit', array('label' => 'Register', 'attr' => array(
                        'class' => 'button large-12')));

        return $form;
    }

    /*
    * Function for new customers to add shipping address and select postage
    */
    public function checkoutshippingAction(Request $request)
    {
        //Get shop location settings from session 
        $session = $request->getSession();
        
        //Check if the basket session exists
        if ($session->has('basket')) {
            //Get the basket from the session
            $basket = $session->get('basket');
        } else{
            //Forward to home as basket doesn't exist
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Get entity manager
        $em = $this->getDoctrine()->getManager();

        //Merge the basket so we have acces to it's methods
        $basket = $em->merge($basket);

        //Create the new customer checkout and existing customer checkout forms
        $customer_order = new CustomerOrder();

        //Get the postage options
        $postage_options = $this->getCustomerOrderPostageOptions($basket);

        //Check if user is logged in 
        if($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            //User is logged in so create the existing customer delivery address form
            $checkout_delivery_form = $this->createCheckoutExistingCustomerDeliveryForm($customer_order, $postage_options);

            //And render the page
            return $this->render('MilesApartBasketBundle::checkout_shipping_existing_customer.html.twig', array(
                'basket' => $basket,
                'form' => $checkout_delivery_form->createView(),
                'postage_options' => $postage_options,
                'submitted' => false
            ));  

        //Not logged in
        } else {
            //User is not logged in so create the new customer checkout delivery form
            $checkout_delivery_form = $this->createCheckoutDeliveryForm($customer_order, $postage_options);
           
            //And render.
            return $this->render('MilesApartBasketBundle::checkout_shipping_new_customer.html.twig', array(
                'basket' => $basket,
                'form' => $checkout_delivery_form->createView(),
                'postage_options' => $postage_options,
                'submitted' => false
            ));  
        } 
    }
    
    /**
    * Creates a form for new customers delivery and billing address
    *
    * @param DeliveryAddress $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCheckoutDeliveryForm(CustomerOrder $entity, $postage_options)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new NewCustomerCheckoutDeliveryType($postage_options, $em), $entity, array(
            'action' => $this->generateUrl('miles_apart_basket_checkout_payment'),
            'method' => 'POST',
            'attr' => array('class' => 'miles_apart_basket_checkout_shipping_select_postcode')
        ));

        $form->add('submit', 'submit', array('label' => 'Continue to Payment', 'attr' => array(
                        'class' => 'button small-12')));

        return $form;
    }

    /**
    * Creates a form for existing customers delivery and billing address
    *
    * @param DeliveryAddress $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCheckoutExistingCustomerDeliveryForm(CustomerOrder $entity, $postage_options)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ExistingCustomerCheckoutDeliveryType($postage_options, $em), $entity, array(
            'action' => $this->generateUrl('miles_apart_basket_checkout_payment'),
            'method' => 'POST',
            'attr' => array('class' => 'miles_apart_basket_checkout_shipping_select_postcode')
        ));

        $form->add('submit', 'submit', array('label' => 'Continue to Payment', 'attr' => array(
                        'class' => 'button small-12')));

        return $form;
    }

    /* 
    * Function to query PAF for postal address info 
    */
    public function getpostcodeaddressesAction(Request $request) {
         //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {
            $response = "false";
        }
       
        //Set the postcode
        $postcode = 'LS18 5SF';
       
        //Set the account details (use test for the moment).
        $account = "test"; 
        $password = "test"; 

        //Crete the url.
        $URL = "http://ws1.postcodesoftware.co.uk/lookup.asmx/getAddress?account=" . $account . "&password=" . $password . "&postcode=" . $postcode;
        
        //Get the xml file
        $xml = simplexml_load_file(str_replace(' ','', $URL));

        // If an error has occured show message
        if ($xml->ErrorNumber <> 0)  { 
            $error = true; 
            $error_message = "<span class=\"text\">" . $xml->ErrorMessage . "</span>"; 
            $chunks = false;
        } else { 
            // Split up premise data
            $chunks = explode (";", $xml->PremiseData);   
        }

        //Get shop location settings from session 
        $session = $request->getSession();

       return $this->render('MilesApartBasketBundle:CheckoutComponents:shipping_postcode_select.html.twig', array(
        'chunks' => $chunks,
        'xml' =>$xml,
        ));
    }

    /* 
    * Function for handling checkout form (redirecting to new customer or existong)
    */
    public function checkoutpaymentAction(Request $request)
    {
        //Check if the user is logged in
        if($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            //Redirect to checkout existing customer
            $response = $this->forward('MilesApartBasketBundle:Checkout:checkoutexistingcustomerpayment', array(
                'request'  => $request,
            ));

        //Not logged in
        } else {
            //Redirect to checkout new customer
            $response = $this->forward('MilesApartBasketBundle:Checkout:checkoutnewcustomerpayment', array(
                'request'  => $request, 
            ));
        }
        
        return $response;
    }
   
    /* 
    * Function for handling new customer checkout form
    */
    public function checkoutnewcustomerpaymentAction(Request $request)
    {
        //Get session 
        $session = $request->getSession();

        //Check if the basket session exists
        if ($session->has('basket')) {
            
            //Get the basket from the session
            $basket = $session->get('basket');

            //Get entity manager
            $em = $this->getDoctrine()->getManager();

            //Merge the basket so we have acces to it's methods
            $basket = $em->merge($basket);
        
        //Basket session doens't exist
        } else {
            
            //Basket session does not exists, return the user to the shop page
            $basket = FALSE;
            
            //Forward to home as basket doesn't exist
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        /***********
        * Handle the new customer form
        ***********/
        //Create the new customer order
        $customer_order = new CustomerOrder();

        //Set the customer order source as MA website
        $customer_order->setCustomerOrderSource(
            $em->getRepository('MilesApartAdminBundle:CustomerOrderSource')->findOneBy(
            array( 'id' => 1 )
        ));

        //Get the postage options
        $postage_options = $this->getCustomerOrderPostageOptions($basket);

        //Create the form
        $checkout_delivery_form = $this->createCheckoutDeliveryForm($customer_order, $postage_options);

        //Handle the request
        $checkout_delivery_form->handleRequest($request);

        //Test if form is valid
        if ($checkout_delivery_form->isValid()) {

            //It is so process form
            $em = $this->getDoctrine()->getManager();

            //Set values from form data
            $customer_order->setSessionId($session->getId());
            $customer_order->setCustomerOrderState(
                $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                array( 'id' => 1 )
            ));

            //If there's a seperate billing address
            if($customer_order->getDeliveryAddress()->getCustomerAddressIsBilling() == false) {

                //Add the billing address
                $billing_address = new CustomerAddress();
                $billing_address = $checkout_delivery_form->get('billing_address')->getData();
                
                //Set the billing address
                $customer_order->setBillingAddress($billing_address);

                //Set the customer id on the address
                $billing_address->setCustomer($customer_order->getCustomer());
            } else {
                //Set the delivery address as the billing address
                $customer_order->setBillingAddress($customer_order->getDeliveryAddress());
                $customer_order->getDeliveryAddress()->setCustomer($customer_order->getCustomer());
            }

            //Set the postage band dispatch logistics
            //Get the pbdl from the database
            $customer_order->setDeliveryOption(
                $em->getRepository('MilesApartAdminBundle:PostageBandDispatchLogistics')->findOneBy(
                array( 'id' => $checkout_delivery_form->get('delivery_option')->getData())
            ));

            //Check if business customer rep is set, and add
            if($checkout_delivery_form->get('customer')->get('business_customer')->get('business_customer_name')->getData() != null) {

                //Create business customer representative
                $business_customer_representative = new BusinessCustomerRepresentative();
                $business_customer_representative = $checkout_delivery_form->get('customer')->get('business_customer')->get('business_customer_representative')->getData();
                $business_customer_representative->setBusinessCustomer($customer_order->getCustomer()->getBusinessCustomer());
                $customer_order->getCustomer()->getBusinessCustomer()->addBusinessCustomerRepresentative($business_customer_representative);

                //Set the buiness customer representative customer order 
                $business_customer_representative_customer_order = new BusinessCustomerRepresentativeCustomerOrder();
                $business_customer_representative_customer_order->setCustomerOrder($customer_order);
                $business_customer_representative_customer_order->setBusinessCustomerRepresentative($business_customer_representative);
                $customer_order->setBusinessCustomerRepresentativeCustomerOrder($business_customer_representative_customer_order);
                $business_customer_representative->setBusinessCustomerRepresentativeCustomerOrder($business_customer_representative_customer_order);
                $em->persist($business_customer_representative_customer_order);

                //Set the VAT invoice option to true
                $customer_order->getCustomer()->setVatInvoiceOption(true);
            }

            //For each product add it to the customer order
            foreach($basket->getPurchasingBasketProduct() as $basket_product) {
                $customer_order_product = new CustomerOrderProduct();
                $customer_order_product->setProduct($basket_product->getProduct());
                $customer_order_product->setCustomerOrderProductQuantity($basket_product->getBasketProductQuantity());
                $customer_order_product->setCustomerOrder($customer_order);
                $customer_order->addCustomerOrderProduct($customer_order_product);
                $em->persist($customer_order_product);
            }
            
            //Add the total to be paid
            $customer_order->setCustomerOrderShippingPaid($this->getCustomerOrderShippingTotal($customer_order));
            //Persist &flush changes
            $em->persist($customer_order);
            $em->flush();

            //Put the customer order in the session
            $em->detach($customer_order);
            $session->set('customer_order', $customer_order);
           
            //Call the payment page
            return $this->checkoutmakepaymentAction($request);

        //Form is not valid
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('checkout-error', 'There has been a problem submitting the shipping form, please check the details below.');
        }

          return $this->render('MilesApartBasketBundle::checkout_shipping_new_customer.html.twig', array(
            'basket' => $basket,
            'form' => $checkout_delivery_form->createView(),
            'postage_options' => $postage_options,
            'submitted' => $submitted,
            //'customer_order' => $customer_order,
        ));
    }

    /* 
    * Function for handling new customer checkout form
    */
    public function checkoutexistingcustomerpaymentAction(Request $request)
    {
        //Get shop location settings from session 
        $session = $request->getSession();

        //Check if the basket session exists
        if ($session->has('basket')) {
            //Get the basket from the session
            $basket = $session->get('basket');

            //Get entity manager
            $em = $this->getDoctrine()->getManager();

            //Merge the basket so we have acces to it's methods
            $basket = $em->merge($basket);
        } else{
            //Basket session does not exists, return the user to the shop page
            $basket = FALSE;
            //Forward to home as basket doesn't exist
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        /***********
        * Handle the existing customer form
        ***********/
        //Create the customer order
        $customer_order = new CustomerOrder();

        //Set the source as MA website
        $customer_order->setCustomerOrderSource(
                $em->getRepository('MilesApartAdminBundle:CustomerOrderSource')->findOneBy(
                array( 'id' => 1 )
            ));

        //Set the customer of the order
        $customer_order->setCustomer($this->container->get('security.context')->getToken()->getUser()->getCustomer());

        //Get the postage options
        $postage_options = $this->getCustomerOrderPostageOptions($basket);

        //Create the form
        $checkout_delivery_form = $this->createCheckoutExistingCustomerDeliveryForm($customer_order, $postage_options);

        //Handle the request
        $checkout_delivery_form->handleRequest($request);

        //Test if form is valid
        if ($checkout_delivery_form->isValid()) {

            //It is so process form
            $em = $this->getDoctrine()->getManager();

            //Set values from form data
            $customer_order->setSessionId($session->getId());
            $customer_order->setCustomerOrderState(
                $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                array( 'id' => 1 )
            ));


            //Check if the delivery address id is true (the delivery address is existing)
            if($checkout_delivery_form->get('delivery_address_id')->getData() != 0) {
                
                //Set the customer deliver address as the id of selected address
                $customer_order->setDeliveryAddress(
                    $em->getRepository('MilesApartAdminBundle:CustomerAddress')->findOneBy(
                        array( 'id' =>  $checkout_delivery_form->get('delivery_address_id')->getData())
                    )
                );
                
            } else {
                //There is no address selected, so add a new one
                //Add the delivery address
                $delivery_address = new CustomerAddress();
                $delivery_address = $checkout_delivery_form->get('delivery_address')->getData();
                
                //Set the delivery address
                $customer_order->setDeliveryAddress($delivery_address);
                
                //Set the customer id on the address
                $delivery_address->setCustomer($customer_order->getCustomer());
            }

            //PROBLEM IS WITH THIS LINE!!!!!!!! - Checks if the delivery address has had billing address tick removed
            if($checkout_delivery_form->get('delivery_address')->get('customer_address_is_billing')->getData() == false) {

                //Check if the billing address id is true (the delivery address is existing)
                if($checkout_delivery_form->get('billing_address_id')->getData() != 0) {
                    
                    //Set the customer deliver address as the id of selected address
                    $customer_order->setBillingAddress(
                        $em->getRepository('MilesApartAdminBundle:CustomerAddress')->findOneBy(
                        array( 'id' =>  $checkout_delivery_form->get('billing_address_id')->getData())));
                   
                //Billing address is new
                } else {
                    //There is no address selected, so add a new on
                    //Add the billing address
                    $billing_address = new CustomerAddress();
                    $billing_address = $checkout_delivery_form->get('billing_address')->getData();
                    
                    //Set the billing address
                    $customer_order->setBillingAddress($billing_address);

                    //Set the customer id on the address
                    $billing_address->setCustomer($customer_order->getCustomer());
                }

            //Billing address is the same as delivery address
            } else {
                //Set the delivery address as the billing address
                $customer_order->setBillingAddress($customer_order->getDeliveryAddress());
                $customer_order->getDeliveryAddress()->setCustomer($customer_order->getCustomer());
            }

            //Set the postage band dispatch logistics
            //Get the pbdl from the database
            $customer_order->setDeliveryOption(
                $em->getRepository('MilesApartAdminBundle:PostageBandDispatchLogistics')->findOneBy(
                array( 'id' => $checkout_delivery_form->get('delivery_option')->getData())
            ));

            //Check if business customer rep, set the extra field if so.
            $business_customer_representative = $this->container->get('security.context')->getToken()->getUser()->getBusinessCustomerRepresentative();
            if($business_customer_representative != null) {

                //Set the buiness customer representative customer order
                $business_customer_representative_customer_order = new BusinessCustomerRepresentativeCustomerOrder();
                $business_customer_representative_customer_order->setCustomerOrder($customer_order);
                $business_customer_representative_customer_order->setBusinessCustomerRepresentative($business_customer_representative);
                $customer_order->setBusinessCustomerRepresentativeCustomerOrder($business_customer_representative_customer_order);
                $em->persist($business_customer_representative_customer_order);
            }

            //For each item add it to the customer order
            foreach($basket->getPurchasingBasketProduct() as $basket_product) {
                $customer_order_product = new CustomerOrderProduct();
                $customer_order_product->setProduct($basket_product->getProduct());
                $customer_order_product->setCustomerOrderProductQuantity($basket_product->getBasketProductQuantity());
                $customer_order_product->setCustomerOrder($customer_order);
                $customer_order->addCustomerOrderProduct($customer_order_product);
                $em->persist($customer_order_product);
            }

            //Insert the shipping total into the customer order table
            $customer_order->setCustomerOrderShippingPaid($this->getCustomerOrderShippingTotal($customer_order));

            //Persist and flush the customer order
            $em->persist($customer_order);
            $em->flush();

            //Put the customer order in the session
            $em->detach($customer_order);
            $session->set('customer_order', $customer_order);

            //Call the payment page
            return $this->checkoutmakepaymentAction($request);
        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('checkout-error', 'There has been a problem submitting the form, please check the details below.');
        }

        //Render the page
        return $this->render('MilesApartBasketBundle::checkout_shipping_existing_customer.html.twig', array(
            'basket' => $basket,
            'form' => $checkout_delivery_form->createView(),
            'postage_options' => $postage_options,
            'submitted' => $submitted,
            //'customer_order' => $customer_order,
        ));
    }
       
    /* 
    * Function for Payment page
    */
    public function checkoutmakepaymentAction(Request $request)
    {
        //Require braintree setup code
        require_once('./braintree-php-3.16.0/lib/Braintree.php'); 

        //Set up Braintree
        \Braintree_Configuration::environment('sandbox');
        \Braintree_Configuration::merchantId('6mb2mcgm9nxnqfv4');
        \Braintree_Configuration::publicKey('q2hpnvsyk6r5ztx3');
        \Braintree_Configuration::privateKey('85b7ea604b309de7e0c8401c9e2b3df3');

        //Generate the client token
        $clientToken = \Braintree_ClientToken::generate();

        //Get session 
        $session = $request->getSession();
        
        //Check if the basket session exists
        if ($session->has('basket')) {
            //Get the basket from the session
            $basket = $session->get('basket');

            //Get entity manager
            $em = $this->getDoctrine()->getManager();

            //Merge the basket so we have acces to it's methods
            $basket = $em->merge($basket);
        } else{
            //Basket session does not exists, return the user to the shop page
            $basket = FALSE;
            //Forward to home as basket doesn't exist
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Get the customer order form the session
        $customer_order = $session->get('customer_order');

        //Merge the customer order so we have access to it's methods
        $customer_order = $em->merge($customer_order);

        //Render the page
        return $this->render('MilesApartBasketBundle::checkout_payment.html.twig', array(
            'basket' => $basket,
            'customer_order' => $customer_order,
            'clientToken' => $clientToken,
            'submitted' => false
        ));
        
    }

    /*
    * Function to show checkout success
    */
    public function checkoutcompleteAction(Request $request)
    {
        //Get the session
        $session = $request->getSession();

        //Check if the basket session exists
        if ($session->has('customer_order')) {
            //Get the basket from the session
            $customer_order = $session->get('customer_order');

            //Get entity manager
            $em = $this->getDoctrine()->getManager();

            //Merge the basket so we have acces to it's methods
            $customer_order = $em->merge($customer_order);
        } else{
            //Basket session does not exists, return the user to the shop page
            $customer_order = FALSE;
            //Forward to home as basket doesn't exist
            return $this->redirect($this->generateUrl('miles_apart_public_homepage'));
        }

        //Include the braintree setup
        require_once('./braintree-php-3.16.0/lib/Braintree.php'); 

        //Set up Braintree
        \Braintree_Configuration::environment('sandbox');
        \Braintree_Configuration::merchantId('6mb2mcgm9nxnqfv4');
        \Braintree_Configuration::publicKey('q2hpnvsyk6r5ztx3');
        \Braintree_Configuration::privateKey('85b7ea604b309de7e0c8401c9e2b3df3');

        //Try to process the payment
        $result = \Braintree_Transaction::sale([
            'amount' => $customer_order->getGrandTotal(),
            'paymentMethodNonce' => $request->query->get('payment_method_nonce'),
            'options' => [
                'submitForSettlement' => True
            ]
        ]);
        
       
        //If payment success, show the sucess page.
        if($result->success == TRUE) {
            
            $em->refresh($customer_order);
            //Add the total payment value to the customer order table
            $customer_order->setCustomerOrderTotalPricePaid($customer_order->getGrandTotal());

            //Call checout success 
            return $this->checkoutSuccess($customer_order);

        //Payment was not successful  
        } else {
           
            //Set the flashbag with the error message 
             $this->get('session')->getFlashBag()->set('payment-error', $result->message);

            //Redirect back to payment
            return $this->checkoutmakepaymentAction($request);
        }
                
        
    }

    //Process successful checkout (update customer order state to paid, send emails)
    public function checkoutSuccess($customer_order)
    {
         $logger = $this->get('logger');
        $logger->info('I just got the logger add update oem');


        //Get entity manager
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        //Send the Miles Apart email
        $this->sendMilesApartOrderEmail($customer_order);
$logger->info('I just got the logger add update oemail');
        //Send the customer email
        $this->sendCustomerOrderEmail($customer_order);

        //Merge the basket so we have acces to it's methods
        $customer_order = $em->merge($customer_order);

        //Get the 'paid' customer order state
        $customer_order_state = $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneById(2);
        
        //Update the customer order with the new state
        $customer_order->setCustomerOrderState($customer_order_state);       

        //Update basket in db to say complete
        $basket = $session->get('basket');
        //Merge the basket so we have access to it's methods
        $basket = $em->merge($basket);
        $basket->setBasketCheckedOut(TRUE);
        $em->flush();

        //Update other channels
        //Amazon
        //Send the array to Amazon Upload script
        if(count($customer_order->getCustomerOrderProduct()) > 1) {
            $amazon_response = $this->forward('MilesApartSellerBundle:Amazon:uploadAmazonMultipleProductNewQty', array(
                'customer_order_products'  => $customer_order->getCustomerOrderProduct(),
            
            ));
        } else {
            $amazon_response = $this->forward('MilesApartSellerBundle:Amazon:uploadAmazonProductNewQty', array(
                'customer_order_product'  => $customer_order->getCustomerOrderProduct()[0],
            
            ));
        }
        
        
        //Unset basket and customer order session
        $session->remove('basket');
        $session->remove('customer_order');

        $session->set('customer_for_post_order_registration', $customer_order->getCustomer());

        //Redirect to thank you page
        return $this->render('MilesApartBasketBundle::checkout_complete.html.twig', array(
            'customer_order' => $customer_order
            ));
    }


    function sendCustomerOrderEmail($customer_order)
    {
        //Set up the mailer
        $mailer = $this->container->get('swiftmailer.mailer.weborders_mailer');

       //Get entity manager
        $em = $this->getDoctrine()->getManager();
        $customer_order = $em->merge($customer_order);

        $email_address = $customer_order->getCustomerOrderEmailAddress();
        
        //Get the supplier email address.
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Miles Apart Order Confirmation')
            ->setFrom(array('customersupport@miles-apart.com' => 'Miles Apart'))
            ->setTo($email_address)
            ->setBody(
                $this->renderView(
                    'MilesApartPublicBundle:Emails:order_confirmation_email.html.twig',
                    array('customer_order' => $customer_order)
                    
                )

            )
        ;

        //Avoid localhost issues
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        //Send the email
        $mailer->send($message);       

        return true;
    }

    function sendMilesApartOrderEmail($customer_order)
    {
        $mailer = $this->container->get('swiftmailer.mailer.weborders_mailer');
        //Get entity manager
        $em = $this->getDoctrine()->getManager();
        $customer_order = $em->merge($customer_order);

        //Set the email address.
        $email_address = 'weborders@miles-apart.com';
        
        //Get the supplier email address.
        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('New Customer Order')
            ->setFrom(array('weborders@miles-apart.com' => 'Miles Apart Web Orders'))
            ->setTo($email_address)
            ->setBody(
                $this->renderView(
                    'MilesApartStaffBundle:Pickpack:order_confirmation_email.html.twig',
                    array('customer_order' => $customer_order)
                )

            )
        ;
        
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');
        
        $mailer->send($message);
        

        return true;
    }

    /*
    *
    * Function to get the postage costs
    * Retruns array with 1st and 2nd class postage options
    */
    function getCustomerOrderPostageOptions($basket)
    {
        //Get entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the order
        $basket = $em->merge($basket);

        //Query the model with the order, height width, depth and weight
        $postage_band = $em->getRepository('MilesApartAdminBundle:PostageBand')->findPostageBandBySizes($basket->getBasketLargestWidth(), $basket->getBasketLargestLength(), $basket->getBasketLargestDepth(), $basket->getBasketTotalWeight());

       
        foreach($postage_band[0]->getPostageBandDispatchLogistics() as $postage_option) {
            
            //If first class
            if($postage_option->getPostageType()->getId() == 1) {
                $first_class_postage = $postage_option->getPostageBandPrice();
            
            //If second class
            } else if($postage_option->getPostageType()->getId() == 2) {
                $second_class_postage = $postage_option->getPostageBandPrice();
            }
        }
        
        //Check if order value is over £30
        if($basket->getBasketTotalPrice() > 30) {
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

    //Get shipping total
    function getCustomerOrderShippingTotal($customer_order)
    {

        $shipping_total = 0;

        //First get the pbdl that has been selected
        $pbdl = $customer_order->getDeliveryOption();

        //Check if the order is over £30
        if($customer_order->getCustomerOrderTotalPrice() > 30){

            //Check if the delivery option is 1st or second class
            if($pbdl->getPostageType()->getId() == 1) {
                //First class so minus second class from the cost
                //Get the second class cost
                $em = $this->getDoctrine()->getManager();

                $entity = $em->getRepository('MilesApartAdminBundle:PostageBandDispatchLogistics')->findOneBy(array('postage_band' =>$pbdl->getPostageBand(), 'postage_type'=>2));
                
                $shipping_total = $pbdl->getPostageBandPrice() - $entity->getPostageBandPrice(); 
            } else {
                $shipping_total = 0;
            }
        } else {
            $shipping_total = $pbdl->getPostageBandPrice();
        }
        
        return $shipping_total;

    }

    /**
    * Creates a form to create a new contact us message.
    *
    * @param Contact Us Message $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPostOrderCreateAccountForm(FosUser $entity)
    {
        $form = $this->createForm(new PostOrderCreateAccountType(), $entity, array(
           
        ));

        $form->add('submit', 'submit', array('label' => 'Register', 'attr' => array(
                        'class' => 'btn btn-primary large-offset-3 large-6')));

        return $form;
    }

    function postordercreateaccountAction()
    {
        $em = $this->getDoctrine()->getManager();

        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Help & Support", $this->get("router")->generate("miles_apart_public_help_and_support"));
        $breadcrumbs->addItem("Contact Us", $this->get("router")->generate("miles_apart_public_contact_us"));

        $entity = new FosUser();

        //This is where i need to set the prepopulated for variables
        //Get the session to get the customer info
        $session = $this->get('session');

        $customer = $session->get('customer_for_post_order_registration');

        $em->merge($customer);

        //Figure out if personal or business bustomer rep
        if($customer->getPersonalCustomer() != null) {
            //Set the attributes of fos user
            
            $entity->setEmail($customer->getPersonalCustomer()->getPersonalCustomerEmailAddress());
            $entity->setPersonalCustomer($customer->getPersonalCustomer());
        }

        ladybug_dump($entity);


        
        $form = $this->container->get('fos_user.registration.form', $entity);
        //$formHandler = $this->container->get('fos_user.registration.form.handler');
        //$confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
            
        //Set the prepopulated values
        //Figure out if personal or business bustomer rep
        if($customer->getPersonalCustomer() != null) {
            $form->get('email')->setData($customer->getPersonalCustomer()->getPersonalCustomerEmailAddress());
            $form->get('personal_customer')->setData($customer->getPersonalCustomer());
        } else {
            $form->get('email')->setData($customer->getPersonalBusinessCustomer()->getBusinessCustomerRepresentative()->getBusinessCustomerRepresentativeEmailAddress());
            $form->get('business_customer')->setData($customer->getBusinessCustomer());
        }
        return $this->render('MilesApartBasketBundle::post_order_create_account.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
        
    }

    //Need to figure out how to make this ajax
     public function postordercreateaccountsubmitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Help & Support", $this->get("router")->generate("miles_apart_public_help_and_support"));
        $breadcrumbs->addItem("Contact Us", $this->get("router")->generate("miles_apart_public_contact_us"));

        $entity = new FosUser();

         //This is where i need to set the prepopulated for variables
        //Get the session to get the customer info
        $session = $this->get('session');

        $customer = $session->get('customer_for_post_order_registration');

        $em->merge($customer);

        //Figure out if personal or business bustomer rep
        if($customer->getPersonalCustomer() != null) {
            //Set the attributes of fos user
            
            $entity->setEmail($customer->getPersonalCustomer()->getPersonalCustomerEmailAddress());
            $entity->setPersonalCustomer($customer->getPersonalCustomer());
        }

        $form = $this->createPostOrderCreateAccountForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();


            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('public-success', 'Thank you. Your account was created successfully. You can now login to see your order.');

            return $this->redirect($this->generateUrl('miles_apart_public_login_or_register'));

        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('public-error', 'Sorry, there has been a problem creating your account. Please check the fields highlighted red.');

            return $this->render('MilesApartPublicBundle:Page:post_order_create_account.html.twig', array(
                'submitted' => $submitted,
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }
    }
}
