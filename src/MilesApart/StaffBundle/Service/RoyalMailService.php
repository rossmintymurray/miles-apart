<?php

namespace MilesApart\StaffBundle\Service;
use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

use MilesApart\AdminBundle\Entity\CustomerOrder;
use MilesApart\AdminBundle\Entity\RoyalMailShipment;
use MilesApart\AdminBundle\Entity\ShippingManifest;

class RoyalMailService
{
    /**
     *
     * @var EntityManager
     */
    protected $em;
    private $logger;

    private $order;
    private $shipment;
    private $manifest;

    private $endpoint;
    private $application_id;
    private $client_id;
    private $client_secret;
    private $username;
    private $password;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger, CustomerOrder $order, RoyalMailShipment $shipment, ShippingManifest $manifest, $endpoint, $application_id, $client_id, $client_secret, $username, $password)
    {
        $this->em = $entityManager;
        $this->logger = $logger;
        $this->order = $order;
        $this->shipment = $shipment;
        $this->manifest = $manifest;
        $this->endpoint = $endpoint;
        $this->application_id = $application_id;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->username = $username;
        $this->password = $password;
    }


    /*************************************************
     * Creating shipment
     *************************************************/
    public function createShipmentAPICall($order, $shipment)
    {
        $logger = $this->logger;
        /*************************************************
         * Creating shipment
         *************************************************/
        /*
        * Takes address details, service details and references
        * Creates new shipment with Royal Mail Shipping API
        *
        */

        /*
        * Dynamic varaibles
        */
        //Transaction id should be random number
        $transactionId = mt_rand();

        $serviceType = $order->getDeliveryOption()->getPostageType()->getPostageTypeRoyalMailCode();
        $serviceCode = "CRL";
        $serviceFormat = $order->getDeliveryOption()->getPostageBand()->getPostageBandType()->getRoyalMailPostageBandTypeCode();

        //Set the address data
        //Check if contact name is set
        if ($order->getDeliveryAddress()->getCustomerAddressContactFullName()) {
            $recipientContactName = $order->getDeliveryAddress()->getCustomerAddressContactFullName();
        } else {

            //Check if personal customer

            $recipientContactName = $order->getCustomerOrderFullName();
        }
        $recipientAddressLine1 = $order->getDeliveryAddress()->getCustomerAddressLine1();
        //Check if there is a customer address line 2
        if ($order->getDeliveryAddress()->getCustomerAddressLine2() != NULL) {
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

        /*
        * Static varaibles
        */
        //Set the URL for the API call
        $apiURL = $this->endpoint;

        //Set the current datetime
        $dateTime = date('Y-m-d\TH:i:s');

        //Set the call specific data
        $applicationId = $this->application_id;

        $shippingDate = gmdate('Y-m-d');
        $shipmentType = "Delivery";
        //$serviceOccurrence = "";

        /* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
        */
        $clientId = $this->client_id;
        $clientSecret = $this->client_secret;
        /* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
        */
        $username = $this->username;
        $password = $this->password;

        /* CREATIONDATE - The timestamp. The computer must be on correct time or the server you are * connecting may reject the password digest for security.
        */
        $creationDate = gmdate('Y-m-d\TH:i:s\Z');

        /* NONCE - A random word.
        * The use of rand() may repeat the word if the server is very loaded. */
        $nonce = mt_rand();

        /* PASSWORDDIGEST This is the way to create the password digest. As per OASIS standard * digest = base64_encode(Sha1(nonce + creationdate + Sha1(password)))
        * Note that we use a Sha1(password) instead of the plain password above
        */
        $nonce_date_pwd = $nonce . $creationDate . base64_encode(sha1($password, TRUE));
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
            ),));
        //Handle the curl response
        $response = curl_exec($curl);
        //Handle any curl errors
        $err = curl_error($curl);

        //Close the curl connection
        curl_close($curl);
        //Process the returned SOAP into array
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        if ($response != null) {
            $xml = new \SimpleXMLElement($response);

            //Check if there has been a submission fault
            if (count($xml->xpath('//Fault')) == 1) {
                //There has been a faults
                $body = $xml->xpath('//soapenv:Body');
                $cancelled = FALSE;
                $array = $xml;
                $allocated = FALSE;
                //Check if there have been any errors
            } else if (count($xml->xpath('//*')) == 9) {
                //There has been a faults
                $body = $xml->xpath('//soapenv:Body');
                $cancelled = FALSE;
                $array = $xml;
            } else {
                //No fault
                $body = $xml->xpath('//SOAP-ENV:Body');
                $cancelled = TRUE;
                $array = json_decode(json_encode($body), TRUE);
                $allocated = true;
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
        return new JsonResponse(
            $return
        );
    }

    /*************************************************
     * Create label
     *************************************************/
    public function createLabelAPICall($shipment)
    {
        $shipment_number = $shipment->getRoyalMailShipmentNumber();
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
        $apiURL = $this->endpoint;

        //Set the current datetime
        $dateTime = date('Y-m-d\TH:i:s');

        //Set the call specific data
        $applicationId = $this->application_id;

        $shippingDate = gmdate('Y-m-d');
        $shipmentType = "Delivery";
        //$serviceOccurrence = "";

        /* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
        */
        $clientId = $this->client_id;
        $clientSecret = $this->client_secret;

        /* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
        */
        $username = $this->username;
        $password = $this->password;

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
 
					<v2:shipmentNumber>$shipment_number</v2:shipmentNumber>\r\n
 
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

        //Check if there has been a submission fault
        if (count($xml->xpath('//Fault')) == 1) {
            //There has been a faults
            $body = $xml->xpath('//soapenv:Body');
            $cancelled = FALSE;
            $array = $xml;
            $allocated = FALSE;
            //Check if there have been any errors
        } else if (count($xml->xpath('//*')) == 9) {
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
        $apiURL = $this->endpoint;

        //Set the current datetime
        $dateTime = date('Y-m-d\TH:i:s');

        //Set the call specific data
        $applicationId = $this->application_id;

        $shippingDate = gmdate('Y-m-d');
        $shipmentType = "Delivery";
        //$serviceOccurrence = "";

        /* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
        */
        $clientId = $this->client_id;
        $clientSecret = $this->client_secret;

        /* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
        */
        $username = $this->username;
        $password = $this->password;

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
 
					<v2:yourDescription>Manifest</v2:yourDescription>\r\n
 
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

        //Check if there has been a submission fault
        if (count($xml->xpath('//Fault')) == 1) {
            //There has been a faults
            $body = $xml->xpath('//soapenv:Body');
            $cancelled = FALSE;
            $array = $xml;
            $allocated = FALSE;
            //Check if there have been any errors
        } else if (count($xml->xpath('//*')) == 9) {
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

        );

        return $return;
    }




    /*************************************************
     * Print manifest
     *************************************************/
    public function printManifestAPICall()
    { $logger = $this->logger;

        $logger->info('In the service again');
        $manifest_batch_number = $this->manifest->getRoyalMailBatchNumber();

        $logger->info('In the service again2');

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
        $apiURL = $this->endpoint;

        //Set the current datetime
        $dateTime = date('Y-m-d\TH:i:s');

        //Set the call specific data
        $applicationId = $this->application_id;

        /* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
        */
        $clientId = $this->client_id;
        $clientSecret = $this->client_secret;

        /* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
        */
        $username = $this->username;
        $password = $this->password;

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

        //Check if there has been a submission fault
        if (count($xml->xpath('//Fault')) == 1) {
            //There has been a faults
            $body = $xml->xpath('//soapenv:Body');
            $cancelled = FALSE;
            $array = $xml;
            $allocated = FALSE;
            //Check if there have been any errors
        } else if (count($xml->xpath('//*')) == 9) {
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
        $apiURL = $this->endpoint;

        //Set the current datetime
        $dateTime = date('Y-m-d\TH:i:s');

        //Set the call specific data
        $applicationId = $this->application_id;

        $shippingDate = gmdate('Y-m-d');
        $shipmentType = "Delivery";
        //$serviceOccurrence = "";

        /* Change the values below to the ClientID and Secret values associated with the application you * registered on the API Developer Portal
        */
        $clientId = $this->client_id;
        $clientSecret = $this->client_secret;

        /* The value below should be changed to your actual username and password. If you store the password * as hashed in your database, you will need to change the code below to remove hashing
        */
        $username = $this->username;
        $password = $this->password;

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

        //Check if there has been a submission fault
        if (count($xml->xpath('//Fault')) == 1) {
            //There has been a faults
            $body = $xml->xpath('//soapenv:Body');
            $cancelled = FALSE;
            $array = $xml;
            $allocated = FALSE;
            //Check if there have been any errors
        } else if (count($xml->xpath('//*')) == 9) {
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

        );

        return $return;
    }

    /*************************************************
     * Cancel shipment
     *************************************************/
    public function cancelShipmentRequestAPICall()
    {
       //Get the shipment number
        $shipment_number = $this->shipment->getRoyalMailShipmentNumber();

        //Transaction id should be random number
        $transactionId = mt_rand();

        //Set the URL for the API call
        $apiURL = $this->endpoint;

        //Set the current datetime
        $dateTime = date('Y-m-d\TH:i:s');

        //Set the call specific data
        $applicationId = $this->application_id;

        //Client Id and secret
        $clientId = $this->client_id;
        $clientSecret = $this->client_secret;

        //User name and password
        $username = $this->username;
        $password = $this->password;

        //Creation date
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

        //Set up CURL
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