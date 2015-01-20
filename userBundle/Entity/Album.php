<?php

namespace eclore\userBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Album
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Album
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="eclore\userBundle\Entity\User", inversedBy="albums")
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="eclore\userBundle\Entity\Image", cascade={"remove", "persist"}, mappedBy="album")
     */
    private $pictures;
    
    public function __construct()
    {
    $this->pictures = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
    return $this->name;
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
    
    public function addPicture(\eclore\userBundle\Entity\Image $image) 
    {
            $this->pictures[] = $image;
            $image->setAlbum($this);
    }
  
    public function removePicture(\eclore\userBundle\Entity\Image $image) 
    {
      $this->pictures->removeElement($image);
    }

    public function getPictures()
    {
        return $this->pictures;
    }
    
    public function setPictures($pics)
    {
        foreach ($pics as $pic) {
            $this->addPicture($pic);
        }
    }

    /**
     * Set owner
     *
     * @param \stdClass $owner
     * @return Album
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        $owner->addAlbum($this);
        return $this;
    }

    /**
     * Get owner
     *
     * @return \stdClass 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Album
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
