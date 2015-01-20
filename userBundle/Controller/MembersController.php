<?php

namespace eclore\userBundle\Controller;

use eclore\userBundle\Entity\User;
use eclore\userBundle\Entity\Association;
use eclore\userBundle\Entity\Institution;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use eclore\userBundle\Form\AssoEditType;
use eclore\userBundle\Form\InstEditType;
use eclore\userBundle\Entity\ProjectApplication;
use eclore\userBundle\Entity\Notification;
use eclore\userBundle\Entity\Action;
use eclore\userBundle\Timeline\TimelineEvents;
use eclore\userBundle\Timeline\MarkedEvent;
use eclore\userBundle\Timeline\PAEvent;

class MembersController extends Controller
{   

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
        if($user->getPAByStatus('TERMINATED')->count() > 0)
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Tu dois donner ton avis sur tes précédents projets avant de pouvoir candidater!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
        }
                
        // verifie que $user n'a pas deja candidaté
        if($user->hasApplied($project))
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
            $PA->setUser($user);
            $PA->setStatus('PENDING');
            $PA->setStatusDate(new \DateTime);
            $PA->setProject($project);
            $this->getDoctrine()->getManager()->persist($PA);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add(
            'notice',
            'Ta candidature a bien été envoyée !');
            // automatically connect user and project responsibles... TBC
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
        return $this->render('ecloreuserBundle:Members:apply.html.twig', array('proj'=>$project,'form' => $form->createView()));  
    }
    
    public function manageApplicationAction($id)
    {
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();
        $em =$this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:ProjectApplication');
        $PA = $repository->find($id);
        
        // verifie que la candidature concerne bien $user
        if(!$PA || $PA->getUser()->getId() != $user->getId())
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
            //cloture candidature coté participant
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

        return $this->render('ecloreuserBundle:Members:manage-application.html.twig',
        array('pa'=>$PA,'form'=>$form->createView()));
    
    }

public function displayMemberAction($id)
    {
    $securityContext = $this->container->get('security.context'); 
    
    $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:User');
        $user2 = $repository->find($id);

        // verifie que le user existe et n'est pas terminé.
        if(!$user2 || $user2->getPrivacyLevel() == 2)
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Ce profil n\'existe pas');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
        }
    $contactRequested=False;
    if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        //check que une demande de contact n'existe pas deja.
        $rep = $em->getRepository('ecloreuserBundle:Notification');
        $contactRequested = $rep->contactRequested($user, $user2);
    }
    return $this->render('ecloreuserBundle:Members:display-member.html.twig', array('u'=>$user2, 'contactRequested'=>$contactRequested));
    }
    
    public function displayAssoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:Association');
        $asso = $repository->find($id);
        if(!$asso)
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Cette association n\'existe pas');
            return $this->redirect($this->generateUrl('ecloreuser_homepage'));
        }
    
    return $this->render('ecloreuserBundle:Members:display-asso.html.twig', array('asso'=>$asso));
    
    }
    
    public function createAssoAction(Request $request)
    {
    $asso = new Association();
    $form = $this->container->get('form.factory')->create(new AssoEditType(), $asso); 
    
    if('POST' === $request->getMethod()) {
        $form->bind($request);     
            if ($form->isValid()) { 
                $this->getDoctrine()->getManager()->persist($asso);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->add(
                'notice',
                'L\'association a été correctement crée!');
                return $this->redirect($this->generateUrl('ecloreuser_home'));
            }
        }
    return $this->render('ecloreuserBundle:Members:create-asso.html.twig',
        array('form' => $form->createView()));
    }
    public function createInstAction(Request $request)
    {
    $inst = new Institution();
    $form = $this->container->get('form.factory')->create(new InstEditType(), $inst); 
    
    if('POST' === $request->getMethod()) {
        $form->bind($request);     
            if ($form->isValid()) { 
                $this->getDoctrine()->getManager()->persist($inst);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->add(
                'notice',
                'L\'association a été correctement crée!');
                return $this->redirect($this->generateUrl('ecloreuser_home'));
            }
        }
    return $this->render('ecloreuserBundle:Members:create-inst.html.twig',
        array('form' => $form->createView()));
    }
    
    public function displayInstAction($id)
    {
            $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ecloreuserBundle:Institution');
        $inst = $repository->find($id);
        if(!$inst)
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Cette association n\'existe pas');
            return $this->redirect($this->generateUrl('ecloreuser_homepage'));
        }
    
    return $this->render('ecloreuserBundle:Members:display-inst.html.twig', array('inst'=>$inst));
    }

    
    
    }