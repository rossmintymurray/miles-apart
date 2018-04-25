<?php 
// src/StaffBundle/Service/AddProductToList.php
namespace MilesApart\StaffBundle\Service;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use MilesApart\AdminBundle\Entity\Product;
use MilesApart\AdminBundle\Entity\ProductPrice;
use MilesApart\AdminBundle\Entity\ProductSupplier;

class AddProductFromAddToList
{
    /**
     *
     * @var EntityManager 
     */
    protected $em;

    private $logger;

    private $session;

    private $product;

    private $product_price;

    private $product_supplier;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger, Session $session, Product $product, ProductPrice $product_price, ProductSupplier $product_supplier)
    {
        $this->em = $entityManager;
        $this->logger = $logger;
        $this->session = $session;
        $this->product = $product;
        $this->product_price = $product_price;
        $this->product_supplier = $product_supplier;

    }

    public function addProductToDatabase($response)
    {
        //Get entity manager
        $logger = $this->logger;
        $em = $this->em;
        
        $logger->info('I just got the logger add update price1');
        $logger->info($response["new_product_barcode"]);
        $logger->info($response["new_product_qty"]);
        $logger->info($response["new_product_supplier_code"]);
        $logger->info($response["new_product_price"]);
        $logger->info($response["new_product_supplier_id"]);
        
        //Set up variables from response 
        $new_product_name = $response["new_product_name"];
        $new_product_barcode = $response["new_product_barcode"];
        $new_product_transfer_qty = $response["new_product_qty"];
        $new_product_product_supplier_code = $response["new_product_supplier_code"];
        $new_product_product_price = $response["new_product_price"];
        $new_product_supplier_id = $response["new_product_supplier_id"];
        
        $logger->info('I just got the logger add update price2');
        //Get the supplier
        $supplier = $em->getRepository('MilesApartAdminBundle:Supplier')->findOneBy(array('id' => $new_product_supplier_id));
$logger->info('I just got the logger add update price3');
        //Set the session for next add.
        $session = $this->session;
        $session->set('new_product_supplier', $supplier);
$logger->info('I just got the logger add update price4');
        //Create new product in the database with the product name and barcode.
        $product = $this->product;
$logger->info('I just got the logger add update price5');
        //Set product values
        $product->setProductName($new_product_name);
        $product->setProductBarcode($new_product_barcode);
        $product->setProductSupplierCode($new_product_product_supplier_code);
$logger->info('I just got the logger add update price6');
        //Add roduct price if it has been set
        if ($new_product_product_price) {
            $product_price = $this->product_price;
$logger->info('I just got the logger add update price7');
            //Add product price values 
            $product_price->setProductPriceValue($new_product_product_price);
            $product_price->setProduct($product);
            $product_price->setProductPriceValidFrom(new \DateTime());
$logger->info('I just got the logger add update price8');
            //Persist product price
            $em->persist($product_price);
             
            //Add the product price to the product
            $product->addProductPrice($product_price);
        }
$logger->info('I just got the logger add update price9');
        //Add the product supplier 
        $product_supplier = $this->product_supplier;
        $product_supplier->setDefaultSupplier(true);
        $product_supplier->setSupplier($supplier);
    $logger->info('I just got the logger add update price10');
        //Persist product
        $em->persist($product);

        //Assign the product to product supplier and persist
        $product_supplier->setProduct($product);
        $em->persist($product_supplier);
   $logger->info('I just got the logger add update price11');
        //Return product
        return $product;
    }
    
}