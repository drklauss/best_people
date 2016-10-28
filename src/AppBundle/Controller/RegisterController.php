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
    public function registerUser(){
        $user = $this->createFromRequest();
        if($this->validateUser($user)) {
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
    private function createFromRequest() {
        $request = Request::createFromGlobals();
        $post = $request->request;
        $user = new Users();
        $user->setNickname($post->get('nickname'));
        $user->setPassword($post->get('password'));
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
        $isError = false;
        $validator = $this->get('validator');
        /**
         * @var ConstraintViolationList $validateResult
         */
        $validateResult = $validator->validate($user);
        $isError = count($validateResult) > 0;
        // here will be captcha validation
//        if (!$this->isGoodCaptcha($this->_captcha)) {
//            $this->addError('Captcha wrong', 'captcha');
//            $isError = true;
//        }
//

        foreach ($validateResult as $error){
            $this->addError($error->getMessage());
        }

        return !$isError;
    }

    /**
     * Get salt password
     * @param $password
     * @return string $password
     */
    private function saltPassword($password) {
        return md5(self::SALT.$password.$password);
    }
}