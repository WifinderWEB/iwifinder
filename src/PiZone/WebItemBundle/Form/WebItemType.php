<?php

namespace PiZone\WebItemBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias', 'text', array('label' => 'Псевдоним'))
            ->add('description', 'textarea', array('label' => 'Описание'))
            ->add('content', 'textarea', array('label' => 'Контент'))
            ->add('is_active', 'checkbox', array('label' => 'Активен?'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('WebItem'),
            'data_class' => 'PiZone\WebItemBundle\Entity\WebItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pizone_webitembundle_webitem';
    }
}
