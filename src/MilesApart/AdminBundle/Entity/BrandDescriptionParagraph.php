<?php
// src/MilesApart/AdminBundle/Entity/BrandDescriptionParagraph.php -- Defines the brand object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\BrandDescriptionParagraphRepository")
 * @ORM\Table(name="brand_description_paragraph")
 * @ORM\HasLifecycleCallbacks()
 */

class BrandDescriptionParagraph
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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $brand_description_paragraph_header;

    /**
     * @ORM\Column(type="string", length=5000, nullable=false)
     */
    protected $brand_description_paragraph_text;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="brand_description_paragraph", cascade={"persist"})
     * @ORM\JoinTable(name="brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    protected $brand;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $brand_description_paragraph_image_path;

     /* For images */
    protected $file;

    private $temp;

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
        if (isset($this->brand_description_paragraph_image_path)) {
            // store the old name to delete after the update
            $this->temp = $this->brand_description_paragraph_image_path;
            $this->brand_description_paragraph_image_path = null;
        } else {
            $this->brand_description_paragraph_image_path = 'initial';
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
        return null === $this->brand_description_paragraph_image_path
            ? null
            : $this->getUploadRootDir().'/'.$this->brand_description_paragraph_image_path;
    }

    public function getWebPath()
    {
        return null === $this->brand_description_paragraph_image_path
            ? null
            : $this->getUploadDir().'/'.$this->brand_description_paragraph_image_path;
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
        return 'images/brand_descriptions';
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
            $this->brand_description_paragraph_image_path = $filename.'.'.$this->getFile()->guessExtension();
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
                $this->brand_description_paragraph_image_path);

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
        return 'BrandDescriptionImage';
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
     * Set brand_description_paragraph_header
     *
     * @param string $brandDescriptionParagraphHeader
     * @return BrandDescriptionParagraph
     */
    public function setBrandDescriptionParagraphHeader($brandDescriptionParagraphHeader)
    {
        $this->brand_description_paragraph_header = $brandDescriptionParagraphHeader;

        return $this;
    }

    /**
     * Get brand_description_paragraph_header
     *
     * @return string 
     */
    public function getBrandDescriptionParagraphHeader()
    {
        return $this->brand_description_paragraph_header;
    }

    /**
     * Set brand_description_paragraph_text
     *
     * @param string $brandDescriptionParagraphText
     * @return BrandDescriptionParagraph
     */
    public function setBrandDescriptionParagraphText($brandDescriptionParagraphText)
    {
        $this->brand_description_paragraph_text = $brandDescriptionParagraphText;

        return $this;
    }

    /**
     * Get brand_description_paragraph_text
     *
     * @return string 
     */
    public function getBrandDescriptionParagraphText()
    {
        return $this->brand_description_paragraph_text;
    }

    /**
     * Set brand_description_paragraph_image_path
     *
     * @param string $brandDescriptionParagraphImagePath
     * @return BrandDescriptionParagraph
     */
    public function setBrandDescriptionParagraphImagePath($brandDescriptionParagraphImagePath)
    {
        $this->brand_description_paragraph_image_path = $brandDescriptionParagraphImagePath;

        return $this;
    }

    /**
     * Get brand_description_paragraph_image_path
     *
     * @return string 
     */
    public function getBrandDescriptionParagraphImagePath()
    {
        return $this->brand_description_paragraph_image_path;
    }

    /**
     * Set brand
     *
     * @param \MilesApart\AdminBundle\Entity\Brand $brand
     * @return BrandDescriptionParagraph
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
}
