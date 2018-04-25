<?php
// src/MilesApart/AdminBundle/Entity/CompetitorType.php -- Defines the competitor type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CompetitorTypeRepository")
 * @ORM\Table(name="competitor_type")
 * @ORM\HasLifecycleCallbacks()
 */

class CompetitorType
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
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $competitor_type_name;

    /**
     * @ORM\OneToMany(targetEntity="Competitor", mappedBy="competitor_type")
     */
    protected $competitor;
   


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Competitor type name
        $metadata->addPropertyConstraint('competitor_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('competitor_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The competitor type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The competitor type name cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->competitor = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set competitor_type_name
     *
     * @param string $competitorTypeName
     * @return CompetitorType
     */
    public function setCompetitorTypeName($competitorTypeName)
    {
        $this->competitor_type_name = $competitorTypeName;
    
        return $this;
    }

    /**
     * Get competitor_type_name
     *
     * @return string 
     */
    public function getCompetitorTypeName()
    {
        return $this->competitor_type_name;
    }

    /**
     * Add competitor
     *
     * @param \MilesApart\AdminBundle\Entity\Competitor $competitor
     * @return CompetitorType
     */
    public function addCompetitor(\MilesApart\AdminBundle\Entity\Competitor $competitor)
    {
        $this->competitor[] = $competitor;
    
        return $this;
    }

    /**
     * Remove competitor
     *
     * @param \MilesApart\AdminBundle\Entity\Competitor $competitor
     */
    public function removeCompetitor(\MilesApart\AdminBundle\Entity\Competitor $competitor)
    {
        $this->competitor->removeElement($competitor);
    }

    /**
     * Get competitor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompetitor()
    {
        return $this->competitor;
    }
}