<?php

namespace eclore\userBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Institution
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Institution
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
     * @var string
     *
     * @ORM\Column(name="institutionName", type="string", length=255)
     */
    private $institutionName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255)
     */
    private $location;
    
    /**
    * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\InstitutionMember", mappedBy="institutions")
    */
    
    private $members;
    
    /**
    * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\Young", mappedBy="institutions")
    */
    
    private $youngs;
    
    /**
     * @ORM\OneToOne(targetEntity="eclore\userBundle\Entity\Image", cascade={"persist", "remove"})
     */
    private $headshot;
    
    public function __toString()
    {
    return $this->getInstitutionName();
    }
    
    public function getHeadshot()
    {
        return $this->headshot;
    }
    
    public function setHeadshot(\eclore\userBundle\Entity\Image $headshot)
    {
        return $this->headshot = $headshot;
    }
    
    public function __construct()
    {
    $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    $this->youngs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set institutionName
     *
     * @param string $institutionName
     * @return Institution
     */
    public function setInstitutionName($institutionName)
    {
        $this->institutionName = $institutionName;

        return $this;
    }

    /**
     * Get institutionName
     *
     * @return string 
     */
    public function getInstitutionName()
    {
        return $this->institutionName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Institution
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Institution
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }
    
        /**
      * Add Members
      *
      * @param eclore\userBundle\Entity\InstitutionMember $member
      */
      
    public function addMember(\eclore\userBundle\Entity\InstitutionMember $member) 
    {
      $this->members[] = $member;
    }
  
    /**
      * Remove Members
      *
      * @param eclore\userBundle\Entity\InstitutionMember $member
      */
    public function removeMember(\eclore\userBundle\Entity\InstitutionMember $member) 
    {
      $this->members->removeElement($member);
    }

    /**
     * Get Members
     *
     * @return array 
     */
    public function getMembers()
    {
        return $this->members;
    }
    
     /**
      * Add young
      *
      * @param eclore\userBundle\Entity\Young $young
      */
      
    public function addYoung(\eclore\userBundle\Entity\Young $young) 
    {
      $this->youngs[] = $young;
    }
  
    /**
      * Remove young
      *
      * @param eclore\userBundle\Entity\Young $young
      */
    public function removeYoung(\eclore\userBundle\Entity\Young $young) 
    {
      $this->youngs->removeElement($young);
    }

    /**
     * Get youngs
     *
     * @return array 
     */
    public function getYoungs()
    {
        return $this->youngs;
    }
    
}
