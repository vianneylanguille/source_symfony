<?php

namespace eclore\userBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ImageAdmin extends Admin
{

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('ext')
            ->add('file', 'entity', array('class'=>'ecloreuserBundle:Image', 'template' => 'ecloreuserBundle:Admin:list_image.html.twig'))
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
    {
        $image = $this->getSubject();
        $fileFieldOptions = array();
        if ($image && ($webPath = $image->getWebPath())) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request')->getBasePath().'/'.$webPath;
            $fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
            }
        $formMapper
            ->add('file', 'file', $fileFieldOptions)
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('ext')
            ->add('file', 'entity', array('class'=>'ecloreuserBundle:Image','template' => 'ecloreuserBundle:Admin:show_image.html.twig'))
        ;
    }
}
