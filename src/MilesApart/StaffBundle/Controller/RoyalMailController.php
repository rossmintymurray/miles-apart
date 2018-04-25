<?php

// src/MilesApart/StaffBundle/Controller/RoyalMailController.php

namespace MilesApart\StaffBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use MilesApart\AdminBundle\Entity\RoyalMailShipment;
use MilesApart\AdminBundle\Entity\ShippingManifest;

class RoyalMailController extends Controller
{
    /*************************************************
    * Royal Mail controller displays the functions and pages for completing orders and generating shipments and labels.
    *************************************************/

    public function completeShipmentAction($order_id, Request $request) 
    {
        //Get the order details
 $logger = $this->get('logger');
        $logger->info('I just got the logger add update price');
        //Get the entity manager
        $em = $this->getDoctrine()->getManager();
        
        //Find order
        $order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($order_id);

        //Check if a tracking ID exists in royal mail shipment (has a successful shipment been created previously?)
        $existing_shipments = $em->getRepository('MilesApartAdminBundle:RoyalMailShipment')->findExistingShipmentsByOrderId($order_id);
       $logger->info('I just got the logger add update price 2');
        if($existing_shipments != null) {
            foreach($existing_shipments as $existing_shipment) {
                $logger->info('I just got the logger add update price 3');
                if ($existing_shipment->getRoyalMailShipmentNumber() != null) {
                    $logger->info('I just got the logger add update price 4');
                    //Shipment has already been created so we need to leave script and notify user.
                    $ajax_response = array(
                       'allocated' => false,
                       'existing' =>true
                    );
                    
                    return new JsonResponse(
                                $ajax_response 
                    );
                }
            } 
        }
        //Check if there have been new values for the dimensions/weight set through
        //Get the $_POST array.
        if ($request->isXMLHttpRequest()) {
            $response = $_POST;
            //$response = new JsonResponse($r);
        } else {

            $response = "false";
        }
$logger->info('I just got the logger add update price2');
        //Get the barcode and search query from the request
        $new_weight = $response["newWeight"];
        $new_height = $response["newHeight"];
        $new_width = $response["newWidth"];
        $new_depth = $response["newDepth"];

        $logger->info('I just got the logger add update price3'. $new_weight .'bo'. $new_height . $new_width . $new_depth);

        if($new_weight != null && $new_height != null && $new_width != null && $new_depth != null) {
            $logger->info('I just got the logger add update price4');
            //New weight and dimensions have been added - get box for these dimensions
            $postage_band = $em->getRepository('MilesApartAdminBundle:PostageBand')->findPostageBandBySizes($new_width, $new_height, $new_depth, $new_weight);

            $logger->info($postage_band[0]->getId());
            foreach($postage_band[0]->getPostageBandDispatchLogistics() as $postage_option) {
                
                //If first class
                if($postage_option->getPostageType()->getId() == 1) {
                    $first_class_option = $postage_option;
                
                //If second class
                } else if($postage_option->getPostageType()->getId() == 2) {
                    $second_class_option = $postage_option;
                }
            }


            //Then persist this box against the customer order 
            //Cjeck the existing order pbdl to see if first or send class 
            if($order->getDeliveryOption()->getPostageType()->getId() == 1) {
                   $logger->info($first_class_option->getId());

                //Postage is first class
                $order->setDeliveryOption($first_class_option);
            } else if($order->getDeliveryOption()->getPostageType()->getId() == 2) {
                $logger->info($second_class_option->getId());
                //Postage is second class
                $order->setDeliveryOption($second_class_option);
            }

            $em->flush();
        }

    	

        //Call createShipmentAPICall sending the order object, set API respinse to the the return
        $create_shipment_API_call_response = $this->createShipmentAPICall($order);
 			$logger->info("this is weree i syart logging");
		//Create shipment entity and populate data
		$shipment = new RoyalMailShipment();

		//Set the shipment raw xml
		$shipment->setRoyalMailCreateShipmentResponseRawXml(addslashes($create_shipment_API_call_response['response']));
		
		//Set the custome order shipment ref
		$shipment->setCustomerOrder($order);	
$logger->info("this is weree i syart logging- 2");
        //Test if it has been allocated
        if($create_shipment_API_call_response['allocated'] == TRUE) {
$logger->info("this is weree i syart logging y ");
        	//Update the dabase to say it has been allocated
        	$shipment->setRoyalMailShipmentState($em->getRepository('MilesApartAdminBundle:RoyalMailShipmentState')->findOneById(1));
	        
	        //Check if createShipmentAPICall returned allocated.
	      	if(array_key_exists('completedShipmentInfo', $create_shipment_API_call_response['array'][0]['createShipmentResponse'])) {
	        	//Set the allocated variabe (this will be "Allocated" if successful)
	        	$allocated = $create_shipment_API_call_response['array'][0]['createShipmentResponse']['completedShipmentInfo']['status']['status']['statusCode']['code'];
	        	//Set the shipment number (to be used in printLabel)
	        	$shipment_number = $create_shipment_API_call_response['array'][0]['createShipmentResponse']['completedShipmentInfo']['allCompletedShipments']['completedShipments']['shipments']['shipmentNumber'];
	       		
	       		//Set the shipment number
				$shipment->setRoyalMailShipmentNumber($shipment_number);

	        } else {
                $logger->info("this is weree i syart logging n ");
				//Error contained in the create shipment response footer
	        	$allocated = FALSE;
	        	$shipment_number = FALSE;
	        	//Save errors to the db
		        //First match the errors recevied to those on the database.
		       
                //Update the dabase to say it has been allocated
                $shipment->setRoyalMailShipmentState($em->getRepository('MilesApartAdminBundle:RoyalMailShipmentState')->findOneById(6));
$logger->info("this is weree i syart logging n 2");
		        //Check if errors exist
		        if(array_key_exists('errors', $create_shipment_API_call_response['array'][0]['createShipmentResponse']['integrationFooter'])) {
$logger->info("this is weree i syart logging n 3");		        	
		        	//Check if single errors
		        	if(array_key_exists('errorCode', $create_shipment_API_call_response['array'][0]['createShipmentResponse']['integrationFooter']['errors']['error'])) {
$logger->info("this is weree i syart logging n 4");
			        	//Only one error
				    	//Find the error
			        	$error_entity = $em->getRepository('MilesApartAdminBundle:RoyalMailShipmentWarning')->findOneBy(array('royal_mail_shipment_warning_code' => $create_shipment_API_call_response['array'][0]['createShipmentResponse']['integrationFooter']['errors']['error']['errorCode']));
		$logger->info("this is weree i syart logging n 5");	        	
			        	//Add the error to the db table (link to shipment)
                        if($error_entity != null) {
			        	    $shipment->addRoyalMailShipmentWarning($error_entity);
                        } 
$logger->info("this is weree i syart logging n 6");
				    } else { 
				    	

			        	//Iterate over errors
				        foreach($create_shipment_API_call_response['array'][0]['createShipmentResponse']['integrationFooter']['errors']['error'] as $response_error) {
				        	//Find the error$logger->info("this is weree i syart logging n 7");
				        	$error_entity = $em->getRepository('MilesApartAdminBundle:RoyalMailShipmentWarning')->findOneBy(array('royal_mail_shipment_warning_code' => $response_error['errorCode']));
				    	
				        	//Add the error to the db table (link to shipment)
				        	$shipment->addRoyalMailShipmentWarning($error_entity);
				        }
				    }
			    }
			    
			   $logger->info("this is weree i syart logging n7");
			    if(array_key_exists('warnings', $create_shipment_API_call_response['array'][0]['createShipmentResponse']['integrationFooter'])) {
$logger->info("this is weree i syart logging n 8");
                    if(array_key_exists('warning', $create_shipment_API_call_response['array'][0]['createShipmentResponse']['integrationFooter']['warnings'])) {
                        $logger->info("this is weree i syart logging n 89");
			    	if(array_key_exists('warningCode', $create_shipment_API_call_response['array'][0]['createShipmentResponse']['integrationFooter']['warnings']['warning'])) {
$logger->info("this is weree i syart logging n 9");
			    		//Only one warning
				    	//Find the warning
			        	$error_entity = $em->getRepository('MilesApartAdminBundle:RoyalMailShipmentWarning')->findOneBy(array('royal_mail_shipment_warning_code' => $create_shipment_API_call_response['array'][0]['createShipmentResponse']['integrationFooter']['warnings']['warning']['warningCode']));

			        	//Add the error to the db table (link to shipment)
			        	$shipment->addRoyalMailShipmentWarning($error_entity);
			        } else {
$logger->info("this is weree i syart logging n 9 y");

				        //Iterate over warnings
				        foreach($create_shipment_API_call_response['array'][0]['createShipmentResponse']['integrationFooter']['warnings']['warning'] as $response_error) {
				        	//Find the error
				        	

				        	$error_entity = $em->getRepository('MilesApartAdminBundle:RoyalMailShipmentWarning')->findOneBy(array('royal_mail_shipment_warning_code' => $response_error['warningCode']));
				        	//Add the error to the db table (link to shipment)
				        	
				        	$shipment->addRoyalMailShipmentWarning($error_entity);
				        	
				        }
				    }
                }
			    }

	        }

            
	    } else {
            $logger->info("this is weree i syart logging n");
	    	//Error contained in the fault envelope
	    	$allocated = FALSE;
	        $shipment_number = FALSE;

	        //Save errors to the db
	        //First match the errors recevied to those on the database.
	        //Iterate over errors 
            //Update the dabase to say it has been allocated
            $shipment->setRoyalMailShipmentState($em->getRepository('MilesApartAdminBundle:RoyalMailShipmentState')->findOneById(6));



	    }

        //If it did, i need to create label API call
        if($allocated != FALSE) {

        	//Update the dabase to say it has been printed
        	$shipment->setRoyalMailShipmentState($em->getRepository('MilesApartAdminBundle:RoyalMailShipmentState')->findOneById(2));
	        
$logger->info("this is weree i syart logging y676");
        	//Call createShipmentAPICall sending the order object, set API respinse to the the return
        	$create_label_API_call_response = $this->createLabelAPICall($shipment_number, $order);
$logger->info("this is weree i syart logging y677");
        	//Set the shipment label raw xml
			$shipment->setRoyalMailCreateLabelResponseRawXml(addslashes($create_label_API_call_response['response']));
		$logger->info("this is weree i syart logging y678");
			
            if(array_key_exists("label", $create_label_API_call_response['array'][0]['printLabelResponse'])) {
        	   $label = $create_label_API_call_response['array'][0]['printLabelResponse']['label'];

$logger->info("this is weree i syart logging y679");
            	//Save the label as PDF file
            	$data = base64_decode($label);
    			file_put_contents('royal_mail_labels/'.$order_id.'.pdf',$data);
    $logger->info("this is weree i syart logging y671088");
    			
    			//Convert pdf to jpg for automatic printing.
    			$im = new \Imagick();
    			$im->setResolution(300,300);
    			$im->readimage('royal_mail_labels/'.$order_id.'.pdf[0]'); 
    			$im->setImageFormat('jpg');
    			file_put_contents('royal_mail_labels/'.$order_id.'.jpg',$im);
            } else {
                $label = FALSE;
            }

        } else {
        	$label = FALSE;
        	$create_label_API_call_response = FALSE;
        }
      
$logger->info("this is weree i syart logging y6710");
        //If it didn't I need to show/return error code 

        //Render the page from template (this will be changed to JSON object to return status via AJAX)
        /*return $this->render('MilesApartStaffBundle:RoyalMail:response.html.twig', array(
           'create_shipment_API_call_response' => $create_shipment_API_call_response,
           'allocated' => $allocated,
           'shipment_number' => $shipment_number,
           'create_label_API_call_response' => $create_label_API_call_response,
           'label' => $label,
            ));
            */

		$ajax_response = array(
           'create_shipment_API_call_response' => $create_shipment_API_call_response,
           'allocated' => $allocated,
           'shipment_number' => $shipment_number,
           'create_label_API_call_response' => $create_label_API_call_response,
           'label' => $label,
           );
$logger->info("this is weree i syart logging y6711");
	 	//Persist all changes to shipment
	 	try {
	 		$em->persist($shipment);
			$em->flush();
	 	} catch (Exception $e) {
	 		echo 'Caught exception: ',  $e->getMessage(), "\n";
	 	}
	 	$logger->info("this is weree i syart logging y6712");
		
		return new JsonResponse(
                    $ajax_response 
                );
    }

