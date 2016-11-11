<?php

namespace AppBundle\Controller;

use AppBundle\Utils\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RenderPagesController extends BaseController
{
    /**
     * @Route("/", name="homePage")
     */
    public function renderIndexPageAction()
    {
        // replace this example code with whatever you need
        return $this->render('homePage/home.html.twig');
    }

    /**
     * Render authRegisterBlock block
     * @param $page
     * @return Response
     */
    public function renderLoginRegisterBlockAction($page = null)

    {
        switch ($page) {
            case 'register':
                return $this->render('commonBlocks/registerBlock.html.twig');
                break;
            case 'login':
                return $this->render('commonBlocks/loginBlock.html.twig');
                break;
            default:
                $session = new SessionService();
                $sessionData = $session->getSessionData();
                return $this->render('commonBlocks/indexBlock.html.twig', array('sessionData' => $sessionData));
                break;
        }
    }

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
     * @Route("/user/{userId}", name="personalPage", requirements={"userId": "\d+"})
     * @param $userId int
     * @return Response
     */
    public function renderPersonalPageAction($userId)
    {
        $sessionService = new SessionService();
        $sessionData = $sessionService->getSessionData();
        $authUserId = $sessionData['userData']['id'];
        $user = $this->getDoctrine()->getRepository('AppBundle:Users')->find($userId);
        $userData = array('hasUser' => false);
        if ($user) {
            $this->getVotesAndKarma($user, $authUserId);
            $userData = array(
                'id' => $user->getId(),
                'nickname' => $user->getNickname(),
                'karma' => $this->_karma,
                'image' => $user->getWebPath(),
                'hasUser' => true,
                'isVoted' => $this->_isVoted,
                'isGoodVote' => $this->_isGoodVote

            );
        }
        return $this->render('userPage/user.html.twig',
            array(
                'sessionData' => $sessionData,
                'discoverUserData' => $userData
            )
        );
    }
}
