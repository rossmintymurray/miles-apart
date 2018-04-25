<?php
// src/MilesApart/AdminBundle/Entity/ShippingManifestState.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ShippingManifestStateRepository")
 * @ORM\Table(name="shipping_manifest_state")
 * @ORM\HasLifecycleCallbacks()
 */

class ShippingManifestState
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
    protected $shipping_manifest_state;

   /**
     * @ORM\OneToMany(targetEntity="ShippingManifest", mappedBy="shipping_manifest_state", cascade={"persist"})
     */
    protected $shipping_manifest;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Custeomr type name
        $metadata->addPropertyConstraint('shipping_manifest_state', new Assert\NotBlank());
        $metadata->addPropertyConstraint('shipping_manifest_state', new Assert\Length(array(
            'min'        => 2,
            'max'        => 20,
            'minMessage' => 'The shipping manifest state must be at least {{ limit }} characters length',
            'maxMessage' => 'The shipping manifest state cannot be longer than {{ limit }} characters length',
        )));
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shipping_manifest = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set shipping_manifest_state
     *
     * @param string $shippingManifestState
     * @return ShippingManifestState
     */
    public function setShippingManifestState($shippingManifestState)
    {
        $this->shipping_manifest_state = $shippingManifestState;

        return $this;
    }

    /**
     * Get shipping_manifest_state
     *
     * @return string 
     */
    public function getShippingManifestState()
    {
        return $this->shipping_manifest_state;
    }

    /**
     * Add shipping_manifest
     *
     * @param \MilesApart\AdminBundle\Entity\ShippingManifest $shippingManifest
     * @return ShippingManifestState
     */
    public function addShippingManifest(\MilesApart\AdminBundle\Entity\ShippingManifest $shippingManifest)
    {
        $this->shipping_manifest[] = $shippingManifest;

        return $this;
    }

    /**
     * Remove shipping_manifest
     *
     * @param \MilesApart\AdminBundle\Entity\ShippingManifest $shippingManifest
     */
    public function removeShippingManifest(\MilesApart\AdminBundle\Entity\ShippingManifest $shippingManifest)
    {
        $this->shipping_manifest->removeElement($shippingManifest);
    }

    /**
     * Get shipping_manifest
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShippingManifest()
    {
        return $this->shipping_manifest;
    }
}
