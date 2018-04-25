<?php
// src/MilesApart/AdminBundle/Entity/CSVFile.php -- Defines the csv file object

namespace MilesApart\StaffBundle\Entity\Products;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ProductListCSVFile
{
    //Define the values

    protected $file;

    private $temp;

    protected $supplier;

    //Validators 
     public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Product image file
        $metadata->addPropertyConstraint('file', new Assert\File(array(
            'maxSize' => 6000000,
        )));
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
        if (isset($this->csv_file_path)) {
            // store the old name to delete after the update
            $this->temp = $this->csv_file_path;
            $this->csv_file_path = null;
        } else {
            $this->csv_file_path = 'initial';
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
        return null === $this->csv_file_path
            ? null
            : $this->getUploadRootDir().'/'.$this->csv_file_path;
    }

    public function getWebPath()
    {
        return null === $this->csv_file_path
            ? null
            : $this->getUploadDir().'/'.$this->csv_file_path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'product-list-uploads';
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
            $this->csv_file_path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), 
            $this->csv_file_path);

         // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
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
        return 'CSVFile';
      }



    /**
     * Set csv_file_path
     *
     * @param string $csvFilePath
     * @return CSVFile
     */
    public function setProductListCSVFilePath($csvFilePath)
    {
        $this->csv_file_path = $productImagePath;
    
        return $this;
    }

    /**
     * Get csv_file_path
     *
     * @return string 
     */
    public function getProductListCSVFilePath()
    {
        return $this->csv_file_path;
    }

    /**
     * Set supplier
     *
     * @param string $supplier
     * @return Supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    
        return $this;
    }

    /**
     * Get supplier
     *
     * @return \MilesApart\AdminBundle\Entity\Supplier 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }


}