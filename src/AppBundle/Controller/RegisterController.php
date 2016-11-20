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
     * Need to prevent double doctrine call during user edit action
     * @var Users $_loggedUser
     */
    private $_loggedUser;


    /**
     * @var bool
     */
    private $_saveNewPassword = false;

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
            // check if need to save new password
            if ($this->_saveNewPassword) {
                $this->_loggedUser->setPassword($this->saltPassword($user['newPassword']));
            }
            // saves user
            $this->save($this->_loggedUser);
            // set data to session
            $this->_sessionService->setUserData($this->_loggedUser);
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
        $user['nickname'] = $post->get('nickname');
        $user['currentPassword'] = $post->get('currentPassword');
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
        $validator = $this->get('validator');
        /**
         * @var ConstraintViolationList $validateErrors
         */
        $loggedUserId = $this->_sessionService->getUserId();
        $this->_loggedUser = $this->getDoctrine()->getRepository('AppBundle:Users')->find($loggedUserId);
        if ($this->saltPassword($user['currentPassword']) !== $this->_loggedUser->getPassword()) {
            $this->addError('Wrong current password');
        }
        if (!empty($user['newPassword']) && !empty($user['confirmNewPassword'])) {
            if ($user['newPassword'] === $user['confirmNewPassword']) {
                $this->_saveNewPassword = true;
                $this->_loggedUser->setPassword($user['newPassword']);
            } else {
                $this->addError('New password does not match confirm password');
            }
        }


        $this->_loggedUser->setNickname($user['nickname']);
        $this->_loggedUser->setImage($user['image']);
        $validateErrors = $validator->validate($this->_loggedUser);
        foreach ($validateErrors as $error) {
            $this->addError($error->getMessage());
        }
        return !$this->hasErrors();
    }

    /**
     * Check passwords matching
     * @param $user
     * @return bool
     */
    private function isMatchPasswords($user)
    {
        $flag = false;
        if (!empty($user['newPassword']) && !empty($user['confirmNewPassword'])) {
            dump('here');
            exit;
            $flag = $user['newPassword'] === $user['confirmNewPassword'] ? true : false;
        }
        return $flag;
    }

    /**
     * Create new User Entity
     * @return Users
     */
    private
    function createRegisterUserFromRequest()
    {
        $request = Request::createFromGlobals();
        $post = $request->request;
        $user = new Users();
        $user->setNickname($post->get('nickname'));
        $user->setPassword($post->get('password'));
        $image = $request->files->get('avatar');
        $user->setImage($image);
        $this->_captcha = $post->get('g-recaptcha-response');
        $isFemale = ($post->get('isFemale') === 'true') ? true : false;
        $user->setIsFemale($isFemale);
        return $user;

    }


    /**
     * Validate a user
     * @param Users $user
     * @return bool
     */
    private
    function validateRegisterUser(Users $user)
    {
        $validator = $this->get('validator');
        /**
         * @var ConstraintViolationList $validateErrors
         */
        $validateErrors = $validator->validate($user);
        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
        $userExist = $usersRepository->findOneBy(
            array('nickname' => $user->getNickname())
        );
        if ($userExist) {
            $this->addError('This user already exist. Try another nickname!');
        }
        if (!$this->isGoodCaptcha($this->_captcha)) {
            $this->addError('Oooh, seems your captcha wrong, are you a robot?');
        }
        foreach ($validateErrors as $error) {
            $this->addError($error->getMessage());
        }
        return !$this->hasErrors();
    }

}