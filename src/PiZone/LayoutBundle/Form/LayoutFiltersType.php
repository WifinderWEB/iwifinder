<?php

namespace PiZone\LayoutBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;

class LayoutFiltersType extends AbstractType
{
    protected $securityContext;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formOptions = $this->getFormOption('name', array(  'required' => false,  'label' => 'Название',));
        $builder->add('name', 'text', $formOptions);

        $formOptions = $this->getFormOption('description', array(  'required' => false,  'label' => 'Описание'));
        $builder->add('description', 'text', $formOptions);

        $formOptions = $this->getFormOption('is_active', array(  'required' => false,  'choices' =>   array(    0 => 'Нет',    1 => 'Да',  ),  'empty_value' => 'Да или Нет',  'label' => 'Активен?'));
        $builder->add('is_active', 'choice', $formOptions);
    }

    protected function getFormOption($name, array $formOptions)
    {
        return $formOptions;
    }

    public function getName()
    {
        return 'filters_layout';
    }

    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;
    }

}
