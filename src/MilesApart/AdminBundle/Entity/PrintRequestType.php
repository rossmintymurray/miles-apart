<?php
// src/MilesApart/AdminBundle/Entity/PrintRequestType.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PrintRequestTypeRepository")
 * @ORM\Table(name="print_request_type")
 * @ORM\HasLifecycleCallbacks()
 */

class PrintRequestType
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
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $print_request_type_name;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $print_request_type_date_created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $print_request_type_date_modified;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $print_request_type_view_path;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $print_request_type_css_append;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $print_request_type_number_per_page;

    /**
     * @ORM\OneToMany(targetEntity="PrintRequest", mappedBy="print_request_type")
     */
    protected $print_request;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setPrintRequestTypeDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getPrintRequestTypeDateCreated() == null)
        {
            $this->setPrintRequestTypeDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
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
     * Set print_request_type_name
     *
     * @param string $printRequestTypeName
     * @return PrintRequestType
     */
    public function setPrintRequestTypeName($printRequestTypeName)
    {
        $this->print_request_type_name = $printRequestTypeName;
    
        return $this;
    }

    /**
     * Get print_request_type_name
     *
     * @return string 
     */
    public function getPrintRequestTypeName()
    {
        return $this->print_request_type_name;
    }

    /**
     * Set print_request_type_date_created
     *
     * @param \DateTime $printRequestTypeDateCreated
     * @return PrintRequestType
     */
    public function setPrintRequestTypeDateCreated($printRequestTypeDateCreated)
    {
        $this->print_request_type_date_created = $printRequestTypeDateCreated;
    
        return $this;
    }

    /**
     * Get print_request_type_date_created
     *
     * @return \DateTime 
     */
    public function getPrintRequestTypeDateCreated()
    {
        return $this->print_request_type_date_created;
    }

    /**
     * Set print_request_type_date_modified
     *
     * @param \DateTime $printRequestTypeDateModified
     * @return PrintRequestType
     */
    public function setPrintRequestTypeDateModified($printRequestTypeDateModified)
    {
        $this->print_request_type_date_modified = $printRequestTypeDateModified;
    
        return $this;
    }

    /**
     * Get print_request_type_date_modified
     *
     * @return \DateTime 
     */
    public function getPrintRequestTypeDateModified()
    {
        return $this->print_request_type_date_modified;
    }

    /**
     * Set print_request_type_view_path
     *
     * @param string $printRequestTypeViewPath
     * @return PrintRequestType
     */
    public function setPrintRequestTypeViewPath($printRequestTypeViewPath)
    {
        $this->print_request_type_view_path = $printRequestTypeViewPath;
    
        return $this;
    }

    /**
     * Get print_request_type_view_path
     *
     * @return string 
     */
    public function getPrintRequestTypeViewPath()
    {
        return $this->print_request_type_view_path;
    }
    
    
    /**
     * Set print_request_type_css_append
     *
     * @param string $printRequestTypeCssAppend
     * @return PrintRequestType
     */
    public function setPrintRequestTypeCssAppend($printRequestTypeCssAppend)
    {
        $this->print_request_type_css_append = $printRequestTypeCssAppend;
    
        return $this;
    }

    /**
     * Get print_request_type_css_append
     *
     * @return string 
     */
    public function getPrintRequestTypeCssAppend()
    {
        return $this->print_request_type_css_append;
    }

    /**
     * Add print_request
     *
     * @param \MilesApart\AdminBundle\Entity\PrintRequest $printRequest
     * @return PrintRequestType
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
     * Set print_request_type_number_per_page
     *
     * @param integer $printRequestTypeNumberPerPage
     * @return PrintRequestType
     */
    public function setPrintRequestTypeNumberPerPage($printRequestTypeNumberPerPage)
    {
        $this->print_request_type_number_per_page = $printRequestTypeNumberPerPage;
    
        return $this;
    }

    /**
     * Get print_request_type_number_per_page
     *
     * @return integer 
     */
    public function getPrintRequestTypeNumberPerPage()
    {
        return $this->print_request_type_number_per_page;
    }

    /**
     * Get print_request total unprinted 
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrintRequestTotalUnprinted()
    {
        $total = 0;
    
        foreach($this->getPrintRequest() as $key => $value) {
            if($value->getPrintRequestPrinted() == FALSE) {
                $total++;
            }
        }

        return $total;
            
    }

}