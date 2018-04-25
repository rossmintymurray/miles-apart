<?php
// src/MilesApart/AdminBundle/Entity/ProductAnswer.php -- Defines the product answer object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductAnswerRepository")
 * @ORM\Table(name="product_answer")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductAnswer
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
    protected $product_answer_text;
                 
    /**
     * @ORM\OneToOne(targetEntity="ProductQuestion", inversedBy="product_answer")
     * @ORM\JoinColumn(name="product_question_id", referencedColumnName="id")
     **/

    /**
     * @ORM\ManyToOne(targetEntity="ProductQuestion", inversedBy="product_answer")
     * @ORM\JoinTable(name="product_question")
     * @ORM\JoinColumn(name="product_question_id", referencedColumnName="id")
     */
     
    protected $product_question;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="product_answer")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="admin_user_id", referencedColumnName="id")
     */
    protected $admin_user;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_answer_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_answer_date_modified;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setProductAnswerDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getProductAnswerDateCreated() == null)
        {
            $this->setProductAnswerDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Product answer text
        $metadata->addPropertyConstraint('product_answer_text', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_answer_text', new Assert\Length(array(
            'min'        => 4,
            'max'        => 2000,
            'minMessage' => 'The answer must be at least {{ limit }} characters length',
            'maxMessage' => 'The answer cannot be longer than {{ limit }} characters length',
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
     * Set product_answer_text
     *
     * @param string $productAnswerText
     * @return ProductAnswer
     */
    public function setProductAnswerText($productAnswerText)
    {
        $this->product_answer_text = $productAnswerText;
    
        return $this;
    }

    /**
     * Get product_answer_text
     *
     * @return string 
     */
    public function getProductAnswerText()
    {
        return $this->product_answer_text;
    }

    /**
     * Set product_answer_date_created
     *
     * @param \DateTime $productAnswerDateCreated
     * @return ProductAnswer
     */
    public function setProductAnswerDateCreated($productAnswerDateCreated)
    {
        $this->product_answer_date_created = $productAnswerDateCreated;
    
        return $this;
    }

    /**
     * Get product_answer_date_created
     *
     * @return \DateTime 
     */
    public function getProductAnswerDateCreated()
    {
        return $this->product_answer_date_created;
    }

    /**
     * Set product_answer_date_modified
     *
     * @param \DateTime $productAnswerDateModified
     * @return ProductAnswer
     */
    public function setProductAnswerDateModified($productAnswerDateModified)
    {
        $this->product_answer_date_modified = $productAnswerDateModified;
    
        return $this;
    }

    /**
     * Get product_answer_date_modified
     *
     * @return \DateTime 
     */
    public function getProductAnswerDateModified()
    {
        return $this->product_answer_date_modified;
    }

    /**
     * Set product_question
     *
     * @param \MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion
     * @return ProductAnswer
     */
    public function setProductQuestion(\MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion = null)
    {
        $this->product_question = $productQuestion;
    
        return $this;
    }

    /**
     * Get product_question
     *
     * @return \MilesApart\AdminBundle\Entity\ProductQuestion 
     */
    public function getProductQuestion()
    {
        return $this->product_question;
    }

    /**
     * Set admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $adminUser
     * @return ProductAnswer
     */
    public function setAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $adminUser = null)
    {
        $this->admin_user = $adminUser;
    
        return $this;
    }

    /**
     * Get admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getAdminUser()
    {
        return $this->admin_user;
    }
}