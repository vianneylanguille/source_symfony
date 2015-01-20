<?php

namespace eclore\userBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="eclore\userBundle\Entity\NotificationRepository")
 */
class Notification
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
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="eclore\userBundle\Entity\User", inversedBy="notifications")
     */
    private $sender;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\User")
     */
    private $receivers;
    
    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="eclore\userBundle\Entity\Project")
     */
    private $project;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="initDate", type="date")
     */
    private $initDate;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;
    
    public function __construct()
    {
    $this->initDate = new \DateTime();
    $this->receivers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set sender
     *
     * @param \stdClass $sender
     * @return Notification
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        $sender->addNotification($this);
        return $this;
    }

    /**
     * Get sender
     *
     * @return \stdClass 
     */
    public function getSender()
    {
        return $this->sender;
    }


    /**
     * Set type
     *
     * @param string $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set initDate
     *
     * @param \DateTime $initDate
     * @return Notification
     */
    public function setInitDate($initDate)
    {
        $this->initDate = $initDate;

        return $this;
    }

    /**
     * Get initDate
     *
     * @return \DateTime 
     */
    public function getInitDate()
    {
        return $this->initDate;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Notification
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
    
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getProject()
    {
        return $this->project;
    }
    
    public function addReceiver($receiver)
    {
      $this->receivers[] = $receiver;
    }
  
    /**
      * Remove institutions
      *
      * @param \eclore\userBundle\Entity\User $receiver
      */
    public function removeReceiver($receiver) 
    {
      $this->receivers->removeElement($receiver);
    }
    
    /**
     * Get institutions
     *
     * @return array 
     */
    public function getReceivers()
    {
        return $this->receivers;
    }
}
