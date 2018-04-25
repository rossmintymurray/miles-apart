<?php
// src/MilesApart/AdminBundle/Entity/ProductQuestion.php -- Defines the product question object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductQuestionRepository")
 * @ORM\Table(name="product_question")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductQuestion
{
    //Define the values

    /**
     *  
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=false)
     */
    protected $product_question_text;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="product_question")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_question_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_question_date_modified;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="product_question")
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="ProductAnswer", mappedBy="product_question")
     */
    protected $product_answer;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setProductQuestionDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getProductQuestionDateCreated() == null)
        {
            $this->setProductQuestionDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Product question text
        $metadata->addPropertyConstraint('product_question_text', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_question_text', new Assert\Length(array(
            'min'        => 4,
            'max'        => 2000,
            'minMessage' => 'Your question must be at least {{ limit }} characters length',
            'maxMessage' => 'Your question cannot be longer than {{ limit }} characters length',
        )));

    }




    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product_question_text
     *
     * @param string $productQuestionText
     * @return ProductQuestion
     */
    public function setProductQuestionText($productQuestionText)
    {
        $this->product_question_text = $productQuestionText;
    
        return $this;
    }

    /**
     * Get product_question_text
     *
     * @return string 
     */
    public function getProductQuestionText()
    {
        return $this->product_question_text;
    }

    /**
     * Set product_question_date_created
     *
     * @param \DateTime $productQuestionDateCreated
     * @return ProductQuestion
     */
    public function setProductQuestionDateCreated($productQuestionDateCreated)
    {
        $this->product_question_date_created = $productQuestionDateCreated;
    
        return $this;
    }

    /**
     * Get product_question_date_created
     *
     * @return \DateTime 
     */
    public function getProductQuestionDateCreated()
    {
        return $this->product_question_date_created;
    }

    /**
     * Set product_question_date_modified
     *
     * @param \DateTime $productQuestionDateModified
     * @return ProductQuestion
     */
    public function setProductQuestionDateModified($productQuestionDateModified)
    {
        $this->product_question_date_modified = $productQuestionDateModified;
    
        return $this;
    }

    /**
     * Get product_question_date_modified
     *
     * @return \DateTime 
     */
    public function getProductQuestionDateModified()
    {
        return $this->product_question_date_modified;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return ProductQuestion
     */
    public function setProduct(\MilesApart\AdminBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \MilesApart\AdminBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return ProductQuestion
     */
    public function setCustomer(\MilesApart\AdminBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;
    
        return $this;
    }

    /**
     * Get customer
     *
     * @return \MilesApart\AdminBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set product_answer
     *
     * @param \MilesApart\AdminBundle\Entity\ProductAnswer $productAnswer
     * @return ProductQuestion
     */
    public function setProductAnswer(\MilesApart\AdminBundle\Entity\ProductAnswer $productAnswer = null)
    {
        $this->product_answer = $productAnswer;
    
        return $this;
    }

    /**
     * Get product_answer
     *
     * @return \MilesApart\AdminBundle\Entity\ProductAnswer 
     */
    public function getProductAnswer()
    {
        return $this->product_answer;
    }
}