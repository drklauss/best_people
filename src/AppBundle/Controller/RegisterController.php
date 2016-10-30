<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

class RegisterController extends BaseController
{

    const SALT = 'bestPeople';

    /**
     * @Route("/register/user")
     */
    public function registerUser()
    {
        $user = $this->createFromRequest();
        if ($this->validateUser($user)) {
            // saves user
            $user->setPassword($this->saltPassword($user->getPassword()));
            $this->save($user);
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
        $isFemale = $post->get('isFemale') ? 1 : 0;
        $user->setIsFemale($isFemale);
        return $user;

    }

    /**
     * Validate a user
     * @param object $user user
     * @return bool
     */
    protected function validateUser($user)
    {
        $validator = $this->get('validator');
        /**
         * @var ConstraintViolationList $validateErrors
         */
        $validateErrors = $validator->validate($user);
        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
        $isBadCaptcha = !$this->isGoodCaptcha($this->_captcha);
        $userExist = $usersRepository->findOneBy(
            array('nickname' => $user->getNickname())
        );
        if ($userExist) {
            $this->addError('This user already exist. Try another nickname!');
        }
        if ($isBadCaptcha) {
            $this->addError('Oooh, seems your captcha wrong, are you a robot?');
        }
        foreach ($validateErrors as $error) {
            $this->addError($error->getMessage());
        }

        $isError = (count($validateErrors) > 0) || $userExist || $isBadCaptcha;
        return !$isError;
    }

    /**
     * Get salt password
     * @param $password
     * @return string $password
     */
    private function saltPassword($password)
    {
        return md5(self::SALT . $password . $password);
    }
}