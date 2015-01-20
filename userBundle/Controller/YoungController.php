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
    
        public function applyAction($id)
    {
    $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();
        $em =$this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:Project');
        $project = $repository->find($id);
        
        // verifie que le projet existe et n'est pas terminé.
        if(!$project || $project->isFinished() || !$project->getEnabled() || $project->isFull())
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Ce projet n\'existe pas, est terminé, ou tu as déja candidaté!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
        }
        // verifie que $user a mis une evaluation a toutes ses candidatures
        if($user->getYoung()->getPAByStatus('TERMINATED')->count() > 0)
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Tu dois donner ton avis sur tes précédents projets avant de pouvoir candidater!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
        }
                
        // verifie que $user n'a pas deja candidaté
        if($user->getYoung()->hasApplied($project))
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Tu as déjà candidaté à ce projet!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
        }
        //cree le formulaire
        $PA = new ProjectApplication();
        $form = $this->createFormBuilder($PA)
            ->add('message', 'textarea')
            ->add('save', 'submit')
            ->getForm();
            
        $form->handleRequest($request);     
        if ($form->isValid()) {
            $PA->setYoung($user->getYoung());
            $PA->setStatus('PENDING');
            $PA->setStatusDate(new \DateTime);
            $PA->setProject($project);
            $this->getDoctrine()->getManager()->persist($PA);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add(
            'notice',
            'Ta candidature a bien été envoyée !');
            // automatically connect young and project responsibles... TBC
            foreach($PA->getProject()->getResponsibles() as $resp)
                {$resp->getUser()->addContact($user);
                $user->addContact($resp->getUser());
                $em->persist($resp->getUser());
                }   
            $em->persist($user);
            $em->flush();
            // event dispatcher
            $event = new PAEvent($PA);
            $this->container->get('event_dispatcher')->dispatch(TimelineEvents::onNewPA, $event);
            return $this->redirect($this->generateUrl('ecloreuser_home'));   
            }
        return $this->render('ecloreuserBundle:Young:apply.html.twig', array('proj'=>$project,'form' => $form->createView()));  
    }
    
    public function manageApplicationAction($id)
    {
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();
        $em =$this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:ProjectApplication');
        $PA = $repository->find($id);
        
        // verifie que la candidature concerne bien $user
        if(!$PA || $PA->getYoung()->getId() != $user->getYoung()->getId())
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Cette candidature n\'existe pas ou ne te concerne pas!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
        }  
        //creation formulaire
        $recomm = new Notification();
        $formbuilder = $this->createFormBuilder($recomm);
        $formbuilder->add('message', 'textarea')
                ->add('mark','submit');
        $form = $formbuilder->getForm();
        
        $form->handleRequest($request);    
        if($form->isValid()) {
            $PA->setStatusDate(new \DateTime);
            $PA->setMessage($recomm->getMessage());
            //cloture candidature coté jeune
            $PA->setStatus('MARKED');
            // event dispatcher
            $event = new MarkedEvent($PA);
            $this->container->get('event_dispatcher')->dispatch(TimelineEvents::onMarkedProject, $event);
            // creation note
            $recomm->setSender($user);
            $recomm->setProject($PA->getProject());
            foreach($PA->getProject()->getResponsibles() as $resp) $recomm->addReceiver($resp->getUser());
            $recomm->setInitDate(new \DateTime());
            $recomm->setType('MARK');
            $em->persist($recomm);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'notice',
            'Avis enregistré!');
        }

        return $this->render('ecloreuserBundle:Young:manage-application.html.twig',
        array('pa'=>$PA,'form'=>$form->createView()));
    
    }

    
    
}
