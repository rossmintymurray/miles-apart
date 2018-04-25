<?php
// src/MilesApart/AdminBundle/Entity/Campaign.php -- Defines the business premises type

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CampaignRepository")
 * @ORM\Table(name="campaign")
 * @ORM\HasLifecycleCallbacks()
 */

class Campaign
{
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
    protected $campaign_name;

    /**
     * @Gedmo\Slug(fields={"campaign_name"}, separator="-")
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $campaign_name_slug;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=false)
     */
    protected $campaign_introduction;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=false)
     */
    protected $campaign_description;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=false)
     */
    protected $campaign_objective;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $campaign_start_date;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $campaign_end_date;

    /**
     * @ORM\ManyToOne(targetEntity="CampaignType", inversedBy="campaign")
     * @ORM\JoinTable(name="campaign_type")
     * @ORM\JoinColumn(name="campaign_type_id", referencedColumnName="id")
     */
    protected $campaign_type;

    /**
     * @ORM\OneToMany(targetEntity="Promotion", mappedBy="campaign", cascade={"persist"})
     */
    protected $promotion;
    


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
         //Promotion name
        $metadata->addPropertyConstraint('campaign_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('campaign_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 200,
            'minMessage' => 'The campaign name must be at least {{ limit }} characters length',
            'maxMessage' => 'The campaign name cannot be longer than {{ limit }} characters length',
        )));

        //Promotion introduction
        $metadata->addPropertyConstraint('campaign_introduction', new Assert\NotBlank());
        $metadata->addPropertyConstraint('campaign_introduction', new Assert\Length(array(
            'min'        => 4,
            'max'        => 2000,
            'minMessage' => 'The campaign introduction must be at least {{ limit }} characters length',
            'maxMessage' => 'The campaign introduction cannot be longer than {{ limit }} characters length',
        )));

        //Promotion description
        $metadata->addPropertyConstraint('campaign_description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('campaign_description', new Assert\Length(array(
            'min'        => 4,
            'max'        => 2000,
            'minMessage' => 'The campaign description must be at least {{ limit }} characters length',
            'maxMessage' => 'The campaign description cannot be longer than {{ limit }} characters length',
        )));

        //Promotion objective
        $metadata->addPropertyConstraint('campaign_objective', new Assert\NotBlank());
        $metadata->addPropertyConstraint('campaign_objective', new Assert\Length(array(
            'min'        => 4,
            'max'        => 2000,
            'minMessage' => 'The campaign objective must be at least {{ limit }} characters length',
            'maxMessage' => 'The campaign objective cannot be longer than {{ limit }} characters length',
        )));
    }


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->promotion = new \Doctrine\Common\Collections\ArrayCollection();

        //Set the start and end date to todays date for first time loaded 
        $this->setCampaignStartDate(new \DateTime());
        $this->setCampaignEndDate(new \DateTime());
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
     * Set campaign_name
     *
     * @param string $campaignName
     * @return Campaign
     */
    public function setCampaignName($campaignName)
    {
        $this->campaign_name = $campaignName;

        return $this;
    }

    /**
     * Get campaign_name
     *
     * @return string 
     */
    public function getCampaignName()
    {
        return $this->campaign_name;
    }

    /**
     * Set campaign_name_slug
     *
     * @param string $campaignNameSlug
     * @return Campaign
     */
    public function setCampaignNameSlug($campaignNameSlug)
    {
        $this->campaign_name_slug = $campaignNameSlug;
    
        return $this;
    }

    /**
     * Get campaign_name_slug
     *
     * @return string 
     */
    public function getCampaignNameSlug()
    {
        return $this->campaign_name_slug;
    }

    /**
     * Set campaign_introduction
     *
     * @param string $campaignIntroduction
     * @return Campaign
     */
    public function setCampaignIntroduction($campaignIntroduction)
    {
        $this->campaign_introduction = $campaignIntroduction;

        return $this;
    }

    /**
     * Get campaign_introduction
     *
     * @return string 
     */
    public function getCampaignIntroduction()
    {
        return $this->campaign_introduction;
    }

    /**
     * Set campaign_description
     *
     * @param string $campaignDescription
     * @return Campaign
     */
    public function setCampaignDescription($campaignDescription)
    {
        $this->campaign_description = $campaignDescription;

        return $this;
    }

    /**
     * Get campaign_description
     *
     * @return string 
     */
    public function getCampaignDescription()
    {
        return $this->campaign_description;
    }

    /**
     * Set campaign_objective
     *
     * @param string $campaignObjective
     * @return Campaign
     */
    public function setCampaignObjective($campaignObjective)
    {
        $this->campaign_objective = $campaignObjective;

        return $this;
    }

    /**
     * Get campaign_objective
     *
     * @return string 
     */
    public function getCampaignObjective()
    {
        return $this->campaign_objective;
    }

    /**
     * Set campaign_start_date
     *
     * @param \DateTime $campaignStartDate
     * @return Campaign
     */
    public function setCampaignStartDate($campaignStartDate)
    {
        $this->campaign_start_date = $campaignStartDate;

        return $this;
    }

    /**
     * Get campaign_start_date
     *
     * @return \DateTime 
     */
    public function getCampaignStartDate()
    {
        return $this->campaign_start_date;
    }

    /**
     * Set campaign_end_date
     *
     * @param \DateTime $campaignEndDate
     * @return Campaign
     */
    public function setCampaignEndDate($campaignEndDate)
    {
        $this->campaign_end_date = $campaignEndDate;

        return $this;
    }

    /**
     * Get campaign_end_date
     *
     * @return \DateTime 
     */
    public function getCampaignEndDate()
    {
        return $this->campaign_end_date;
    }

    /**
     * Set campaign_type
     *
     * @param \MilesApart\AdminBundle\Entity\CampaignType $campaignType
     * @return Campaign
     */
    public function setCampaignType(\MilesApart\AdminBundle\Entity\CampaignType $campaignType = null)
    {
        $this->campaign_type = $campaignType;

        return $this;
    }

    /**
     * Get campaign_type
     *
     * @return \MilesApart\AdminBundle\Entity\CampaignType 
     */
    public function getCampaignType()
    {
        return $this->campaign_type;
    }

    /**
     * Add promotion
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotion
     * @return Campaign
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
}
