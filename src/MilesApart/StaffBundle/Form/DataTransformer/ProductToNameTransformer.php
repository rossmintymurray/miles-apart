<?php
// src/MilesApart/StaffBundle/Form/DataTransformer/ProductToNameTransformer.php
namespace MilesApart\StaffBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use MilesApart\AdminBundle\Entity\Product;

class ProductToNameTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (product) to a string (name).
     *
     * @param  Product|null $product
     * @return string
     */
    public function transform($product)
    {
        if (null === $product) {
            return "";
        }

        return $product->getProductName();
    }

    /**
     * Transforms a string (name) to an object (product).
     *
     * @param  string $name
     *
     * @return Product|null
     *
     * @throws TransformationFailedException if object (product) is not found.
     */
    public function reverseTransform($name)
    {
        if (!$name) {
            return null;
        }

        $product = $this->om
            ->getRepository('MilesApartAdminBundle:Product')
            ->findOneBy(array('product_name' => $name))
        ;

        if (null === $product) {
            throw new TransformationFailedException(sprintf(
                'An product with name "%s" does not exist!',
                $name
            ));
        }

        return $product;
    }
}