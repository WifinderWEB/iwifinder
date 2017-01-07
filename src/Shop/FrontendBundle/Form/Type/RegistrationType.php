<?php

namespace Shop\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('full_name', 'text', array('required' => true, 'label' => 'Ф. И. О.'));
        $builder->add('phone', 'text', array('required' => false, 'label' => 'Телефон'));
//        $builder->remove('username');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'shop_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
