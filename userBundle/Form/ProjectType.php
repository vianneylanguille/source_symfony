<?php

namespace eclore\userBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\Container;

class ProjectType extends AbstractType
{
private $securityContext;
protected $container;

    public function __construct(SecurityContext $securityContext, Container $container)
    {
        $this->securityContext = $securityContext;
        $this->container = $container;
    }
    
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
        $user = $this->securityContext->getToken()->getUser();
        
        $data;
        foreach($user->getAssoM()->getAssociations() as $value)
            {
            $data[$value->getId()]=$value;
            }
            
        $builder
            ->add('projectName' , 'text', array('label' => ' Intitulé du projet :'))
            ->add('shortDescription' , 'textarea', array('label' => ' Votre projet en une phrase !') )
            ->add('description' , 'textarea', array('label' => 'Décrivez plus précisement le projet :'))
            ->add('association', 'entity', array('class' => 'eclore\userBundle\Entity\Association',
            'choices' => $data))
            ->add('labels', 'entity', array('class' => 'eclore\userBundle\Entity\ProjectLabels', 'label' => ' Catégorie :'))
            ->add('startDate', 'date', array( 'widget' => 'choice', 'years'=>range((int)date("Y"), (int)date("Y")+50) , 'label' => ' Date de début du projet'))
            ->add('endDate', 'date', array( 'widget' => 'choice', 'years'=>range((int)date("Y"), (int)date("Y")+50) , 'label' => ' Date de fin du projet'))
            ->add('address', 'text', array('attr' => array('class'=>'addressPickerInput') , 'label' => "Lieu du projet") )
             ->add('required', 'text', array('label' => ' Nombre souhaité de jeunes :'))  
            ->add('investmentRequired', 'choice', array('choices'=>$this->container->getParameter('investmentRequired'), 'label' => ' Investissement nécessaire :'))
            ->add('projectApplications', 'entity', array('class'=>'ecloreuserBundle:ProjectApplication',
            'property'=>'id', 'multiple'=>true, 'required'=>false))
            ->add('lat', 'hidden', array('attr' => array('class'=>'addressPickerLat')))
            ->add('lng', 'hidden', array('attr' => array('class'=>'addressPickerLng')))
            ->add('city', 'hidden', array('attr' => array('class'=>'addressPickerCity')))
            ->add('country', 'hidden', array('attr' => array('class'=>'addressPickerCountry')))
            ->add('postcode', 'hidden', array('attr' => array('class'=>'addressPickerPostcode')))	   ->add('responsibles', 'entity', array('class' => 'eclore\userBundle\Entity\AssociationMember',
            'multiple'=>true))
            ->add('save', 'submit' , array( 'label' => 'Déposer le projet !' ))
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'eclore\userBundle\Entity\Project'
    ));
  }

  public function getName()
  {
    return 'eclore_userbundle_projecttype';
  }
}