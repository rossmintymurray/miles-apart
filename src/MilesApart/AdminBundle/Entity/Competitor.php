<?php
// src/MilesApart/AdminBundle/Entity/Competitor.php -- Defines the competitor object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CompetitorRepository")
 * @ORM\Table(name="competitor")
 * @ORM\HasLifecycleCallbacks()
 */

class Competitor
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
     * @ORM\ManyToOne(targetEntity="CompetitorType", inversedBy="competitor")
     * @ORM\JoinTable(name="competitor_type")
     * @ORM\JoinColumn(name="competitor_type_id", referencedColumnName="id")
     */
    protected $competitor_type;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $competitor_name;

    /**
     * @ORM\OneToMany(targetEntity="CompetitorProduct", mappedBy="competitor")
     */
    protected $competitor_product;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Competitor name
        $metadata->addPropertyConstraint('competitor_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('competitor_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The competitor name must be at least {{ limit }} characters length',
            'maxMessage' => 'The competitor name cannot be longer than {{ limit }} characters length',
        )));

    }
   


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->competitor_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set competitor_name
     *
     * @param string $competitorName
     * @return Competitor
     */
    public function setCompetitorName($competitorName)
    {
        $this->competitor_name = $competitorName;
    
        return $this;
    }

    /**
     * Get competitor_name
     *
     * @return string 
     */
    public function getCompetitorName()
    {
        return $this->competitor_name;
    }

    /**
     * Set competitor_type
     *
     * @param \MilesApart\AdminBundle\Entity\CompetitorType $competitorType
     * @return Competitor
     */
    public function setCompetitorType(\MilesApart\AdminBundle\Entity\CompetitorType $competitorType = null)
    {
        $this->competitor_type = $competitorType;
    
        return $this;
    }

    /**
     * Get competitor_type
     *
     * @return \MilesApart\AdminBundle\Entity\CompetitorType 
     */
    public function getCompetitorType()
    {
        return $this->competitor_type;
    }

    /**
     * Add competitor_product
     *
     * @param \MilesApart\AdminBundle\Entity\CompetitorProduct $competitorProduct
     * @return Competitor
     */
    public function addCompetitorProduct(\MilesApart\AdminBundle\Entity\CompetitorProduct $competitorProduct)
    {
        $this->competitor_product[] = $competitorProduct;
    
        return $this;
    }

    /**
     * Remove competitor_product
     *
     * @param \MilesApart\AdminBundle\Entity\CompetitorProduct $competitorProduct
     */
    public function removeCompetitorProduct(\MilesApart\AdminBundle\Entity\CompetitorProduct $competitorProduct)
    {
        $this->competitor_product->removeElement($competitorProduct);
    }

    /**
     * Get competitor_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompetitorProduct()
    {
        return $this->competitor_product;
    }
}