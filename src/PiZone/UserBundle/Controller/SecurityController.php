<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PiZone\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends FOSRestController
{
    protected $requestInvalid = false;

    protected $requestInvalidMessages = array();

    public function loginAction()
    {
        $view = new View();

        $username = $this->getParameter('username');
        $password = $this->getParameter('password');

        // Validating parameters
        if($this->requestInvalid){
            $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
            $view->setData(array(
                'result' => 'error',
                'message' => $this->requestInvalidMessages
            ));
            return $this->handleView($view);
        }

        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);
        if (!$user) {
            $view->setStatusCode(Codes::HTTP_FORBIDDEN);
            $view->setData(array(
                'result' => 'error',
                'message' => 'You do not have the necessary permissions'
            ));
            return $this->handleView($view);
        }
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $encoded_pass = $encoder->encodePassword($password, $user->getSalt());

        if ($user->getPassword() != $encoded_pass) {
            $view->setStatusCode(Codes::HTTP_FORBIDDEN);
            $view->setData(array(
                'result' => 'error',
                'message' => 'You do not have the necessary permissions'
            ));
            return $this->handleView($view);
        }

        $token = new UsernamePasswordToken($user, null, "main", $user->getRoles());
        $this->get("security.context")->setToken($token); //now the user is logged in
        //now dispatch the login event
        $request = $this->get("request");
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        $view->setData(array(
            'result' => 'ok'
        ));
        return $this->handleView($view);
    }

    public function logoutAction()
    {
        $view = new View();
        try {
            $this->get("request")->getSession()->invalidate();
            $this->get("security.context")->setToken(null);
            $view->setData(array(
                'result' => 'ok'
            ));
        } catch (\Exception $e) {
            $view->setStatusCode(Codes::HTTP_INTERNAL_SERVER_ERROR);
            $view->setData(array(
                'result' => 'error',
                'message' => $e->getMessage()
            ));
        }
        return $this->handleView($view);
    }

    protected function getParameter($key, $optional = false, $message = null, $default = null){
        $parameter = $this->get('request')->get($key, $default);

        if (!$optional && is_null($parameter)) {
            if (is_null($message)) {
                $message = $key . ' parameter is required';
            }
            $this->addRequestInvalidMessage($key, $message);
        }

        return $parameter;
    }

    protected function addRequestInvalidMessage($key, $message){
        $this->requestInvalid = true;
        if ($message != '')
            $this->requestInvalidMessages[$key] = $message;
    }
}
