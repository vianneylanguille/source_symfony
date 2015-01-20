<?php

namespace eclore\userBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssociationMember
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="eclore\userBundle\Entity\AssociationMemberRepository")
 */
class AssociationMember
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
     * @ORM\OneToOne(targetEntity="eclore\userBundle\Entity\User", inversedBy="assoM")
     */
    protected $user;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\Association", inversedBy="members")
     */
    protected $associations;
    
    /**
    * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\Project", inversedBy="responsibles")
    */
    private $managedProjects;
    
    public function __toString()
    {
    return $this->getUser()->__toString()." (".implode('; ',array_map(function($elem){return $elem->__toString();},$this->getAssociations()->toArray())).")";
    }
    
    public function __construct()
    {
    $this->associations = new \Doctrine\Common\Collections\ArrayCollection();
    $this->managedProjects = new \Doctrine\Common\Collections\ArrayCollection();
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
        $user->setAssoM($this);

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
      * Add associations
      *
      * @param eclore\userBundle\Entity\Association $association
      */
      
    public function addAssociation(\eclore\userBundle\Entity\Association $association)
    {
      $this->associations[] = $association;
      $association->addMember($this);
    }
  
    /**
      * Remove associations
      *
      * @param eclore\userBundle\Entity\Association $association
      */
    public function removeAssociation(\eclore\userBundle\Entity\Association $association) 
    {
      $association->removeMember($this);
      $this->associations->removeElement($association);
    }

    /**
     * Get associations
     *
     * @return array 
     */
    public function getAssociations()
    {
        return $this->associations;
    }
    
    /**
      * Add proj
      *
      * \eclore\userBundle\Entity\Project $project
      */
      
    public function addManagedProject(\eclore\userBundle\Entity\Project $project)
    {
      $this->managedProjects[] = $project;
    }
  
    /**
      * Remove proj
      *
      * \eclore\userBundle\Entity\Project $project
      */
    public function removeManagedProjects(\eclore\userBundle\Entity\Project $project) 
    {
      $this->managedProjects->removeElement($project);
    }

    /**
     * Get proj
     *
     * @return array 
     */
    public function getManagedProjects()
    {
        return $this->managedProjects;
    }
}
