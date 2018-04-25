<?php
// src/MilesApart/AdminBundle/Entity/SupplierType.php  -- Defines the supplier type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierTypeRepository")
 * @ORM\Table(name="supplier_type")
 */

class SupplierType
{
    //Define the values

    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     */
    protected $supplier_type_name;
    
    /**
     * @ORM\OneToMany(targetEntity="Supplier", mappedBy="supplier_type")
     */
    protected $supplier;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //VAT rate type name
        $metadata->addPropertyConstraint('supplier_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 50,
            'minMessage' => 'The supplier type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier type name cannot be longer than {{ limit }} characters length',
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
     * Set supplier_type_name
     *
     * @param string $supplierTypeName
     * @return SupplierType
     */
    public function setSupplierTypeName($supplierTypeName)
    {
        $this->supplier_type_name = $supplierTypeName;
    
        return $this;
    }

    /**
     * Get supplier_type_name
     *
     * @return string 
     */
    public function getSupplierTypeName()
    {
        return $this->supplier_type_name;
    }

    /**
     * Add supplier
     *
     * @param \MilesApart\AdminBundle\Entity\Supplier $supplier
     * @return SupplierType
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