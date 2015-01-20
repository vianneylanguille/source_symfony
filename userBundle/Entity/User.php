<?php
// src/eclore/UserBundle/Entity/User.php

namespace eclore\userBundle\Entity;


use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="eclore_user")
 */
class User extends BaseUser
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
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="date")
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="privacyLevel", type="integer")
     */
    protected $privacyLevel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registrationDate", type="date")
     */
    private $registrationDate;
    
    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\User")
     */
    private $contacts;
    
    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="eclore\userBundle\Entity\Notification", mappedBy="sender")
     */
    private $notifications;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=20)
     */
    private $mobile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastSeenDate", type="date")
     */
    private $lastSeenDate;
    
    /**
    * @ORM\OneToOne(targetEntity="eclore\userBundle\Entity\Young", mappedBy = "user", cascade={"remove", "persist"})
    */
    private $young;
    
    /**
    * @ORM\OneToOne(targetEntity="eclore\userBundle\Entity\InstitutionMember", mappedBy = "user", cascade={"remove", "persist"})
    */
    private $instM;
    
    /**
    * @ORM\OneToOne(targetEntity="eclore\userBundle\Entity\AssociationMember", mappedBy = "user", cascade={"remove", "persist"})
    */
    private $assoM;
    
    /**
     * @ORM\OneToOne(targetEntity="eclore\userBundle\Entity\Image", cascade={"persist", "remove"})
     */
    private $headshot;
    
    /**
     * @ORM\OneToMany(targetEntity="eclore\userBundle\Entity\Album", cascade={"remove"}, mappedBy="owner")
     */
    private $albums;
    
    /**
     * @var string
     *
     * @ORM\Column(name="quality", type="text")
     */
    private $quality;
    
    public function __construct()
    {
    parent::__construct();
    $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
    $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
    $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
    $this->lastSeenDate = new \DateTime();
    $this->registrationDate = new \DateTime();
    }
    
    public function getHeadshot()
    {
        return $this->headshot;
    }
    
    public function setHeadshot(\eclore\userBundle\Entity\Image $headshot)
    {
        return $this->headshot = $headshot;
    }
    
    public function getPrivacyLevel()
    {
        return $this->privacyLevel;
    }
    
    public function setPrivacyLevel($lvl)
    {
        return $this->privacyLevel = $lvl;
    }
    
    public function getYoung()
    {
        return $this->young;
    }
    
    public function getAssoM()
    {
        return $this->assoM;
    }
    
    public function getInstM()
    {
        return $this->instM;
    }
    
    public function setYoung(\eclore\userBundle\Entity\Young $young)
    {
        $this->young = $young;
        return $this;
    }
    
    public function setAssoM(\eclore\userBundle\Entity\AssociationMember $assoM)
    {
        $this->assoM = $assoM;
        return $this;
    }
    
    public function setInstM(\eclore\userBundle\Entity\InstitutionMember $instM)
    {
        $this->instM = $instM;
        return $this;
    }
    
    public function __toString()
    {
    return $this->getFirstName()." ".$this->getLastName();
    }
    
    public function getExpiresAt()
    {
    return $this->expiresAt;
    }
    
    public function getCredentialsExpireAt()
    {
    return $this->credentialsExpireAt;
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
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return Member
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime 
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Member
     */
    public function setLastName($lastName)
    {
        $this->lastName = ucfirst($lastName);

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Member
     */
    public function setFirstName($firstName)
    {
        $this->firstName = ucfirst($firstName);

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     * @return Member
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime 
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return Member
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set lastSeenDate
     *
     * @param \DateTime $lastSeenDate
     * @return Member
     */
    public function setLastSeenDate($lastSeenDate)
    {
        $this->lastSeenDate = $lastSeenDate;

        return $this;
    }

    /**
     * Get lastSeenDate
     *
     * @return \DateTime 
     */
    public function getLastSeenDate()
    {
        return $this->lastSeenDate;
    }
    
    /**
      * Add contacts
      *
      * @param eclore\userBundle\Entity\User $user
      */
      
    public function addContact(\eclore\userBundle\Entity\User $user) 
    {
       if(!$this->contacts->contains($user))
            $this->contacts[] = $user;
    }
  
    /**
      * Remove contacts
      *
      * @param eclore\userBundle\Entity\User $user
      */
    public function removeContact(\eclore\userBundle\Entity\User $user) 
    {
      $this->contacts->removeElement($user);
    }

    /**
     * Get contacts
     *
     * @return array 
     */
    public function getContacts()
    {
        return $this->contacts;
    }
    
    public function addAlbum(\eclore\userBundle\Entity\Album $album) 
    {
            $this->albums[] = $album;
    }
  
    public function removeAlbum(\eclore\userBundle\Entity\Album $album) 
    {
      $this->albums->removeElement($album);
    }

    public function getAlbums()
    {
        return $this->albums;
    }
    
    public function addNotification(\eclore\userBundle\Entity\Notification $notification) 
    {
      $this->notifications[] = $notification;
    }
  
    public function removeNotification(\eclore\userBundle\Entity\Notification $notification) 
    {
      $this->notifications->removeElement($notification);
    }

    public function getNotifications()
    {
        return $this->notifications;
    }
    
    /**
     * Set quality
     *
     * @param string $quality
     * @return User
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * Get quality
     *
     * @return string 
     */
    public function getQuality()
    {
        return $this->quality;
    }
    
    public function getAge()
    {
        $today = new \DateTime;
        return (int)(($today->format('U') - $this->getBirthDate()->format('U'))/(365*24*3600));
    }
    
    public function getContactsByRole($role)
    {
        $res=array();
        foreach($this->getContacts() as $contact)
            if($contact->hasRole($role))
                $res[]=$contact;
        return $res;
    }
    
    public function getHeadshotWebPath()
    {
        if($this->headshot==null)
            return 'uploads/img/defaultHeadshot.jpg';
        return $this->headshot->getWebPath();
    }
    
}