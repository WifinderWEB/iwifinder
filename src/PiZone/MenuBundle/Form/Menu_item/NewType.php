<?php

namespace PiZone\MenuBundle\Form\Type\Menu_item;

use Admingenerated\PiZoneMenuBundle\Form\BaseMenu_itemType\NewType as BaseNewType;
use Symfony\Component\Form\FormBuilderInterface;
use PiZone\MenuBundle\Entity\Repository\MenuItemRepository;

/**
 * NewType
 */
class NewType extends BaseNewType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('parent', 'entity', array(
            'required' => true,
            'em' => 'default',
            'translation_domain' => 'Admin',
            'class' => 'PiZoneMenuBundle:MenuItem',
            'query_builder' => function(MenuItemRepository $er){
                return $er->getListItem();
            },
        ));
    }
}
