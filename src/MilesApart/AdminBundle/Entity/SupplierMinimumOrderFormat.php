<?php
// src/MilesApart/AdminBundle/Entity/SupplierMinimumOrderFormat.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierMinimumOrderFormatRepository")
 * @ORM\Table(name="supplier_minimum_order_format")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierMinimumOrderFormat
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
    protected $supplier_minimum_order_format_name;

    /**
     * @ORM\OneToMany(targetEntity="Supplier", mappedBy="supplier_minimum_order_format")
     */
    protected $supplier;




	//Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
    	//Access right action
        $metadata->addPropertyConstraint('supplier_minimum_order_format_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_minimum_order_format_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The order format must be at least {{ limit }} characters length',
            'maxMessage' => 'The order format action cannot be longer than {{ limit }} characters length',
        )));
       
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supplier = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set supplier_minimum_order_format_name
     *
     * @param string $supplierMinimumOrderFormatName
     * @return SupplierMinimumOrderFormat
     */
    public function setSupplierMinimumOrderFormatName($supplierMinimumOrderFormatName)
    {
        $this->supplier_minimum_order_format_name = $supplierMinimumOrderFormatName;

        return $this;
    }

    /**
     * Get supplier_minimum_order_format_name
     *
     * @return string 
     */
    public function getSupplierMinimumOrderFormatName()
    {
        return $this->supplier_minimum_order_format_name;
    }

    /**
     * Add supplier
     *
     * @param \MilesApart\AdminBundle\Entity\Supplier $supplier
     * @return SupplierMinimumOrderFormat
     */
    public function addSupplier(\MilesApart\AdminBundle\Entity\Supplier $supplier)
    {
        $this->supplier[] = $supplier;

        return $this;
    }

    /**
     * Remove supplier
     *
     * @param \MilesApart\AdminBundle\Entity\Supplier $supplier
     */
    public function removeSupplier(\MilesApart\AdminBundle\Entity\Supplier $supplier)
    {
        $this->supplier->removeElement($supplier);
    }

    /**
     * Get supplier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }
}
