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
            ->add('email')
            ->add('young')
            ->add('instM')
            ->add('assoM')
            ->add('quality')
            ->add('enabled')
            ->add('locked')
            ->add('expired')
            ->add('albums')   
            ->add('roles', 'choice', array('choices'=>$roles,'multiple'=>true ))
            ->add('credentialsExpired')
            ->add('lastName')
            ->add('firstName') 
            ->add('birthDate', 'date') 
            ->add('contacts', 'entity', array('class'=>'ecloreuserBundle:User',
            'multiple'=>true, 'required' =>false))            
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
            ->add('username')
            ->add('usernameCanonical')
            ->add('young', 'entity', array('class'=>'ecloreuserBundle:Young',
            'required' => false ) )
            ->add('instM', 'entity', array('class'=>'ecloreuserBundle:InstitutionMember',
            'required' => false ) )
            ->add('assoM', 'entity', array('class'=>'ecloreuserBundle:AssociationMember',
            'required' => false ) )
            ->add('email')
            ->add('quality')
            ->add('emailCanonical')
            ->add('enabled', 'checkbox', array('required' => false ))
            ->add('locked', 'checkbox', array('required' => false ))
            ->add('expired', 'checkbox', array('required' => false ))
            ->add('roles', 'choice', array('choices'=>$roles,'multiple'=>true ))
            ->add('credentialsExpired', 'checkbox', array('required' => false ))
            ->add('credentialsExpireAt', 'date', array( 'widget' => 'single_text', 'required'=>false))
            ->add('birthDate', 'date', array( 'widget' => 'choice', 'years'=>range((int)date("Y")-100, (int)date("Y"))))
            ->add('lastName')
            ->add('firstName')
            ->add('mobile')
            ->add('headshot', new ImageType(), $fileFieldOptions)
            ->add('lastSeenDate', 'date')
            ->add('contacts', 'entity', array('class'=>'ecloreuserBundle:User',
            'multiple'=>true, 'required' =>false))
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
