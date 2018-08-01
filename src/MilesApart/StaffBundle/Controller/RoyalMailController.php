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

    //Complete shipment function that will create a shipment and then a label and print the label
    public function completeShipmentAction($order_id, Request $request)
    {
        //Get the Royal Mail service
        $royal_mail_service = $this->get('staff.royal_mail_service');

        //Get the entity manager
        $em = $this->getDoctrine()->getManager();

        //Find order
        $order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findOneById($order_id);

        //Check if a tracking ID exists in royal mail shipment (has a successful shipment been created previously?)
        $existing_shipments = $em->getRepository('MilesApartAdminBundle:RoyalMailShipment')->findExistingShipmentsByOrderId($order_id);

        if ($existing_shipments != null) {
            foreach ($existing_shipments as $existing_shipment) {

                if ($existing_shipment->getRoyalMailShipmentNumber() != null) {

                    //Shipment has already been created so we need to leave script and notify user.
                    $ajax_response = array(
                        'allocated' => false,
                        'existing' => true
                    );

                    return new JsonResponse(
                        $ajax_response
                    );
                } else {
                    //Shipment has been created in database, so recall create shipment
                    $ajax_response = $royal_mail_service->createShipmentAPICall($order, $existing_shipment);
                    return $ajax_response;
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

        //Get the barcode and search query from the request
        $new_weight = $response["newWeight"];
        $new_height = $response["newHeight"];
        $new_width = $response["newWidth"];
        $new_depth = $response["newDepth"];

        //Check if new data has been submitted
        if ($new_weight != null && $new_height != null && $new_width != null && $new_depth != null) {
            //New weight and dimensions have been added - get box for these dimensions
            $postage_band = $em->getRepository('MilesApartAdminBundle:PostageBand')->findPostageBandBySizes($new_width, $new_height, $new_depth, $new_weight);

            foreach ($postage_band[0]->getPostageBandDispatchLogistics() as $postage_option) {

                //If first class
                if ($postage_option->getPostageType()->getId() == 1) {
                    $first_class_option = $postage_option;

                    //If second class
                } else if ($postage_option->getPostageType()->getId() == 2) {
                    $second_class_option = $postage_option;
                }
            }

            //Then persist this box against the customer order 
            //Check the existing order pbdl to see if first or send class
            if ($order->getDeliveryOption()->getPostageType()->getId() == 1) {
                //Postage is first class
                $order->setDeliveryOption($first_class_option);
            } else if ($order->getDeliveryOption()->getPostageType()->getId() == 2) {
                //Postage is second class
                $order->setDeliveryOption($second_class_option);
            }

            $em->flush();
        }

        //Call the create shipment api
        $ajax_response = $royal_mail_service->createShipmentAPICall($order);

        //Create shipment entity and populate data
        $shipment = new RoyalMailShipment();
        $shipment->setRoyalMailShipmentNumber(3);




        return $ajax_response;
    }

    //Creates and then prints a manifest with all unmanifested items
    function printManifestAction() 
	{
        //Get the Royal Mail service
        $royal_mail_service = $this->get('staff.royal_mail_service');

        //Call service api
        $create_manifest_API_call_response = $royal_mail_service->createManifestAPICall();

        //Check if there has been a fault with the API call
        if($create_manifest_API_call_response['array']->xpath('//Fault')) {
            //Render the page from template, showing the error
            return $this->render('MilesApartStaffBundle:RoyalMail:response.html.twig', array(
                'create_manifest_API_call_response' => $create_manifest_API_call_response,
                'manifest'=> false,

            ));
        }

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

        //Call the print manifest function
        $manifest = $this->printManifestFunction($manifest_batch_number);
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:RoyalMail:response.html.twig', array( 
            'create_manifest_API_call_response' => $create_manifest_API_call_response,
            'manifest'=> $manifest,

        ));

	}

	//Function to print a manifest (either an existing or newly created, depending on what called it).
	public function printManifestFunction($manifest_batch_number)
    {
        //Get the Royal Mail service
        $royal_mail_service = $this->get('staff.royal_mail_service');

        //Call service api
        $print_manifest_API_call_response = $royal_mail_service->printManifestAPICall($manifest_batch_number);

        //Check for faults
        if($print_manifest_API_call_response['array']->xpath('//Fault')) {
            return false;
        }

        //Check if the print was successful andf update the DB
        if(array_key_exists('manifest', $print_manifest_API_call_response['array'][0]['printManifestResponse'])) {

        	$manifest = $print_manifest_API_call_response['array'][0]['printManifestResponse']['manifest'];

        	//Update the state in the database 
	        $em = $this->getDoctrine()->getManager();

	        //Get the shipping manifest object
	        $shipping_manifest = $em->getRepository('MilesApartAdminBundle:ShippingManifest')->findOneBy(array('royal_mail_batch_number' => $manifest_batch_number));

            //Set the state
	        $shipping_manifest->setShippingManifestState($em->getRepository('MilesApartAdminBundle:ShippingManifestState')->findOneById(2));

	        //Persist changes
	        $em->persist($shipping_manifest);
	        $em->flush();

        	return $manifest;
        } else {
        	return false;
        }
	} 

	//Show all manifests
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

        //Get the manifests
        $manifests = $em->getRepository('MilesApartAdminBundle:ShippingManifest')->findBy(array(), array('shipping_manifest_date_created' => 'DESC'));

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:view_manifests.html.twig', array(
            'manifests' => $manifests,
        ));
    }

    //View a manifest's details page
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

        //Get the manifest from the db
        $manifest = $em->getRepository('MilesApartAdminBundle:ShippingManifest')->findOneById($manifest_id);

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:view_manifest_details.html.twig', array(
            'manifest' => $manifest,
        ));
    }

    //Print an individual manifest selected from the list
    public function printindividualmanifestAction($manifest_id) 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));
        
        //Get entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the manifest from the db
        $shipping_manifest = $em->getRepository('MilesApartAdminBundle:ShippingManifest')->findOneById($manifest_id);

        //Call the function that calls the API
        $manifest = $this->printManifestFunction($shipping_manifest->getRoyalMailBatchNumber());
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:RoyalMail:response.html.twig', array(
            'manifest'=> $manifest,
        ));
    }

    //Cancel a Royal Mail shipment - cannot be done if manifested
    public function cancelshipmentAction($shipment_number) 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));

        //Get entity manager
        $em = $this->getDoctrine()->getManager();

        //Get the shipment
        $shipment = $em->getRepository('MilesApartAdminBundle:RoyalMailShipment')->findOneById($shipment_number);

        //Get the Royal Mail service
        $royal_mail_service = $this->get('staff.royal_mail_service');

        //Call service api
        $cancel_shipment_response = $royal_mail_service->cancelShipmentRequestAPICall($shipment);

        //Check if there has been a fault with the API call
        if($cancel_shipment_response['array']->xpath('//Fault')) {
            //Render the page from template, showing the error
            return $this->render('MilesApartStaffBundle:Pickpack:cancel_shipment.html.twig', array(
                'cancel_shipment_response' => $cancel_shipment_response,
                'cancel_shipment_success'=> false,

            ));
        }

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

    //Show list of all shipments
	public function viewshipmentsAction() 
    {
    	//Set up breadcrumb.
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add home to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_staff_homepage"));
        // Add pick & pack to breadcrumb.
        $breadcrumbs->addItem("Pick & Pack Notifications", $this->get("router")->generate("staff-pickpack_notifications"));
        
        //Get entity manager
        $em = $this->getDoctrine()->getManager();

        //Get outstanding shipments to check if they need to be marked as manifested
        $outstanding_shipments = $em->getRepository('MilesApartAdminBundle:RoyalMailShipment')->findBy(array('royal_mail_shipment_state' => 2));

        //Check any shipments that need to be marked as manifested (as Royal Mail manifests automatically at end of the day).
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

        //Get all shipments
        $shipments = $em->getRepository('MilesApartAdminBundle:RoyalMailShipment')->findBy(array(), array('royal_mail_shipment_date_created' => 'DESC'));
        
        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:view_shipments.html.twig', array(
            'shipments' => $shipments,
        ));
    }

    //Show the shipment details page
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
        //Check if there has been a submission fault
        if(count($xml->xpath('//Fault')) == 1) {
            //There has been a faults
            $body = $xml->xpath('//soapenv:Body');
            $cancelled = FALSE;
            $array = $xml;
            //Check if there have been any errors
        } else if(count($xml->xpath('//*')) == 9) {
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

        //Render the page from template
        return $this->render('MilesApartStaffBundle:Pickpack:view_shipment_details.html.twig', array(
            'shipment' => $shipment,
            'array'=> $array,
            ));
   
    }





	
}



