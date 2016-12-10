<?php

namespace Shop\OrderBundle\Form;

use Shop\ApiBundle\Form\GoodsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
//            ->add('id')
//            ->add('lastName', TextType::class, array('label' => 'Фамилия'))
//            ->add('middleName', TextType::class, array('label' => 'Отчество'))
//            ->add('firstName', TextType::class, array('label' => 'Имя'))
            ->add('country', TextType::class, array('label' => 'Страна'))
            ->add('region', TextType::class, array('label' => 'Регион'))
            ->add('city', TextType::class, array('label' => 'Населенный пункт'))
            ->add('street', TextType::class, array('label' => 'Улица'))
            ->add('house', TextType::class, array('label' => 'Дом'))
            ->add('room', TextType::class, array('label' => 'Квартира'))
            ->add('postcode', TextType::class, array('label' => 'Почтовый индекс'))
//            ->add('email', HiddenType::class)
//            ->add('phone', HiddenType::class)
            ->add('discount', HiddenType::class)
            ->add('itog', HiddenType::class)
//            ->add('goods', 'collection', array(
//                'allow_add' => true,
//                'allow_delete' => true,
//                'type' => new GoodsType()
//            ))
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
