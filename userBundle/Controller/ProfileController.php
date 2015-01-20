<?php

namespace eclore\userBundle\Controller;

use eclore\userBundle\Entity\User;
use eclore\userBundle\Entity\Album;
use eclore\userBundle\Entity\Notification;
use FOS\UserBundle\Controller\ProfileController as Controller;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Util\StringUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use eclore\userBundle\Timeline\TimelineEvents;
use eclore\userBundle\Timeline\ContactAckEvent;
use eclore\userBundle\Form\ImageType;
use eclore\userBundle\Form\AlbumType;

class ProfileController extends Controller
{   
    public function showAlbumsAction()
    {
    return $this->container->get('templating')->renderResponse('ecloreuserBundle:Profile:albums.html.twig');
    }
    
    public function removeAlbumAction($id)
    {
        $em = $rep = $this->container->get('doctrine')->getManager();
        $rep = $em->getRepository('ecloreuserBundle:Album');
        $album = $rep->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
         
        if($album->getOwner()->getId() != $user->getId()) 
            $this->container->get('session')->getFlashBag()->add(
                'notice',
                'Vous n\'êtes pas concerné par cet album!');
        else 
        {
            $em->remove($album);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add(
                'notice',
                'L\'album a été supprimé.');
        }
            
        return new RedirectResponse($this->container->get('router')->generate('user_albums'));
    }
    
    public function removePicAction($id)
    {
        $em = $rep = $this->container->get('doctrine')->getManager();
        $rep = $em->getRepository('ecloreuserBundle:Image');
        $image = $rep->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
         
        if($image->getAlbum()->getOwner()->getId() != $user->getId()) 
            $this->container->get('session')->getFlashBag()->add(
                'notice',
                'Vous n\'êtes pas concerné par cette image!');
        else 
        {
            $em->remove($image);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add(
                'notice',
                'L\'image a été supprimée.');
        }
            
        return new RedirectResponse($this->container->get('router')->generate('user_albums'));
    }
    
    public function createAlbumAction(Request $request)
    {
    $user = $this->container->get('security.context')->getToken()->getUser();
    $em = $this->container->get('doctrine')->getManager();
    $album = new Album();
    $form = $this->container->get('form.factory')->create(new AlbumType(), $album); 
    
    if('POST' === $request->getMethod()) {
        $form->bind($request);       
        if ($form->isValid()) { 
            $album->setOwner($user);
            $em->persist($album);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add(
            'notice',
            'L\'album a correctement été créé.');
            return new RedirectResponse($this->container->get('router')->generate('user_albums'));
        }
    }
                
    return $this->container->get('templating')->renderResponse('ecloreuserBundle:Profile:create-album.html.twig', 
        array('form'=> $form->createView()));
    }
    
