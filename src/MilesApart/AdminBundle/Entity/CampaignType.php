<?php
// src/MilesApart/AdminBundle/Entity/CampaignType.php -- Defines the business premises type

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CampaignTypeRepository")
 * @ORM\Table(name="campaign_type")
 * @ORM\HasLifecycleCallbacks()
 */

class CampaignType
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
    protected $campaign_type_name;

    /**
     * @ORM\OneToMany(targetEntity="Campaign", mappedBy="campaign_type")
     */
    protected $campaign;
    


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
         //Promotion name
        $metadata->addPropertyConstraint('campaign_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('campaign_type_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 200,
            'minMessage' => 'The campaign type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The campaign type name cannot be longer than {{ limit }} characters length',
        )));

        
    }


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->campaign = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set campaign_type_name
     *
     * @param string $campaignTypeName
     * @return CampaignType
     */
    public function setCampaignTypeName($campaignTypeName)
    {
        $this->campaign_type_name = $campaignTypeName;

        return $this;
    }

    /**
     * Get campaign_type_name
     *
     * @return string 
     */
    public function getCampaignTypeName()
    {
        return $this->campaign_type_name;
    }

    /**
     * Add campaign
     *
     * @param \MilesApart\AdminBundle\Entity\Campaign $campaign
     * @return CampaignType
     */
    public function addCampaign(\MilesApart\AdminBundle\Entity\Campaign $campaign)
    {
        $this->campaign[] = $campaign;

        return $this;
    }

    /**
     * Remove campaign
     *
     * @param \MilesApart\AdminBundle\Entity\Campaign $campaign
     */
    public function removeCampaign(\MilesApart\AdminBundle\Entity\Campaign $campaign)
    {
        $this->campaign->removeElement($campaign);
    }

    /**
     * Get campaign
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCampaign()
    {
        return $this->campaign;
    }
}
