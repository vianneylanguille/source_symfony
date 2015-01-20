<?php

namespace eclore\userBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use eclore\userBundle\Form\ImageType;

class AssociationAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
    
    $headshot = $this->getSubject()->getHeadshot();
        $fileFieldOptions = array('required'=>false, 'help'=>'Pas de photo');
        if ($headshot && ($webPath = $headshot->getWebPath())) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request')->getBasePath().'/'.$webPath;
            $fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
        }
        
        $formMapper
            ->add('associationName')
            ->add('headshot', new ImageType(), $fileFieldOptions)
            ->add('description')
            ->add('location')
            ->add('members', 'entity', array('class'=>'ecloreuserBundle:AssociationMember',
            'multiple'=>true, 'required'=>false))
            ->add('projects', 'entity', array('class'=>'ecloreuserBundle:Project',
            'multiple'=>true, 'required'=>false))
        ;
    }

    // Fields to be shown on filter forms
    /*protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('associationName')
            ->add('description')
            ->add('location')
        ;
    }*/

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('associationName')
            ->add('description')
            ->add('location')
            ->add('members', 'entity', array('class'=>'ecloreuserBundle:AssociationMember',
            'multiple'=>true, 'required'=>false))
            ->add('projects', 'entity', array('class'=>'ecloreuserBundle:Project',
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
    
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('associationName')
            ->add('headshot', 'entity', array('class'=>'ecloreuserBundle:Image',
            'required' =>false, 'template' => 'ecloreuserBundle:Admin:show_headshot.html.twig'))
            ->add('description')
            ->add('location')
            ->add('members', 'entity', array('class'=>'ecloreuserBundle:AssociationMember',
            'multiple'=>true, 'required'=>false))
            ->add('projects', 'entity', array('class'=>'ecloreuserBundle:Project',
            'multiple'=>true, 'required'=>false))
        ;
    }
}