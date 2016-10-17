<?php

namespace Shop\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Shop\ContentBundle\Entity\Content;

class ContentController extends Controller
{
    public function indexAction(Request $request, $alias){

        $em = $this->getDoctrine()->getRepository('PiZoneContentBundle:Content');
        $content = $em->GetContentByAlias($alias);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content with alias = ['.$alias.']');
        }

        return $this->render(
            'ShopFrontendBundle:Content:index.html.twig', array(
                'content' => $content)
        );
    }
    
    public function testAction(Request $request){
        return $this->render(
            'ShopFrontendBundle:Content:test.html.twig', array()
        );
    }
    
    public function getFormAction($alias, $redirect_url){
        $form = $this->get('form_api')->getTemplate($alias);
        $api_url = $this->container->getParameter('form_api');

        return $this->render(
            'ShopFrontendBundle:Content:_getForm.html.twig', array(
                'form' => $form['template'],
                'redirect_url' => $redirect_url,
                'wf_form_js' => $api_url.'getJS/wf.form.js',
                'api_url' => $api_url,
                'alias' => $alias
            )
        );
    }
    
    public function submitFormAction(Request $request){
        
    }

    public function supportAction(){
        if($this->getUser()) {
            if ($this->getUser()->hasRole('DESIGNER_ROLE')) {
                return $this->redirect($this->generateUrl('shop_frontend_content_show', array('alias' => 'designers')));
            }
            if ($this->getUser()->hasRole('PROGRAMMER_ROLE')) {
                return $this->redirect($this->generateUrl('shop_frontend_content_show', array('alias' => 'installers')));
            }
            if ($this->getUser()->hasRole('SERVICE_ROLE')) {
                return $this->redirect($this->generateUrl('shop_frontend_content_show', array('alias' => 'services')));
            }
        }


        $em = $this->getDoctrine()->getRepository('PiZoneContentBundle:Content');
        $content = $em->GetContentByAlias('support');

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content with alias = [support]');
        }

        return $this->render(
            'ShopFrontendBundle:Content:support.html.twig', array(
                'content' => $content
            )
        );
    }
}

