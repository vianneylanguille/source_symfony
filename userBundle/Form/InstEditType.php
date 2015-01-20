<?php

namespace eclore\userBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use eclore\userBundle\Form\ImageType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;

class InstEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
        ->add('institutionName','text' , array('label' => 'Nom de l\'institution :'))
        ->add('description','textarea' , array('label' => 'Courte description :'))
        ->add('location', 'text', array('attr' => array('class'=>'addressPickerInput') , 'label' => "Adresse de l'institution :") )
        ->add('headshot', new ImageType(), array('required'=>false, 'label' => 'Logo :'))
        ->add('save', 'submit', array( 'label' => 'Ajouter' ) )
    ;
  }
public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'eclore\userBundle\Entity\Institution'
    ));
  }

  public function getName()
  {
    return 'eclore_user_instedittype';
  }
}