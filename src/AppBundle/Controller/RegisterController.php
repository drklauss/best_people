<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Utils\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\ImageValidator;
use Symfony\Component\Validator\ConstraintViolationList;


class RegisterController extends BaseController
{


    /**
     * @Route("/register/user")
     * @return JsonResponse
     */
    public function registerUser()
    {
        $user = $this->createRegisterUserFromRequest();
        if ($this->validateRegisterUser($user)) {
            // saves user
            $user->setPassword($this->saltPassword($user->getPassword()));
            $this->save($user);
            // set data to session
            $this->_sessionService->setUserData($user);
        };
        return $this->getErrorsJsonResult();
    }

    /**
     * @Route("/edit/user")
     * @return JsonResponse
     */
    public function editUser()
    {
        $user = $this->createEditUserFromRequest();
        if ($this->validateEditUser($user)) {
            // saves user
            $user->setPassword($this->saltPassword($user->getPassword()));
            $this->save($user);
            // set data to session
            $this->_sessionService->setUserData($user);
        };
        return $this->getErrorsJsonResult();
    }

    /**
     * Generate new User fields
     * @return array
     */
    private function createEditUserFromRequest()
    {
        $user = array();
        $request = Request::createFromGlobals();
        $post = $request->request;
        $user['password'] = $post->get('password');
        $user['newPassword'] = $post->get('newPassword');
        $user['confirmNewPassword'] = $post->get('confirmNewPassword');
        $user['image'] = $request->files->get('avatar');
        return $user;

    }

    /**
     * Validate a user
     * @param array $user
     * @return bool
     */
    private function validateEditUser(array $user)
    {
//        dump($user);
//        exit;
        $validator = $this->get('validator');
        $validateErrors = array();
        $wrongPass = true;
        /**
         * @var ConstraintViolationList $validateErrors
         * @var Users $loggedUser
         */
        $userId = $this->_sessionService->getUserId();
        $loggedUser = $this->getDoctrine()->getRepository('AppBundle:Users')->find($userId);
        if ($this->saltPassword($user['password']) == $loggedUser->getPassword()) {
            $wrongPass = true;
            if (!empty($user['newPassword']) && $user['confirmPassword']) {
                if ($user['newPassword'] !== $user['confirmPassword']) {
                    $this->addError('New Password and Confirm Password do not match!');
                } else {
                    $loggedUser->setPassword($this->saltPassword($user['newPassword']));
                }
            }
            $loggedUser->setImage($user['image']);
            $validateErrors = $validator->validate($user);
            foreach ($validateErrors as $error) {
                $this->addError($error->getMessage());
            }

        } else {
            $this->addError('Is that really you? Wrong password!');
        }
        $isError = (!$validateErrors) ? $wrongPass : count($validateErrors) > 0;
        return !$isError;
    }

    /**
     * Create new User Entity
     * @return Users
     */
    private function createRegisterUserFromRequest()
    {
        $request = Request::createFromGlobals();
        $post = $request->request;
        $user = new Users();
        $user->setNickname($post->get('nickname'));
        $user->setPassword($post->get('password'));
        $image = $request->files->get('avatar');
        $user->setImage($image);
        $this->_captcha = $post->get('g-recaptcha-response');
        $isFemale = ($post->get('isFemale') === 'true') ? 1 : 0;
        $user->setIsFemale($isFemale);
        return $user;

    }


    /**
     * Validate a user
     * @param Users $user
     * @return bool
     */
    private function validateRegisterUser(Users $user)
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

}