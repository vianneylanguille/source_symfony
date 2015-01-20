<?php

namespace eclore\userBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AssociationMemberAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('user')
            ->add('associations', 'entity', array('class'=>'ecloreuserBundle:Association',
            'property'=>'associationName', 'multiple'=>true))
            ->add('managedProjects', 'entity', array('class'=>'ecloreuserBundle:Project',
            'multiple'=>true, 'required'=>false))

            
        ;
    }

    // Fields to be shown on filter forms
    /*protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user')
            ->add('associations')
        ;
    }*/

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('user')
            ->add('associations')
            ->add('managedProjects', 'entity', array('class'=>'ecloreuserBundle:Project'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }
}