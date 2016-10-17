<?php

namespace PiZone\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentMetaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('meta_title', 'text', array(
                'label' => 'Meta Title',
                'translation_domain' => 'Admin',
                'required' => false
            ))
            ->add('meta_keywords', 'text', array(
                'label' => 'Meta Keywords',
                'translation_domain' => 'Admin',
                'required' => false
            ))
            ->add('meta_description', 'textarea', array(
                'label' => 'Meta Description',
                'translation_domain' => 'Admin',
                'required' => false
            ))
            ->add('more_scripts', 'textarea', array(
                'label' => 'More scripts',
                'translation_domain' => 'Admin',
                'required' => false
            ))
            ->add('in_site_map', 'checkbox', array(
                'required' => false,
                'label' => 'In site map?',
                'translation_domain' => 'Admin'
            ))
            ->add('in_robots', 'checkbox', array(
                'required' => false,
                'label' => 'In robots?',
                'translation_domain' => 'Admin'
            ))
            ->add('in_breadcrumbs', 'checkbox', array(
                'required' => false,
                'label' => 'In breadcrumbs?',
                'translation_domain' => 'Admin'
            ))
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PiZone\ContentBundle\Entity\ContentMeta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pizone_contentbundle_contentmeta';
    }
}

