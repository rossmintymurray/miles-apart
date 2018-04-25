<?php
// src/MilesApart/AdminBundle/Entity/BusinessCustomerRepresentativeCustomerOrder.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\BusinessCustomerRepresentativeCustomerOrderRepository")
 * @ORM\Table(name="business_customer_representative_customer_order")
 * @ORM\HasLifecycleCallbacks()
 */

class BusinessCustomerRepresentativeCustomerOrder
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
     * @ORM\OneToOne(targetEntity="CustomerOrder", inversedBy="business_customer_representative_customer_order", cascade={"persist"})
     * @ORM\JoinTable(name="customer_order")
     * @ORM\JoinColumn(name="customer_order_id", referencedColumnName="id")
     */
    protected $customer_order;

     /**
     * @ORM\OneToOne(targetEntity="BusinessCustomerRepresentative", inversedBy="business_customer_representative_customer_order", cascade={"persist"})
     * @ORM\JoinTable(name="business_customer_representative")
     * @ORM\JoinColumn(name="business_customer_representative_id", referencedColumnName="id")
     */
    protected $business_customer_representative;
    

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
     * Set customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder
     * @return BusinessCustomerRepresentativeCustomerOrder
     */
    public function setCustomerOrder(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder = null)
    {
        $this->customer_order = $customerOrder;

        return $this;
    }

    /**
     * Get customer_order
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerOrder 
     */
    public function getCustomerOrder()
    {
        return $this->customer_order;
    }

    /**
     * Set business_customer_representative
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative $businessCustomerRepresentative
     * @return BusinessCustomerRepresentativeCustomerOrder
     */
    public function setBusinessCustomerRepresentative(\MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative $businessCustomerRepresentative = null)
    {
        $this->business_customer_representative = $businessCustomerRepresentative;

        return $this;
    }

    /**
     * Get business_customer_representative
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative 
     */
    public function getBusinessCustomerRepresentative()
    {
        return $this->business_customer_representative;
    }
}
