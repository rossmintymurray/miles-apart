<?php
// src/MilesApart/AdminBundle/Entity/PromotionType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PromotionTypeRepository")
 * @ORM\Table(name="promotion_type")
 * @ORM\HasLifecycleCallbacks()
 */

class PromotionType
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
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $promotion_type_name;

    /**
     * @Gedmo\Slug(fields={"promotion_type_name"}, separator="-")
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $promotion_type_name_slug;

    /**
     * @ORM\OneToMany(targetEntity="Promotion", mappedBy="promotion_type")
     */
    protected $promotion;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Promotion type name
        $metadata->addPropertyConstraint('promotion_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('promotion_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 200,
            'minMessage' => 'The promotion type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The promotion type name cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->promotion = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set promotion_type_name
     *
     * @param string $promotionTypeName
     * @return PromotionType
     */
    public function setPromotionTypeName($promotionTypeName)
    {
        $this->promotion_type_name = $promotionTypeName;
    
        return $this;
    }

    /**
     * Get promotion_type_name
     *
     * @return string 
     */
    public function getPromotionTypeName()
    {
        return $this->promotion_type_name;
    }

    /**
     * Add promotion
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotion
     * @return PromotionType
     */
    public function addPromotion(\MilesApart\AdminBundle\Entity\Promotion $promotion)
    {
        $this->promotion[] = $promotion;
    
        return $this;
    }

    /**
     * Remove promotion
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotion
     */
    public function removePromotion(\MilesApart\AdminBundle\Entity\Promotion $promotion)
    {
        $this->promotion->removeElement($promotion);
    }

    /**
     * Get promotion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set promotion_type_name_slug
     *
     * @param string $promotionTypeNameSlug
     * @return PromotionType
     */
    public function setPromotionTypeNameSlug($promotionTypeNameSlug)
    {
        $this->promotion_type_name_slug = $promotionTypeNameSlug;

        return $this;
    }

    /**
     * Get promotion_type_name_slug
     *
     * @return string 
     */
    public function getPromotionTypeNameSlug()
    {
        return $this->promotion_type_name_slug;
    }
}
