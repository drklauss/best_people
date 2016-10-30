<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

class LoginController extends BaseController
{

    /**
     * @Route("/login/user")
     */
    public function loginUser()
    {
        $user = $this->createFromRequest();
        if ($this->loginTry($user)) {
            // start user session

        };
        return $this->getErrorsJsonResult();
    }

    /**
     * Create new User Entity
     * @return Users
     */
    private function createFromRequest()
    {
        $request = Request::createFromGlobals();
        $post = $request->request;
        $user = new Users();
        $user->setNickname($post->get('nickname'));
        $user->setPassword($post->get('password'));
        $this->_captcha = $post->get('g-recaptcha-response');
        return $user;
    }

    /**
     * Try to login a user
     * @param object $user User
     * @return bool
     */
    protected function loginTry($user)
    {
        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
        $isBadCaptcha = !$this->isGoodCaptcha($this->_captcha);
        $userExist = $usersRepository->findOneBy(
            array(
                'nickname' => $user->getNickname(),
                'password' => $this->saltPassword($user->getPassword())
            )
        );
        if (!$userExist) {
            $this->addError('Wrong nickname or password');
        }
        if ($isBadCaptcha) {
            $this->addError('Oooh, seems your captcha wrong, are you a robot?');
        }

        $isError = !$userExist || $isBadCaptcha;
        return !$isError;
    }

}