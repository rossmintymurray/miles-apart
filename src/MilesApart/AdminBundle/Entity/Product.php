<?php
// src/MilesApart/AdminBundle/Entity/Product.php -- Defines the product object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductRepository")
 * @ORM\Table(name="product")
 * @ORM\HasLifecycleCallbacks()
 */

class Product
{
    //Define the values

    /**
     *  
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;


    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $product_name;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=true)
     */
    protected $product_marketing_name;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=true)
     */
    protected $product_marketing_sub_name;

   

    /**
     * Person domain object class
     *
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="brand"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="brand_name"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="-"),
     *          @Gedmo\SlugHandlerOption(name="urilize", value=true)
     *      })
     * }, fields={"product_marketing_name", "product_marketing_sub_name",  "product_supplier_code"},)
     * @ORM\Column(type="string", length=200, unique=true)
     */
    protected $product_slug;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $short_name;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $print_subtitle;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=true)
     */
    protected $product_introduction;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=true)
     */
    protected $product_meta_description;

    /**
     * @ORM\Column(type="string", length=4000, unique=false, nullable=true)
     */
    protected $product_description;

    /**
     * @ORM\Column(type="integer", length=6, unique=false, nullable=true)
     */
    protected $product_weight;

    /**
     * @ORM\Column(type="integer", length=6, unique=false, nullable=true)
     */
    protected $product_width;

    /**
     * @ORM\Column(type="integer", length=6, unique=false, nullable=true)
     */
    protected $product_height;

    /**
     * @ORM\Column(type="integer", length=6, unique=false, nullable=true)
     */
    protected $product_depth;

    /**
     * @ORM\ManyToOne(targetEntity="VATRateType", inversedBy="product")
     * @ORM\JoinTable(name="vat_rate_type")
     * @ORM\JoinColumn(name="vat_rate_type_id", referencedColumnName="id")
     */
    protected $vat_rate_type;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="product_creator")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="product_creator_id", referencedColumnName="id")
     */
    protected $product_creator_admin_user;
    
    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="product_modifier")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="product_modifier_id", referencedColumnName="id")
     */
    protected $product_modifier_admin_user;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_date_modified;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $product_supplier_code;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="product")
     * @ORM\JoinTable(name="brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    protected $brand;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $product_outer_barcode;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $product_inner_barcode;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $product_barcode;

    /**
     * @ORM\Column(type="integer", length=6, unique=false, nullable=true)
     */
    protected $product_outer_quantity;

    /**
     * @ORM\Column(type="integer", length=6, unique=false, nullable=true)
     */
    protected $product_inner_quantity;

    /**
     * @ORM\Column(type="integer", length=6, unique=false, nullable=true)
     */
    protected $product_pack_quantity;

    /**
     * @ORM\OneToMany(targetEntity="CompetitorProduct", mappedBy="product")
     */
    protected $competitor_product;
     
    /**
     * @ORM\ManyToMany(targetEntity="ProductFeature", inversedBy="product", cascade={"persist"})
     * @ORM\JoinTable(name="product_product_feature",
     * joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="product_feature_id", referencedColumnName="id")})
     */
    protected $product_feature;

    /**
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="product", cascade={"persist"})
     * @ORM\OrderBy({"product_image_is_main" = "DESC"})
     */
    protected $product_image;

    /**
     * @ORM\ManyToMany(targetEntity="Keyword", inversedBy="product", cascade={"persist"})
     * @ORM\JoinTable(name="product_keyword",
     * joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="keyword_id", referencedColumnName="id")})
     */
    protected $keyword;

    /**
     * @ORM\OneToMany(targetEntity="ProductPrice", mappedBy="product", cascade={"persist"})
     */
    protected $product_price;

    /**
     * @ORM\OneToMany(targetEntity="ProductQuestion", mappedBy="product")
     */
    protected $product_question;
  
    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="product", cascade={"persist"})
     * @ORM\JoinTable(name="product_category",
     * joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")})
     */
    protected $category;
   
    /**
     * @ORM\ManyToMany(targetEntity="Season", inversedBy="product", cascade={"persist"})
     * @ORM\JoinTable(name="product_season",
     * joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="season_id", referencedColumnName="id")})
     */

    protected $season;

    
   
   /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="product_default")
     * @ORM\JoinTable(name="category")
     * @ORM\JoinColumn(name="product_default_category_id", referencedColumnName="id")
     */
   protected $product_default_category;
    

    /**
     * @ORM\ManyToMany(targetEntity="AttributeValue", inversedBy="product", cascade={"persist"})
     * @ORM\JoinTable(name="product_attribute_value",
     * joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="attribute_value_id", referencedColumnName="id")})
     */
    protected $attribute_value;

    /**
     * @ORM\OneToMany(targetEntity="ProductSupplier", mappedBy="product", cascade={"persist"})
     */
    protected $product_supplier;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOrderProduct", mappedBy="product")
     */
    protected $customer_order_product;

    /**
     * @ORM\OneToMany(targetEntity="CustomerWishListProduct", mappedBy="product")
     */
    protected $customer_wish_list_product;

    /**
     * @ORM\OneToMany(targetEntity="ProductTransferRequest", mappedBy="product")
     */
    protected $product_transfer_request;

    /**
     * @ORM\OneToMany(targetEntity="PurchaseOrderProduct", mappedBy="product")
     */
    protected $purchase_order_product;

    /**
     * @ORM\OneToMany(targetEntity="StocktakeProduct", mappedBy="product", cascade={"persist"})
     */
    protected $stocktake_product;

    /**
     * @ORM\OneToMany(targetEntity="SeasonalStorageBoxProduct", mappedBy="product", cascade={"persist"})
     */
    protected $seasonal_storage_box_product;

    /**
     * @ORM\OneToMany(targetEntity="SupplierDeliveryProduct", mappedBy="product")
     */
    protected $supplier_delivery_product;

    /**
     * @ORM\OneToMany(targetEntity="PrintRequest", mappedBy="product")
     */
    protected $print_request;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_product_online;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_product_on_amazon;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_product_discontinued;

     /**
     * @ORM\OneToMany(targetEntity="MilesApart\BasketBundle\Entity\BasketProduct", mappedBy="product")
     */
    protected $basket_product;

    /**
     * @ORM\OneToMany(targetEntity="MilesApart\AdminBundle\Entity\ProductReview", mappedBy="product")
     */
    protected $product_review;

     /**
     * @ORM\OneToMany(targetEntity="ShopSoldProduct", mappedBy="product", cascade={"persist"})
     */
    protected $shop_sold_product;

    /**
     * @ORM\OneToMany(targetEntity="ShopReturnedProduct", mappedBy="product", cascade={"persist"})
     */
    protected $shop_returned_product;

    /**
     * @ORM\OneToMany(targetEntity="StaffPickProduct", mappedBy="product", cascade={"persist"})
     */
    protected $staff_pick_product;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setProductDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getProductDateCreated() == null)
        {
            $this->setProductDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Product name
        $metadata->addPropertyConstraint('product_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 200,
            'minMessage' => 'The product name must be at least {{ limit }} characters length',
            'maxMessage' => 'The product name cannot be longer than {{ limit }} characters length',
        )));

        //Short name
        $metadata->addPropertyConstraint('short_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 50,
            'minMessage' => 'The short name must be at least {{ limit }} characters length',
            'maxMessage' => 'The short name cannot be longer than {{ limit }} characters length',
        )));

        //Print subtitle
        $metadata->addPropertyConstraint('print_subtitle', new Assert\Length(array(
            'min'        => 4,
            'max'        => 50,
            'minMessage' => 'The print subtitle must be at least {{ limit }} characters length',
            'maxMessage' => 'The print subtitle cannot be longer than {{ limit }} characters length',
        )));

        //Product introduction
        $metadata->addPropertyConstraint('product_introduction', new Assert\Length(array(
            'min'        => 4,
            'max'        => 2000,
            'minMessage' => 'The product introduction must be at least {{ limit }} characters length',
            'maxMessage' => 'The product introduction cannot be longer than {{ limit }} characters length',
        )));

        //Product description
        $metadata->addPropertyConstraint('product_description', new Assert\Length(array(
            'min'        => 4,
            'max'        => 2000,
            'minMessage' => 'The product description must be at least {{ limit }} characters length',
            'maxMessage' => 'The product description cannot be longer than {{ limit }} characters length',
        )));

        
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->competitor_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_feature = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_image = new \Doctrine\Common\Collections\ArrayCollection();
        $this->keyword = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_price = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_question = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->season = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attribute_value = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_supplier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customer_order_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customer_wish_list_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_transfer_request = new \Doctrine\Common\Collections\ArrayCollection();
        $this->purchase_order_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stocktake_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seasonal_storage_box_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_delivery_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->basket_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->shop_sold_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->shop_returned_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->staff_pick_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->print_request = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
{
    return $this->getProductName();
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
     * Set product_name
     *
     * @param string $productSupplierName
     * @return Product
     */
    public function setProductName($productName)
    {
        $this->product_name = $productName;
    
        return $this;
    }

    /**
     * Get product_name
     *
     * @return string 
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Set product_marketing_sub_name
     *
     * @param string $productMarketingSubName
     * @return Product
     */
    public function setProductMarketingSubName($productMarketingSubName)
    {
        $this->product_marketing_sub_name = $productMarketingSubName;
    
        return $this;
    }

    /**
     * Get product_marketing_sub_name
     *
     * @return string 
     */
    public function getProductMarketingSubName()
    {
        return $this->product_marketing_sub_name;
    }

    /**
     * Get product_title
     *
     * @return string 
     */
    public function getProductTitle()
    {
        if ($this->getBrand()) {
            $brand_name = $this->getBrand()->getBrandName() ." - ";
        } else {
            $brand_name = "";
        }

        if ($this->product_marketing_name) {
            $product_marketing_name = $this->product_marketing_name ." - ";
        } else if ($this->product_name) {
            $product_marketing_name = $this->product_name ." - ";
        } else {
            $product_marketing_name = "";
        }

        if ($this->getProductMarketingSubName()) {
            $product_marketing_sub_name = $this->getProductMarketingSubName() ." - ";
        } else {
            $product_marketing_sub_name = "";
        }

        if ($this->getProductSupplierCode()) {
            $product_supplier_code = $this->getProductSupplierCode();
        } else {
            $product_supplier_code = "";
        }
        

        return $brand_name . $product_marketing_name . $product_marketing_sub_name . $product_supplier_code;
    }

    /**
     * Set product_slug
     *
     * @param string $productSlug
     * @return Product
     */
    public function setProductSlug($productSlug)
    {
        $this->product_slug = $productSlug;
    
        return $this;
    }

    /**
     * Get product_slug
     *
     * @return string 
     */
    public function getProductSlug()
    {
        return MilesApart::slugify($this->getProductTitle());
    }

    /**
     * Set product_introduction
     *
     * @param string $productIntroduction
     * @return Product
     */
    public function setProductIntroduction($productIntroduction)
    {
        $this->product_introduction = $productIntroduction;
    
        return $this;
    }

    /**
     * Get product_introduction
     *
     * @return string 
     */
    public function getProductIntroduction()
    {
        return $this->product_introduction;
    }

    /**
     * Set product_description
     *
     * @param string $productDescription
     * @return Product
     */
    public function setProductDescription($productDescription)
    {
        $this->product_description = $productDescription;
    
        return $this;
    }

    /**
     * Get product_description
     *
     * @return string 
     */
    public function getProductDescription()
    {
        return $this->product_description;
    }

    /**
     * Set product_weight
     *
     * @param integer $productWeight
     * @return Product
     */
    public function setProductWeight($productWeight)
    {
        $this->product_weight = $productWeight;
    
        return $this;
    }

    /**
     * Get product_weight
     *
     * @return integer 
     */
    public function getProductWeight()
    {
        return $this->product_weight;
    }

    /**
     * Set product_width
     *
     * @param integer $productWidth
     * @return Product
     */
    public function setProductWidth($productWidth)
    {
        $this->product_width = $productWidth;
    
        return $this;
    }

    /**
     * Get product_width
     *
     * @return integer 
     */
    public function getProductWidth()
    {
        return $this->product_width;
    }

    /**
     * Set product_height
     *
     * @param integer $productHeight
     * @return Product
     */
    public function setProductHeight($productHeight)
    {
        $this->product_height = $productHeight;
    
        return $this;
    }

    /**
     * Get product_height
     *
     * @return integer 
     */
    public function getProductHeight()
    {
        return $this->product_height;
    }

    /**
     * Set product_depth
     *
     * @param integer $productDepth
     * @return Product
     */
    public function setProductDepth($productDepth)
    {
        $this->product_depth = $productDepth;
    
        return $this;
    }

    /**
     * Get product_depth
     *
     * @return integer 
     */
    public function getProductDepth()
    {
        return $this->product_depth;
    }

    /**
     * Set product_date_created
     *
     * @param \DateTime $productDateCreated
     * @return Product
     */
    public function setProductDateCreated($productDateCreated)
    {
        $this->product_date_created = $productDateCreated;
    
        return $this;
    }

    /**
     * Get product_date_created
     *
     * @return \DateTime 
     */
    public function getProductDateCreated()
    {
        return $this->product_date_created;
    }

    /**
     * Set product_date_modified
     *
     * @param \DateTime $productDateModified
     * @return Product
     */
    public function setProductDateModified($productDateModified)
    {
        $this->product_date_modified = $productDateModified;
    
        return $this;
    }

    /**
     * Get product_date_modified
     *
     * @return \DateTime 
     */
    public function getProductDateModified()
    {
        return $this->product_date_modified;
    }

    /**
     * Set product_supplier_code
     *
     * @param string $productSupplierCode
     * @return Product
     */
    public function setProductSupplierCode($productSupplierCode)
    {
        $this->product_supplier_code = $productSupplierCode;
    
        return $this;
    }

    /**
     * Get product_supplier_code
     *
     * @return string 
     */
    public function getProductSupplierCode()
    {
        return $this->product_supplier_code;
    }

    /**
     * Set product_outer_barcode
     *
     * @param string $productOuterBarcode
     * @return Product
     */
    public function setProductOuterBarcode($productOuterBarcode)
    {
        $this->product_outer_barcode = $productOuterBarcode;
    
        return $this;
    }

    /**
     * Get product_outer_barcode
     *
     * @return string 
     */
    public function getProductOuterBarcode()
    {
        return $this->product_outer_barcode;
    }

    /**
     * Set product_inner_barcode
     *
     * @param string $productInnerBarcode
     * @return Product
     */
    public function setProductInnerBarcode($productInnerBarcode)
    {
        $this->product_inner_barcode = $productInnerBarcode;
    
        return $this;
    }

    /**
     * Get product_inner_barcode
     *
     * @return string 
     */
    public function getProductInnerBarcode()
    {
        return $this->product_inner_barcode;
    }

    /**
     * Set product_barcode
     *
     * @param string $productBarcode
     * @return Product
     */
    public function setProductBarcode($productBarcode)
    {
        $this->product_barcode = $productBarcode;
    
        return $this;
    }

    /**
     * Get product_barcode
     *
     * @return string 
     */
    public function getProductBarcode()
    {
        return $this->product_barcode;
    }

    /**
     * Set product_outer_quantity
     *
     * @param integer $productOuterQuantity
     * @return Product
     */
    public function setProductOuterQuantity($productOuterQuantity)
    {
        $this->product_outer_quantity = $productOuterQuantity;
    
        return $this;
    }

    /**
     * Get product_outer_quantity
     *
     * @return integer 
     */
    public function getProductOuterQuantity()
    {
        return $this->product_outer_quantity;
    }

    /**
     * Set product_inner_quantity
     *
     * @param integer $productInnerQuantity
     * @return Product
     */
    public function setProductInnerQuantity($productInnerQuantity)
    {
        $this->product_inner_quantity = $productInnerQuantity;
    
        return $this;
    }

    /**
     * Get product_inner_quantity
     *
     * @return integer 
     */
    public function getProductInnerQuantity()
    {
        return $this->product_inner_quantity;
    }

    /**
     * Set product_pack_quantity
     *
     * @param integer $productPackQuantity
     * @return Product
     */
    public function setProductPackQuantity($productPackQuantity)
    {
        $this->product_pack_quantity = $productPackQuantity;
    
        return $this;
    }

    /**
     * Get product_pack_quantity
     *
     * @return integer 
     */
    public function getProductPackQuantity()
    {
        return $this->product_pack_quantity;
    }

    /**
     * Set is_product_online
     *
     * @param boolean $isProductOnline
     * @return Product
     */
    public function setIsProductOnline($isProductOnline)
    {
        $this->is_product_online = $isProductOnline;
    
        return $this;
    }

    /**
     * Get is_product_online
     *
     * @return boolean 
     */
    public function getIsProductOnline()
    {
        return $this->is_product_online;
    }

    /**
     * Set vat_rate_type
     *
     * @param \MilesApart\AdminBundle\Entity\VATRateType $vatRateType
     * @return Product
     */
    public function setVatRateType(\MilesApart\AdminBundle\Entity\VATRateType $vatRateType = null)
    {
        $this->vat_rate_type = $vatRateType;
    
        return $this;
    }

    /**
     * Get vat_rate_type
     *
     * @return \MilesApart\AdminBundle\Entity\VATRateType 
     */
    public function getVatRateType()
    {
        return $this->vat_rate_type;
    }

    /**
     * Set product_creator_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $productCreatorAdminUser
     * @return Product
     */
    public function setProductCreatorAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $productCreatorAdminUser = null)
    {
        $this->product_creator_admin_user = $productCreatorAdminUser;
    
        return $this;
    }

    /**
     * Get product_creator_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getProductCreatorAdminUser()
    {
        return $this->product_creator_admin_user;
    }

    /**
     * Set product_modifier_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $productModifierAdminUser
     * @return Product
     */
    public function setProductModifierAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $productModifierAdminUser = null)
    {
        $this->product_modifier_admin_user = $productModifierAdminUser;
    
        return $this;
    }

    /**
     * Get product_modifier_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getProductModifierAdminUser()
    {
        return $this->product_modifier_admin_user;
    }

    /**
     * Set brand
     *
     * @param \MilesApart\AdminBundle\Entity\Brand $brand
     * @return Product
     */
    public function setBrand(\MilesApart\AdminBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;
    
        return $this;
    }

    /**
     * Get brand
     *
     * @return \MilesApart\AdminBundle\Entity\Brand 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Add competitor_product
     *
     * @param \MilesApart\AdminBundle\Entity\CompetitorProduct $competitorProduct
     * @return Product
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

    /**
     * Add product_feature
     *
     * @param \MilesApart\AdminBundle\Entity\ProductFeature $productFeature
     * @return Product
     */
    public function addProductFeature(\MilesApart\AdminBundle\Entity\ProductFeature $productFeature)
    {
        $this->product_feature[] = $productFeature;
    
        return $this;
    }

    /**
     * Remove product_feature
     *
     * @param \MilesApart\AdminBundle\Entity\ProductFeature $productFeature
     */
    public function removeProductFeature(\MilesApart\AdminBundle\Entity\ProductFeature $productFeature)
    {
        $this->product_feature->removeElement($productFeature);
    }

    /**
     * Get product_feature
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductFeature()
    {
        return $this->product_feature;
    }

    /**
     * Add product_image
     *
     * @param \MilesApart\AdminBundle\Entity\ProductImage $productImage
     * @return Product
     */
    public function addProductImage(\MilesApart\AdminBundle\Entity\ProductImage $productImage)
    {
        $this->product_image[] = $productImage;
    
        return $this;
    }

    /**
     * Remove product_image
     *
     * @param \MilesApart\AdminBundle\Entity\ProductImage $productImage
     */
    public function removeProductImage(\MilesApart\AdminBundle\Entity\ProductImage $productImage)
    {
        $this->product_image->removeElement($productImage);
    }

    /**
     * Get product_image
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductImage()
    {
        return $this->product_image;
    }

    /**
     * Add keyword
     *
     * @param \MilesApart\AdminBundle\Entity\Keyword $keyword
     * @return Product
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
     * Add product_price
     *
     * @param \MilesApart\AdminBundle\Entity\ProductPrice $productPrice
     * @return Product
     */
    public function addProductPrice(\MilesApart\AdminBundle\Entity\ProductPrice $productPrice)
    {
        $this->product_price[] = $productPrice;
    
        return $this;
    }

    /**
     * Add product_price
     *
     * @param \MilesApart\AdminBundle\Entity\ProductPrice $productPrice
     * @return Product
     */
    public function addProductProuse(\MilesApart\AdminBundle\Entity\ProductPrice $productPrice)
    {
        $this->product_price[] = $productPrice;
    
        return $this;
    }

    /**
     * Remove product_price
     *
     * @param \MilesApart\AdminBundle\Entity\ProductPrice $productPrice
     */
    public function removeProductProuse(\MilesApart\AdminBundle\Entity\ProductPrice $productPrice)
    {
        $this->product_price->removeElement($productPrice);
    }

    /**
     * Remove product_price
     *
     * @param \MilesApart\AdminBundle\Entity\ProductPrice $productPrice
     */
    public function removeProductPrice(\MilesApart\AdminBundle\Entity\ProductPrice $productPrice)
    {
        $this->product_price->removeElement($productPrice);
    }

    /**
     * Get product_price
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductPrice()
    {
        return $this->product_price;
    }

    /**
     * Get current_price
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentPrice()
    {
        
       $most_recent = 0;
       $most_recent_price = null;
       $date = null;
       if (count($this->getProductPrice()) > 0) {
            foreach($this->getProductPrice() as $key => $value) {
//$most_recent_price .= "-1";
                $date = $value->getProductPriceValidFrom();
                $curDate = strtotime($date->format('Y-m-d H:i:s'));
                if ($curDate > $most_recent) {
                    $most_recent = $curDate;
                    $most_recent_price = $value;
                }
            } 
        } else {
            $most_recent_price = null;
        }

        
        if ($date != null) {
            
            return $most_recent_price;
        }
    }

    /**
     * Get current_price_decimal
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentPriceDecimal()
    {
        if($this->getCurrentPrice() != null) {
            return $this->getCurrentPrice()->getProductPriceValue();
        } else {
            return null;
        }
    }


    /**
     * Get pre_sale_price
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPreSalePrice()
    {
        
       $most_recent = 0;
       $most_recent_price = "1";
       $date = null;


       if (count($this->getProductPrice()) > 0) {
            foreach($this->getProductPrice() as $key => $value) {
               
                $date = $value->getProductPriceValidFrom();
                $curDate = strtotime($date->format('Y-m-d H:i:s'));
                if ($curDate > $most_recent) {
                    if($value->getProductPriceIsSpecial() == false) {
                        $most_recent = $curDate;
                        $most_recent_price = $value->getProductPriceValue();
                    }

                }
            } 
        } else {
            $most_recent_price = "Not set";
        }

        
        if ($date != null) {
            
            return $most_recent_price;
        }
    }


    /**
     * Get product_price_by_date
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductPriceByDate($date)
    {
        //ladybug_dump($date);
       $most_recent = 0;
       $most_recent_price = null;
       //$date = null;
       
       //If at least one price exists
       if (count($this->getProductPrice()) > 0) {

            //For each price 
            foreach($this->getProductPrice() as $key => $value) {
            
                //Check valid from is less than (before) or equal to the date.
                if($value->getProductPriceValidFrom() < $date) {
                    
                    $most_recent_price = $value;
                    
                } else {
                    $most_recent_price = $value;
                }
            } 
        } else {
            $most_recent_price = null;
        }

        
        if ($date != null) {
            
            return $most_recent_price;
        }
    }

    public function getProductPriceByDateDecimal($date)
    {
        return $this->getProductPriceByDate($date)->getProductPriceValue();
    }

     /* Get product_price_by_date
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductPriceByDateDisplay($date)
    {
        
        $price = $this->getProductPriceByDate($date)->getProductPrice();
        if ($price < 1 && $price > 0) {
            $price = $price * 100;
            $price = $price . "p";
        } else if ($price >= 1) {
            $price = "£" . $price;
        } else {
            $price = "N/A";
        }
        return $price;
    }

    /**
     * Get current_price_display
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentPriceDisplay()
    {
        $most_recent_price = $this->getCurrentPriceDecimal();

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
     * Get pre_sale_price_display
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPreSalePriceDisplay()
    {
        $most_recent_price = $this->getPreSalePrice();

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
     * Get current_stock_level
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentStockLevel()
    {   
        $most_recent = 0;
        $most_recent_price = "1";
        $date = 0;
        $most_recent_stocktake = null;
        $stock_level = 0;
        
        //Check if there has been a stocktake
        if (count($this->getStocktakeProduct()) > 0) {

            //For each time there is a stocktake product entry, find the most recent
            foreach($this->getStocktakeProduct() as $key => $value) {

                //Set the date of the stocktake product date created
                $date = $value->getStocktakeProductDateCreated();

                //Format the date
                $curDate = strtotime($date->format('Y-m-d H:i:s'));

                //If the date is not the most recent
                if ($curDate > $most_recent) {
                    $most_recent = $curDate;
                    $most_recent_stocktake = $value->getStocktake();
                }
            }

            if ($most_recent_stocktake != null) {
                foreach($this->getStocktakeProduct() as $key => $value) {
                    if($value->getStocktake()->getId() == $most_recent_stocktake->getId()) {
                        $stock_level = $stock_level + $value->getStocktakeProductQty();
                        
                    }
                }    
            }
            
            $amount = $stock_level;
            
            //Find any stock deliveries and add the stock location product sent figure to this.
            if (count($this->getSupplierDeliveryProduct()) > 0) {

                //For each delivery of the product
                foreach($this->getSupplierDeliveryProduct() as $key => $value) {

                    //Check if the date is since the most recent stocktake.
                    if ($value->getSupplierDelivery()->getDeliveredDatetime() > $date) {

                        foreach($value->getStockLocationShelfProductSent() as $key => $value2) {
                            $amount = $amount + $value2->getStockLocationShelfProductSentQty();
                        }
                    }
                }
            }

            //Find any product transfer requests and minus the stock transfer figure from this.
            if (count($this->getProductTransferRequest()) > 0) {
                foreach($this->getProductTransferRequest() as $key => $value) {

                    //Check if the date is sinse the last stocktake.
                    if ($value->getProductTransferRequestDateCreated() > $date) {


                        //Check if the transfer request was completed (only remove the number if it was)
                        if($value->getProductTransferRequestState()->getId() == 2 || $value->getProductTransferRequestState()->getId() == 3 || $value->getProductTransferRequestState()->getId() == 4) {

                            $amount =  $amount - $value->getProductTransferRequestQty();
                        }
                    }
                    
                }
            }

            //Find any customer orders and minus the stock order total from this.
            if (count($this->getCustomerOrderProduct()) > 0) {
                foreach($this->getCustomerOrderProduct() as $key => $value) {

                    //Check if the date is sinse the last stocktake.
                    if ($value->getCustomerOrder()->getCustomerOrderDateCreated() > $date) {
                        
                        $amount = $amount - $value->getCustomerOrderProductQuantity();
                    }
                }
            }

            //Find any sold products and minus the stock order total from this.
            if (count($this->getShopSoldProduct()) > 0) {
                foreach($this->getShopSoldProduct() as $key => $value) {
                    //Check if the date is sinse the last stocktake.
                    if ($value->getShopSoldProductDateCreated() > $date) {
                        
                        $amount = $amount - $value->getShopSoldProductQuantity();
                    }
                }
            }

            //Find any shop returned products and minus the stock order total from this.
            if (count($this->getShopReturnedProduct()) > 0) {
                foreach($this->getShopReturnedProduct() as $key => $value) {
                    //Check if the date is sinse the last stocktake.
                    if ($value->getShopReturnedProductDateCreated() > $date) {
                        
                        $amount = $amount + $value->getShopReturnedProductQuantity();
                    }
                }
            }

        } else {
            $amount = "N/A";
        }
        return $amount;
    }

    /**
     * Get current_stock_level minus the qty in any basket at any time
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCurrentStockLevelMinusBasket()
    {
        //Get current date and minus 10 minutes
        $endtime = new \DateTime();
        $endtime->setTimestamp(strtotime('- 10 minutes', time()));

        $stock_level = $this->getCurrentStockLevel();

        //Check every un-checked out basket for this product and deduct from the stock level
        //Check if there are any products in baskets that are not checked out and have not been added to for 10 mins
        if (count($this->getBasketProduct()) > 0) {

            //For each time there is a basket product
            foreach ($this->getBasketProduct() as $key => $value) {

                //If the basket has not been checked out
                if ($value->getBasket()->getBasketCheckedOut() != TRUE && $value->getBasket()->getBasketDateModified() > $endtime)  {

                    //Update the qty
                    $stock_level = $stock_level - $value->getBasketProductQuantity();

                }
            }
        }

        return $stock_level;
    }

    /**
     * Add product_question
     *
     * @param \MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion
     * @return Product
     */
    public function addProductQuestion(\MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion)
    {
        $this->product_question[] = $productQuestion;
    
        return $this;
    }

    /**
     * Remove product_question
     *
     * @param \MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion
     */
    public function removeProductQuestion(\MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion)
    {
        $this->product_question->removeElement($productQuestion);
    }

    /**
     * Get product_question
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductQuestion()
    {
        return $this->product_question;
    }

    /**
     * Get answered_product_question
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnsweredProductQuestion()
    {
        //Make array
        $answered_product_questions = array();
        //Iterate over the product question and check if they have answers
        foreach($this->getProductQuestion() as $question) {
            if(count($question->getProductAnswer()) > 0) {
                array_push($answered_product_questions, $question);
            }
        }
        return $answered_product_questions;
    }

    /**
     * Add category
     *
     * @param \MilesApart\AdminBundle\Entity\Category $category
     * @return Product
     */
    public function addCategory(\MilesApart\AdminBundle\Entity\Category $category)
    {
        $this->category[] = $category;
    
        return $this;
    }

    /**
     * Remove category
     *
     * @param \MilesApart\AdminBundle\Entity\Category $category
     */
    public function removeCategory(\MilesApart\AdminBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add season
     *
     * @param \MilesApart\AdminBundle\Entity\Season $season
     * @return Product
     */
    public function addSeason(\MilesApart\AdminBundle\Entity\Season $season)
    {
        $this->season[] = $season;
    
        return $this;
    }

    /**
     * Remove season
     *
     * @param \MilesApart\AdminBundle\Entity\Season $season
     */
    public function removeSeason(\MilesApart\AdminBundle\Entity\Season $season)
    {
        $this->season->removeElement($season);
    }

    /**
     * Get season
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Add attribute_value
     *
     * @param \MilesApart\AdminBundle\Entity\AttributeValue $attributeValue
     * @return Product
     */
    public function addAttributeValue(\MilesApart\AdminBundle\Entity\AttributeValue $attributeValue)
    {
        $this->attribute_value[] = $attributeValue;
    
        return $this;
    }

    /**
     * Remove attribute_value
     *
     * @param \MilesApart\AdminBundle\Entity\AttributeValue $attributeValue
     */
    public function removeAttributeValue(\MilesApart\AdminBundle\Entity\AttributeValue $attributeValue)
    {
        $this->attribute_value->removeElement($attributeValue);
    }

    /**
     * Get attribute_value
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttributeValue()
    {
        return $this->attribute_value;
    }

    /**
     * Add product_supplier
     *
     * @param \MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier
     * @return Product
     */
    public function addProductSupplier(\MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier)
    {
        $this->product_supplier[] = $productSupplier;
    
        return $this;
    }

    /**
     * Remove product_supplier
     *
     * @param \MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier
     */
    public function removeProductSupplier(\MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier)
    {
        $this->product_supplier->removeElement($productSupplier);
    }

    /**
     * Get product_supplier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductSupplier()
    {
        return $this->product_supplier;
    }

    /**
     * Get default_product_supplier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDefaultProductSupplier()
    {
        $default_product_supplier = null;
        foreach($this->getProductSupplier() as $key => $value) {
            //if ($value->getDefaultSupplier() == true) {
                $default_product_supplier = $value;
            //}
        }
        return $default_product_supplier;
    }

    /**
     * Get default_product_supplier_object
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDefaultProductSupplierObject()
    {
        $supplier = null;
        foreach($this->getProductSupplier() as $key => $value) {
            if ($value->getDefaultSupplier() == true) {
                $supplier = $value->getSupplier();
            }
        }
        return $supplier;
    }

    /**
     * Get current_cost
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentCost()
    {

        foreach($this->getProductSupplier() as $key => $value) {
            if ($value->getDefaultSupplier() == 1) {


                    $most_recent = 0;
                    $most_recent_cost = "1";
                    $date = null;
                    if (count($value->getProductCost()) > 0) {
                        foreach($value->getProductCost() as $k => $v) {
                            //$most_recent_cost .= "-1";
                            $date = $v->getProductCostValidFrom();
                            $curDate = strtotime($date->format('Y-m-d H:i:s'));
                            if ($curDate > $most_recent) {
                                $most_recent = $curDate;
                                $most_recent_cost = $v;
                            }
                        } 
                    } else {
                        $most_recent_cost = null;
                    }


                    if ($date != null) {
                       
                        return $most_recent_cost;
                    }
            }
        }
    }



    /**
     * Get current_cost_decimal
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentCostDecimal()
    {
        if($this->getCurrentCost() != null) {
            return $this->getCurrentCost()->getProductCostValue();
        } else {
            return null;
        }
    }

    /**
     * Get current_cost_display
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentCostDisplay()
    {
        $most_recent_cost = $this->getCurrentCostDecimal();
        
        if ($most_recent_cost < 1 && $most_recent_cost > 0) {
            $most_recent_cost = $most_recent_cost * 100;
            $most_recent_cost = $most_recent_cost . "p";
        } else if ($most_recent_cost >= 1) {
            $most_recent_cost = "£" . $most_recent_cost;
        } else {
            $most_recent_cost = "N/A";
        }
         
        return $most_recent_cost;
        
           
    }

    /**
     * Get product_cost_by_date
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductCostByDate($date)
    {
        foreach($this->getProductSupplier() as $key => $value) {
            if ($value->getDefaultSupplier() == 1) {
                //ladybug_dump($date);
               $most_recent = 0;
               $most_recent_cost = null;
               //$date = null;
               
               //If at least one cost exists
               if (count($value->getProductCost()) > 0) {

                    //For each cost 
                    foreach($value->getProductCost() as $key => $v) {
                    
                        //Check valid from is less than (before) or equal to the date.
                        if($v->getProductCostValidFrom() < $date) {
                            
                            $most_recent_cost = $v;
                            
                        } else {
                            $most_recent_cost = $v;
                        }
                    } 
                } else {
                    $most_recent_cost = null;
                }

                
                if ($date != null) {
                    
                    return $most_recent_cost;
                }
            }
        }
    }

    public function getProductCostByDateDecimal($date)
    {
        return $this->getProductCostByDate($date)->getProductCostValue();
    }

     /* Get product_cost_by_date
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductCostByDateDisplay($date)
    {
        
        $price = $this->getProductCostByDate($date)->getProductCost();
        if ($price < 1 && $price > 0) {
            $price = $price * 100;
            $price = $price . "p";
        } else if ($price >= 1) {
            $price = "£" . $price;
        } else {
            $price = "N/A";
        }
        return $price;
    }

    /**
     * Get current_mark_up
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentMarkUp()
    {
        if($this->getCurrentPriceDecimal() > 0 && $this->getCurrentCostDecimal() > 0) {
            
            $profit = ($this->getCurrentPriceDecimal()/1.2) - $this->getCurrentCostDecimal();
            $mark_up = ($profit/$this->getCurrentPriceDecimal()) * 100;
            $mark_up = number_format($mark_up, 2, '.', ' ');

        } else {
            $mark_up = null;
        }

        return $mark_up;
    }

    /**
     * Get current_mark_up_display
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentMarkUpDisplay()
    {
        if($this->getCurrentMarkUp() != null) {
            $mark_up = $this->getCurrentMarkUp();
        } else {
            $mark_up = "N/A";
        }

        return $mark_up;
    }

    /**
     * Get min_recommended_price
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMinRecommendedPrice()
    {
        if($this->getCurrentCostDecimal() != null) {
            
           $min_recommended_price = $this->getCurrentCostDecimal() * 2;
           $min_recommended_price = number_format($min_recommended_price, 2, '.', ' ');

        } else {
            $min_recommended_price = null;
        }

        return $min_recommended_price;
    }

    /**
     * Get max_recommended_price
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMaxRecommendedPrice()
    {
        if($this->getCurrentCostDecimal() != null) {

            $max_recommended_price = $this->getMinRecommendedPrice() * 1.2;
        
            $max_recommended_price = number_format($max_recommended_price, 2, '.', ' ');
        } else {
            $max_recommended_price = null;
        }
        
        return $max_recommended_price;
    }

    /**
     * Add customer_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct
     * @return Product
     */
    public function addCustomerOrderProduct(\MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct)
    {
        $this->customer_order_product[] = $customerOrderProduct;
    
        return $this;
    }

    /**
     * Remove customer_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct
     */
    public function removeCustomerOrderProduct(\MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct)
    {
        $this->customer_order_product->removeElement($customerOrderProduct);
    }

    /**
     * Get customer_order_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOrderProduct()
    {
        return $this->customer_order_product;
    }

    /**
     * Add customer_wish_list_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerWishListProduct $customerWishListProduct
     * @return Product
     */
    public function addCustomerWishListProduct(\MilesApart\AdminBundle\Entity\CustomerWishListProduct $customerWishListProduct)
    {
        $this->customer_wish_list_product[] = $customerWishListProduct;
    
        return $this;
    }

    /**
     * Remove customer_wish_list_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerWishListProduct $customerWishListProduct
     */
    public function removeCustomerWishListProduct(\MilesApart\AdminBundle\Entity\CustomerWishListProduct $customerWishListProduct)
    {
        $this->customer_wish_list_product->removeElement($customerWishListProduct);
    }

    /**
     * Get customer_wish_list_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerWishListProduct()
    {
        return $this->customer_wish_list_product;
    }

    /**
     * Add product_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductTransferRequest $productTransferRequest
     * @return Product
     */
    public function addProductTransferRequest(\MilesApart\AdminBundle\Entity\ProductTransferRequest $productTransferRequest)
    {
        $this->product_transfer_request[] = $productTransferRequest;
    
        return $this;
    }

    /**
     * Remove product_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductTransferRequest $productTransferRequest
     */
    public function removeProductTransferRequest(\MilesApart\AdminBundle\Entity\ProductTransferRequest $productTransferRequest)
    {
        $this->product_transfer_request->removeElement($productTransferRequest);
    }

    /**
     * Get product_transfer_request
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductTransferRequest()
    {
        return $this->product_transfer_request;
    }

    /**
     * Add purchase_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrderProduct $purchaseOrderProduct
     * @return Product
     */
    public function addPurchaseOrderProduct(\MilesApart\AdminBundle\Entity\PurchaseOrderProduct $purchaseOrderProduct)
    {
        $this->purchase_order_product[] = $purchaseOrderProduct;
    
        return $this;
    }

    /**
     * Remove purchase_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrderProduct $purchaseOrderProduct
     */
    public function removePurchaseOrderProduct(\MilesApart\AdminBundle\Entity\PurchaseOrderProduct $purchaseOrderProduct)
    {
        $this->purchase_order_product->removeElement($purchaseOrderProduct);
    }

    /**
     * Get purchase_order_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchaseOrderProduct()
    {
        return $this->purchase_order_product;
    }

    /**
     * Add stocktake_product
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct
     * @return Product
     */
    public function addStocktakeProduct(\MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct)
    {
        $this->stocktake_product[] = $stocktakeProduct;
    
        return $this;
    }

    /**
     * Remove stocktake_product
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct
     */
    public function removeStocktakeProduct(\MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct)
    {
        $this->stocktake_product->removeElement($stocktakeProduct);
    }

    /**
     * Get stocktake_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStocktakeProduct()
    {
        return $this->stocktake_product;
    }

    /**
     * Add seasonal_storage_box_product
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct $stocktakeProduct
     * @return Product
     */
    public function addSeasonalStorageBoxProduct(\MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct $seasonalStorageBoxProduct)
    {
        $this->seasonal_storage_box_product[] = $seasonalStorageBoxProduct;
    
        return $this;
    }

    /**
     * Remove seasonal_storage_box_product
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct $seasonalStorageBoxProduct
     */
    public function removeSeasonalStorageBoxProduct(\MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct $seasonalStorageBoxProduct)
    {
        $this->seasonal_storage_box_product->removeElement($seasonalStorageBoxProduct);
    }

    /**
     * Get seasonal_storage_box_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeasonalStorageBoxProduct()
    {
        return $this->seasonal_storage_box_product;
    }

    /**
     * Add supplier_delivery_product
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct
     * @return Product
     */
    public function addSupplierDeliveryProduct(\MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct)
    {
        $this->supplier_delivery_product[] = $supplierDeliveryProduct;
    
        return $this;
    }

    /**
     * Remove supplier_delivery_product
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct
     */
    public function removeSupplierDeliveryProduct(\MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct)
    {
        $this->supplier_delivery_product->removeElement($supplierDeliveryProduct);
    }

    /**
     * Get supplier_delivery_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierDeliveryProduct()
    {
        return $this->supplier_delivery_product;
    }

    /**
     * Add basket_product
     *
     * @param \MilesApart\BasketBundle\Entity\BasketProduct $basketProduct
     * @return Product
     */
    public function addBasketProduct(\MilesApart\BasketBundle\Entity\BasketProduct $basketProduct)
    {
        $this->basket_product[] = $basketProduct;
    
        return $this;
    }

    /**
     * Remove basket_product
     *
     * @param \MilesApart\BasketBundle\Entity\BasketProduct $basketProduct
     */
    public function removeBasketProduct(\MilesApart\BasketBundle\Entity\BasketProduct $basketProduct)
    {
        $this->basket_product->removeElement($basketProduct);
    }

    /**
     * Get basket_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBasketProduct()
    {
        return $this->basket_product;
    }


    /**
     * Set short_name
     *
     * @param string $shortName
     * @return Product
     */
    public function setShortName($shortName)
    {
        $this->short_name = $shortName;
    
        return $this;
    }

    /**
     * Get short_name
     *
     * @return string 
     */
    public function getShortName()
    {
        return $this->short_name;
    }

    /**
     * Set print_subtitle
     *
     * @param string $printSubtitle
     * @return Product
     */
    public function setPrintSubtitle($printSubtitle)
    {
        $this->print_subtitle = $printSubtitle;
    
        return $this;
    }

    /**
     * Get print_subtitle
     *
     * @return string 
     */
    public function getPrintSubtitle()
    {
        return $this->print_subtitle;
    }

  

    /**
     * Add print_request
     *
     * @param \MilesApart\AdminBundle\Entity\PrintRequest $printRequest
     * @return Product
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
     * Add product_default_category
     *
     * @param \MilesApart\AdminBundle\Entity\Season $productDefaultCategory
     * @return Product
     */
    public function addProductDefaultCategory(\MilesApart\AdminBundle\Entity\Season $productDefaultCategory)
    {
        $this->product_default_category[] = $productDefaultCategory;
    
        return $this;
    }

    /**
     * Remove product_default_category
     *
     * @param \MilesApart\AdminBundle\Entity\Season $productDefaultCategory
     */
    public function removeProductDefaultCategory(\MilesApart\AdminBundle\Entity\Season $productDefaultCategory)
    {
        $this->product_default_category->removeElement($productDefaultCategory);
    }

    /**
     * Get product_default_category
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductDefaultCategory()
    {
        return $this->product_default_category;
    }

    /**
     * Set season
     *
     * @param \MilesApart\AdminBundle\Entity\Category $season
     * @return Product
     */
    public function setSeason(\MilesApart\AdminBundle\Entity\Category $season = null)
    {
        $this->season = $season;
    
        return $this;
    }

    /**
     * Add product_review
     *
     * @param \MilesApart\AdminBundle\Entity\ProductReview $productReview
     * @return Product
     */
    public function addProductReview(\MilesApart\AdminBundle\Entity\ProductReview $productReview)
    {
        $this->product_review[] = $productReview;
    
        return $this;
    }

    /**
     * Remove product_review
     *
     * @param \MilesApart\AdminBundle\Entity\ProductReview $productReview
     */
    public function removeProductReview(\MilesApart\AdminBundle\Entity\ProductReview $productReview)
    {
        $this->product_review->removeElement($productReview);
    }

    /**
     * Get product_review
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductReview()
    {
        return $this->product_review;
    }

    /**
     * Get approved_product_review
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApprovedProductReview()
    {
        //Make array
        $approved_product_reviews = array();
        //Iterate over the product question and check if they have answers
        foreach($this->getProductReview() as $review) {
            if($review->getProductReviewApproved() == true) {
                array_push($approved_product_reviews, $review);
            }
        }
        return $approved_product_reviews;
    }

    /**
     * Set product_marketing_name
     *
     * @param string $productMarketingName
     * @return Product
     */
    public function setProductMarketingName($productMarketingName)
    {
        $this->product_marketing_name = $productMarketingName;
    
        return $this;
    }

    /**
     * Get product_marketing_name
     *
     * @return string 
     */
    public function getProductMarketingName()
    {
        return $this->product_marketing_name;
    }

    /**
     * Set product_default_category
     *
     * @param \MilesApart\AdminBundle\Entity\Category $productDefaultCategory
     * @return Product
     */
    public function setProductDefaultCategory(\MilesApart\AdminBundle\Entity\Category $productDefaultCategory = null)
    {
        $this->product_default_category = $productDefaultCategory;
    
        return $this;
    }

    /**
     * Get average_review_rating
     *
     * @return float 
     */
    public function getAverageReviewRating()
    {
        if (count($this->getProductReview()) > 0) {
            $rating = 0;
            foreach($this->getProductReview() as $key => $value) {
                $rating = $rating + $value->getProductReviewRating();
            }

            $average_rating = $rating / count($this->getProductReview());
            return $average_rating;
        } else {
            return false;
        }
    }

    /**
     * Get product_main_image_path
     *
     * @return float 
     */
    public function getProductMainImagePath()
    {
        if (count($this->getProductImage()) > 0) {
           
            foreach($this->getProductImage() as $key => $value) {
                if($value->getProductImageIsMain() == true){
                    $product_main_image_path = $value->getProductImagePath();
                }
            }

            return $product_main_image_path;
        } else {
            return false;
        }
    }


    /**
     * Add shop_sold_product
     *
     * @param \MilesApart\AdminBundle\Entity\ShopSoldProduct $shopSoldProduct
     * @return Product
     */
    public function addShopSoldProduct(\MilesApart\AdminBundle\Entity\ShopSoldProduct $shopSoldProduct)
    {
        $this->shop_sold_product[] = $shopSoldProduct;

        return $this;
    }

    /**
     * Remove shop_sold_product
     *
     * @param \MilesApart\AdminBundle\Entity\ShopSoldProduct $shopSoldProduct
     */
    public function removeShopSoldProduct(\MilesApart\AdminBundle\Entity\ShopSoldProduct $shopSoldProduct)
    {
        $this->shop_sold_product->removeElement($shopSoldProduct);
    }

    /**
     * Get shop_sold_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShopSoldProduct()
    {
        return $this->shop_sold_product;
    }

    /**
     * Set is_product_discontinued
     *
     * @param boolean $isProductDiscontinued
     * @return Product
     */
    public function setIsProductDiscontinued($isProductDiscontinued)
    {
        $this->is_product_discontinued = $isProductDiscontinued;

        return $this;
    }

    /**
     * Get is_product_discontinued
     *
     * @return boolean 
     */
    public function getIsProductDiscontinued()
    {
        return $this->is_product_discontinued;
    }

    /**
     * Add shop_returned_product
     *
     * @param \MilesApart\AdminBundle\Entity\ShopReturnedProduct $shopReturnedProduct
     * @return Product
     */
    public function addShopReturnedProduct(\MilesApart\AdminBundle\Entity\ShopReturnedProduct $shopReturnedProduct)
    {
        $this->shop_returned_product[] = $shopReturnedProduct;

        return $this;
    }

    /**
     * Remove shop_returned_product
     *
     * @param \MilesApart\AdminBundle\Entity\ShopReturnedProduct $shopReturnedProduct
     */
    public function removeShopReturnedProduct(\MilesApart\AdminBundle\Entity\ShopReturnedProduct $shopReturnedProduct)
    {
        $this->shop_returned_product->removeElement($shopReturnedProduct);
    }

    /**
     * Get shop_returned_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShopReturnedProduct()
    {
        return $this->shop_returned_product;
    }

    /**
     * Add staff_pick_product
     *
     * @param \MilesApart\AdminBundle\Entity\StaffPickProduct $staffPickProduct
     * @return Product
     */
    public function addStaffPickProduct(\MilesApart\AdminBundle\Entity\StaffPickProduct $staffPickProduct)
    {
        $this->staff_pick_product[] = $staffPickProduct;

        return $this;
    }

    /**
     * Remove staff_pick_product
     *
     * @param \MilesApart\AdminBundle\Entity\StaffPickProduct $staffPickProduct
     */
    public function removeStaffPickProduct(\MilesApart\AdminBundle\Entity\StaffPickProduct $staffPickProduct)
    {
        $this->staff_pick_product->removeElement($staffPickProduct);
    }

    /**
     * Get staff_pick_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStaffPickProduct()
    {
        return $this->staff_pick_product;
    }

    /**
     * Set product_meta_description
     *
     * @param string $productMetaDescription
     * @return Product
     */
    public function setProductMetaDescription($productMetaDescription)
    {
        $this->product_meta_description = $productMetaDescription;

        return $this;
    }

    /**
     * Get product_meta_description
     *
     * @return string 
     */
    public function getProductMetaDescription()
    {
        return $this->product_meta_description;
    }

    /**
     * Set is_product_on_amazon
     *
     * @param boolean $isProductOnAmazon
     * @return Product
     */
    public function setIsProductOnAmazon($isProductOnAmazon)
    {
        $this->is_product_on_amazon = $isProductOnAmazon;

        return $this;
    }

    /**
     * Get is_product_on_amazon
     *
     * @return boolean 
     */
    public function getIsProductOnAmazon()
    {
        return $this->is_product_on_amazon;
    }
}
