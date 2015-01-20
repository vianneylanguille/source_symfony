<?php

namespace eclore\userBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class ProjectAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('startDate', 'date', array( 'widget' => 'choice', 'years'=>range((int)date("Y")-50, (int)date("Y")+50)))
            ->add('endDate', 'date', array( 'widget' => 'choice', 'years'=>range((int)date("Y")-50, (int)date("Y")+50)))
            ->add('enabled', 'checkbox', array('required'=> false))
            ->add('address')
            ->add('city')
            ->add('postcode', 'text', array('required'=> false))
            ->add('country', 'text', array('required'=> false))
            ->add('lat')
            ->add('lng')
            ->add('shortDescription')
            ->add('description')
            ->add('projectName')
            ->add('required')
            ->add('investmentRequired', 'choice', array('choices'=>$this->getConfigurationPool()->getContainer()->getParameter('investmentRequired')))
            ->add('projectApplications', 'entity', array('class'=>'ecloreuserBundle:ProjectApplication',
            'property'=>'id', 'multiple'=>true, 'required'=>false))
            ->add('labels', 'entity', array('class' => 'eclore\userBundle\Entity\ProjectLabels'))
            ->add('responsibles', 'entity', array('class' => 'eclore\userBundle\Entity\AssociationMember',
            'multiple'=>true))
            ->add('association', 'entity', array('class' => 'eclore\userBundle\Entity\Association'))
        
        ;
    }

    // Fields to be shown on filter forms
    /*protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('startDate')
            ->add('endDate')
            ->add('location')
            ->add('description')
            ->add('projectName')
            ->add('labels', 'entity', array('class' => 'eclore\userBundle\Entity\ProjectLabels'))
            ->add('responsible', 'entity', array('class' => 'eclore\userBundle\Entity\AssociationMember'))
            ->add('association', 'entity', array('class' => 'eclore\userBundle\Entity\Association'))
        ;
    }*/

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('startDate')
            ->add('endDate')
            ->add('enabled')
            ->add('city')
            ->add('shortDescription')
            ->add('projectName')
            ->add('required')
            ->add('labels', 'entity', array('class' => 'eclore\userBundle\Entity\ProjectLabels'))
            ->add('responsibles', 'entity', array('class' => 'eclore\userBundle\Entity\AssociationMember'))
            ->add('association', 'entity', array('class' => 'eclore\userBundle\Entity\Association'))
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
    $showMapper->add('startDate', 'date')
            ->add('endDate', 'date')
            ->add('enabled')
            ->add('address')
            ->add('city')
            ->add('postcode')
            ->add('country')
            ->add('lat')
            ->add('lng')
            ->add('shortDescription')
            ->add('description')
            ->add('projectName')
            ->add('required')
            ->add('investmentRequired', 'choice', array('choices'=>$this->getConfigurationPool()->getContainer()->getParameter('investmentRequired')))
            ->add('projectApplications', 'entity', array('class'=>'ecloreuserBundle:ProjectApplication',
            'property'=>'id', 'multiple'=>true, 'required'=>false))
            ->add('labels', 'entity', array('class' => 'eclore\userBundle\Entity\ProjectLabels'))
            ->add('responsibles', 'entity', array('class' => 'eclore\userBundle\Entity\AssociationMember',
            'multiple'=>true))
            ->add('association', 'entity', array('class' => 'eclore\userBundle\Entity\Association'))
        
        ;
    }
}
