<?php

namespace PiZone\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use PiZone\LayoutBundle\Entity\Repository\LayoutRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => true,
                'label' => 'Title',
                'translation_domain' => 'Admin'
            ))
            ->add('alias', 'text', array(
                'required' => true,
                'label' => 'Alias',
                'translation_domain' => 'Admin'
            ))
            ->add('anons', 'textarea', array(
                'required' => false,
                'label' => 'Anons',
                'translation_domain' => 'Admin'
            ))
            ->add('show_editor_anons', 'checkbox', array(
                'required' => false,
                'label' => 'Show editor anons',
                'translation_domain' => 'Admin'
            ))
            ->add('image_path')
            ->add('image_origin_name')
            ->add('content', 'textarea', array(
                'required' => false,
                'label' => 'Content',
                'translation_domain' => 'Admin'
            ))
            ->add('show_editor_content', 'checkbox', array(
                'required' => false,
                'label' => 'Show editor content',
                'translation_domain' => 'Admin'
            ))
            ->add('big_image_path')
            ->add('big_image_origin_name')
            ->add('is_active', 'checkbox', array(
                'label' => 'Is Active?',
                'translation_domain' => 'Admin'
            ))
            ->add('meta', new ContentMetaType())
            ->add('layout', 'entity', array(
                'placeholder' => 'Choose an option',
                'multiple' => false,
                'em' => 'default',
                'class' => 'PiZoneLayoutBundle:Layout',
                'required' => true,
                'label' => 'Template',
                'translation_domain' => 'Admin',
                'query_builder' => function(LayoutRepository $er) {
                    return $er->getActiveLayouts();
                },
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Сontent'),
            'data_class' => 'PiZone\ContentBundle\Entity\Content'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pizone_contentbundle_content';
    }
}
