<?php

namespace eclore\userBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class NotificationAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type', 'choice', array('choices'=>$this->getConfigurationPool()->getContainer()->getParameter('Notification')))
            ->add('message', 'textarea')
            ->add('initDate')
            ->add('project', 'entity', array('class'=>'ecloreuserBundle:Project', 'required'=>false))
            ->add('sender', 'entity', array('class'=>'ecloreuserBundle:User'))
            ->add('receivers', 'entity', array('class'=>'ecloreuserBundle:User',
            'multiple'=>true))
        ;
    }

    // Fields to be shown on filter forms
    /*protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user')
            ->add('schoolSituation')
            ->add('institutions')
            ->add('appliedProjects')
            ->add('friends')
        ;
    }*/

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('type', 'choice', array('choices'=>$this->getConfigurationPool()->getContainer()->getParameter('Notification')))
            ->add('message', 'textarea')
            ->add('initDate')
            ->add('project', 'entity', array('class'=>'ecloreuserBundle:Project', 'required'=>false))
            ->add('sender', 'entity', array('class'=>'ecloreuserBundle:User'))
            ->add('receivers', 'entity', array('class'=>'ecloreuserBundle:User',
            'multiple'=>true))
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