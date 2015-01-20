<?php

namespace eclore\userBundle\Controller;

use eclore\userBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InstMController extends Controller
{   
    
    public function editInstAction($id, Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('ecloreuserBundle:Institution');
            $inst = $repository->find($id);
            
        if(!$inst || !$user->getInstM()->getInstitutions()->contains($inst)){
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
            
    $form = $this->container->get('form.factory')->create(new InstEditType(), $inst); 
    
    if('POST' === $request->getMethod()) {
        $form->bind($request);     
            if ($form->isValid()) { 
                $this->getDoctrine()->getManager()->persist($asso);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->add(
                'notice',
                'L\'institution a été correctement mise à jour!');
                return $this->redirect($this->generateUrl('displayInst', 
                array('id'=>$inst->getId())));
            }
        }
        
        
    return $this->render('ecloreuserBundle:InstM:edit-inst.html.twig',
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
        
        return $this->render('ecloreuserBundle:InstM:home.html.twig', 
        array('instM'=>$user->getInstM(),'timeline_coll'=>$timeline));
         
    }
    
    public function displayYoungAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('ecloreuserBundle:Young');
        $young = $repository->find($id);

        if($user->getInstM()->getInstitutions()
        ->forAll(function($k, $i) use ($young){return !$i->getYoungs()->contains($young);}))
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Vous n \'êtes pas responsable de ce jeune!');
            return $this->redirect($this->generateUrl('ecloreuser_home'));
        }
        
        return $this->render('ecloreuserBundle:InstM:display-young.html.twig', array('young'=>$young));
         
    }

}
