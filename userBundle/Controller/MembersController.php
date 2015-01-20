<?php

namespace eclore\userBundle\Controller;

use eclore\userBundle\Entity\User;
use eclore\userBundle\Entity\Association;
use eclore\userBundle\Entity\Institution;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use eclore\userBundle\Form\AssoEditType;
use eclore\userBundle\Form\InstEditType;

class MembersController extends Controller
{   

public function displayMemberAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();
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
        
        //check que une demande de contact n'existe pas deja.
        $rep = $em->getRepository('ecloreuserBundle:Notification');
        $contactRequested = $rep->contactRequested($user, $user2);
    
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