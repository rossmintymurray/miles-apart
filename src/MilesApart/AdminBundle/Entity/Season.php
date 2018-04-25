<?php
// src/MilesApart/AdminBundle/Entity/Season.php -- Defines the season object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SeasonRepository")
 * @ORM\Table(name="season")
 * @ORM\HasLifecycleCallbacks()
 */

class Season
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
    protected $season_name;

    /**
     * @ORM\Column(type="string", length=1000, nullable=false)
     */
    protected $season_introduction;

    /**
     * @ORM\Column(type="string", length=2, unique=true, nullable=false)
     */
    protected $season_code;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    protected $season_start_date;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    protected $season_end_date;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    protected $season_purchase_stock_start;
    
    /**
     * @Gedmo\Slug(fields={"season_name"}, separator="-")
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $season_slug;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=true)
     */
    protected $season_image_path;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=true)
     */
    protected $season_background_colour;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="season", cascade={"persist"})
     */
    protected $product;

    /**
     * @ORM\OneToMany(targetEntity="SeasonalStorageBox", mappedBy="season", cascade={"persist"})
     */
    protected $seasonal_storage_box;
   

    protected $file;


    private $temp;


    
    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Season name
        $metadata->addPropertyConstraint('season_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('season_name', new Assert\Length(array(
            'min'        => 3,
            'max'        => 100,
            'minMessage' => 'The season name must be at least {{ limit }} characters length',
            'maxMessage' => 'The season name cannot be longer than {{ limit }} characters length',
        )));

        //Season name
        $metadata->addPropertyConstraint('season_code', new Assert\NotBlank());
        $metadata->addPropertyConstraint('season_code', new Assert\Length(array(
            'min'        => 2,
            'max'        => 2,
            'minMessage' => 'The season code must be at least {{ limit }} characters length',
            'maxMessage' => 'The season code cannot be longer than {{ limit }} characters length',
        )));

        //Season start date
        $metadata->addPropertyConstraint('season_start_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('season_start_date', new Assert\Date());

        //Season end date
        $metadata->addPropertyConstraint('season_end_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('season_end_date', new Assert\Date());

        //Season purchase stock start
        $metadata->addPropertyConstraint('season_purchase_stock_start', new Assert\NotBlank());
        $metadata->addPropertyConstraint('season_purchase_stock_start', new Assert\Date());


        //Season image file
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
        return 'images/seasons';
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




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seasonal_storage_box = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set season_name
     *
     * @param string $seasonName
     * @return Season
     */
    public function setSeasonName($seasonName)
    {
        $this->season_name = $seasonName;
    
        return $this;
    }

    /**
     * Get season_name
     *
     * @return string 
     */
    public function getSeasonName()
    {
        return $this->season_name;
    }

    /**
     * Set season_code
     *
     * @param string $seasonCode
     * @return Season
     */
    public function setSeasonCode($seasonCode)
    {
        $this->season_code = $seasonCode;
    
        return $this;
    }

    /**
     * Get season_code
     *
     * @return string 
     */
    public function getSeasonCode()
    {
        return $this->season_code;
    }

    /**
     * Set season_background_colour
     *
     * @param string $seasonBackgroundColour
     * @return Season
     */
    public function setSeasonBackgroundColour($seasonBackgroundColour)
    {
        $this->season_background_colour = $seasonBackgroundColour;
    
        return $this;
    }

    /**
     * Get season_background_colour
     *
     * @return string 
     */
    public function getSeasonBackgroundColour()
    {
        return $this->season_background_colour;
    }

    /**
     * Set season_start_date
     *
     * @param \DateTime $seasonStartDate
     * @return Season
     */
    public function setSeasonStartDate($seasonStartDate)
    {
        $this->season_start_date = $seasonStartDate;
    
        return $this;
    }

    /**
     * Get season_start_date
     *
     * @return \DateTime 
     */
    public function getSeasonStartDate()
    {
        return $this->season_start_date;
    }

    /**
     * Set season_end_date
     *
     * @param \DateTime $seasonEndDate
     * @return Season
     */
    public function setSeasonEndDate($seasonEndDate)
    {
        $this->season_end_date = $seasonEndDate;
    
        return $this;
    }

    /**
     * Get season_end_date
     *
     * @return \DateTime 
     */
    public function getSeasonEndDate()
    {
        return $this->season_end_date;
    }

    /**
     * Set season_purchase_stock_start
     *
     * @param \DateTime $seasonPurchaseStockStart
     * @return Season
     */
    public function setSeasonPurchaseStockStart($seasonPurchaseStockStart)
    {
        $this->season_purchase_stock_start = $seasonPurchaseStockStart;
    
        return $this;
    }

    /**
     * Get season_purchase_stock_start
     *
     * @return \DateTime 
     */
    public function getSeasonPurchaseStockStart()
    {
        return $this->season_purchase_stock_start;
    }

    /**
     * Set season_slug
     *
     * @param string $seasonSlug
     * @return Season
     */
    public function setSeasonSlug($seasonSlug)
    {
        $this->season_slug = $seasonSlug;
    
        return $this;
    }

    /**
     * Get season_slug
     *
     * @return string 
     */
    public function getSeasonSlug()
    {
        return $this->season_slug;
    }

    /**
     * Set season_image_path
     *
     * @param string $seasonImagePath
     * @return Season
     */
    public function setSeasonImagePath($seasonImagePath)
    {
        $this->season_image_path = $seasonImagePath;
    
        return $this;
    }

    /**
     * Get season_image_path
     *
     * @return string 
     */
    public function getSeasonImagePath()
    {
        return $this->season_image_path;
    }

    /**
     * Add product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return Season
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
     * Add seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox
     * @return Season
     */
    public function addSeasonalStorageBox(\MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox)
    {
        $this->seasonal_storage_box[] = $seasonalStorageBox;
    
        return $this;
    }

    /**
     * Remove seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox
     */
    public function removeSeasonalStorageBox(\MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox)
    {
        $this->seasonal_storage_box->removeElement($seasonalStorageBox);
    }

    /**
     * Get seasonal_storage_box
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeasonalStorageBox()
    {
        return $this->seasonal_storage_box;
    }

    /**
     * Get stored_season_stock
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStoredSeasonStock()
    {

        //Create query for joining the season and product tables
        $existing = false;
        $stored_season_stock = array();
        

        //If storage box exists
        if (count($this->getSeasonalStorageBox()) > 0) {

            //Iterate over the storage boxes
            foreach($this->getSeasonalStorageBox() as $key => $value) {

                //Iterate over the contents of the storage boxes
                foreach($value->getSeasonalStorageBoxProduct() as $key => $value2) {
                    $existing = false;
                    //Iterate over stored season stock array to check if any product ids = this one.
                    foreach($stored_season_stock as $key2 => $value3){
                        
                        //If the product id of the object exists in the season stock array
                        if($value2->getProduct()->getId() == $value3["id"]) {
                            //Set existing to true
                            $existing = true;
                            //Add the quantity.
                            $stored_season_stock[$key2]["qty"] = $value3["qty"] + $value2->getSeasonalStorageBoxProductQty();
                        } 

                    }

                    if ($existing == false){
                        //Doesn't exist in array so add it.

                        //Fisrts set up supplier (incase not set).
                        if($value2->getProduct()->getDefaultProductSupplier() == null) {
                            $supplier = "unknown";
                        } else {
                            $supplier = $value2->getProduct()->getDefaultProductSupplier()->getSupplier()->getSupplierShortName();
                        }
                        
                        $stored_season_stock_product = array(
                            'id' => $value2->getProduct()->getId(),
                            'qty' => $value2->getSeasonalStorageBoxProductQty(),
                            'product_name' => $value2->getProduct()->getProductName(),
                            'supplier' => $supplier,
                            'price' => $value2->getProduct()->getCurrentPriceDecimal(),
                            'box' => $value2->getSeasonalStorageBox()->getSeasonalStorageBoxCode()
                        );

                        //Push to stored season stock array.
                        array_push($stored_season_stock, $stored_season_stock_product);

                        
                    }
                }
            }
        }

    


        return $stored_season_stock;
    }

    /**
     * Get stored_season_stock_count
     *
     * 
     */
    public function getStoredSeasonStockProductCount()
    {   
        $stored_season_stock_count = 0;

        //If storage box exists
        if (count($this->getSeasonalStorageBox()) > 0) {

            //Iterate over the storage boxes
            foreach($this->getSeasonalStorageBox() as $key => $value) {

                //Iterate over the contents of the storage boxes
                foreach($value->getSeasonalStorageBoxProduct() as $key => $value2) {

                    $stored_season_stock_count = $stored_season_stock_count +1;
                }
            }
        }

        return $stored_season_stock_count;
    }


    /**
     * Set season_introduction
     *
     * @param string $seasonIntroduction
     * @return Season
     */
    public function setSeasonIntroduction($seasonIntroduction)
    {
        $this->season_introduction = $seasonIntroduction;
    
        return $this;
    }

    /**
     * Get season_introduction
     *
     * @return string 
     */
    public function getSeasonIntroduction()
    {
        return $this->season_introduction;
    }
}