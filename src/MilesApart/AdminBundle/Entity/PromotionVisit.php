<?php
// src/MilesApart/AdminBundle/Entity/PromotionVisit.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PromotionVisitRepository")
 * @ORM\Table(name="promotion_visit")
 * @ORM\HasLifecycleCallbacks()
 */

class PromotionVisit
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
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $promotion_visit_datetime;

    /**
     * @ORM\ManyToOne(targetEntity="Promotion", inversedBy="promotion_visit")
     * @ORM\JoinTable(name="promotion")
     * @ORM\JoinColumn(name="promotion_id", referencedColumnName="id")
     */
    protected $promotion;

    

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Promotion visit datetime
        $metadata->addPropertyConstraint('promotion_visit_datetime', new Assert\NotBlank());
        $metadata->addPropertyConstraint('promotion_visit_datetime', new Assert\Date());

        //Promotion
        $metadata->addPropertyConstraint('promotion', new Assert\Choice(array(
            'callback' => 'getPromotion',
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
     * Set promotion_visit_datetime
     *
     * @param \DateTime $promotionVisitDatetime
     * @return PromotionVisit
     */
    public function setPromotionVisitDatetime($promotionVisitDatetime)
    {
        $this->promotion_visit_datetime = $promotionVisitDatetime;
    
        return $this;
    }

    /**
     * Get promotion_visit_datetime
     *
     * @return \DateTime 
     */
    public function getPromotionVisitDatetime()
    {
        return $this->promotion_visit_datetime;
    }

    /**
     * Set promotion
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotion
     * @return PromotionVisit
     */
    public function setPromotion(\MilesApart\AdminBundle\Entity\Promotion $promotion = null)
    {
        $this->promotion = $promotion;
    
        return $this;
    }

    /**
     * Get promotion
     *
     * @return \MilesApart\AdminBundle\Entity\Promotion 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }
}