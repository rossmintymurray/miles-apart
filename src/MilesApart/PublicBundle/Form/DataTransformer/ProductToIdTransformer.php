<?php 
// src/AppBundle/Form/DataTransformer/ProductToIdTransformer.php
namespace MilesApart\PublicBundle\Form\DataTransformer;

use MilesApart\AdminBundle\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductToIdTransformer implements DataTransformerInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Transforms an object (product) to a string (number).
     *
     * @param  Issue|null $product
     * @return string
     */
    public function transform($product)
    {
        if (null === $product) {
            return '';
        }

        return $product->getId();
    }

    /**
     * Transforms a string (number) to an object (product).
     *
     * @param  string $productNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (product) is not found.
     */
    public function reverseTransform($productNumber)
    {
        // no product number? It's optional, so that's ok
        if (!$productNumber) {
            return;
        }

        $product = $this->objectManager
            ->getRepository(Product::class)
            // query for the product with this id
            ->find($productNumber)
        ;

        if (null === $product) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An product with number "%s" does not exist!',
                $productNumber
            ));
        }

        return $product;
    }
}