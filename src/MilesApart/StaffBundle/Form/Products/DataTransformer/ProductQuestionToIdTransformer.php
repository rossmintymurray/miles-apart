<?php 
// src/AppBundle/Form/DataTransformer/ProductQuestionToIdTransformer.php
namespace MilesApart\StaffBundle\Form\Products\DataTransformer;

use MilesApart\AdminBundle\Entity\ProductQuestion;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductQuestionToIdTransformer implements DataTransformerInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Transforms an object (product question) to an (id).
     *
     * @param  Issue|null $productQuestion
     * @return string
     */
    public function transform($product_question)
    {
        if (null === $product_question) {
            return '';
        }

        return $product_question->getId();
    }

    /**
     * Transforms a string (number) to an object (product question).
     *
     * @param  string $customerName
     * @return Issue|null
     * @throws TransformationFailedException if object (product question) is not found.
     */
    public function reverseTransform($productQuestionId)
    {
        // no product question It's optional, so that's ok
        if (!$productQuestionId) {
            return;
        }

        $product_question = $this->objectManager
            ->getRepository(ProductQuestion::class)
            // query for the product question with this id
            ->find($productQuestionId)
        ;

        if (null === $product_question) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An product question with id "%s" does not exist!',
                $productQuestionId
            ));
        }

        return $product_question;
    }
}