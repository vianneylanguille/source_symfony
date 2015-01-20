<?php

namespace eclore\userBundle\Timeline;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\Event;

class TimelineListener
{
    protected $container;

    public function __construct(Container $container)
    {
    $this->container = $container;
    }
    
    public function onContactAck(ContactAckEvent $event)
    {
    $actionManager = $this->container->get('spy_timeline.action_manager');
    $subject = $actionManager->findOrCreateComponent($event->getAckUser());
    $complement = $actionManager->findOrCreateComponent($event->getRequestingUser());
    $action = $actionManager->create($subject, 'contact', array('complement' => $complement));
    $actionManager->updateAction($action);
    }
    
    public function onValidatedPA(PAEvent $event)
    {
    $actionManager = $this->container->get('spy_timeline.action_manager');
    $subject = $actionManager->findOrCreateComponent($event->getPA()->getUser());
    $complement = $actionManager->findOrCreateComponent($event->getPA()->getProject());
    $action = $actionManager->create($subject, 'take_part', array('complement' => $complement));
    $actionManager->updateAction($action);
    }
    
    public function onNewPA(PAEvent $event)
    {
    $actionManager = $this->container->get('spy_timeline.action_manager');
    $subject = $actionManager->findOrCreateComponent($event->getPA()->getUser());
    $complement = $actionManager->findOrCreateComponent($event->getPA()->getProject());
    $action = $actionManager->create($subject, 'apply', array('complement' => $complement));
    $actionManager->updateAction($action);
    //send email email-newPA.html.twig
    /*
    $template = $this->container->get('twig')->loadTemplate('ecloreuserBundle:AssoM:email-newPA.html.twig');
    $subject = $template->renderBlock('subject', array('project'=>$event->getPA()->getProject()));
    $htmlBody = $template->renderBlock('body_html', array('project'=>$event->getPA()->getProject()));

    $resps_emails = []
    foreach($event->getPA()->getProject()->getResponsibles() as $resp)
        $resps_emails[]=$resp->getUser()->getEmail();
        
    $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom('reseau.eclore@gmail.com')
        ->setTo($resps_emails)
        ->setBody($htmlBody, 'text/html');
    $this->container->get('mailer')->send($message);
    */
    }
    
    public function onRejectedPA(PAEvent $event)
    {
    $actionManager = $this->container->get('spy_timeline.action_manager');
    $subject = $actionManager->findOrCreateComponent($event->getPA()->getUser());
    $complement = $actionManager->findOrCreateComponent($event->getPA()->getProject());
    $action = $actionManager->create($subject, 'be_rejected', array('complement' => $complement));
    $actionManager->updateAction($action);
    }
    
    public function onNewProject(NewProjectEvent $event)
    {
    $actionManager = $this->container->get('spy_timeline.action_manager');
    $subject = $actionManager->findOrCreateComponent($event->getProject()->getAssociation());
    $complement = $actionManager->findOrCreateComponent($event->getProject());
    $action = $actionManager->create($subject, 'create_project', array('complement' => $complement));
    $actionManager->updateAction($action);
    }
    
    public function onMarkedProject(MarkedEvent $event)
    {
    $actionManager = $this->container->get('spy_timeline.action_manager');
    $subject = $actionManager->findOrCreateComponent($event->getPA()->getUser());
    $complement = $actionManager->findOrCreateComponent($event->getPA()->getProject());
    $action = $actionManager->create($subject, 'mark_project', array('complement' => $complement));
    $actionManager->updateAction($action);
    }
    
    public function onMarkedYoung(MarkedEvent $event)
    {
    $actionManager = $this->container->get('spy_timeline.action_manager');
    $subject = $actionManager->findOrCreateComponent($event->getPA()->getProject()->getAssociation());
    $complement = $actionManager->findOrCreateComponent($event->getPA()->getUser());
    $action = $actionManager->create($subject, 'mark_young', array('complement' => $complement));
    $actionManager->updateAction($action);
    }
    
    public function onNewUser(NewUserEvent $event)
    {
    $actionManager = $this->container->get('spy_timeline.action_manager');
    $subject = $actionManager->findOrCreateComponent($event->getUser());
    $complement = $actionManager->findOrCreateComponent($event->getUser());
    $action = $actionManager->create($subject, 'registered', array('complement' => $complement));
    $actionManager->updateAction($action);
    }
    
    public function onPendingValidation(Event $event)
    {
     $message = \Swift_Message::newInstance();
    $template = $this->container->get('twig')->loadTemplate('ecloreuserBundle:Admin:email-pending-validation.html.twig');
    $subject = $template->renderBlock('subject', array('subject' => 'subject'));
    $htmlBody = $template->renderBlock('body_html',array('body_html' => 'body_html'));

    $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom('reseau.eclore@gmail.com')
        ->setTo('reseau.eclore@gmail.com')
        ->setBody($htmlBody, 'text/html');
    $this->container->get('mailer')->send($message);
    }

}
