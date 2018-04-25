<?php
// src/MilesApart/StaffBundle/Controller/EbayController.php

namespace MilesApart\SellerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EbayController extends Controller
{
    /*************************************************
    * Ebay controller displays the functions and pages in ebay menu area.
    *************************************************/


    /*************************************************
    * All following code is creation of responses for eBay API
    *************************************************/

    //Code to get eBay Time
    public function geteBayTimeAPICall() 
    {

    	//Set up the parameters for the call
    	$api_version =
        $dev_id = 'fc50ab98-5fb6-4a12-ad7c-2bac4bed725d';
        $app_id = 'MilesApa-MilesApa-SBX-945f64428-36561f0e';
        $cert_id = 'SBX-45f644285ccf-a72e-4b62-b25b-c609';
        $call_name =
    	$site_id = 3; //UK


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
			'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $api_version,
            'X-EBAY-API-DEV-NAME: ' . $dev_id,
            'X-EBAY-API-APP-NAME: ' . $app_id,
            'X-EBAY-API-CERT-NAME: ' . $cert_id,
            'X-EBAY-API-CALL-NAME: ' . $call_name,
            'X-EBAY-API-SITEID: ' . $site_id,
		), 
		));
		
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

        ladybug_dump($array);

        return $return;
		
	}
   	<ReviseInventoryStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		<InventoryStatus>
			<ItemID>190004054177</ItemID>
			<Quantity>0</Quantity>
		</InventoryStatus>
	</ReviseInventoryStatusRequest>
}
