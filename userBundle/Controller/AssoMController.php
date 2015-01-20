<?php

namespace eclore\userBundle\Controller;

use eclore\userBundle\Entity\User;
use eclore\userBundle\Entity\Project;
use eclore\userBundle\Entity\ProjectApplication;
use eclore\userBundle\Form\ProjectRegisterType;
use eclore\userBundle\Form\AssoEditType;
use eclore\userBundle\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use eclore\userBundle\Entity\Notification;
use eclore\userBundle\Timeline\TimelineEvents;
use eclore\userBundle\Timeline\MarkedEvent;
use eclore\userBundle\Timeline\PAEvent;
use Symfony\Component\EventDispatcher\Event;

class AssoMController extends Controller
{   

    public function editAssoAction($id, Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('ecloreuserBundle:Association');
            $asso = $repository->find($id);
            
        if(!$asso || !$user->getAssoM()->getAssociations()->contains($asso)){
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Vous n \'êtes pas concerné par cette page!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
            }
            
    if($user->hasRole('ROLE_TBC')){
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Votre profil doit être validé par le réseau avant de pouvoir effectuer cette action. Il devrait l\'être rapidement.'); 
            return $this->redirect($this->generateUrl('ecloreuser_home'));
    }
            
    $form = $this->container->get('form.factory')->create(new AssoEditType(), $asso); 
    
