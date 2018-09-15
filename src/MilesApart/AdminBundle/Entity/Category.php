<?php
// src/MilesApart/AdminBundle/Entity/Category.php -- Defines the main category object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CategoryRepository")
 * @ORM\Table(name="category")
 * @ORM\HasLifecycleCallbacks()
 */

class Category
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
    protected $category_name;

    /**
     * @Gedmo\Slug(fields={"category_name"}, separator="-")
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $category_slug;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $category_image_path;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=true)
     */
    protected $category_description;

    /**
     * @ORM\Column(type="integer")
     */
    protected $category_display_order;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $category_navigation_display;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $category_products_display;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $category_description_display;

    /**
     * @ORM\ManyToMany(targetEntity="Keyword", inversedBy="category", cascade={"persist"})
     * @ORM\JoinTable(name="keyword_category",
     * joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="keyword_id", referencedColumnName="id")})
     */
    protected $keyword;

    /**
     * @ORM\ManyToOne(targetEntity="CategoryType", inversedBy="category")
     * @ORM\JoinTable(name="category_type")
     * @ORM\JoinColumn(name="category_type_id", referencedColumnName="id")
     */
    protected $category_type;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"category_display_order" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="product_default_category")
     */
    protected $product_default;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="category", cascade={"persist"})
     */
    protected $product;

    protected $file;

    private $temp;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $category_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $category_date_modified;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Category name
        $metadata->addPropertyConstraint('category_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('category_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The category name must be at least {{ limit }} characters length',
            'maxMessage' => 'The category name cannot be longer than {{ limit }} characters length',
        )));

        //Product image file
        $metadata->addPropertyConstraint('file', new Assert\File(array(
            'maxSize' => 60000000,
        )));


    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setCategoryDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCategoryDateCreated() == null)
        {
            $this->setCategoryDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->keyword = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category_display_order = 0;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->category_image_path)) {
            // store the old name to delete after the update
            $this->temp = $this->category_image_path;
            $this->category_image_path = null;
        } else {
            $this->category_image_path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }


    public function getAbsolutePath()
    {
        return null === $this->category_image_path
            ? null
            : $this->getUploadRootDir().'/'.$this->category_image_path;
    }

    public function getWebPath()
    {
        return null === $this->category_image_path
            ? null
            : $this->getUploadDir().'/'.$this->category_image_path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'images/categories';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            //var_dump($this);
            // do whatever you want to generate a unique name
            $filename = $this->getFile()->getClientOriginalName();
            $this->category_image_path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        
        //Check that the image file exists
        if($this->getFile() != NULL) {



            // if there is an error when moving the file, an exception will
            // be automatically thrown by move(). This will properly prevent
            // the entity from being persisted to the database on error
            $this->getFile()->move($this->getUploadRootDir(), 
                $this->category_image_path);

             // check if we have an old image
            if (isset($this->temp)) {
                // delete the old image
                unlink($this->getUploadRootDir().'/'.$this->temp);
                // clear the temp image path
                $this->temp = null;
            }
            $this->file = null;
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    public function __toString()
      {
        return 'CategoryImage';
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
     * Set category_date_created
     *
     * @param string $categoryDateCreated
     * @return Category
     */
    public function setCategoryDateCreated($categoryDateCreated)
    {
        $this->category_date_created = $categoryDateCreated;
    
        return $this;
    }

    /**
     * Get category_date_created
     *
     * @return string 
     */
    public function getCategoryDateCreated()
    {
        return $this->category_date_created;
    }

    /**
     * Set category_date_modified
     *
     * @param string $categoryDateModified
     * @return Category
     */
    public function setCategoryDateModified($categoryDateModified)
    {
        $this->category_date_modified = $categoryDateModified;
    
        return $this;
    }

    /**
     * Get category_date_modified
     *
     * @return string 
     */
    public function getCategoryDateModified()
    {
        return $this->category_date_modified;
    }

    /**
     * Set category_name
     *
     * @param string $categoryName
     * @return Category
     */
    public function setCategoryName($categoryName)
    {
        $this->category_name = $categoryName;
    
        return $this;
    }

    /**
     * Get category_name
     *
     * @return string 
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * Set category_slug
     *
     * @param string $categorySlug
     * @return Category
     */
    public function setCategorySlug($categorySlug)
    {
        $this->category_slug = $categorySlug;
    
        return $this;
    }

    /**
     * Get product_slug
     *
     * @return string 
     */
    public function getCategorySlug()
    {
        return MilesApart::slugify($this->getCategoryName());
    }

    /**
     * Set category_image_path
     *
     * @param string $categoryImagePath
     * @return Category
     */
    public function setCategoryImagePath($categoryImagePath)
    {
        $this->category_image_path = $categoryImagePath;
    
        return $this;
    }

    /**
     * Get category_image_path
     *
     * @return string 
     */
    public function getCategoryImagePath()
    {
        return $this->category_image_path;
    }

    /**
     * Set category_description
     *
     * @param string $categoryDescription
     * @return Category
     */
    public function setCategoryDescription($categoryDescription)
    {
        $this->category_description = $categoryDescription;
    
        return $this;
    }

    /**
     * Get category_description
     *
     * @return string 
     */
    public function getCategoryDescription()
    {
        return $this->category_description;
    }

    /**
     * Set category_navigation_display
     *
     * @param boolean $categoryNavigationDisplay
     * @return Category
     */
    public function setCategoryNavigationDisplay($categoryNavigationDisplay)
    {
        $this->category_navigation_display = $categoryNavigationDisplay;
    
        return $this;
    }

    /**
     * Get category_navigation_display
     *
     * @return boolean 
     */
    public function getCategoryNavigationDisplay()
    {
        return $this->category_navigation_display;
    }

    /**
     * Set category_products_display
     *
     * @param boolean $categoryProductsDisplay
     * @return Category
     */
    public function setCategoryProductsDisplay($categoryProductsDisplay)
    {
        $this->category_products_display = $categoryProductsDisplay;
    
        return $this;
    }

    /**
     * Get category_products_display
     *
     * @return boolean 
     */
    public function getCategoryProductsDisplay()
    {
        return $this->category_products_display;
    }

    /**
     * Add keyword
     *
     * @param \MilesApart\AdminBundle\Entity\Keyword $keyword
     * @return Category
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
     * Set category_type
     *
     * @param \MilesApart\AdminBundle\Entity\CategoryType $categoryType
     * @return Category
     */
    public function setCategoryType(\MilesApart\AdminBundle\Entity\CategoryType $categoryType = null)
    {
        $this->category_type = $categoryType;
    
        return $this;
    }

    /**
     * Get category_type
     *
     * @return \MilesApart\AdminBundle\Entity\CategoryType 
     */
    public function getCategoryType()
    {
        return $this->category_type;
    }

    /**
     * Add children
     *
     * @param \MilesApart\AdminBundle\Entity\Category $children
     * @return Category
     */
    public function addChildren(\MilesApart\AdminBundle\Entity\Category $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \MilesApart\AdminBundle\Entity\Category $children
     */
    public function removeChildren(\MilesApart\AdminBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \MilesApart\AdminBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\MilesApart\AdminBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \MilesApart\AdminBundle\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return Category
     */
    public function addProduct(\MilesApart\AdminBundle\Entity\Product $product)
    {
        $this->product[] = $product;
    
        return $this;
    }

    /**
     * Remove product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     */
    public function removeProduct(\MilesApart\AdminBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set category_display_order
     *
     * @param integer $categoryDisplayOrder
     * @return Category
     */
    public function setCategoryDisplayOrder($categoryDisplayOrder)
    {
        $this->category_display_order = $categoryDisplayOrder;
    
        return $this;
    }

    /**
     * Get category_display_order
     *
     * @return integer 
     */
    public function getCategoryDisplayOrder()
    {
        return $this->category_display_order;
    }

    /**
     * Add product_default
     *
     * @param \MilesApart\AdminBundle\Entity\Product $productDefault
     * @return Category
     */
    public function addProductDefault(\MilesApart\AdminBundle\Entity\Product $productDefault)
    {
        $this->product_default[] = $productDefault;
    
        return $this;
    }

    /**
     * Remove product_default
     *
     * @param \MilesApart\AdminBundle\Entity\Product $productDefault
     */
    public function removeProductDefault(\MilesApart\AdminBundle\Entity\Product $productDefault)
    {
        $this->product_default->removeElement($productDefault);
    }

    /**
     * Get product_default
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductDefault()
    {
        return $this->product_default;
    }

    /**
     * Set category_description_display
     *
     * @param boolean $categoryDescriptionDisplay
     * @return Category
     */
    public function setCategoryDescriptionDisplay($categoryDescriptionDisplay)
    {
        $this->category_description_display = $categoryDescriptionDisplay;

        return $this;
    }

    /**
     * Get category_description_display
     *
     * @return boolean 
     */
    public function getCategoryDescriptionDisplay()
    {
        return $this->category_description_display;
    }

    /**
     * Add children
     *
     * @param \MilesApart\AdminBundle\Entity\Category $children
     * @return Category
     */
    public function addChild(\MilesApart\AdminBundle\Entity\Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \MilesApart\AdminBundle\Entity\Category $children
     */
    public function removeChild(\MilesApart\AdminBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }
}
