<?php
// src/MilesApart/AdminBundle/Entity/PrintRequest.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PrintRequestRepository")
 * @ORM\Table(name="print_request")
 * @ORM\HasLifecycleCallbacks()
 */

class PrintRequest
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
     * @ORM\Column(type="datetime")
     */
    protected $print_request_date_created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $print_request_date_modified;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="print_request")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="ProductGroup", inversedBy="print_request")
     * @ORM\JoinTable(name="product_group")
     * @ORM\JoinColumn(name="product_group_id", referencedColumnName="id")
     */
    protected $product_group;

    /**
     * @ORM\ManyToOne(targetEntity="PrintRequestType", inversedBy="print_request")
     * @ORM\JoinTable(name="print_request_type")
     * @ORM\JoinColumn(name="print_request_type_id", referencedColumnName="id")
     */
    protected $print_request_type;

    /**
     * @ORM\Column(type="integer", length=2, unique=false, nullable=true)
     */
    protected $print_request_qty;

    /**
     * @ORM\ManyToOne(targetEntity="BusinessPremises", inversedBy="print_request", cascade={"persist"})
     * @ORM\JoinTable(name="business_premises")
     * @ORM\JoinColumn(name="business_premises_id", referencedColumnName="id")
     */
    protected $business_premises;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $print_request_printed;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setPrintRequestDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getPrintRequestDateCreated() == null)
        {
            $this->setPrintRequestDateCreated(new \DateTime(date('Y-m-d H:i:s')));
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
     * Set print_request_date_created
     *
     * @param \DateTime $printRequestDateCreated
     * @return PrintRequest
     */
    public function setPrintRequestDateCreated($printRequestDateCreated)
    {
        $this->print_request_date_created = $printRequestDateCreated;
    
        return $this;
    }

    /**
     * Get print_request_date_created
     *
     * @return \DateTime 
     */
    public function getPrintRequestDateCreated()
    {
        return $this->print_request_date_created;
    }

    /**
     * Set print_request_date_modified
     *
     * @param \DateTime $printRequestDateModified
     * @return PrintRequest
     */
    public function setPrintRequestDateModified($printRequestDateModified)
    {
        $this->print_request_date_modified = $printRequestDateModified;
    
        return $this;
    }

    /**
     * Get print_request_date_modified
     *
     * @return \DateTime 
     */
    public function getPrintRequestDateModified()
    {
        return $this->print_request_date_modified;
    }

    /**
     * Set print_request_qty
     *
     * @param integer $printRequestQty
     * @return PrintRequest
     */
    public function setPrintRequestQty($printRequestQty)
    {
        $this->print_request_qty = $printRequestQty;
    
        return $this;
    }

    /**
     * Get print_request_qty
     *
     * @return integer 
     */
    public function getPrintRequestQty()
    {
        return $this->print_request_qty;
    }

    /**
     * Set print_request_printed
     *
     * @param boolean $printRequestPrinted
     * @return PrintRequest
     */
    public function setPrintRequestPrinted($printRequestPrinted)
    {
        $this->print_request_printed = $printRequestPrinted;
    
        return $this;
    }

    /**
     * Get print_request_printed
     *
     * @return boolean 
     */
    public function getPrintRequestPrinted()
    {
        return $this->print_request_printed;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return PrintRequest
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
     * Set product_group
     *
     * @param \MilesApart\AdminBundle\Entity\Product $productGroup
     * @return PrintRequest
     */
    public function setProductGroup(\MilesApart\AdminBundle\Entity\ProductGroup $productGroup = null)
    {
        $this->product_group = $productGroup;
    
        return $this;
    }

    /**
     * Get product_group
     *
     * @return \MilesApart\AdminBundle\Entity\ProductGroup 
     */
    public function getProductGroup()
    {
        return $this->product_group;
    }

    /**
     * Set print_request_type
     *
     * @param \MilesApart\AdminBundle\Entity\PrintRequestType $printRequestType
     * @return PrintRequest
     */
    public function setPrintRequestType(\MilesApart\AdminBundle\Entity\PrintRequestType $printRequestType = null)
    {
        $this->print_request_type = $printRequestType;
    
        return $this;
    }

    /**
     * Get print_request_type
     *
     * @return \MilesApart\AdminBundle\Entity\PrintRequestType 
     */
    public function getPrintRequestType()
    {
        return $this->print_request_type;
    }

    /**
     * Set business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return PrintRequest
     */
    public function setBusinessPremises(\MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises = null)
    {
        $this->business_premises = $businessPremises;
    
        return $this;
    }

    /**
     * Get business_premises
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessPremises 
     */
    public function getBusinessPremises()
    {
        return $this->business_premises;
    }
}