    public function getAnnuaireAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $rep = $this->container->get('doctrine')
                       ->getManager()
                       ->getRepository('ecloreuserBundle:Notification');
        $contact_not = $rep->findByReceiverAndType($user, 'CONTACT');
        $contact_send = $rep->findBySenderAndType($user, 'CONTACT');
        return $this->container->get('templating')->renderResponse('ecloreuserBundle:Profile:annuaire.html.twig',
        array('contact_not'=>$contact_not, 'contact_send'=>$contact_send));
    }
    
    public function getRecommendationsAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $rep = $this->container->get('doctrine')
                       ->getManager()
                       ->getRepository('ecloreuserBundle:Notification');
        $recomm = $rep->findByReceiverAndType($user, 'RECOMMENDATION');
        $mark = $rep->findByReceiverAndType($user, 'MARK');
        return $this->container->get('templating')->renderResponse('ecloreuserBundle:Profile:recommendations.html.twig',
        array('recomm'=>$recomm, 'mark'=>$mark));
    }
    
    public function acknowledgeContactAction($id, $action)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->container->get('doctrine')->getManager();
        $rep = $em->getRepository('ecloreuserBundle:Notification'); 
        $contacts_not = $rep->findByReceiverAndType($user, 'CONTACT');        
        $contact_not;
        // recupere la contact_not
        foreach($contacts_not as $ct)
            if($ct->getId() == $id)
                $contact_not = $ct;
        // si la not n'existe pas
        if(!isset($contact_not))
        {
        $this->container->get('session')->getFlashBag()->add(
                        'notice',
                        'Vous n\'êtes pas concerné par cette notification.');
        }
        else{
            if(StringUtils::equals($action, 'ack')){
                $user2 = $contact_not->getSender();
                $user->addContact($user2);
                $user2->addContact($user);
                $em->persist($user);
                $em->persist($user2);
                $this->container->get('session')->getFlashBag()->add(
                        'notice',
                        'Ce contact a été ajouté à votre annuaire.');
                // event dispatcher
                $event = new ContactAckEvent($user2, $user);
                $this->container->get('event_dispatcher')->dispatch(TimelineEvents::onContactAck, $event);
            }else{
            $this->container->get('session')->getFlashBag()->add(
                        'notice',
                        'Notification supprimée.');
            }
            $em->remove($contact_not);
            $em->flush();
        }
        
        return new RedirectResponse($this->container->get('router')->generate('user_annuaire'));

    }
    
        public function sendDemandAction($id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->container->get('doctrine')->getManager();
        $rep = $em->getRepository('ecloreuserBundle:User');
        $user2 = $rep->find($id);
        
        $rep = $em->getRepository('ecloreuserBundle:Notification');     
                
        // si $user2 n'existe pas ou ets en mode invisible
        if(!$user2 || $user2->getPrivacyLevel() == 2)
        {
            $this->container->get('session')->getFlashBag()->add(
                        'notice',
                        'Cet utilisateur n\'existe pas!');
        }
        //si une demande de contact existe deja entre les deux ou qu'ils sont deja contacts
        elseif($user->getContacts()->contains($user2) || $rep->contactRequested($user, $user2) || ($user->getId() == $user2->getId()))
        {
            $this->container->get('session')->getFlashBag()->add(
                        'notice',
                        'Cette personne est déjà dans vos contacts ou vous avez déjà une demande de contact en attente pour cette personne!');
        }
        else
        {
            $not = new Notification();
            $not->setType('CONTACT');
            $not->setSender($user);
            $not->setMessage('');
            $not->addReceiver($user2);
            $em->persist($not);
            $this->container->get('session')->getFlashBag()->add(
                    'notice',
                    'La demande a bien été envoyée.');
            $em->flush();
        }
        
        return new RedirectResponse($this->container->get('router')->generate('user_annuaire'));
    }
    
    public function removeContactAction($id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->container->get('doctrine')->getManager();
        $rep = $em->getRepository('ecloreuserBundle:User');
        $user2 = $rep->find($id);
                
        // si $user2 n'existe pas
        if(!$user2)
        {   $this->container->get('session')->getFlashBag()->add(
                        'notice',
                        'Cet utilisateur n\'existe pas!');
        }
        //si user2 n'est pas contact de user
        elseif(!$user->getContacts()->contains($user2))
        {   $this->container->get('session')->getFlashBag()->add(
                        'notice',
                        'Cette personne n\'est pas dans vos contacts!');
        }
        else
        {   $user->removeContact($user2);
            $user2->removeContact($user);
            $em->persist($user);
            $em->persist($user2);
            $this->container->get('session')->getFlashBag()->add(
                    'notice',
                    'Contact supprimé.');
            $em->flush();
        }
        return new RedirectResponse($this->container->get('router')->generate('user_annuaire'));
    }

   public function editAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();

        if(!$user->hasRole('ROLE_YOUNG')&&!$user->hasRole('ROLE_INSTM')&&!$user->hasRole('ROLE_ASSOM'))
                $form->remove('quality');
                
        $form->setData($user);

        
        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
                $userManager = $this->container->get('fos_user.user_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);
                
                $userManager->updateUser($user);

                $em = $this->container->get('doctrine')->getEntityManager();
                $em->persist($user);
                $em->flush();
                
                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('fos_user_profile_show');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView())
        );
    }
}