    if('POST' === $request->getMethod()) {
        $form->bind($request);     
            if ($form->isValid()) { 
                $this->getDoctrine()->getManager()->persist($asso);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->add(
                'notice',
                'L\'association a été correctement mise à jour!');
                return $this->redirect($this->generateUrl('displayAsso', 
                array('id'=>$asso->getId())));
            }
        }
        
        
    return $this->render('ecloreuserBundle:AssoM:edit-asso.html.twig',
        array('form' => $form->createView()));
    }
    public function displayHomeAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        //get timeline
        $actionManager = $this->get('spy_timeline.action_manager');
        $subject = $actionManager->findOrCreateComponent($user);
        $timelineManager = $this->get('spy_timeline.timeline_manager');
        $timeline = $timelineManager->getTimeline($subject, array('page' => 1, 'max_per_page' => '10', 'paginate' => true));
        
        return $this->render('ecloreuserBundle:AssoM:home.html.twig', array('timeline_coll'=>$timeline));
         
    }
    
    public function manageProjectAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('ecloreuserBundle:Project');
        $project = $repository->find($id);
        
        // verification que le projet est bien managé par $user
        if(!$project || !$user->getAssoM()->getManagedProjects()->contains($project))
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Vous n \'êtes pas responsable de ce projet!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
        }
        
        return $this->render('ecloreuserBundle:AssoM:manage-project.html.twig', array('proj'=>$project));
         
    }
    
    public function manageApplicationAction($id)
    {   
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $repository = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:ProjectApplication');
        $PA = $repository->find($id);
        // verifie que la candidature concerne bien un projet managé par $user
        if(!$PA || !$user->getAssoM()->getManagedProjects()->contains($PA->getProject()))
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Cette candidature ne concerne pas un de vos projets!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
        }  
        // creation formulaires validation/rejet ou cloture
        $recomm = new Notification();
        $formbuilder = $this->createFormBuilder($recomm);
                      
        
        if($PA->getStatus() == 'VALIDATED' && $PA->getProject()->isFinished()){
            $formbuilder->add('message', 'textarea', array('required'=>false)) 
            ->add('terminate','submit');
         }elseif($PA->getStatus() == 'PENDING'){
            $formbuilder->add('message', 'textarea', array('required'=>false))
            ->add('validate','submit')
            ->add('reject','submit');
            }
        $form = $formbuilder->getForm();
            
        $form->handleRequest($request);
        
            if($form->isValid()) {
                $PA->setStatusDate(new \DateTime);
                $PA->setMessage($recomm->getMessage());
                if ($form->has('validate') && $form->get('validate')->isClicked()){
                    $PA->setStatus('VALIDATED');
                    $event = new PAEvent($PA);
                    $this->get('event_dispatcher')->dispatch(TimelineEvents::onValidatedPA, $event);
                    }
                elseif ($form->has('reject') && $form->get('reject')->isClicked()){
                    $PA->setStatus('REJECTED');
                    $event = new PAEvent($PA);
                    $this->get('event_dispatcher')->dispatch(TimelineEvents::onRejectedPA, $event);
                    }
                elseif ($form->has('terminate') && $form->get('terminate')->isClicked()) 
                    {
                    //cloture candidature
                    $PA->setStatus('TERMINATED');
                    // creation recommandation
                    $recomm->setSender($user);
                    $recomm->setProject($PA->getProject());
                    $recomm->addReceiver($PA->getYoung()->getUser());
                    $recomm->setInitDate(new \DateTime());
                    $recomm->setType('RECOMMENDATION');
                    $em->persist($recomm);
                    $event = new MarkedEvent($PA);
                    $this->get('event_dispatcher')->dispatch(TimelineEvents::onMarkedYoung, $event);
                    }
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                'notice',
                'Mise à jour effectuée!');
            }

        $choices=array('PENDING'=>'En attente de validation',
            'VALIDATED'=>'Validée', 'REJECTED'=>'Rejetée', 'WITHDRAWN'=>'Retirée',
            'TERMINATED'=>'Clôturée', 'MARKED'=>'Avis jeune enregistré');

        return $this->render('ecloreuserBundle:AssoM:manage-application.html.twig', 
        array('pa'=>$PA, 'form'=>$form->createView()));
         
    }
    
    public function registerProjectAction(Request $request)
    {$user = $this->get('security.context')->getToken()->getUser();
    if($user->hasRole('ROLE_TBC')){
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Votre profil doit être validé par le réseau avant de pouvoir effectuer cette action. Il devrait l\'être rapidement.'); 
            return $this->redirect($this->generateUrl('ecloreuser_home'));
    }
    // create project registration forms
    $project = new Project();
    $form = $this->createForm(new ProjectRegisterType($this->get('security.context'), $this->container), $project);   
    
    $project->addResponsible($this->get('security.context')->getToken()->getUser()->getAssoM());
    
    if('POST' === $request->getMethod()) {
        $form->bind($request);     
            if ($form->isValid()) { 
                $this->getDoctrine()->getManager()->persist($project);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->add(
                'notice',
                'Le projet a été correctement créé! Il est soumis à validation.');
                //event dispatcher
                $event = new Event();
                $this->container->get('event_dispatcher')->dispatch(TimelineEvents::onPendingValidation, $event);
                return $this->redirect($this->generateUrl('assom_manageProject', 
                array('id'=>$project->getId())));
            }
        }
        return $this->render('ecloreuserBundle:AssoM:register-project.html.twig',
        array('form' => $form->createView()));
    }
    
    public function editProjectAction($id, Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:Project');
        $proj = $repository->find($id);
            
        if(!$proj || !$user->getAssoM()->getManagedProjects()->contains($proj)){
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Vous n \'êtes pas concerné par cette page!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
            }
            
    $form = $this->container->get('form.factory')->create(new ProjectRegisterType($this->get('security.context'), $this->container), $proj); 
    
    if('POST' === $request->getMethod()) {
        $form->bind($request);     
            if ($form->isValid()) { 
                $this->getDoctrine()->getManager()->persist($proj);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->add(
                'notice',
                'Le projet a été correctement mis à jour!');
                return $this->redirect($this->generateUrl('assom_manageProject', 
                array('id'=>$proj->getId())));
            }
        }
        return $this->render('ecloreuserBundle:AssoM:register-project.html.twig',
        array('form' => $form->createView()));
    }
    
}
