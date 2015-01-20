<?php

namespace eclore\userBundle\Controller;

use eclore\userBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{   
    public function dispatchHomeAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if($user->hasRole('ROLE_YOUNG') || $user->hasRole('ROLE_ASSOM') || $user->hasRole('ROLE_INSTM')){
            // redirect user to his home
            if($user->hasRole('ROLE_YOUNG'))return $this->redirect($this->generateUrl('ecloreuser_younghome'));
            if($user->hasRole('ROLE_INSTM'))return $this->redirect($this->generateUrl('ecloreuser_instmhome'));
            if($user->hasRole('ROLE_ASSOM'))return $this->redirect($this->generateUrl('ecloreuser_assomhome'));

        }
        elseif($user->hasRole('ROLE_TBC')){
        // user role is awaiting confirmation
        return $this->render('ecloreuserBundle:Registration:waitConfirmation.html.twig');
        }
        // user has not any role yet, he must create at least one.
            return $this->forward('ecloreuserBundle:RegistrationProfile:registerProfile');
    }

}
