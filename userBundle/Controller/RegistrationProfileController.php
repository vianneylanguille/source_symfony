<?php

namespace eclore\userBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use eclore\userBundle\Entity\Young;
use eclore\userBundle\Entity\AssociationMember;
use eclore\userBundle\Entity\InstitutionMember;
use eclore\userBundle\Timeline\TimelineEvents;
use eclore\userBundle\Timeline\NewUserEvent;
use Symfony\Component\EventDispatcher\Event;

class RegistrationProfileController extends Controller
{   
    public function registerProfileAction(Request $request)
    {   $user = $this->container->get('security.context')->getToken()->getUser();
        $token = $this->get('security.context')->getToken();
    
        // create young registration forms
        $young = new Young();
        $youngFormBuilder = $this->get('form.factory')->createNamedBuilder('young', 'form', $young);
        $youngFormBuilder
            ->setMethod('POST')
            ->add('institutions', 'entity', array('class'=>'ecloreuserBundle:Institution',
            'property'=>'institutionName', 'multiple'=>true, 'label'=>'A quelle(s) institution(s) es-tu rattaché ?', 'empty_data'=>'Aucune.'))
            ->add('quality', 'textarea', array('mapped'=>false, 'label'=>'Tes activités'))
            ->add('submitYoung', 'submit', array('label'=>'Finir l\'inscription'));
           
        // create institutionmember registration forms
        $instM = new InstitutionMember();
        $instMFormBuilder = $this->get('form.factory')->createNamedBuilder('instM', 'form', $instM);
        $instMFormBuilder
            ->setMethod('POST')
            ->add('institutions', 'entity', array('class'=>'ecloreuserBundle:Institution',
            'property'=>'institutionName', 'multiple'=>true, 'label'=>'De quelles institutions faites-vous partie ?')) 
            ->add('quality', 'textarea', array('mapped'=>false, 'label'=>'Quel est vôtre rôle dans dans cette (ces) institution(s) ?'))
            ->add('submitInstM', 'submit', array('label'=>'Finir l\'inscription'));

        // create associationmember registration forms
        $assoM = new AssociationMember();
        $assoMFormBuilder = $this->get('form.factory')->createNamedBuilder('assoM', 'form', $assoM);
        $assoMFormBuilder
            ->setMethod('POST')
            ->add('associations', 'entity', array('class'=>'ecloreuserBundle:Association',
            'property'=>'associationName', 'multiple'=>true, 'label'=>'De quelles associations faites-vous partie ?'))
            ->add('quality', 'textarea', array('mapped'=>false, 'label'=>'Quel est vôtre rôle dans dans cette (ces) association(s) ?'))
            ->add('submitAssoM', 'submit', array('label'=>'Finir l\'inscription'));        
        
        $instMForm = $instMFormBuilder->getForm();
        $assoMForm = $assoMFormBuilder->getForm();
        $youngForm = $youngFormBuilder->getForm();
        
        if('POST' === $request->getMethod()) {
            if ($request->request->has('young')) {
                $youngForm->bind($request);
                if ($youngForm->isValid()) {
                    $user->setQuality($youngForm->get('quality')->getData());
                    $young->setUser($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($young);
                    $user->addRole("ROLE_YOUNG");
                    $em->persist($user);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add(
                'notice',
                'Votre profil a été correctement créé!');
                //event dispatcher
                $event = new NewUserEvent($user);
                $this->container->get('event_dispatcher')->dispatch(TimelineEvents::onNewUser, $event);
                    // Generate new token with new roles
                    $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
                      $user,
                      null,
                      'main',
                      $user->getRoles()
                    );
                    $this->container->get('security.context')->setToken($token);
                    // Get the userManager and refresh user
                    $userManager = $this->container->get('fos_user.user_manager');
                    $userManager->refreshUser($user);
                    return $this->redirect($this->generateUrl('ecloreuser_younghome'));
                }
            }
            if ($request->request->has('instM')) {
                $instMForm->bind($request);
                if ($instMForm->isValid()) {
                    $user->setQuality($instMForm->get('quality')->getData());
                    $instM->setUser($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($instM);
                    $user->addRole("ROLE_TBC");
                    $user->addRole("ROLE_INSTM");
                    $em->persist($user);
                    $em->flush();
                    // Generate new token with new roles
                    $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
                      $user,
                      null,
                      'main',
                      $user->getRoles()
                    );
                    $this->container->get('security.context')->setToken($token);
                    // Get the userManager and refresh user
                    $userManager = $this->container->get('fos_user.user_manager');
                    $userManager->refreshUser($user);
                    $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Votre profil a correctement été créé!');
                    //event dispatcher
                $event = new Event();
                $this->container->get('event_dispatcher')->dispatch(TimelineEvents::onPendingValidation, $event);
                    return $this->redirect($this->generateUrl('ecloreuser_home'));
                }
            }
            if ($request->request->has('assoM')) {
                $assoMForm->bind($request);
                if ($assoMForm->isValid()) {
                    $user->setQuality($assoMForm->get('quality')->getData());
                    $assoM->setUser($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($assoM);
                    $user->addRole("ROLE_TBC");
                    $user->addRole("ROLE_ASSOM");
                    $em->persist($user);
                    $em->flush();
                    // Generate new token with new roles
                    $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
                      $user,
                      null,
                      'main',
                      $user->getRoles()
                    );
                    $this->container->get('security.context')->setToken($token);
                    // Get the userManager and refresh user
                    $userManager = $this->container->get('fos_user.user_manager');
                    $userManager->refreshUser($user);
                    $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Votre profil a correctement été créé!');
                    //event dispatcher
                $event = new Event();
                $this->container->get('event_dispatcher')->dispatch(TimelineEvents::onPendingValidation, $event);
                    return $this->redirect($this->generateUrl('ecloreuser_home'));
                }
            }
        }
        
        return $this->render('ecloreuserBundle:Registration:registerProfile.html.twig', array(
        'youngForm' => $youngForm->createView(),'instMForm' => $instMForm->createView(),
        'assoMForm' => $assoMForm->createView(),
        ));
    }
    
}
