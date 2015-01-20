<?php

namespace eclore\userBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use eclore\userBundle\Form\ImageType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;

class AssoEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
        ->add('associationName', 'text', array('label' => " Nom de l'association :"))
        ->add('description','textarea', array('label' => " Quelles sont les activités de votre association ?"))
        ->add('location', 'text',  array('attr' => array('class'=>'addressPickerInput') , 'label' => "Adresse de l'association") )
        ->add('headshot', new ImageType(), array('required'=>false, , 'label' => "Logo de l'association"))
        ->add('save', 'submit')
    ;
  }
public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'eclore\userBundle\Entity\Association'
    ));
  }

  public function getName()
  {
    return 'eclore_user_assoedittype';
  }
}