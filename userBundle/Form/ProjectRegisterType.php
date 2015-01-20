<?php

namespace eclore\userBundle\Form;

use Doctrine\ORM\EntityManager;
use NoxLogic\DemoBundle\Entity\Province;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
 
class ProjectRegisterType extends ProjectType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('projectApplications');
        $builder->remove('responsibles');

    }
 
    public function getName()
    {
        return 'eclore_userbundle_projectregistertype';
    }
 
}