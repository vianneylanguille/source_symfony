<?php

namespace eclore\userBundle\Timeline;

use Symfony\Component\EventDispatcher\Event;
use eclore\userBundle\Entity\Project;

class NewProjectEvent extends Event
{
    protected $project;

    public function __construct(Project $project)
        {
        $this->project = $project;
        }
        
    public function getProject()
    {
    return $this->project;
    }
}