<?php

namespace eclore\userBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use eclore\userBundle\Form\ImageType;

class UserAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    /*protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('birthDate')
            ->add('lastName')
            ->add('firstName')
            ->add('registrationDate')
            ->add('mobile')
            ->add('location')
            ->add('lastSeenDate')
        ;
    }*/

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {$roles;
     foreach($this->getConfigurationPool()->getContainer()
     ->getParameter('security.role_hierarchy.roles') as $key=>$value)
     {
     $roles[$key]=$key;
     }
        $listMapper
            ->add('username')
            ->add('lastName')
            ->add('firstName')
            ->add('young')
            ->add('instM')
            ->add('assoM')
            ->add('enabled')
            ->add('appliedProjects', 'entity', array('class'=>'ecloreuserBundle:ProjectApplication',
            'multiple'=>true, 'required' =>false))  
            ->add('roles', 'choice', array('choices'=>$roles,'multiple'=>true ))
                       
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {$roles;
     foreach($this->getConfigurationPool()->getContainer()
     ->getParameter('security.role_hierarchy.roles') as $key=>$value)
     {
     $roles[$key]=$key;
     }
     
        $headshot = $this->getSubject()->getHeadshot();
        $fileFieldOptions = array('required'=>false, 'help'=>'Pas de photo');
        if ($headshot && ($webPath = $headshot->getWebPath())) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request')->getBasePath().'/'.$webPath;
            $fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
        }
         
        $formMapper
            ->with('General')
                ->add('username')
                ->add('email')
                ->add('usernameCanonical')
                ->add('emailCanonical')
            ->end()
            ->with('Management')
                ->add('locked', null, array('required' => false))
                ->add('expired', null, array('required' => false))
                ->add('enabled', null, array('required' => false))
                ->add('credentialsExpired', null, array('required' => false))
                ->add('roles', 'choice', array('choices'=>$roles,'multiple'=>true ))
            ->end()
        ->with('Reseau')      
            ->add('appliedProjects', 'entity', array('class'=>'ecloreuserBundle:ProjectApplication',
            'multiple'=>true, 'required' =>false))  
            ->add('young', 'entity', array('class'=>'ecloreuserBundle:Young',
            'required' => false ) )
            ->add('instM', 'entity', array('class'=>'ecloreuserBundle:InstitutionMember',
            'required' => false ) )
            ->add('assoM', 'entity', array('class'=>'ecloreuserBundle:AssociationMember',
            'required' => false ) )
            ->add('quality')

            ->add('birthDate', 'date', array( 'widget' => 'choice', 'years'=>range((int)date("Y")-100, (int)date("Y"))))
            ->add('lastName')
            ->add('firstName')
            ->add('mobile')
            ->add('headshot', new ImageType(), $fileFieldOptions)
            ->add('lastSeenDate', 'date')
            ->add('contacts', 'entity', array('class'=>'ecloreuserBundle:User',
            'multiple'=>true, 'required' =>false))
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('usernameCanonical')
            ->add('appliedProjects', 'entity', array('class'=>'ecloreuserBundle:ProjectApplication',
            'multiple'=>true, 'required' =>false))  
            ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            ->add('quality')
            ->add('salt')
            ->add('password')
            ->add('lastLogin', 'date')
            ->add('locked')
            ->add('expired')
            ->add('expiresAt', 'date')
            ->add('confirmationToken')
            ->add('passwordRequestedAt', 'date')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt', 'date')
            ->add('id')
            ->add('birthDate', 'date')
            ->add('lastName')
            ->add('firstName')
            ->add('registrationDate', 'date')
            ->add('mobile')
            ->add('headshot', 'entity', array('class'=>'ecloreuserBundle:Image',
            'required' =>false, 'template' => 'ecloreuserBundle:Admin:show_headshot.html.twig'))
            ->add('lastSeenDate', 'date')
            ->add('contacts', 'entity', array('class'=>'ecloreuserBundle:User',
            'multiple'=>true, 'required' =>false))
        ;
    }
  
}
