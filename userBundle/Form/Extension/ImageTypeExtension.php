<?php

namespace eclore\userBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageTypeExtension extends AbstractTypeExtension
{
    /**
     * Retourne le nom du type de champ qui est étendu
     *
     * @return string Le nom du type étendu
     */
    public function getExtendedType()
    {
        return 'file';
    }

    /**
     * Ajoute l'option image_path
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('image_path'));
    }

    /**
     * Passe l'url de l'image à la vue
     *
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('image_path', $options)) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
               $accessor = PropertyAccess::createPropertyAccessor();
               $imageUrl = $accessor->getValue($parentData, $options['image_path']);
            } else {
                $imageUrl = null;
            }

            // définit une variable "image_url" qui sera disponible à l'affichage du champ
            $view->vars['image_url'] = $imageUrl;
        }
    }
}