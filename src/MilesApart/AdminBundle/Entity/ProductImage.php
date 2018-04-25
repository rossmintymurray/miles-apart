<?php
// src/MilesApart/AdminBundle/Entity/ProductImage.php -- Defines the product image object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductImageRepository")
 * @ORM\Table(name="product_image")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductImage
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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="product_image", cascade={"persist"})
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $product_image_path;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $product_image_title;

    /**
     * @ORM\Column(type="string", length=500, unique=false, nullable=true)
     */
    protected $product_image_description;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $product_image_is_main;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $product_image_web_display;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_image_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_image_date_modified;

    /*
    protected $file;


    private $temp;
*/


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setProductImageDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getProductImageDateCreated() == null)
        {
            $this->setProductImageDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    

    //Validators 
     public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        

        //Product image title
        $metadata->addPropertyConstraint('product_image_title', new Assert\Length(array(
            'min'        => 4,
            'max'        => 100,
            'minMessage' => 'The product image title must be at least {{ limit }} characters length',
            'maxMessage' => 'The product image title cannot be longer than {{ limit }} characters length',
        )));

        //Product image description
        $metadata->addPropertyConstraint('product_image_description', new Assert\Length(array(
            'min'        => 4,
            'max'        => 500,
            'minMessage' => 'The product image title must be at least {{ limit }} characters length',
            'maxMessage' => 'The product image title cannot be longer than {{ limit }} characters length',
        )));

        
    }



    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    /*
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->product_image_path)) {
            // store the old name to delete after the update
            $this->temp = $this->product_image_path;
            $this->product_image_path = null;
        } else {
            $this->product_image_path = 'initial';
        }
    }
*/
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    /*
    public function getFile()
    {
        return $this->file;
    }


    public function getAbsolutePath()
    {
        return null === $this->product_image_path
            ? null
            : $this->getUploadRootDir().'/'.$this->product_image_path;
    }

    public function getWebPath()
    {
        return null === $this->product_image_path
            ? null
            : $this->getUploadDir().'/'.$this->product_image_path;
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
        return 'images/products';
    }
*/
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    /*
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            //var_dump($this);
            // do whatever you want to generate a unique name
            $filename = $this->getFile()->getClientOriginalName();
            $this->product_image_path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }
*/
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    /*
    public function upload()
    {
        

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), 
            $this->product_image_path);

         // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }
*/
    /**
     * @ORM\PostRemove()
     */
    /*
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    public function __toString()
      {
        return 'ProductImage';
      }

    
    */


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
     * Set product_image_path
     *
     * @param string $productImagePath
     * @return ProductImage
     */
    public function setProductImagePath($productImagePath)
    {
        $this->product_image_path = $productImagePath;
    
        return $this;
    }

    /**
     * Get product_image_path
     *
     * @return string 
     */
    public function getProductImagePath()
    {
        return $this->product_image_path;
    }

    /**
     * Set product_image_title
     *
     * @param string $productImageTitle
     * @return ProductImage
     */
    public function setProductImageTitle($productImageTitle)
    {
        $this->product_image_title = $productImageTitle;
    
        return $this;
    }

    /**
     * Get product_image_title
     *
     * @return string 
     */
    public function getProductImageTitle()
    {
        return $this->product_image_title;
    }

    /**
     * Set product_image_description
     *
     * @param string $productImageDescription
     * @return ProductImage
     */
    public function setProductImageDescription($productImageDescription)
    {
        $this->product_image_description = $productImageDescription;
    
        return $this;
    }

    /**
     * Get product_image_description
     *
     * @return string 
     */
    public function getProductImageDescription()
    {
        return $this->product_image_description;
    }

    /**
     * Set product_image_is_main
     *
     * @param boolean $productImageIsMain
     * @return ProductImage
     */
    public function setProductImageIsMain($productImageIsMain)
    {
        $this->product_image_is_main = $productImageIsMain;
    
        return $this;
    }

    /**
     * Get product_image_is_main
     *
     * @return boolean 
     */
    public function getProductImageIsMain()
    {
        return $this->product_image_is_main;
    }

    /**
     * Set product_image_web_display
     *
     * @param boolean $productImageWebDisplay
     * @return ProductImage
     */
    public function setProductImageWebDisplay($productImageWebDisplay)
    {
        $this->product_image_web_display = $productImageWebDisplay;
    
        return $this;
    }

    /**
     * Get product_image_web_display
     *
     * @return boolean 
     */
    public function getProductImageWebDisplay()
    {
        return $this->product_image_web_display;
    }

    /**
     * Set product_image_date_created
     *
     * @param \DateTime $productImageDateCreated
     * @return ProductImage
     */
    public function setProductImageDateCreated($productImageDateCreated)
    {
        $this->product_image_date_created = $productImageDateCreated;
    
        return $this;
    }

    /**
     * Get product_image_date_created
     *
     * @return \DateTime 
     */
    public function getProductImageDateCreated()
    {
        return $this->product_image_date_created;
    }

    /**
     * Set product_image_date_modified
     *
     * @param \DateTime $productImageDateModified
     * @return ProductImage
     */
    public function setProductImageDateModified($productImageDateModified)
    {
        $this->product_image_date_modified = $productImageDateModified;
    
        return $this;
    }

    /**
     * Get product_image_date_modified
     *
     * @return \DateTime 
     */
    public function getProductImageDateModified()
    {
        return $this->product_image_date_modified;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return ProductImage
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