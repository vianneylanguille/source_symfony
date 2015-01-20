<?php

namespace eclore\userBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Young
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="eclore\userBundle\Entity\YoungRepository")
 */
class Young
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="eclore\userBundle\Entity\User", inversedBy="young")
     */
    protected $user;

   
    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\Institution", inversedBy="youngs")
     */
    protected $institutions;
    
    public function __construct()
    {
    $this->institutions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
    return $this->getUser()->__toString();
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
     * Set user
     *
     * @param string $user
     * @return InstitutionMember
     */
    public function setUser($user)
    {
        $this->user = $user;
        $user->setYoung($this);

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
      * Add institutions
      *
      * @param eclore\userBundle\Entity\Institution $institution
      */
      
    public function addInstitution(\eclore\userBundle\Entity\Institution $institution)
    {
      $this->institutions[] = $institution;
      $institution->addYoung($this);
    }
  
    /**
      * Remove institutions
      *
      * @param eclore\userBundle\Entity\Institution $institution
      */
    public function removeInstitution(\eclore\userBundle\Entity\Institution $institution) 
    {
      $this->institutions->removeElement($institution);
    }

    /**
     * Get institutions
     *
     * @return array 
     */
    public function getInstitutions()
    {
        return $this->institutions;
    }

    
    
}
