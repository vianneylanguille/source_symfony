<?php

namespace eclore\userBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use eclore\userBundle\Form\ImageType;
use Symfony\Component\DependencyInjection\Container;

class ProfileFormType extends BaseType
{

    protected $container;
     
    public function __construct($user_class, Container $container)
    {
        parent::__construct($user_class);
        $this->container = $container;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('birthDate', 'date', array( 'widget' => 'choice', 'years'=>range((int)date("Y")-100, (int)date("Y")), 'label'=>"Date de naissance"))
                ->add('lastName', 'text', array(  'label'=>" Nom de famille :"))
                ->add('firstName', 'text',  array(  'label'=>" Prénom :"))
                ->add('mobile','text',  array(  'label'=>"Numéro de téléphone :"))
                ->add('quality','text',  array(  'label'=>"Votre situation actuelle (par exemple: 2ème année de BTS, président d'association, etc...)"))
                ->add('privacyLevel', 'choice', array('choices'=>$this->container->getParameter('privacyLevels'), 'label'=>"Confidentialité de vos données personnelles"))
                ->add('headshot', new ImageType(),  array(  'label'=>"Photo de profil"))
                ;
    }

    public function getName()
    {
        return 'eclore_user_profile';
    }
}

?>