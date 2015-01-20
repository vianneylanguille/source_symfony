<?php

namespace eclore\userBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use eclore\userBundle\Form\ImageType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlbumType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name',        'text')
      ->add('pictures', 'collection', array('type'         => new ImageType(),
                                              'allow_add'    => true,
                                              'allow_delete' => true,
                                              'required' => false,
                                              'by_reference' => false))
      ->add('save', 'submit')
    ;
  }
public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'eclore\userBundle\Entity\Album'
    ));
  }

  public function getName()
  {
    return 'eclore_userbundle_albumtype';
  }
}