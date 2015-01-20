<?php

namespace eclore\userBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Association
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Association
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
     * @ORM\Column(name="associationName", type="string", length=255)
     */
    private $associationName;
    
    /**
    * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\AssociationMember", mappedBy="associations")
    */
    
    private $members;
    
    /**
    * @ORM\OneToMany(targetEntity="eclore\userBundle\Entity\Project", mappedBy="association")
    */
    
    private $projects;
    
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
     * @ORM\OneToOne(targetEntity="eclore\userBundle\Entity\Image", cascade={"persist", "remove"})
     */
    private $headshot;
    
    public function __construct()
    {
    $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
    return $this->getAssociationName();
    }
    
    public function getHeadshot()
    {
        return $this->headshot;
    }
    
    public function setHeadshot(\eclore\userBundle\Entity\Image $headshot)
    {
        return $this->headshot = $headshot;
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
     * Set associationName
     *
     * @param string $associationName
     * @return Association
     */
    public function setAssociationName($associationName)
    {
        $this->associationName = $associationName;

        return $this;
    }

    /**
     * Get associationName
     *
     * @return string 
     */
    public function getAssociationName()
    {
        return ucwords($this->associationName);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Association
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
      * Add Projects
      *
      * @param eclore\userBundle\Entity\Project $project
      */
      
    public function addProject(\eclore\userBundle\Entity\Project $project) 
    {
      $this->projects[] = $project;
    }
  
    /**
      * Remove Projects
      *
      * @param eclore\userBundle\Entity\Project $project
      */
    public function removeProject(\eclore\userBundle\Entity\Project $project) 
    {
      $this->projects->removeElement($project);
    }

    /**
     * Get Projects
     *
     * @return array 
     */
    public function getProjects()
    {
        return $this->projects;
    }
    
    /**
      * Add Members
      *
      * @param eclore\userBundle\Entity\AssociationMember $member
      */
      
    public function addMember(\eclore\userBundle\Entity\AssociationMember $member) 
    {
      $this->members[] = $member;
    }
  
    /**
      * Remove Members
      *
      * @param eclore\userBundle\Entity\AssociationMember $member
      */
    public function removeMember(\eclore\userBundle\Entity\AssociationMember $member) 
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
    public function getHeadshotWebPath()
    {
        if($this->headshot==null)
            return 'uploads/img/symbole_eclore.png';
        return $this->headshot->getWebPath();
    }
}
