<?php

namespace PiZone\MenuBundle\Form\Type\Menu_item;

use Admingenerated\PiZoneMenuBundle\Form\BaseMenu_itemType\EditType as BaseEditType;
use Symfony\Component\Form\FormBuilderInterface;
use PiZone\MenuBundle\Entity\Repository\MenuItemRepository;

/**
 * EditType
 */
class EditType extends BaseEditType
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
