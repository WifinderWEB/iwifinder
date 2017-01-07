<?php

namespace Shop\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', TextType::class, array('label' => 'Страна'))
            ->add('region', TextType::class, array('label' => 'Регион'))
            ->add('city', TextType::class, array('label' => 'Населенный пункт'))
            ->add('street', TextType::class, array('label' => 'Улица'))
            ->add('house', TextType::class, array('label' => 'Дом'))
            ->add('room', TextType::class, array('label' => 'Квартира'))
            ->add('postcode', TextType::class, array('label' => 'Почтовый индекс'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Order'),
            'data_class' => 'Shop\OrderBundle\Entity\Order'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'shop_orderbundle_order';
    }
}
