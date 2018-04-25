<?php
// src/MilesApart/AdminBundle/Entity/Brand.php -- Defines the brand object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\BrandRepository")
 * @ORM\Table(name="brand")
 * @ORM\HasLifecycleCallbacks()
 */

class Brand
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
    protected $brand_name;

    /**
     * @Gedmo\Slug(fields={"brand_name"}, separator="-")
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $brand_slug;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=true)
     */
    protected $brand_logo_image_path;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $brand_introduction;

    /**
     * @ORM\OneToMany(targetEntity="BrandDescriptionParagraph", mappedBy="brand", cascade={"persist"})
     */
    protected $brand_description_paragraph;

    /**
     * @ORM\OneToMany(targetEntity="BrandFeature", mappedBy="brand", cascade={"persist"})
     */
    protected $brand_feature;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="brand")
     */
    protected $product;

    /* For images */
    protected $file;

    private $temp;


    
    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Brand name
        $metadata->addPropertyConstraint('brand_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('brand_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The brand name must be at least {{ limit }} characters length',
            'maxMessage' => 'The brand name cannot be longer than {{ limit }} characters length',
        )));

        
        $metadata->addPropertyConstraint('file', new Assert\File(array(
            'maxSize' => 6000000,
        )));
    }   



    /**
    * Image upload handling 
    */
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->brand_logo_image_path)) {
            // store the old name to delete after the update
            $this->temp = $this->brand_logo_image_path;
            $this->brand_logo_image_path = null;
        } else {
            $this->brand_logo_image_path = 'initial';
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
        return null === $this->brand_logo_image_path
            ? null
            : $this->getUploadRootDir().'/'.$this->brand_logo_image_path;
    }

    public function getWebPath()
    {
        return null === $this->brand_logo_image_path
            ? null
            : $this->getUploadDir().'/'.$this->brand_logo_image_path;
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
        return 'images/brands';
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
            $this->brand_logo_image_path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        //Check if file is set
        if(null !== $this->getFile()) {

            // if there is an error when moving the file, an exception will
            // be automatically thrown by move(). This will properly prevent
            // the entity from being persisted to the database on error
            $this->getFile()->move($this->getUploadRootDir(), 
                $this->brand_logo_image_path);

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
        return 'BrandImage';
      }


    /**
     * Set brand_slug
     *
     * @param string $brandSlug
     * @return Brand
     */
    public function setBrandSlug($brandSlug)
    {
        $this->brand_slug = $brandSlug;
    
        return $this;
    }

     /**
     * Get product_slug
     *
     * @return string 
     */
    public function getBrandSlug()
    {
        return MilesApart::slugify($this->getBrandName());
    }

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set brand_name
     *
     * @param string $brandName
     * @return Brand
     */
    public function setBrandName($brandName)
    {
        $this->brand_name = $brandName;
    
        return $this;
    }

    /**
     * Get brand_name
     *
     * @return string 
     */
    public function getBrandName()
    {
        return $this->brand_name;
    }

    /**
     * Set brand_logo_image_path
     *
     * @param string $brandLogoImagePath
     * @return Brand
     */
    public function setBrandLogoImagePath($brandLogoImagePath)
    {
        $this->brand_logo_image_path = $brandLogoImagePath;
    
        return $this;
    }

    /**
     * Get brand_logo_image_path
     *
     * @return string 
     */
    public function getBrandLogoImagePath()
    {
        return $this->brand_logo_image_path;
    }

    /**
     * Add product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return Brand
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
     * Set brand_introduction
     *
     * @param string $brandIntroduction
     * @return Brand
     */
    public function setBrandIntroduction($brandIntroduction)
    {
        $this->brand_introduction = $brandIntroduction;
    
        return $this;
    }

    /**
     * Get brand_introduction
     *
     * @return string 
     */
    public function getBrandIntroduction()
    {
        return $this->brand_introduction;
    }

    /**
     * Add brand_description_paragraph
     *
     * @param \MilesApart\AdminBundle\Entity\BrandDescriptionParagraph $brandDescriptionParagraph
     * @return Brand
     */
    public function addBrandDescriptionParagraph(\MilesApart\AdminBundle\Entity\BrandDescriptionParagraph $brandDescriptionParagraph)
    {
        $this->brand_description_paragraph[] = $brandDescriptionParagraph;
    
        return $this;
    }

    /**
     * Remove brand_description_paragraph
     *
     * @param \MilesApart\AdminBundle\Entity\BrandDescriptionParagraph $brandDescriptionParagraph
     */
    public function removeBrandDescriptionParagraph(\MilesApart\AdminBundle\Entity\BrandDescriptionParagraph $brandDescriptionParagraph)
    {
        $this->brand_description_paragraph->removeElement($brandDescriptionParagraph);
    }

    /**
     * Get brand_description_paragraph
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBrandDescriptionParagraph()
    {
        return $this->brand_description_paragraph;
    }

    /**
     * Add brand_feature
     *
     * @param \MilesApart\AdminBundle\Entity\BrandFeature $brandFeature
     * @return Brand
     */
    public function addBrandFeature(\MilesApart\AdminBundle\Entity\BrandFeature $brandFeature)
    {
        $this->brand_feature[] = $brandFeature;
    
        return $this;
    }

    /**
     * Remove brand_feature
     *
     * @param \MilesApart\AdminBundle\Entity\BrandFeature $brandFeature
     */
    public function removeBrandFeature(\MilesApart\AdminBundle\Entity\BrandFeature $brandFeature)
    {
        $this->brand_feature->removeElement($brandFeature);
    }

    /**
     * Get brand_feature
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBrandFeature()
    {
        return $this->brand_feature;
    }
}