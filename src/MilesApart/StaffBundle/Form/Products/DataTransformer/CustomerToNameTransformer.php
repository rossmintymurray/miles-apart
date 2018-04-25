<?php 
// src/AppBundle/Form/DataTransformer/CustomerToNameTransformer.php
namespace MilesApart\StaffBundle\Form\Products\DataTransformer;

use MilesApart\AdminBundle\Entity\Customer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CustomerToNameTransformer implements DataTransformerInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Transforms an object (customer) to a string (name).
     *
     * @param  Issue|null $customer
     * @return string
     */
    public function transform($customer)
    {
        if (null === $customer) {
            return '';
        }

        return $customer->getCustomerFullName();
    }

    /**
     * Transforms a string (number) to an object (customer name).
     *
     * @param  string $customerName
     * @return Issue|null
     * @throws TransformationFailedException if object (customer name) is not found.
     */
    public function reverseTransform($CustomerFullName)
    {
        // no customer name It's optional, so that's ok
        if (!$CustomerFullName) {
            return;
        }

        $CustomerFullName = $this->objectManager
            ->getRepository(Customer::class)
            // query for the customer with this name
            ->find($CustomerFullName)
        ;

        if (null === $CustomerFullName) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An customer with name "%s" does not exist!',
                $CustomerFullName
            ));
        }

        return $CustomerFullName;
    }
}