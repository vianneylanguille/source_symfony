<?php

namespace eclore\userBundle\Timeline;

use Symfony\Component\EventDispatcher\Event;
use eclore\userBundle\Entity\User;

class NewUserEvent extends Event
{
    protected $user;

    public function __construct(User $user)
        {
        $this->user = $user;
        }
        
    public function getUser()
    {
    return $this->user;
    }
}