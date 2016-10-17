<?php

namespace PiZone\ContentBundle\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Response;

class PiZoneView{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct($container) {
        $this->container = $container;
    }

    public function ToFormat($_format, $result)
    {
        if ($_format == 'yml') {
            $engine = $this->container->get('templating');
            $tmpl = $engine->render('PiZoneContentBundle:Main:index.yml.twig', array(
                'entity' => Yaml::dump($result)
            ));
            return new Response($tmpl);
        }

        return new JsonResponse($result);
    }
}
