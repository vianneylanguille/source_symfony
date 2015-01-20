<?php

namespace eclore\userBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectApplication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="eclore\userBundle\Entity\ProjectApplicationRepository")
 */
class ProjectApplication
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
     *
     * @ORM\ManyToOne(targetEntity="eclore\userBundle\Entity\Young", inversedBy="appliedProjects")
     */
    private $young;

    /**
     *
     * @ORM\ManyToOne(targetEntity="eclore\userBundle\Entity\Project", inversedBy="projectApplications")
     */
    private $project;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @var date
     *
     * @ORM\Column(name="statusDate", type="date")
     */
    private $statusDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string")
     */
    private $message;

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
     * Set young
     *
     * @param string $message
     * @return ProjectApplication
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set young
     *
     * @param \stdClass $young
     * @return ProjectApplication
     */
    public function setYoung($young)
    {
        $this->young = $young;

        return $this;
    }

    /**
     * Get young
     *
     * @return \stdClass 
     */
    public function getYoung()
    {
        return $this->young;
    }

    /**
     * Set project
     *
     * @param \stdClass $project
     * @return ProjectApplication
     */
    public function setProject($project)
    {
        $this->project = $project;
        $project->addProjectApplication($this);

        return $this;
    }

    /**
     * Get project
     *
     * @return \stdClass 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
      * set status
      *
      * @param string
      */
      
    public function setStatus($status)
    {
      $this->status = $status;
    }
    
    public function __toString()
    {
    return $this->id."";//$this->young->__toString()." - ".$this->project->__toString();
    }


    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
      * set statusDate
      *
      * @param date
      */
      
    public function setStatusDate($statusDate)
    {
      $this->statusDate = $statusDate;
    }
  

    /**
     * Get statusDate
     *
     * @return array 
     */
    public function getStatusDate()
    {
        return $this->statusDate;
    }
}
