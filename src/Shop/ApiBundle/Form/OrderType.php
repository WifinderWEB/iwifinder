<?php

namespace Shop\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
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
            ->add('id')
            ->add('lastName')
            ->add('middleName')
            ->add('firstName')
            ->add('country')
            ->add('region')
            ->add('city')
            ->add('street')
            ->add('house')
            ->add('room')
            ->add('postcode')
            ->add('email')
            ->add('phone')
            ->add('discount')
            ->add('itog')
            ->add('goods', 'collection', array(
                'allow_add' => true,
                'allow_delete' => true,
                'type' => new GoodsType()
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Order'),
            'data_class' => 'Shop\ApiBundle\Entity\Order'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'shop_apibundle_order';
    }
}
