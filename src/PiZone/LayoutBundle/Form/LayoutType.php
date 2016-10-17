<?php

namespace PiZone\LayoutBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LayoutType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Название'))
            ->add('description', 'textarea', array('label' => 'Описание'))
            ->add('file', 'text', array('label' => 'Файл'))
            ->add('is_active', 'checkbox', array('label' => 'Активен?'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Layout'),
            'data_class' => 'PiZone\LayoutBundle\Entity\Layout'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pizone_layoutbundle_layout';
    }
}
