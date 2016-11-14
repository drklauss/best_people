<?php

namespace AppBundle\Controller;

use AppBundle\Utils\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RenderPagesController extends UserController
{

    /**
     * @Route("/register", name="registerPage")
     */
    public function renderRegisterPageAction()
    {
        // todo if user is authorized -> offer him to logout first
        return $this->render('registerPage/register.html.twig');
    }

    /**
     * @Route("/login", name="loginPage")
     */
    public function renderLoginPageAction()
    {
        return $this->render('loginPage/login.html.twig');
    }

    /**
     * @Route("/", name="homePage")
     */
    public function renderIndexPageAction()
    {
        $sessionService = new SessionService();
        $sessionData = $sessionService->getSessionData();
        return $this->render('homePage/home.html.twig',
            array(
                'sessionData' => $sessionData
            )
        );
    }

    /**
     * @Route("/user/{userId}", name="personalPage", requirements={"userId": "\d+"})
     * @param int $userId
     * @return Response
     */
    public function renderPersonalPageAction($userId)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:Users')->find($userId);
        $hasUser = false;
        $sessionService = new SessionService();
        $sessionData = $sessionService->getSessionData();
        $authUserId = $sessionData['userData']['id'];
        $userData = array();
        if ($user) {
            $hasUser = true;
            $this->setVotesHistory($user, $authUserId);
            $this->setMessagesHistory($user);
            $userData = array(
                'id' => $user->getId(),
                'nickname' => $user->getNickname(),
                'karma' => $this->_karma,
                'image' => $user->getWebPath(),
                'isGoodVote' => $this->_isGoodVote

            );
        }
        return $this->render('userPage/user.html.twig',
            array(
                'hasUser' => $hasUser,
                'sessionData' => $sessionData,
                'discoverUserData' => $userData,
                'votesHistory' => $this->_votesHistory,
                'messages' => $this->_messages
            )
        );
    }

    /**
     * @Route("/profile", name="profilePage")
     * @return Response
     */
    public function renderProfileAction()
    {

        $sessionService = new SessionService();
        $sessionData = $sessionService->getSessionData();
        if ($sessionData['isLogin']) {

            return $this->render('profilePage/profile.html.twig',
                array(
                    'sessionData' => $sessionData,
//                    'image' =>
                )
            );
        } else {
            return $this->redirectToRoute('homePage');
        }


    }

}
