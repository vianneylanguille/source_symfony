<?php

namespace eclore\userBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use eclore\userBundle\Entity\ProjectApplication;

class ProjectApplicationAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
            
        $formMapper
            ->add('user', 'entity', array('class'=>'ecloreuserBundle:User')) 
            ->add('project', 'entity', array('class'=>'ecloreuserBundle:Project')) 
            ->add('message', 'textarea')
            ->add('status', 'choice', array('choices'=>$this->getConfigurationPool()->getContainer()->getParameter('PAStatus')))
            ->add('statusDate')
            
        ;
    }

    // Fields to be shown on filter forms
    /*protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('young', 'entity', array('class'=>'ecloreuserBundle:Young')) 
            ->add('project', 'entity', array('class'=>'ecloreuserBundle:Project')) 
            ->add('status')
            ->add('statusDate')
        ;
    }*/

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('user', 'entity', array('class'=>'ecloreuserBundle:User')) 
            ->add('project', 'entity', array('class'=>'ecloreuserBundle:Project')) 
            ->add('status', 'choice', array('choices'=>$this->getConfigurationPool()->getContainer()->getParameter('PAStatus')))
            ->add('statusDate')
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