<?php

namespace eclore\userBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class InstitutionAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('institutionName')
            ->add('description')
            ->add('location')
            ->add('members', 'entity', array('class'=>'ecloreuserBundle:InstitutionMember',
            'multiple'=>true, 'required'=>false))
            ->add('youngs', 'entity', array('class'=>'ecloreuserBundle:Young',
            'multiple'=>true, 'required'=>false))
        ;
    }

    // Fields to be shown on filter forms
    /*protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('institutionName')
            ->add('description')
            ->add('location')
        ;
    }*/

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('institutionName')
            ->add('description')
            ->add('location')
            ->add('members', 'entity', array('class'=>'ecloreuserBundle:InstitutionMember',
            'multiple'=>true, 'required'=>false))
            ->add('youngs', 'entity', array('class'=>'ecloreuserBundle:Young',
            'multiple'=>true, 'required'=>false))
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