<?php
// src/MilesApart/AdminBundle/Entity/Promotion.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PromotionRepository")
 * @ORM\Table(name="promotion")
 * @ORM\HasLifecycleCallbacks()
 */

class Promotion
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
     * @ORM\Column(type="string", length=200, unique=true, nullable=false)
     */
    protected $promotion_name;

    /**
     * @Gedmo\Slug(fields={"promotion_name"}, separator="-")
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $promotion_name_slug;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $promotion_start_date;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $promotion_end_date;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $promotion_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $promotion_date_modified;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $promotion_impressions;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $promotion_click_throughs;

    /**
     * @ORM\Column(type="string", length=400)
     */
    protected $promotion_landing_page;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $promotion_total_cost;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $vanity_url_append_text;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="promotion_creator")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="promotion_creator_admin_user_id", referencedColumnName="id")
     */
    protected $promotion_creator_admin_user;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="promotion_modifier")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="promotion_modifier_admin_user_id", referencedColumnName="id")
     */
    protected $promotion_modifier_admin_user;

    /**
     * @ORM\OneToMany(targetEntity="MarketingEmail", mappedBy="promotion")
     */
    protected $marketing_email;

    /**
     * @ORM\ManyToOne(targetEntity="TrafficSource", inversedBy="promotion")
     * @ORM\JoinTable(name="traffic_source")
     * @ORM\JoinColumn(name="traffic_source_id", referencedColumnName="id")
     */
    protected $traffic_source;

    /**
     * @ORM\ManyToMany(targetEntity="Keyword", inversedBy="promotion", cascade={"persist"})
     * @ORM\JoinTable(name="promotion_keyword",
     * joinColumns={@ORM\JoinColumn(name="promotion_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="keyword_id", referencedColumnName="id")})
     */
    protected $keyword;

    /**
     * @ORM\ManyToOne(targetEntity="PromotionType", inversedBy="promotion")
     * @ORM\JoinTable(name="promotion_type")
     * @ORM\JoinColumn(name="promotion_type_id", referencedColumnName="id")
     */
    protected $promotion_type;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign", inversedBy="promotion", cascade={"persist"})
     * @ORM\JoinTable(name="campaign")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     */
    protected $campaign;

    /**
     * @ORM\OneToMany(targetEntity="PromotionVisit", mappedBy="promotion")
     */
    protected $promotion_visit;


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setPromotionDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getPromotionDateCreated() == null)
        {
            $this->setPromotionDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
       
        //Promotion start date
        $metadata->addPropertyConstraint('promotion_start_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('promotion_start_date', new Assert\Date());

        //Promotion end date
        $metadata->addPropertyConstraint('promotion_end_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('promotion_end_date', new Assert\Date());

        //Vanity URL  apopend text
        $metadata->addPropertyConstraint('vanity_url_append_text', new Assert\NotBlank());
        $metadata->addPropertyConstraint('vanity_url_append_text', new Assert\Length(array(
            'min'        => 4,
            'max'        => 50,
            'minMessage' => 'The append text must be at least {{ limit }} characters length',
            'maxMessage' => 'The append text cannot be longer than {{ limit }} characters length',
        )));
        $metadata->addPropertyConstraint('vanity_url_append_text', new Assert\Regex(array(
            'pattern'     => '/^[a-zA-Z]*$/',
            'message'     => 'Regular expression does not match, remove spaces'
            
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->marketing_email = new \Doctrine\Common\Collections\ArrayCollection();
        $this->keyword = new \Doctrine\Common\Collections\ArrayCollection();
        $this->promotion_visit = new \Doctrine\Common\Collections\ArrayCollection();

        //Set the start and end date to todays date for first time loaded 
        $this->setPromotionStartDate(new \DateTime());
        $this->setPromotionEndDate(new \DateTime());
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
     * Set promotion_name
     *
     * @param string $promotionName
     * @return Promotion
     */
    public function setPromotionName($promotionName)
    {
        $this->promotion_name = $promotionName;

        return $this;
    }

    /**
     * Get promotion_name
     *
     * @return string 
     */
    public function getPromotionName()
    {
        return $this->promotion_name;
    }

    /**
     * Set promotion_name_slug
     *
     * @param string $promotionNameSlug
     * @return Promotion
     */
    public function setPromotionNameSlug($promotionNameSlug)
    {
        $this->promotion_name_slug = $promotionNameSlug;
    
        return $this;
    }

    /**
     * Get promotion_name_slug
     *
     * @return string 
     */
    public function getPromotionNameSlug()
    {
        return $this->promotion_name_slug;
    }

    /**
     * Set promotion_start_date
     *
     * @param \DateTime $promotionStartDate
     * @return Promotion
     */
    public function setPromotionStartDate($promotionStartDate)
    {
        $this->promotion_start_date = $promotionStartDate;

        return $this;
    }

    /**
     * Get promotion_start_date
     *
     * @return \DateTime 
     */
    public function getPromotionStartDate()
    {
        return $this->promotion_start_date;
    }

    /**
     * Set promotion_end_date
     *
     * @param \DateTime $promotionEndDate
     * @return Promotion
     */
    public function setPromotionEndDate($promotionEndDate)
    {
        $this->promotion_end_date = $promotionEndDate;

        return $this;
    }

    /**
     * Get promotion_end_date
     *
     * @return \DateTime 
     */
    public function getPromotionEndDate()
    {
        return $this->promotion_end_date;
    }

    /**
     * Set promotion_date_created
     *
     * @param \DateTime $promotionDateCreated
     * @return Promotion
     */
    public function setPromotionDateCreated($promotionDateCreated)
    {
        $this->promotion_date_created = $promotionDateCreated;

        return $this;
    }

    /**
     * Get promotion_date_created
     *
     * @return \DateTime 
     */
    public function getPromotionDateCreated()
    {
        return $this->promotion_date_created;
    }

    /**
     * Set promotion_date_modified
     *
     * @param \DateTime $promotionDateModified
     * @return Promotion
     */
    public function setPromotionDateModified($promotionDateModified)
    {
        $this->promotion_date_modified = $promotionDateModified;

        return $this;
    }

    /**
     * Get promotion_date_modified
     *
     * @return \DateTime 
     */
    public function getPromotionDateModified()
    {
        return $this->promotion_date_modified;
    }

    /**
     * Set promotion_impressions
     *
     * @param integer $promotionImpressions
     * @return Promotion
     */
    public function setPromotionImpressions($promotionImpressions)
    {
        $this->promotion_impressions = $promotionImpressions;

        return $this;
    }

    /**
     * Get promotion_impressions
     *
     * @return integer 
     */
    public function getPromotionImpressions()
    {
        return $this->promotion_impressions;
    }

    /**
     * Set promotion_click_throughs
     *
     * @param integer $promotionClickThroughs
     * @return Promotion
     */
    public function setPromotionClickThroughs($promotionClickThroughs)
    {
        $this->promotion_click_throughs = $promotionClickThroughs;

        return $this;
    }

    /**
     * Get promotion_click_throughs
     *
     * @return integer 
     */
    public function getPromotionClickThroughs()
    {
        return $this->promotion_click_throughs;
    }

    /**
     * Set promotion_landing_page
     *
     * @param string $promotionLandingPage
     * @return Promotion
     */
    public function setPromotionLandingPage($promotionLandingPage)
    {
        $this->promotion_landing_page = $promotionLandingPage;

        return $this;
    }

    /**
     * Get promotion_landing_page
     *
     * @return string 
     */
    public function getPromotionLandingPage()
    {
        return $this->promotion_landing_page;
    }

    /**
     * Set vanity_url_append_text
     *
     * @param string $vanityUrlAppendText
     * @return Promotion
     */
    public function setVanityUrlAppendText($vanityUrlAppendText)
    {
        $this->vanity_url_append_text = $vanityUrlAppendText;

        return $this;
    }

    /**
     * Get vanity_url_append_text
     *
     * @return string 
     */
    public function getVanityUrlAppendText()
    {
        return $this->vanity_url_append_text;
    }

    /**
     * Set promotion_creator_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $promotionCreatorAdminUser
     * @return Promotion
     */
    public function setPromotionCreatorAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $promotionCreatorAdminUser = null)
    {
        $this->promotion_creator_admin_user = $promotionCreatorAdminUser;

        return $this;
    }

    /**
     * Get promotion_creator_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getPromotionCreatorAdminUser()
    {
        return $this->promotion_creator_admin_user;
    }

    /**
     * Set promotion_modifier_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $promotionModifierAdminUser
     * @return Promotion
     */
    public function setPromotionModifierAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $promotionModifierAdminUser = null)
    {
        $this->promotion_modifier_admin_user = $promotionModifierAdminUser;

        return $this;
    }

    /**
     * Get promotion_modifier_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getPromotionModifierAdminUser()
    {
        return $this->promotion_modifier_admin_user;
    }

    /**
     * Add marketing_email
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail
     * @return Promotion
     */
    public function addMarketingEmail(\MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail)
    {
        $this->marketing_email[] = $marketingEmail;

        return $this;
    }

    /**
     * Remove marketing_email
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail
     */
    public function removeMarketingEmail(\MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail)
    {
        $this->marketing_email->removeElement($marketingEmail);
    }

    /**
     * Get marketing_email
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmail()
    {
        return $this->marketing_email;
    }

    /**
     * Set traffic_source
     *
     * @param \MilesApart\AdminBundle\Entity\TrafficSource $trafficSource
     * @return Promotion
     */
    public function setTrafficSource(\MilesApart\AdminBundle\Entity\TrafficSource $trafficSource = null)
    {
        $this->traffic_source = $trafficSource;

        return $this;
    }

    /**
     * Get traffic_source
     *
     * @return \MilesApart\AdminBundle\Entity\TrafficSource 
     */
    public function getTrafficSource()
    {
        return $this->traffic_source;
    }

    /**
     * Add keyword
     *
     * @param \MilesApart\AdminBundle\Entity\Keyword $keyword
     * @return Promotion
     */
    public function addKeyword(\MilesApart\AdminBundle\Entity\Keyword $keyword)
    {
        $this->keyword[] = $keyword;

        return $this;
    }

    /**
     * Remove keyword
     *
     * @param \MilesApart\AdminBundle\Entity\Keyword $keyword
     */
    public function removeKeyword(\MilesApart\AdminBundle\Entity\Keyword $keyword)
    {
        $this->keyword->removeElement($keyword);
    }

    /**
     * Get keyword
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set promotion_type
     *
     * @param \MilesApart\AdminBundle\Entity\PromotionType $promotionType
     * @return Promotion
     */
    public function setPromotionType(\MilesApart\AdminBundle\Entity\PromotionType $promotionType = null)
    {
        $this->promotion_type = $promotionType;

        return $this;
    }

    /**
     * Get promotion_type
     *
     * @return \MilesApart\AdminBundle\Entity\PromotionType 
     */
    public function getPromotionType()
    {
        return $this->promotion_type;
    }

    /**
     * Add promotion_visit
     *
     * @param \MilesApart\AdminBundle\Entity\PromotionVisit $promotionVisit
     * @return Promotion
     */
    public function addPromotionVisit(\MilesApart\AdminBundle\Entity\PromotionVisit $promotionVisit)
    {
        $this->promotion_visit[] = $promotionVisit;

        return $this;
    }

    /**
     * Remove promotion_visit
     *
     * @param \MilesApart\AdminBundle\Entity\PromotionVisit $promotionVisit
     */
    public function removePromotionVisit(\MilesApart\AdminBundle\Entity\PromotionVisit $promotionVisit)
    {
        $this->promotion_visit->removeElement($promotionVisit);
    }

    /**
     * Get promotion_visit
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromotionVisit()
    {
        return $this->promotion_visit;
    }

    /**
     * Set campaign
     *
     * @param \MilesApart\AdminBundle\Entity\Campaign $campaign
     * @return Promotion
     */
    public function setCampaign(\MilesApart\AdminBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \MilesApart\AdminBundle\Entity\Campaign 
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Get tracking URL
     * 
     */
    public function getTrackingURL()
    {
        return $this->getPromotionLandingPage() . "?utm_source=" . $this->getTrafficSource()->getTrafficSourceType()->getTrafficSourceTypeNameSlug() . "_" . $this->getTrafficSource()->getTrafficSourceCompanyNameSlug() . "&utm_medium=" . $this->getPromotionType()->getPromotionTypeNameSlug() . "&utm_campaign=C_" . $this->getCampaign()->getCampaignNameSlug() . "_" . $this->getCampaign()->getId() . "&utm_content=P_" . $this->getPromotionNameSlug() ."_".$this->getId();
    }

    /**
     * Set promotion_total_cost
     *
     * @param string $promotionTotalCost
     * @return Promotion
     */
    public function setPromotionTotalCost($promotionTotalCost)
    {
        $this->promotion_total_cost = $promotionTotalCost;

        return $this;
    }

    /**
     * Get promotion_total_cost
     *
     * @return string 
     */
    public function getPromotionTotalCost()
    {
        return $this->promotion_total_cost;
    }
}
