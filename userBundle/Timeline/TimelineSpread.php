<?php

namespace eclore\userBundle\Timeline;

use Spy\Timeline\Spread\SpreadInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Spread\Entry\EntryCollection;
use Spy\Timeline\Spread\Entry\EntryUnaware;
use Symfony\Component\DependencyInjection\Container;
use Spy\Timeline\Spread\Entry\Entry;

class TimelineSpread implements SpreadInterface
{
protected $container;
protected $informedUsers;
CONST USER_CLASS = 'eclore\userBundle\Entity\User';

    public function __construct(Container $container)
    {
    $this->container = $container;
    }
    
    public function supports(ActionInterface $action)
    {
        // here you define what actions you want to support, you have to return a boolean.
        return true;
    }

    public function process(ActionInterface $action, EntryCollection $coll)
    {   //common part to all spreads
        $subjectId =  $action->getSubject()->getIdentifier();
        $complementId =  $action->getComponent('complement')->getIdentifier();
        $em = $this->container->get('doctrine')->getManager();
        //reps
        $userRep = $em->getRepository('ecloreuserBundle:User');
        $projectRep = $em->getRepository('ecloreuserBundle:Project');
        $assoRep = $em->getRepository('ecloreuserBundle:Association');
        $instMRep = $em->getRepository('ecloreuserBundle:InstitutionMember');
        $this->informedUsers=array();
        
        if($action->getVerb() == 'mark_young'){
            //markedYoung
            $user = $userRep->find($complementId);
            if(isset($user) && $user->getPrivacyLevel() != 2){
                $this->informsYoungInstM($user);
            }
        }elseif(($action->getVerb() == 'take_part' || $action->getVerb() == 'apply')){
            //validatedPA or newPA
            $user = $userRep->find($subjectId);
            $project = $projectRep->find($complementId);
            if(isset($user) && isset($project)  && $user->getPrivacyLevel() != 2){
                // informs project
                $coll->add(new Entry($action->getComponent('complement')));
                $this->informsUserContacts($user);
                $this->informsYoungInstM($user);
                // informs projects responsibles
                foreach($project->getResponsibles() as $assoM)
                    $this->informedUsers[]=$assoM->getUser()->getId();
            }
        }elseif($action->getVerb() == 'contact'){
            //contactAck
            $ackUser = $userRep->find($subjectId);
            $requestingUser = $userRep->find($complementId);
            if(isset($ackUser) && isset($requestingUser)){
                // informs requestingUser
                if($requestingUser->getPrivacyLevel() != 2){
                    $this->informedUsers[]=$requestingUser->getId();
                    $this->informsUserContacts($requestingUser);
                }
                if($ackUser->getPrivacyLevel() != 2){
                    $this->informsUserContacts($ackUser);
                }
                $this->informsUserContacts($ackUser);
                
            }
        }elseif($action->getVerb() == 'mark_project'){
            //markedProject
            $user = $userRep->find($subjectId);
            $project = $projectRep->find($complementId);
            if(isset($project) && isset($user) && $user->getPrivacyLevel() != 2){
                // informs project
                $coll->add(new Entry($action->getComponent('complement')));
                $this->informsYoungInstM($user);
                // informs project responsibles
                foreach($project->getResponsibles() as $resp)
                    $this->informedUsers[]=$resp->getUser()->getId();
            }
        }elseif($action->getVerb() == 'be_rejected'){
            //rejectedPA
            $user = $userRep->find($subjectId);
            $project = $projectRep->find($complementId);
            if(isset($user) && isset($project) && $user->getPrivacyLevel() != 2){
                $this->informsYoungInstM($user);
                // informs projects responsibles
                foreach($project->getResponsibles() as $assoM)
                    $this->informedUsers[]=$assoM->getUser()->getId();
            }
        }elseif($action->getVerb() == 'create_project'){
            //newProject
            $asso = $assoRep->find($subjectId);
            $project = $projectRep->find($complementId);
            if(isset($project) && isset($asso)){
                // informs project
                $coll->add(new Entry($action->getComponent('complement')));
                // informs all instM
                foreach($instMRep->findAll() as $instM)
                    $this->informedUsers[]=$instM->getUser()->getId();
                // informs all assoM of asso
                foreach($asso->getMembers() as $assoM)
                    $this->informedUsers[]=$assoM->getUser()->getId();
                // informs youngs who took part in one of asso projects
                foreach($asso->getProjects() as $proj)
                    foreach($proj->getProjectApplications() as $PA)
                        $this->informedUsers[]=$PA->getUser()->getId();
            }
        }elseif($action->getVerb() == 'registered'){
            //newUser
            $user = $userRep->find($subjectId);
            if(isset($user) && $user->getPrivacyLevel() != 2){
                if($user->hasRole('ROLE_YOUNG')){
                    $this->informsYoungInstM($user);
                    // informs youngs from inst
                    foreach($user->getYoung()->getInstitutions() as $inst)
                        foreach($inst->getYoungs() as $yg)
                            $this->informedUsers[]=$yg->getUser()->getId();
                }elseif($user->hasRole('ROLE_ASSOM')){
                    //informs other assom
                    foreach($user->getAssoM()->getAssociations() as $asso)
                        foreach($asso->getMembers() as $assoM)
                            $this->informedUsers[]=$assoM->getUser()->getId();
                }elseif($user->hasRole('ROLE_INSTM')){
                    // informs other instm
                    foreach($user->getInstM()->getInstitutions() as $inst)
                        foreach($inst->getMembers() as $instM)
                            $this->informedUsers[]=$instM->getUser()->getId();
                    // informs young of inst
                    foreach($user->getInstM()->getInstitutions() as $inst)
                        foreach($inst->getYoungs() as $yg)
                            $this->informedUsers[]=$yg->getUser()->getId();
                }
            }
        }
        
        //informs every required users
        foreach(array_unique($this->informedUsers) as $id)
            $coll->add(new EntryUnAware(self::USER_CLASS, $id)); 
    }
        
    public function informsYoungInstM($user){
    if($user->hasRole('ROLE_YOUNG')){
        foreach($user->getYoung()->getInstitutions() as $inst)
            foreach($inst->getMembers() as $instM)
                $this->informedUsers[]=$instM->getUser()->getId();
        $this->informedUsers[]=$user->getId();
        }
    }
    
    public function informsUserContacts($user){
        foreach($user->getContacts() as $people)
            $this->informedUsers[]=$people->getId();
        $this->informedUsers[]=$user->getId();
    }
    
}