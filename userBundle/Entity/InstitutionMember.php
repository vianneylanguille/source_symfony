<?php

namespace eclore\userBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InstitutionMember
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="eclore\userBundle\Entity\InstitutionMemberRepository")
 */
class InstitutionMember
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
     * @ORM\OneToOne(targetEntity="eclore\userBundle\Entity\User", inversedBy="instM")
     */
    protected $user;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\Institution", inversedBy="members")
     */
    protected $institutions;
    
    public function __toString()
    {
    return $this->getUser()->__toString()." (".implode('; ',array_map(function($elem){return $elem->__toString();},$this->getInstitutions()->toArray())).")";
    }
    
    public function __construct()
    {
    $this->institutions = new \Doctrine\Common\Collections\ArrayCollection();
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
        $user->setInstM($this);

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
      $institution->addMember($this);
    }
  
    /**
      * Remove institutions
      *
      * @param eclore\userBundle\Entity\Institution $institution
      */
    public function removeInstitution(\eclore\userBundle\Entity\Institution $institution) 
    {
       $institutions->removeMember($this);
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
