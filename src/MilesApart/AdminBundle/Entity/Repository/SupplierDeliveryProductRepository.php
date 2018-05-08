<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SupplierDeliveryProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SupplierDeliveryProductRepository extends EntityRepository
{
	//Check the products for array match. (For CSV import)
    public function findProductByBarcode($product_barcode, $supplier_delivery_id)
    {
        //Check if there is a value for product id.
        if ($product_barcode && $supplier_delivery_id) {
                $query = $this->getEntityManager()
                        ->createQuery('
                            SELECT sdp FROM MilesApartAdminBundle:SupplierDeliveryProduct sdp
                            JOIN sdp.product p
                            WHERE p.product_barcode = :product_barcode
                            AND sdp.supplier_delivery = :supplier_delivery_id 
                            ')
                        ->setParameter('product_barcode', $product_barcode)
                        ->setParameter('supplier_delivery_id', $supplier_delivery_id);
            } 
        return $query
                  ->getResult(); 
   }

}