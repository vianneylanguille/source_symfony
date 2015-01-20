<?php

namespace eclore\userBundle\Entity;

use Symfony\Component\Validator\ExecutionContextInterface;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;
use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="eclore\userBundle\Entity\ProjectRepository")
 */
class Project
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
     * @var integer
     *
     * @ORM\Column(name="required", type="integer")
     */
    private $required;

    /**
     * @var string
     *
     * @ORM\Column(name="projectName", type="string", length=255)
     */
    private $projectName;
    
    /**
     * @var boolean
     * @Exclude
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="shortDescription", type="text")
     */
    private $shortDescription;

    /**
     * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\AssociationMember", mappedBy="managedProjects")
     * @Exclude
     */
    private $responsibles;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="investmentRequired", type="integer")
     */
    protected $investmentRequired;
    
    /**
     * @ORM\ManyToOne(targetEntity="eclore\userBundle\Entity\Association", inversedBy="projects")
     * @Serializer\Accessor(getter="getReducedAssociation")
      * @Type("array")
     */
    private $association;
    
    /**
    * @ORM\OneToMany(targetEntity="eclore\userBundle\Entity\ProjectApplication", mappedBy="project")
     * @Serializer\Accessor(getter="getProjectApplicationsCount")
     * @Type("integer")
     */
    private $projectApplications;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     * @Serializer\Accessor(getter="getStartDateTimestamp")
     * @Type("integer")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date")
     * @Serializer\Accessor(getter="getEndDateTimestamp")
     * @Type("integer")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="eclore\userBundle\Entity\ProjectLabels")
     */
    private $labels;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;
    
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;
    
    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=255, nullable=true)
     */
    private $postcode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=255)
     */
    private $lat;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=255)
     */
    private $lng;
    
    public function getProjectApplicationsCount()
    {
    return count($this->getPAByStatus('VALIDATED'));
    }
    
    public function getReducedAssociation()
    {
    return array('id'=>$this->getAssociation()->getId(), 
    'associationName'=>$this->getAssociation()->getAssociationName(),
    'associationHeadshot'=>$this->getAssociation()->getHeadshotWebPath());
    }
    
    public function getStartDateTimestamp()
    {
        return $this->getStartDate()->format('U');
    }
    
    public function getEndDateTimestamp()
    {
        return $this->getEndDate()->format('U');
    }
    
    public function getInvestmentRequired()
    {
        return $this->investmentRequired;
    }
    
    public function setInvestmentRequired($inv)
    {
        return $this->investmentRequired = $inv;
    }
   
    
    public function getPAByStatus($stat)
    {
    return $this->projectApplications->filter(function($p) use ($stat){return $p->getStatus()==$stat;});
    }
    
    public function __construct()
    {
    $this->projectApplications = new \Doctrine\Common\Collections\ArrayCollection();
    $this->responsibles = new \Doctrine\Common\Collections\ArrayCollection();
    $this->enabled=False;
    }
    
    public function __toString()
    {
    return $this->getProjectName();
    }
    
    public function hasResponsible($resp)
    {$data;
    
    foreach($this->getResponsibles() as $value) $data[]=$value->getId();
   
        return in_array($resp->getId(), $data);
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
    
    public function getRequired()
    {
        return $this->required;
    }
    
    public function setRequired($required)
    {
        return $this->required = $required;
    }

    /**
     * Set projectName
     *
     * @param string $projectName
     * @return Project
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * Get projectName
     *
     * @return string 
     */
    public function getProjectName()
    {
        return $this->projectName;
    }
    
        public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
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
     * Set responsibles
     *
     * @param \eclore\userBundle\Entity\AssociationMember $responsible
     * @return Project
     */
    public function addResponsible(\eclore\userBundle\Entity\AssociationMember $responsible)
    {
        $this->responsibles[] = $responsible;
        $responsible->addManagedProject($this);

        return $this;
    }
    
    /**
     * remove responsibles
     *
     * @param \eclore\userBundle\Entity\AssociationMember $responsible
     * @return Project
     */
    public function removeResponsible(\eclore\userBundle\Entity\AssociationMember $responsible)
    {
        $this->responsibles->removeElement($responsible);

        return $this;
    }

    /**
     * Get responsible
     *
     * @return \stdClass 
     */
    public function getResponsibles()
    {
        return $this->responsibles;
    }
    
    /**
     * Set association
     *
     * @param \eclore\userBundle\Entity\Association $association
     * @return Project
     */
    public function setAssociation(\eclore\userBundle\Entity\Association $association)
    {
        $this->association = $association;
        $association->addProject($this);

        return $this;
    }

    /**
     * Get association
     *
     * @return Association
     */
    public function getAssociation()
    {
        return $this->association;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Project
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Project
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set labels
     *
     * @param \eclore\userBundle\Entity\ProjectLabels $labels
     * @return Project
     */
    public function setLabels(\eclore\userBundle\Entity\ProjectLabels $labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Get labels
     *
     * @return \eclore\userBundle\Entity\ProjectLabels
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Project
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
      * Add ProjectApplications
      *
      * @param eclore\userBundle\Entity\ProjectApplication $projectApplication
      */
      
    public function addProjectApplication(\eclore\userBundle\Entity\ProjectApplication $projectApplication) 
    {
      $this->projectApplications[] = $projectApplication;
    }
  
    /**
      * Remove ProjectApplications
      *
      * @param eclore\userBundle\Entity\ProjectApplication $projectApplication
      */
    public function removeProjectApplication(\eclore\userBundle\Entity\ProjectApplication $projectApplication) 
    {
      $this->projectApplications->removeElement($projectApplication);
    }

    /**
     * Get ProjectApplications
     *
     * @return array 
     */
    public function getProjectApplications()
    {
        return $this->projectApplications;
    }
    
        public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    
public function getCity()
    {
        return $this->city;
    }

public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
    
public function getCountry()
    {
        return $this->country;
    }
    
public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }
    
public function getPostcode()
    {
        return $this->postcode;
    }
    
public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }
    
public function getLat()
    {
        return $this->lat;
    }
    
public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }
    
public function getLng()
    {
        return $this->lng;
    }
    
public function isFinished()
    {
    $today = new \DateTime();
    return $today->format('U') > $this->getEndDate()->format('U');
    }
    
public function isStarted()
    {
    $today = new \DateTime();
    return $today->format('U') >= $this->getStartDate()->format('U');
    }
    
    
    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Test
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    
    //validators
    
    public function isResponsiblesValid()
    {
        // vérifie si les responsables sont de la meme association
        foreach($this->getResponsibles() as $resp)
            if(!$resp->getAssociations()->contains($this->getAssociation()))
                return False;
    }
    
    public function isEndDateValid()
    {
        return $this->getEndDate()->format('U') >= $this->getStartDate()->format('U');
    }
    
    public function getDuration()
    {
        return (int) ($this->getEndDate()->format('U') - $this->getStartDate()->format('U'))/86400;
    }
    
    public function isFull()
    {
    return count($this->getPAByStatus('VALIDATED')) >= $this->getRequired();
    }
}