    function printManifestAction() 
	{

		//Call createShipmentAPICall sending the order object, set API respinse to the the return
        $create_manifest_API_call_response = $this->createManifestAPICall();
        ladybug_dump($create_manifest_API_call_response);
        //Check if createShipmentAPICall returned allocated.
        if(array_key_exists('completedManifests', $create_manifest_API_call_response['array'][0]['createManifestResponse'])) {
	        //Set the manifest batch number for print manifest to use.
	        $manifest_batch_number = $create_manifest_API_call_response['array'][0]['createManifestResponse']['completedManifests']['completedManifestInfo']['manifestBatchNumber'];
	    	
	    	//Create the manifest in the database
	        $em = $this->getDoctrine()->getManager();

	        //Create the shipping manifest object
	        $shipping_manifest = new ShippingManifest();

	        //Set the state as created.
        	$shipping_manifest->setShippingManifestState($em->getRepository('MilesApartAdminBundle:ShippingManifestState')->findOneById(1));

	        //Set the manifest number
	        $shipping_manifest->setRoyalMailBatchNumber($manifest_batch_number);

	        $em->persist($shipping_manifest);
	        $em->flush();
	    } else {
	    	$manifest_batch_number = FALSE;
	    }

	   

	    //sleep(120);
        
        $manifest = $this->printManifestFunction($manifest_batch_number);
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:RoyalMail:response.html.twig', array( 
            'create_manifest_API_call_response' => $create_manifest_API_call_response,
            
            'manifest'=> $manifest,

            ));

	}

	public function printManifestFunction($manifest_batch_number)
	{
		//Call createShipmentAPICall sending the order object, set API respinse to the the return
        $print_manifest_API_call_response = $this->printManifestAPICall($manifest_batch_number);

        ladybug_dump($print_manifest_API_call_response);

        //Check if the print was successful andf update the DB
        if(array_key_exists('manifest', $print_manifest_API_call_response['array'][0]['printManifestResponse'])) {

        	$manifest = $print_manifest_API_call_response['array'][0]['printManifestResponse']['manifest'];

        	//Update the state in the database 
	        $em = $this->getDoctrine()->getManager();

	        $shipping_manifest = $em->getRepository('MilesApartAdminBundle:ShippingManifest')->findOneBy(array('royal_mail_batch_number' => $manifest_batch_number));

	        $shipping_manifest->setShippingManifestState($em->getRepository('MilesApartAdminBundle:ShippingManifestState')->findOneById(2));

	        $em->persist($shipping_manifest);
	        $em->flush();

        	return $manifest;
        } else {
        	return false;
        }

	} 

	public function viewmanifestAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));
        
        //Get orders from db
        $em = $this->getDoctrine()->getManager();

        $manifests = $em->getRepository('MilesApartAdminBundle:ShippingManifest')->findBy(array(), array('shipping_manifest_date_created' => 'DESC'));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:view_manifests.html.twig', array(
            'manifests' => $manifests,
           
            ));
   
    }

    public function viewmanifestDetailsAction($manifest_id) 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));
        
        //Get orders from db
        $em = $this->getDoctrine()->getManager();

        $manifest = $em->getRepository('MilesApartAdminBundle:ShippingManifest')->findOneById($manifest_id);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:view_manifest_details.html.twig', array(
            'manifest' => $manifest,
           
        ));
   
    }

    public function printindividualmanifestAction($manifest_id) 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));
        
        //Get orders from db
        $em = $this->getDoctrine()->getManager();

        $shipping_manifest = $em->getRepository('MilesApartAdminBundle:ShippingManifest')->findOneById($manifest_id);

        $manifest = $this->printManifestFunction($shipping_manifest->getRoyalMailBatchNumber());
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:RoyalMail:response.html.twig', array( 
            
            
            'manifest'=> $manifest,

            ));
   
    }

    public function cancelshipmentAction($shipment_number) 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));
        
        //Call the cancel shipment script
        $cancel_shipment_response = $this->cancelShipmentRequestAPICall($shipment_number);

        //Check the success of shipment cancellation
        if(array_key_exists('completedCancelInfo', $cancel_shipment_response['array'][0]['cancelShipmentResponse'])) {

        	//Shipment has been cancelled, update the shipment state
        	//Get orders from db
	        $em = $this->getDoctrine()->getManager();

	        $shipment = $em->getRepository('MilesApartAdminBundle:RoyalMailShipment')->findOneBy(array('royal_mail_shipment_number' => $shipment_number));
	        
		    //Update the dabase to say it has been allocated
        	$shipment->setRoyalMailShipmentState($em->getRepository('MilesApartAdminBundle:RoyalMailShipmentState')->findOneById(5));

            //Set success or failure
            $cancel_shipment_success = true;
            $em->flush();
        } else {
            $cancel_shipment_success = false;
        }
	       

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:cancel_shipment.html.twig', array(
            'cancel_shipment_response' => $cancel_shipment_response,
            'cancel_shipment_success' => $cancel_shipment_success
            ));
   
    }

	public function viewshipmentsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));
        
        //Get orders from db
        $em = $this->getDoctrine()->getManager();

        $outstanding_shipments = $em->getRepository('MilesApartAdminBundle:RoyalMailShipment')->findBy(array('royal_mail_shipment_state' => 2));

        /*
        *
        * Check any shipments that need to be marked as manifested
        *
        */
        foreach($outstanding_shipments as $shipment) {
        	//Compare the shipment created datetime with the current datetime
        	$now = new \DateTime();

        	//Find difference between the 2
        	$interval = $shipment->getRoyalMailShipmentDateCreated()->diff($now);

        	if($interval->format('%a') >= 1) {
        		//Update the dabase to say it has been allocated
        		$shipment->setRoyalMailShipmentState($em->getRepository('MilesApartAdminBundle:RoyalMailShipmentState')->findOneById(3));

        		//Flush the changes
        		$em->flush();
        	} 
        	
        }

        $shipments = $em->getRepository('MilesApartAdminBundle:RoyalMailShipment')->findBy(array(), array('royal_mail_shipment_date_created' => 'DESC'));

        
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:view_shipments.html.twig', array(
            'shipments' => $shipments,
           
            ));
   
    }

    public function viewshipmentdetailsAction($shipment_id) 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));
        
        //Get orders from db
        $em = $this->getDoctrine()->getManager();

        $shipment = $em->getRepository('MilesApartAdminBundle:RoyalMailShipment')->findOneById($shipment_id);
        //Handle the xml data
        $xml = new \SimpleXMLElement(stripslashes($shipment->getRoyalMailCreateShipmentResponseRawXml()));
        //Check if there has been a submission fault
		if(count($xml->xpath('//*')) == 9) {
			//There has been a faults
			$body = $xml->xpath('//soapenv:Body');
			$allocated = FALSE;
			$array = $xml;
		} else {
			//No fault
			$body = $xml->xpath('//SOAP-ENV:Body');
			$allocated = TRUE;
			$array = json_decode(json_encode($body), TRUE); 
		}

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:view_shipment_details.html.twig', array(
            'shipment' => $shipment,
            'array'=> $array,
            ));
   
    }

    /*************************************************
    *
    *
    * API calls for Royal Mail
    *
    *
    *************************************************/
    /*************************************************
    * Creating shipment
    *************************************************/
    public function createShipmentAPICall($order) 
    {
    	/* 
    	 * Takes address details, service details and references
    	 * Creates new shipment with Royal Mail Shipping API
    	 * 
    	 */

    	/* 
    	 * Dynamic varaibles
    	 */
$logger = $this->get('logger');
        $logger->info('I just got the logger add update price');
    	//Transaction id should be random number
		$transactionId = mt_rand();

		$serviceType = $order->getDeliveryOption()->getPostageType()->getPostageTypeRoyalMailCode();
		$serviceCode = "CRL";
		$serviceFormat = $order->getDeliveryOption()->getPostageBand()->getPostageBandType()->getRoyalMailPostageBandTypeCode();

		//Set the address data
		//Check if contact name is set
		if($order->getDeliveryAddress()->getCustomerAddressContactFullName()) {
			$recipientContactName = $order->getDeliveryAddress()->getCustomerAddressContactFullName();
		} else {

			//Check if personal customer

			$recipientContactName = $order->getCustomerOrderFullName();
		}
		$logger->info('I just got the logger add update price2');
		$recipientAddressLine1 = $order->getDeliveryAddress()->getCustomerAddressLine1();
		
		//Check if there is a customer address line 2
		if($order->getDeliveryAddress()->getCustomerAddressLine2() != NULL) {
			$recipientAddressLine2 = $order->getDeliveryAddress()->getCustomerAddressLine2();
		} else {
			$recipientAddressLine2 = NULL;
		}

		$postTown = $order->getDeliveryAddress()->getCustomerAddressTown();
		$postcode = $order->getDeliveryAddress()->getCustomerAddressPostcode();
		$countryCode = "GB";
		
		//Items details
		$noOfItems = "1";
		
		//Weight
		$unitOfMeasure = "g";
		$weightValue = $order->getCustomerOrderTotalWeight();

		//Ref is order id
		$sendersReference = $order->getId();
$logger->info('I just got the logger add update price3');

    	/* 
    	 * Static varaibles
    	 */

		//Set the URL for the API call
		$apiURL = "https://api.royalmail.net/shipping/v2"; 

		//Set the current datetime
		$dateTime = date('Y-m-d\TH:i:s');

		//Set the call specific data
		$applicationId = "RMG-API-G-01";

		$shippingDate = gmdate('Y-m-d');
		$shipmentType = "Delivery";
		//$serviceOccurrence = "";
		
		/* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
		*/
		$clientId = "dcf0b455-acc6-49cf-9699-f90d239b49e0";
		$clientSecret = "oY4xI3bS3dG4wA3aL4sT6bL3dG0uK5kX2iM2jL4vB8bJ2jC2nW";

		/* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
		*/
		$username = "milesapart_9000460333API";
		$password = "Password2014!";

		/* CREATIONDATE - The timestamp. The computer must be on correct time or the server you are * connecting may reject the password digest for security.
		*/
		$creationDate = gmdate('Y-m-d\TH:i:s\Z');

		/* NONCE - A random word.
		* The use of rand() may repeat the word if the server is very loaded. */
		$nonce = mt_rand();

		/* PASSWORDDIGEST This is the way to create the password digest. As per OASIS standard * digest = base64_encode(Sha1(nonce + creationdate + Sha1(password)))
		* Note that we use a Sha1(password) instead of the plain password above
		*/
		$nonce_date_pwd = $nonce.$creationDate.base64_encode(sha1($password, TRUE)); 
		$passwordDigest = base64_encode(sha1($nonce_date_pwd, TRUE));

		/* ENCODEDNONCE - Now encode the nonce for security header */
		$encodedNonce = base64_encode($nonce);

		/* Print all WS-Security values for debugging
		* echo 'nonce: ' . $nonce . PHP_EOL;
		* echo 'password digest: ' . $passwordDigest . PHP_EOL; * echo 'encoded nonce: ' . $encodedNonce . PHP_EOL; * echo 'creation date: ' . $creationDate . PHP_EOL;
		*/
		$curl = curl_init();

		/* The commented code below is provided for customers to adapt to handle the client side security
		* implementation for the API *
		* PHP code to validate the certificate returned from APIm
		* CURLOPT_SSL_VERIFYHOST can be set to the following integer values:
		* 0: Dont check the common name (CN) attribute
		* 1: Check that the common name attribute at least exists
		* 2: Check that the common name exists and that it matches the host name of the server */
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "\(path)\api.royalmail.net.crt");
	 
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $apiURL,
		CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => 
		"<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\" >\r\n 
			<soapenv:Header>\r\n 
				<wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">\r\n 
					<wsse:UsernameToken>\r\n 
						<wsse:Username>$username</wsse:Username>\r\n 
						<wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">$passwordDigest</wsse:Password>\r\n 
						<wsse:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">$encodedNonce</wsse:Nonce>\r\n
						<wsu:Created>$creationDate</wsu:Created>\r\n 
					</wsse:UsernameToken>\r\n 
				</wsse:Security>\r\n
			</soapenv:Header>\r\n 

			<soapenv:Body>\r\n
				<v2:createShipmentRequest>\r\n 
					<v2:integrationHeader>\r\n 
						<v1:dateTime>$dateTime</v1:dateTime>\r\n
						<v1:version>2</v1:version>\r\n 
						<v1:identification>\r\n
							<v1:applicationId>$applicationId</v1:applicationId>\r\n 
							<v1:transactionId>$transactionId</v1:transactionId>\r\n 
						</v1:identification>\r\n 
					</v2:integrationHeader>\r\n 

					<v2:requestedShipment>\r\n 
						<v2:shipmentType>\r\n 
							<code>$shipmentType</code>\r\n 
						</v2:shipmentType>\r\n 

						
						<v2:serviceType>\r\n 
							<code>$serviceType</code>\r\n 
						</v2:serviceType>\r\n 

						<v2:serviceOffering>\r\n
							<serviceOfferingCode>\r\n
								<code>$serviceCode</code>\r\n 
							</serviceOfferingCode>\r\n 
						</v2:serviceOffering>\r\n 

						<v2:serviceFormat>\r\n 
							<serviceFormatCode>\r\n 
								<code>$serviceFormat</code>\r\n 
							</serviceFormatCode>\r\n 
						</v2:serviceFormat>\r\n 

						<v2:shippingDate>$shippingDate</v2:shippingDate>\r\n 

						<v2:recipientContact>\r\n 
							<v2:name>$recipientContactName</v2:name>\r\n 
						</v2:recipientContact>\r\n 

						<v2:recipientAddress>\r\n
							<addressLine1>$recipientAddressLine1</addressLine1>\r\n
							<addressLine2>$recipientAddressLine2</addressLine2>\r\n
							<postTown>$postTown</postTown>\r\n
							<postcode>$postcode</postcode>\r\n
							<country>\r\n 
			 					<countryCode>\r\n
			 						<code>$countryCode</code>\r\n
			 					</countryCode>\r\n
			 				</country>\r\n
			 			</v2:recipientAddress>\r\n
			
						<v2:items>\r\n 
							<v2:item>\r\n
								<v2:numberOfItems>$noOfItems</v2:numberOfItems>\r\n
								<v2:weight>\r\n 
									<unitOfMeasure>\r\n 
										<unitOfMeasureCode>\r\n 
											<code>$unitOfMeasure</code>\r\n 
										</unitOfMeasureCode>\r\n 
									</unitOfMeasure>\r\n 
									<value>$weightValue</value>\r\n 
								</v2:weight>\r\n 
							</v2:item>\r\n 
						</v2:items>\r\n 

						<v2:senderReference>$sendersReference</v2:senderReference>\r\n 
					</v2:requestedShipment>\r\n 
				</v2:createShipmentRequest>\r\n 
			</soapenv:Body>\r\n
		</soapenv:Envelope>",
		
		CURLOPT_HTTPHEADER => array( 
			"accept: application/soap+xml", 
			"accept-encoding: gzip,deflate", 
			"connection: keep-alive", 
			"content-type: text/xml",
			"host: api.royalmail.net", 
			"soapaction: \"createShipment\"", 
			"x-ibm-client-id: $clientId", 
			"x-ibm-client-secret: $clientSecret"
		), ));
	
		//Handle the curl response 
		$response = curl_exec($curl); 

		//Handle any curl errors
		$err = curl_error($curl);

		//Close the curl connection
		curl_close($curl);

		//Process the returned SOAP into array
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);

        if($response != null) {

    		$xml = new \SimpleXMLElement($response);

    		 
            //Check if there has been a submission fault
    		if(count($xml->xpath('//*')) == 9) {
    			//There has been a faults
    			$body = $xml->xpath('//soapenv:Body');
    			$allocated = FALSE;
    			$array = $xml;
    		} else {
    			//No fault
    			$body = $xml->xpath('//SOAP-ENV:Body');
    			$allocated = TRUE;
    			$array = json_decode(json_encode($body), TRUE); 
    		}

        } else {
            $allocated = FALSE;
            $body = null;
            $array = null;
        }

		//Create response array with errors or curl response and return
		//Render the page from template
        $return = array(
        	'err' => $err,
        	'array' => $array,
           	'allocated' => $allocated,
           	'response' => $response,
            );


        return $return;
		
	}

	/*************************************************
    * Create label
    *************************************************/
	public function createLabelAPICall($shipment_number, $order) 
    {
    	/* 
    	 * Takes address details, service details and references
    	 * Creates new shipment with Royal Mail Shipping API
    	 * 
    	 */

    	//Transaction id should be random number
		$transactionId = mt_rand();

    	/* 
    	 * Static varaibles
    	 */

		//Set the URL for the API call
		$apiURL = "https://api.royalmail.net/shipping/v2"; 

		//Set the current datetime
		$dateTime = date('Y-m-d\TH:i:s');

		//Set the call specific data
		$applicationId = "RMG-API-G-01";

		$shippingDate = gmdate('Y-m-d');
		$shipmentType = "Delivery";
		//$serviceOccurrence = "";
		
		/* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
		*/
		$clientId = "dcf0b455-acc6-49cf-9699-f90d239b49e0";
		$clientSecret = "oY4xI3bS3dG4wA3aL4sT6bL3dG0uK5kX2iM2jL4vB8bJ2jC2nW";

		/* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
		*/
		$username = "milesapart_9000460333API";
		$password = "Password2014!";

		/* CREATIONDATE - The timestamp. The computer must be on correct time or the server you are * connecting may reject the password digest for security.
		*/
		$creationDate = gmdate('Y-m-d\TH:i:s\Z');

		/* NONCE - A random word.
		* The use of rand() may repeat the word if the server is very loaded. */
		$nonce = mt_rand();

		/* PASSWORDDIGEST This is the way to create the password digest. As per OASIS standard * digest = base64_encode(Sha1(nonce + creationdate + Sha1(password)))
		* Note that we use a Sha1(password) instead of the plain password above
		*/
		$nonce_date_pwd = $nonce.$creationDate.base64_encode(sha1($password, TRUE)); 
		$passwordDigest = base64_encode(sha1($nonce_date_pwd, TRUE));

		/* ENCODEDNONCE - Now encode the nonce for security header */
		$encodedNonce = base64_encode($nonce);

		/* Print all WS-Security values for debugging
		* echo 'nonce: ' . $nonce . PHP_EOL;
		* echo 'password digest: ' . $passwordDigest . PHP_EOL; * echo 'encoded nonce: ' . $encodedNonce . PHP_EOL; * echo 'creation date: ' . $creationDate . PHP_EOL;
		*/
		$curl = curl_init();

		/* The commented code below is provided for customers to adapt to handle the client side security
		* implementation for the API *
		* PHP code to validate the certificate returned from APIm
		* CURLOPT_SSL_VERIFYHOST can be set to the following integer values:
		* 0: Dont check the common name (CN) attribute
		* 1: Check that the common name attribute at least exists
		* 2: Check that the common name exists and that it matches the host name of the server */
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "\(path)\api.royalmail.net.crt");
	 
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $apiURL,
		CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => 
		"<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\" >\r\n 
			<soapenv:Header>\r\n 
				<wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">\r\n 
					<wsse:UsernameToken>\r\n 
						<wsse:Username>$username</wsse:Username>\r\n 
						<wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">$passwordDigest</wsse:Password>\r\n 
						<wsse:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">$encodedNonce</wsse:Nonce>\r\n
						<wsu:Created>$creationDate</wsu:Created>\r\n 
					</wsse:UsernameToken>\r\n 
				</wsse:Security>\r\n
			</soapenv:Header>\r\n 

			<soapenv:Body>\r\n
				<v2:printLabelRequest>\r\n
 
					<v2:integrationHeader>\r\n 
						<v1:dateTime>$dateTime</v1:dateTime>\r\n
						<v1:version>2</v1:version>\r\n 
						<v1:identification>\r\n
							<v1:applicationId>$applicationId</v1:applicationId>\r\n 
							<v1:transactionId>$transactionId</v1:transactionId>\r\n 
						</v1:identification>\r\n 
					</v2:integrationHeader>\r\n 
 
					<v2:shipmentNumber>TTT009762818GB</v2:shipmentNumber>\r\n
 
				</v2:printLabelRequest>\r\n
			</soapenv:Body>\r\n
		</soapenv:Envelope>",
		
		CURLOPT_HTTPHEADER => array( 
			"accept: application/soap+xml", 
			"accept-encoding: gzip,deflate", 
			"connection: keep-alive", 
			"content-type: text/xml",
			"host: api.royalmail.net", 
			"soapaction: \"printLabel\"", 
			"x-ibm-client-id: $clientId", 
			"x-ibm-client-secret: $clientSecret"
		), ));
		
		//Handle the curl response 
		$response = curl_exec($curl); 

		//Handle any curl errors
		$err = curl_error($curl);

		//Close the curl connection
		curl_close($curl);

		//Process the returned SOAP into array
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
		$xml = new \SimpleXMLElement($response);
		$body = $xml->xpath('//SOAP-ENV:Body');
		$array = json_decode(json_encode($body), TRUE); 
		


		//Create response array with errors or curl response and return
		//Render the page from template
        $return = array(
        	'err' => $err,
        	'array' => $array,
        	'response' => $response,
           
            );


        return $return;
		
	}

	
	/*************************************************
    * Create manifest
    *************************************************/
	public function createManifestAPICall() 
    {
    	/* 
    	 * Takes address details, service details and references
    	 * Creates new shipment with Royal Mail Shipping API
    	 * 
    	 */

    	//Transaction id should be random number
		$transactionId = mt_rand();

    	/* 
    	 * Static varaibles
    	 */

		//Set the URL for the API call
		$apiURL = "https://api.royalmail.net/shipping/v2"; 

		//Set the current datetime
		$dateTime = date('Y-m-d\TH:i:s');

		//Set the call specific data
		$applicationId = "RMG-API-G-01";

		$shippingDate = gmdate('Y-m-d');
		$shipmentType = "Delivery";
		//$serviceOccurrence = "";
		
		/* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
		*/
		$clientId = "dcf0b455-acc6-49cf-9699-f90d239b49e0";
		$clientSecret = "oY4xI3bS3dG4wA3aL4sT6bL3dG0uK5kX2iM2jL4vB8bJ2jC2nW";

		/* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
		*/
		$username = "milesapart_9000460333API";
		$password = "Password2014!";

		/* CREATIONDATE - The timestamp. The computer must be on correct time or the server you are * connecting may reject the password digest for security.
		*/
		$creationDate = gmdate('Y-m-d\TH:i:s\Z');

		/* NONCE - A random word.
		* The use of rand() may repeat the word if the server is very loaded. */
		$nonce = mt_rand();

		/* PASSWORDDIGEST This is the way to create the password digest. As per OASIS standard * digest = base64_encode(Sha1(nonce + creationdate + Sha1(password)))
		* Note that we use a Sha1(password) instead of the plain password above
		*/
		$nonce_date_pwd = $nonce.$creationDate.base64_encode(sha1($password, TRUE)); 
		$passwordDigest = base64_encode(sha1($nonce_date_pwd, TRUE));

		/* ENCODEDNONCE - Now encode the nonce for security header */
		$encodedNonce = base64_encode($nonce);

		/* Print all WS-Security values for debugging
		* echo 'nonce: ' . $nonce . PHP_EOL;
		* echo 'password digest: ' . $passwordDigest . PHP_EOL; * echo 'encoded nonce: ' . $encodedNonce . PHP_EOL; * echo 'creation date: ' . $creationDate . PHP_EOL;
		*/
		$curl = curl_init();

		/* The commented code below is provided for customers to adapt to handle the client side security
		* implementation for the API *
		* PHP code to validate the certificate returned from APIm
		* CURLOPT_SSL_VERIFYHOST can be set to the following integer values:
		* 0: Dont check the common name (CN) attribute
		* 1: Check that the common name attribute at least exists
		* 2: Check that the common name exists and that it matches the host name of the server */
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "\(path)\api.royalmail.net.crt");
	 
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $apiURL,
		CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => 
		"<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\" >\r\n 
			<soapenv:Header>\r\n 
				<wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">\r\n 
					<wsse:UsernameToken>\r\n 
						<wsse:Username>$username</wsse:Username>\r\n 
						<wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">$passwordDigest</wsse:Password>\r\n 
						<wsse:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">$encodedNonce</wsse:Nonce>\r\n
						<wsu:Created>$creationDate</wsu:Created>\r\n 
					</wsse:UsernameToken>\r\n 
				</wsse:Security>\r\n
			</soapenv:Header>\r\n 

			<soapenv:Body>\r\n
				<v2:createManifestRequest>\r\n
 
					<v2:integrationHeader>\r\n 
						<v1:dateTime>$dateTime</v1:dateTime>\r\n
						<v1:version>2</v1:version>\r\n 
						<v1:identification>\r\n
							<v1:applicationId>$applicationId</v1:applicationId>\r\n 
							<v1:transactionId>$transactionId</v1:transactionId>\r\n 
						</v1:identification>\r\n 
					</v2:integrationHeader>\r\n 
 
					<v2:yourDescription>Manifest333</v2:yourDescription>\r\n
 
				</v2:createManifestRequest>\r\n
			</soapenv:Body>\r\n
		</soapenv:Envelope>",
		
		CURLOPT_HTTPHEADER => array( 
			"accept: application/soap+xml", 
			"accept-encoding: gzip,deflate", 
			"connection: keep-alive", 
			"content-type: text/xml",
			"host: api.royalmail.net", 
			"soapaction: \"createManifest\"", 
			"x-ibm-client-id: $clientId", 
			"x-ibm-client-secret: $clientSecret"
		), ));
		
		//Handle the curl response 
		$response = curl_exec($curl); 

		//Handle any curl errors
		$err = curl_error($curl);

		//Close the curl connection
		curl_close($curl);

		//Process the returned SOAP into array
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
		$xml = new \SimpleXMLElement($response);
		$body = $xml->xpath('//SOAP-ENV:Body');
		$array = json_decode(json_encode($body), TRUE); 
		


		//Create response array with errors or curl response and return
		//Render the page from template
        $return = array(
        	'err' => $err,
        	'array' => $array,
           
            );


        return $return;
		
	}

	


    /*************************************************
    * Print manifest
    *************************************************/
	public function printManifestAPICall($manifest_batch_number) 
    {
    	/* 
    	 * Takes address details, service details and references
    	 * Creates new shipment with Royal Mail Shipping API
    	 * 
    	 */

    	//Transaction id should be random number
		$transactionId = mt_rand();

    	/* 
    	 * Static varaibles
    	 */

		//Set the URL for the API call
		$apiURL = "https://api.royalmail.net/shipping/v2"; 

		//Set the current datetime
		$dateTime = date('Y-m-d\TH:i:s');

		//Set the call specific data
		$applicationId = "RMG-API-G-01";

		$shippingDate = gmdate('Y-m-d');
		$shipmentType = "Delivery";
		//$serviceOccurrence = "";
		
		/* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
		*/
		$clientId = "dcf0b455-acc6-49cf-9699-f90d239b49e0";
		$clientSecret = "oY4xI3bS3dG4wA3aL4sT6bL3dG0uK5kX2iM2jL4vB8bJ2jC2nW";

		/* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
		*/
		$username = "milesapart_9000460333API";
		$password = "Password2014!";

		/* CREATIONDATE - The timestamp. The computer must be on correct time or the server you are * connecting may reject the password digest for security.
		*/
		$creationDate = gmdate('Y-m-d\TH:i:s\Z');

		/* NONCE - A random word.
		* The use of rand() may repeat the word if the server is very loaded. */
		$nonce = mt_rand();

		/* PASSWORDDIGEST This is the way to create the password digest. As per OASIS standard * digest = base64_encode(Sha1(nonce + creationdate + Sha1(password)))
		* Note that we use a Sha1(password) instead of the plain password above
		*/
		$nonce_date_pwd = $nonce.$creationDate.base64_encode(sha1($password, TRUE)); 
		$passwordDigest = base64_encode(sha1($nonce_date_pwd, TRUE));

		/* ENCODEDNONCE - Now encode the nonce for security header */
		$encodedNonce = base64_encode($nonce);

		/* Print all WS-Security values for debugging
		* echo 'nonce: ' . $nonce . PHP_EOL;
		* echo 'password digest: ' . $passwordDigest . PHP_EOL; * echo 'encoded nonce: ' . $encodedNonce . PHP_EOL; * echo 'creation date: ' . $creationDate . PHP_EOL;
		*/
		$curl = curl_init();

		/* The commented code below is provided for customers to adapt to handle the client side security
		* implementation for the API *
		* PHP code to validate the certificate returned from APIm
		* CURLOPT_SSL_VERIFYHOST can be set to the following integer values:
		* 0: Dont check the common name (CN) attribute
		* 1: Check that the common name attribute at least exists
		* 2: Check that the common name exists and that it matches the host name of the server */
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "\(path)\api.royalmail.net.crt");
	 
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $apiURL,
		CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => 
		"<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\" >\r\n 
			<soapenv:Header>\r\n 
				<wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">\r\n 
					<wsse:UsernameToken>\r\n 
						<wsse:Username>$username</wsse:Username>\r\n 
						<wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">$passwordDigest</wsse:Password>\r\n 
						<wsse:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">$encodedNonce</wsse:Nonce>\r\n
						<wsu:Created>$creationDate</wsu:Created>\r\n 
					</wsse:UsernameToken>\r\n 
				</wsse:Security>\r\n
			</soapenv:Header>\r\n 

			<soapenv:Body>\r\n
				<v2:printManifestRequest>\r\n
 
					<v2:integrationHeader>\r\n 
						<v1:dateTime>$dateTime</v1:dateTime>\r\n
						<v1:version>2</v1:version>\r\n 
						<v1:identification>\r\n
							<v1:applicationId>$applicationId</v1:applicationId>\r\n 
							<v1:transactionId>$transactionId</v1:transactionId>\r\n 
						</v1:identification>\r\n 
					</v2:integrationHeader>\r\n 
 
					<v2:manifestBatchNumber>$manifest_batch_number</v2:manifestBatchNumber>
 
				</v2:printManifestRequest>\r\n
			</soapenv:Body>\r\n
		</soapenv:Envelope>",
		
		CURLOPT_HTTPHEADER => array( 
			"accept: application/soap+xml", 
			"accept-encoding: gzip,deflate", 
			"connection: keep-alive", 
			"content-type: text/xml",
			"host: api.royalmail.net", 
			"soapaction: \"printManifest\"", 
			"x-ibm-client-id: $clientId", 
			"x-ibm-client-secret: $clientSecret"
		), ));
		
		//Handle the curl response 
		$response = curl_exec($curl); 

		//Handle any curl errors
		$err = curl_error($curl);

		//Close the curl connection
		curl_close($curl);

		//Process the returned SOAP into array
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
		$xml = new \SimpleXMLElement($response);
		$body = $xml->xpath('//SOAP-ENV:Body');
		$array = json_decode(json_encode($body), TRUE); 
		


		//Create response array with errors or curl response and return
		//Render the page from template
        $return = array(
        	'err' => $err,
        	'array' => $array,
           
            );


        return $return;
		
	}

	/*************************************************
    * Update shipment
    *************************************************/
	public function updateShipmentRequestAPICall($shipment_number) 
    {
    	/* 
    	 * Takes address details, service details and references
    	 * Creates new shipment with Royal Mail Shipping API
    	 * 
    	 */

    	//Transaction id should be random number
		$transactionId = mt_rand();

    	/* 
    	 * Static varaibles
    	 */

		//Set the URL for the API call
		$apiURL = "https://api.royalmail.net/shipping/v2"; 

		//Set the current datetime
		$dateTime = date('Y-m-d\TH:i:s');

		//Set the call specific data
		$applicationId = "RMG-API-G-01";

		$shippingDate = gmdate('Y-m-d');
		$shipmentType = "Delivery";
		//$serviceOccurrence = "";
		
		/* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
		*/
		$clientId = "dcf0b455-acc6-49cf-9699-f90d239b49e0";
		$clientSecret = "oY4xI3bS3dG4wA3aL4sT6bL3dG0uK5kX2iM2jL4vB8bJ2jC2nW";

		/* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
		*/
		$username = "milesapart_9000460333API";
		$password = "Password2014!";

		/* CREATIONDATE - The timestamp. The computer must be on correct time or the server you are * connecting may reject the password digest for security.
		*/
		$creationDate = gmdate('Y-m-d\TH:i:s\Z');

		/* NONCE - A random word.
		* The use of rand() may repeat the word if the server is very loaded. */
		$nonce = mt_rand();

		/* PASSWORDDIGEST This is the way to create the password digest. As per OASIS standard * digest = base64_encode(Sha1(nonce + creationdate + Sha1(password)))
		* Note that we use a Sha1(password) instead of the plain password above
		*/
		$nonce_date_pwd = $nonce.$creationDate.base64_encode(sha1($password, TRUE)); 
		$passwordDigest = base64_encode(sha1($nonce_date_pwd, TRUE));

		/* ENCODEDNONCE - Now encode the nonce for security header */
		$encodedNonce = base64_encode($nonce);

		/* Print all WS-Security values for debugging
		* echo 'nonce: ' . $nonce . PHP_EOL;
		* echo 'password digest: ' . $passwordDigest . PHP_EOL; * echo 'encoded nonce: ' . $encodedNonce . PHP_EOL; * echo 'creation date: ' . $creationDate . PHP_EOL;
		*/
		$curl = curl_init();

		/* The commented code below is provided for customers to adapt to handle the client side security
		* implementation for the API *
		* PHP code to validate the certificate returned from APIm
		* CURLOPT_SSL_VERIFYHOST can be set to the following integer values:
		* 0: Dont check the common name (CN) attribute
		* 1: Check that the common name attribute at least exists
		* 2: Check that the common name exists and that it matches the host name of the server */
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "\(path)\api.royalmail.net.crt");
	 
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $apiURL,
		CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => 
		"<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\" >\r\n 
			<soapenv:Header>\r\n 
				<wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">\r\n 
					<wsse:UsernameToken>\r\n 
						<wsse:Username>$username</wsse:Username>\r\n 
						<wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">$passwordDigest</wsse:Password>\r\n 
						<wsse:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">$encodedNonce</wsse:Nonce>\r\n
						<wsu:Created>$creationDate</wsu:Created>\r\n 
					</wsse:UsernameToken>\r\n 
				</wsse:Security>\r\n
			</soapenv:Header>\r\n 

			<soapenv:Body>\r\n
				<v2:createManifestRequest>\r\n
 
					<v2:integrationHeader>\r\n 
						<v1:dateTime>$dateTime</v1:dateTime>\r\n
						<v1:version>2</v1:version>\r\n 
						<v1:identification>\r\n
							<v1:applicationId>$applicationId</v1:applicationId>\r\n 
							<v1:transactionId>$transactionId</v1:transactionId>\r\n 
						</v1:identification>\r\n 
					</v2:integrationHeader>\r\n 
 
					<v2:yourDescription>Manifest333</v2:yourDescription>\r\n
 
				</v2:createManifestRequest>\r\n
			</soapenv:Body>\r\n
		</soapenv:Envelope>",
		
		CURLOPT_HTTPHEADER => array( 
			"accept: application/soap+xml", 
			"accept-encoding: gzip,deflate", 
			"connection: keep-alive", 
			"content-type: text/xml",
			"host: api.royalmail.net", 
			"soapaction: \"createManifest\"", 
			"x-ibm-client-id: $clientId", 
			"x-ibm-client-secret: $clientSecret"
		), ));
		
		//Handle the curl response 
		$response = curl_exec($curl); 

		//Handle any curl errors
		$err = curl_error($curl);

		//Close the curl connection
		curl_close($curl);

		//Process the returned SOAP into array
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
		$xml = new \SimpleXMLElement($response);
		$body = $xml->xpath('//SOAP-ENV:Body');
		$array = json_decode(json_encode($body), TRUE); 
		


		//Create response array with errors or curl response and return
		//Render the page from template
        $return = array(
        	'err' => $err,
        	'array' => $array,
           
            );


        return $return;
		
	}

	/*************************************************
    * Cancel shipment
    *************************************************/
	public function cancelShipmentRequestAPICall($shipment_number) 
    {
    	/* 
    	 * Takes address details, service details and references
    	 * Creates new shipment with Royal Mail Shipping API
    	 * 
    	 */

    	//Transaction id should be random number
		$transactionId = mt_rand();

    	/* 
    	 * Static varaibles
    	 */

		//Set the URL for the API call
		$apiURL = "https://api.royalmail.net/shipping/v2"; 

		//Set the current datetime
		$dateTime = date('Y-m-d\TH:i:s');

		//Set the call specific data
		$applicationId = "RMG-API-G-01";

		$shippingDate = gmdate('Y-m-d');
		$shipmentType = "Delivery";
		//$serviceOccurrence = "";
		
		/* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
		*/
		$clientId = "dcf0b455-acc6-49cf-9699-f90d239b49e0";
		$clientSecret = "oY4xI3bS3dG4wA3aL4sT6bL3dG0uK5kX2iM2jL4vB8bJ2jC2nW";

		/* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
		*/
		$username = "milesapart_9000460333API";
		$password = "Password2014!";

		/* CREATIONDATE - The timestamp. The computer must be on correct time or the server you are * connecting may reject the password digest for security.
		*/
		$creationDate = gmdate('Y-m-d\TH:i:s\Z');

		/* NONCE - A random word.
		* The use of rand() may repeat the word if the server is very loaded. */
		$nonce = mt_rand();

		/* PASSWORDDIGEST This is the way to create the password digest. As per OASIS standard * digest = base64_encode(Sha1(nonce + creationdate + Sha1(password)))
		* Note that we use a Sha1(password) instead of the plain password above
		*/
		$nonce_date_pwd = $nonce.$creationDate.base64_encode(sha1($password, TRUE)); 
		$passwordDigest = base64_encode(sha1($nonce_date_pwd, TRUE));

		/* ENCODEDNONCE - Now encode the nonce for security header */
		$encodedNonce = base64_encode($nonce);

		/* Print all WS-Security values for debugging
		* echo 'nonce: ' . $nonce . PHP_EOL;
		* echo 'password digest: ' . $passwordDigest . PHP_EOL; * echo 'encoded nonce: ' . $encodedNonce . PHP_EOL; * echo 'creation date: ' . $creationDate . PHP_EOL;
		*/
		$curl = curl_init();

		/* The commented code below is provided for customers to adapt to handle the client side security
		* implementation for the API *
		* PHP code to validate the certificate returned from APIm
		* CURLOPT_SSL_VERIFYHOST can be set to the following integer values:
		* 0: Dont check the common name (CN) attribute
		* 1: Check that the common name attribute at least exists
		* 2: Check that the common name exists and that it matches the host name of the server */
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "\(path)\api.royalmail.net.crt");
	 
	
		curl_setopt_array($curl, array(
		CURLOPT_URL => $apiURL,
		CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => 
		"<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\" >\r\n 
			<soapenv:Header>\r\n 
				<wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">\r\n 
					<wsse:UsernameToken>\r\n 
						<wsse:Username>$username</wsse:Username>\r\n 
						<wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">$passwordDigest</wsse:Password>\r\n 
						<wsse:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">$encodedNonce</wsse:Nonce>\r\n
						<wsu:Created>$creationDate</wsu:Created>\r\n 
					</wsse:UsernameToken>\r\n 
				</wsse:Security>\r\n
			</soapenv:Header>\r\n 

			<soapenv:Body>\r\n
				<v2:cancelShipmentRequest>\r\n
 
					<v2:integrationHeader>\r\n 
						<v1:dateTime>$dateTime</v1:dateTime>\r\n
						<v1:version>2</v1:version>\r\n 
						<v1:identification>\r\n
							<v1:applicationId>$applicationId</v1:applicationId>\r\n 
							<v1:transactionId>$transactionId</v1:transactionId>\r\n 
						</v1:identification>\r\n 
					</v2:integrationHeader>\r\n 
 					
 					<v2:cancelShipments>\r\n 
 						<v2:shipmentNumber>$shipment_number</v2:shipmentNumber>\r\n 
 					</v2:cancelShipments>\r\n 
 
				</v2:cancelShipmentRequest>\r\n
			</soapenv:Body>\r\n
		</soapenv:Envelope>",
		
		CURLOPT_HTTPHEADER => array( 
			"accept: application/soap+xml", 
			"accept-encoding: gzip,deflate", 
			"connection: keep-alive", 
			"content-type: text/xml",
			"host: api.royalmail.net", 
			"soapaction: \"cancelShipment\"", 
			"x-ibm-client-id: $clientId", 
			"x-ibm-client-secret: $clientSecret"
		), ));
		
		//Handle the curl response 
		$response = curl_exec($curl); 

		//Handle any curl errors
		$err = curl_error($curl);

		//Close the curl connection
		curl_close($curl);

		//Process the returned SOAP into array
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
		$xml = new \SimpleXMLElement($response);

		 //Check if there has been a submission fault
		if(count($xml->xpath('//*')) == 9) {
			//There has been a faults
			$body = $xml->xpath('//soapenv:Body');
			$cancelled = FALSE;
			$array = $xml;
		} else {
			//No fault
			$body = $xml->xpath('//SOAP-ENV:Body');
			$cancelled = TRUE;
			$array = json_decode(json_encode($body), TRUE); 
		}


		//Create response array with errors or curl response and return
		//Render the page from template
        $return = array(
        	'err' => $err,
        	'array' => $array,
           	'cancelled' => $cancelled,
           	'response' => $response,
            );

        return $return;
		
	}

	
}



