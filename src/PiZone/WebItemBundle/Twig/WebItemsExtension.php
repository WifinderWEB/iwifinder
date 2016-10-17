<?php

namespace PiZone\WebItemBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class WebItemsExtension extends \Twig_Extension{


    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public function __construct(RegistryInterface $doctrine){
        $this->doctrine = $doctrine;
    }
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('web_item', array($this, 'webItemFilter'), array('is_safe' => array('all'))),
        );
    }

    public function webItemFilter($alias)
    {
        $r = $this->doctrine->getRepository('PiZone\WebItemBundle\Entity\WebItem');
        $webItem = $r->GetItemByAlias($alias);
        if ($webItem != null){
            return $webItem->getContent();
        }

        return $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'webitems_extension';
    }
}