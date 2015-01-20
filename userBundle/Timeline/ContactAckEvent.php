<?php

namespace eclore\userBundle\Timeline;

use Symfony\Component\EventDispatcher\Event;
use eclore\userBundle\Entity\User;

class ContactAckEvent extends Event
{
    protected $requestingUser;
    protected $ackUser;

    public function __construct(User $requestingUser, User $ackUser)
        {
        $this->requestingUser = $requestingUser;
        $this->ackUser = $ackUser;
        }
        
    public function getRequestingUser()
    {
    return $this->requestingUser;
    }
    
    public function getAckUser()
    {
    return $this->ackUser;
    }
}