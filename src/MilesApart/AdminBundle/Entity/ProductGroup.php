<?php
// src/MilesApart/AdminBundle/Entity/ProductGroup.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductGroupRepository")
 * @ORM\Table(name="product_group")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductGroup
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
    protected $product_group_name;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $product_group_default_price;

    /**
     * @ORM\OneToMany(targetEntity="PrintRequest", mappedBy="product_group")
     */
    protected $print_request;

    /**
     * @ORM\OneToMany(targetEntity="ProductGroupTransferRequest", mappedBy="product_group")
     */
    protected $product_group_transfer_request;


	//Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
    	//Access right action
        $metadata->addPropertyConstraint('product_group_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_group_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 200,
            'minMessage' => 'The product group name must be at least {{ limit }} characters length',
            'maxMessage' => 'The product group name cannot be longer than {{ limit }} characters length',
        )));
       
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->print_request = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set product_group_name
     *
     * @param string $productGroupName
     * @return ProductGroup
     */
    public function setProductGroupName($productGroupName)
    {
        $this->product_group_name = $productGroupName;

        return $this;
    }

    /**
     * Get product_group_name
     *
     * @return string 
     */
    public function getProductGroupName()
    {
        return $this->product_group_name;
    }

    /**
     * Set product_group_default_price
     *
     * @param string $productGroupDefaultPrice
     * @return ProductGroup
     */
    public function setProductGroupDefaultPrice($productGroupDefaultPrice)
    {
        $this->product_group_default_price = $productGroupDefaultPrice;

        return $this;
    }

    /**
     * Get product_group_default_price
     *
     * @return string 
     */
    public function getProductGroupDefaultPrice()
    {
        return $this->product_group_default_price;
    }

    /**
     * Get product_group_barcode
     *
     * @return string 
     */
    public function getProductGroupBarcode()
    {
        return "PG-" . $this->getId();
    }

    /**
     * Get product_group_default_price_display
     *
     *  @return string 
     */
    public function getProductGroupDefaultPriceDisplay()
    {
        $most_recent_price = $this->getProductGroupDefaultPrice();

        if ($most_recent_price < 1 && $most_recent_price > 0) {
            $most_recent_price = $most_recent_price * 100;
            $most_recent_price = $most_recent_price . "p";
        } else if ($most_recent_price >= 1) {
            $most_recent_price = "£" . $most_recent_price;
        } else {
            $most_recent_price = "N/A";
        }
        return $most_recent_price;
    
    }

    /**
     * Add print_request
     *
     * @param \MilesApart\AdminBundle\Entity\PrintRequest $printRequest
     * @return ProductGroup
     */
    public function addPrintRequest(\MilesApart\AdminBundle\Entity\PrintRequest $printRequest)
    {
        $this->print_request[] = $printRequest;
    
        return $this;
    }

    /**
     * Remove print_request
     *
     * @param \MilesApart\AdminBundle\Entity\PrintRequest $printRequest
     */
    public function removePrintRequest(\MilesApart\AdminBundle\Entity\PrintRequest $printRequest)
    {
        $this->print_request->removeElement($printRequest);
    }

    /**
     * Get print_request
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrintRequest()
    {
        return $this->print_request;
    }

    /**
     * Add product_group_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductGroupTransferRequest $productGroupTransferRequest
     * @return ProductGroup
     */
    public function addProductGroupTransferRequest(\MilesApart\AdminBundle\Entity\ProductGroupTransferRequest $productGroupTransferRequest)
    {
        $this->product_group_transfer_request[] = $productGroupTransferRequest;

        return $this;
    }

    /**
     * Remove product_group_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductGroupTransferRequest $productGroupTransferRequest
     */
    public function removeProductGroupTransferRequest(\MilesApart\AdminBundle\Entity\ProductGroupTransferRequest $productGroupTransferRequest)
    {
        $this->product_group_transfer_request->removeElement($productGroupTransferRequest);
    }

    /**
     * Get product_group_transfer_request
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductGroupTransferRequest()
    {
        return $this->product_group_transfer_request;
    }
}
