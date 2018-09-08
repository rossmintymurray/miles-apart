<?php 
// src/AppBundle/Form/DataTransformer/ProductToIdTransformer.php
namespace MilesApart\PublicBundle\Form\DataTransformer;

use MilesApart\AdminBundle\Entity\Customer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CustomerToIdTransformer implements DataTransformerInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Transforms an object (customer) to a string (number).
     *
     * @param  Issue|null $customer
     * @return string
     */
    public function transform($customer)
    {
        if (null === $customer) {
            return '';
        }

        return $customer->getId();
    }

    /**
     * Transforms a string (number) to an object (product).
     *
     * @param  string $productNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (product) is not found.
     */
    public function reverseTransform($customerNumber)
    {
        // no product number? It's optional, so that's ok
        if (!$customerNumber) {
            return;
        }

        $customer = $this->objectManager
            ->getRepository(Customer::class)
            // query for the product with this id
            ->find($customerNumber)
        ;

        if (null === $customer) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'A customer with number "%s" does not exist!',
                $customerNumber
            ));
        }

        return $customer;
    }
}