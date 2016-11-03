<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\ImageValidator;
use Symfony\Component\Validator\ConstraintViolationList;

class RegisterController extends BaseController
{

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
        $image = $request->files->get('avatar');
        // replace avatarlink with setImage
        $user->setAvatarLink($image);
        // test FileSystem
        $avatarsDir = __DIR__.'/../../../web/public/assets/images/avatars';
        $fs = new Filesystem();
        if(!$fs->exists($avatarsDir)){
            $fs->mkdir($avatarsDir);
            dump('false');
        } else{
            dump('true');
        }
        exit;
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
    private function validateUser($user)
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