<?php

namespace eclore\userBundle\Controller;

use eclore\userBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use eclore\userBundle\Entity\ProjectApplication;
use eclore\userBundle\Entity\Notification;
use eclore\userBundle\Entity\Action;
use eclore\userBundle\Timeline\TimelineEvents;
use eclore\userBundle\Timeline\MarkedEvent;
use eclore\userBundle\Timeline\PAEvent;

class YoungController extends Controller
{   
    public function displayHomeAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        //get timeline
        $actionManager = $this->get('spy_timeline.action_manager');
        $subject = $actionManager->findOrCreateComponent($user);
        $timelineManager = $this->get('spy_timeline.timeline_manager');
        $timeline = $timelineManager->getTimeline($subject, array('page' => 1, 'max_per_page' => '10', 'paginate' => true));

        return $this->render('ecloreuserBundle:Young:home.html.twig', array('timeline_coll'=>$timeline)); 
    }
    
          
    
}
