<?php
// src/MilesApart/AdminBundle/Entity/StaffPickProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\StaffPickProductRepository")
 * @ORM\Table(name="staff_pick_product")
 * @ORM\HasLifecycleCallbacks()
 */

class StaffPickProduct
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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="staff_pick_product", cascade={"persist"})
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $staff_pick_product_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $staff_pick_product_date_modified;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setStaffPickProductDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getStaffPickProductDateCreated() == null)
        {
            $this->setStaffPickProductDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
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
     * Set staff_pick_product_date_created
     *
     * @param \DateTime $staffPickProductDateCreated
     * @return StaffPickProduct
     */
    public function setStaffPickProductDateCreated($staffPickProductDateCreated)
    {
        $this->staff_pick_product_date_created = $staffPickProductDateCreated;
    
        return $this;
    }

    /**
     * Get staff_pick_product_date_created
     *
     * @return \DateTime 
     */
    public function getStaffPickProductDateCreated()
    {
        return $this->staff_pick_product_date_created;
    }

    /**
     * Set staff_pick_product_date_modified
     *
     * @param \DateTime $staffPickProductDateModified
     * @return StaffPickProduct
     */
    public function setStaffPickProductDateModified($staffPickProductDateModified)
    {
        $this->staff_pick_product_date_modified = $staffPickProductDateModified;
    
        return $this;
    }

    /**
     * Get staff_pick_product_date_modified
     *
     * @return \DateTime 
     */
    public function getStaffPickProductDateModified()
    {
        return $this->staff_pick_product_date_modified;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return StaffPickProduct
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
}