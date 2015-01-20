<?php
namespace eclore\userBundle\Extensions\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

class templateFunctionsHelper extends \Twig_Extension
{
private $container;
private $context;

public function __construct(ContainerInterface $container, SecurityContext $context) {
        $this->container = $container;
        $this->context = $context;
    }
    

    public function getFilters() 
    {
        return array(
            'printName'  => new \Twig_Filter_Method($this, 'printName', array('is_safe' => array('html'))),
            'printAsso'  => new \Twig_Filter_Method($this, 'printAsso', array('is_safe' => array('html'))),
            'printInst'  => new \Twig_Filter_Method($this, 'printInst', array('is_safe' => array('html'))),
            'PAStatus'  => new \Twig_Filter_Method($this, 'PAStatus', array('is_safe' => array('html'))),
            'privacyLevels'  => new \Twig_Filter_Method($this, 'privacyLevels', array('is_safe' => array('html'))),
            'printDate'  => new \Twig_Filter_Method($this, 'printDate', array('is_safe' => array('html')))
        );
    }


    public function printName($user2)
    {   $first = "<a href='".$this->container->get('router')->generate('displayMember', array('id'=>$user2->getId()))."'>";
    
        if(!$this->context->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $first.$user2->getUsername()."</a>";
            
        $user=$this->context->getToken()->getUser();
        
        if($user->getContacts()->contains($user2))
            return $first.$user2->__toString()."</a>";
            
        if($user->getId() == $user2->getId())
            return $first."vous</a>";
            
        return $first.$user2->getUsername()."</a>";
    }
    
    public function printInst($inst)
    {
        return "<a href='".$this->container->get('router')->generate('displayInst', array('id'=>$inst->getId()))."'>".$inst->getInstitutionName()."</a>";
    }
    
    public function printAsso($asso)
    {
        return "<a href='".$this->container->get('router')->generate('displayAsso', array('id'=>$asso->getId()))."'>".$asso->getAssociationName()."</a>";
    }
    
    public function printDate($date)
    {
        return $date->format('d/m/Y');
    }
    
    public function PAStatus($status)
    {   
        return $this->container->getParameter('PAStatus')[$status];
    }
    
     public function privacyLevels($pl)
    {   
        return $this->container->getParameter('privacyLevels')[$pl];
    }

    public function getName()
    {
        return 'templateFunctions';
    }

}
?>