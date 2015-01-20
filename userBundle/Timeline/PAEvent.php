<?php

namespace eclore\userBundle\Timeline;

use Symfony\Component\EventDispatcher\Event;
use eclore\userBundle\Entity\ProjectApplication;

class PAEvent extends Event
{
    protected $PA;

    public function __construct(ProjectApplication $PA)
        {
        $this->PA = $PA;
        }
        
    public function getPA()
    {
    return $this->PA;
    }
}