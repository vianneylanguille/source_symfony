<?php

namespace eclore\userBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use eclore\userBundle\Form\ImageType;
use Symfony\Component\DependencyInjection\Container;

class RegistrationFormType extends BaseType
{
    protected $container;
     
    public function __construct($user_class, Container $container)
    {
        parent::__construct($user_class);
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->add('birthDate', 'date', array( 'widget' => 'choice', 'years'=>range((int)date("Y")-100, (int)date("Y")), 'label'  => 'Date de naissance :'))
                ->add('lastName' , 'text' , array('label' => 'Nom :') )
                ->add('firstName' , 'text' , array('label' => 'Prénom :'))
                ->add('mobile' ,'text', array('label' => 'Numéro de téléphone : '))
                ->remove('quality')
                ->add('privacyLevel', 'choice', array('choices'=>$this->container->getParameter('privacyLevels') , 'label'  => 'Paramètres de confidentialité :'))
                ->add('headshot', new ImageType(), array('required'=>false , 'label'  => 'Photo de profil :  '))
                ;
    }

    public function getName()
    {
        return 'eclore_user_registration';
    }
}

?>