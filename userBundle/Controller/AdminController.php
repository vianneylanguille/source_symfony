<?php

namespace eclore\userBundle\Controller;

use eclore\userBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use eclore\userBundle\Timeline\TimelineEvents;
use eclore\userBundle\Timeline\NewUserEvent;
use eclore\userBundle\Timeline\NewProjectEvent;

class AdminController extends Controller
{   
    public function indexAction()
    {
        $assoMs = $this->getDoctrine()
                           ->getManager()
                           ->getRepository('ecloreuserBundle:AssociationMember')
                           ->findByRole('ROLE_TBC');                  
                        
        $instMs = $this->getDoctrine()
                           ->getManager()
                           ->getRepository('ecloreuserBundle:InstitutionMember')
                           ->findByRole('ROLE_TBC');
                           
        $projects = $this->getDoctrine()
                           ->getManager()
                           ->getRepository('ecloreuserBundle:Project')
                           ->findByEnabled(False);
                   
        return $this->render('ecloreuserBundle:Admin:home.html.twig', array('assoMs'=>$assoMs,
            'instMs'=>$instMs, 'projects'=>$projects));
    }
    
    public function validateProfileAction($type, $id)
    {
        $formatted_type = 'InstitutionMember';
        if($type=='AssoM')$formatted_type = 'AssociationMember';
        $em = $this->getDoctrine()->getManager();
        $profile = $em->getRepository('ecloreuserBundle:'.$formatted_type)
                      ->find($id);                      
        if(!$profile || !$profile->getUser()->hasRole('ROLE_TBC'))
        {
        $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Erreur: le profil est déjà validé ou n\'existe pas.');
        }
        elseif($profile->getUser()->hasRole('ROLE_TBC'))
        {
            $profile->getUser()->removeRole('ROLE_TBC');           
            $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Profil validé! L\'utilisateur a été averti.');            
            $em->persist($profile->getUser());
            $em->flush();
            //event dispatcher
            $event = new NewUserEvent($profile->getUser());
            $this->container->get('event_dispatcher')->dispatch(TimelineEvents::onNewUser, $event);
            
            // send mail to user
            $profile->getUser()->getEmail();
            
        }
        return $this->redirect($this->generateUrl('admin_page'));
    }
    
       public function validateProjectAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ecloreuserBundle:Project')
                      ->find($id);                      
        if(!$project || $project->getEnabled())
        {
        $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Erreur: le projet est déjà validé ou n\'existe pas.');
        }
        elseif(!$project->getEnabled())
        {
            $project->setEnabled(True);           
            $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Projet validé! Le responsable a été averti.');            
            $em->flush(); 
            // event dispatcher
            $event = new NewProjectEvent($project);
            $this->container->get('event_dispatcher')->dispatch(TimelineEvents::onNewProject, $event);
            
        }
        return $this->redirect($this->generateUrl('admin_page'));
    }
}
