<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Utils\HistoryService;
use AppBundle\Utils\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\SortService;

class RenderPagesController extends BaseController
{

    /**
     * @Route("/register", name="registerPage")
     */
    public function renderRegisterPageAction()

    {
        $sessionData = $this->getSessionServiceData();
        if ($sessionData['isLogin']) {
            return $this->redirectToRoute('homePage');
        } else {
            return $this->render('registerPage/register.html.twig');
        }
    }

    /**
     * @Route("/login", name="loginPage")
     */
    public function renderLoginPageAction()
    {
        $sessionData = $this->getSessionServiceData();
        if ($sessionData['isLogin']) {
            return $this->redirectToRoute('homePage');
        } else {
            return $this->render('loginPage/login.html.twig');
        }
    }

    /**
     * @Route("/", name="homePage")
     */
    public function renderIndexPageAction()
    {
        $sessionData = $this->getSessionServiceData();
        return $this->render('homePage/home.html.twig',
            array(
                'sessionData' => $sessionData
            )
        );
    }

    /**
     * @Route("/user/{userId}", name="personalPage", requirements={"userId": "\d+"})
     * @ParamConverter("user", options={"id" = "userId"})
     * @param Users $user
     * @return Response
     */
    public function renderPersonalPageAction(Users $user)
    {
        $hasUser = false;
        $sessionData = $this->getSessionServiceData();
        $authUserId = $sessionData['userData']['id'];
        HistoryService::setVotesHistory($user, $authUserId);
        HistoryService::setMessagesHistory($user);
        $userData = array(
            'id' => $user->getId(),
            'nickname' => $user->getNickname(),
            'karma' => HistoryService::$_karma,
            'image' => $user->getWebPath(),
            'isGoodVote' => HistoryService::$_isGoodVote

        );
        return $this->render('userPage/user.html.twig',
            array(
                'sessionData' => $sessionData,
                'discoverUserData' => $userData,
                'votesHistory' => HistoryService::$_votesHistory,
                'messages' => HistoryService::$_messages
            )
        );
    }

    /**
     * @Route("/profile", name="profilePage")
     * @return Response
     */
    public function renderProfileAction()
    {

        $sessionData = $this->getSessionServiceData();
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

    /**
     * @Route("/get_top_list")
     * Shows top-15 users list
     * @return Response
     */
    public function showTopUsersListAction()
    {
        $sessionData = $this->getSessionServiceData();
        $authUserId = $sessionData['userData']['id'];
        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
        $usersListData = array();
        /**
         * @var Users $user
         */
        foreach ($usersRepository->findAll() as $user) {

            HistoryService::setVotesHistory($user, $authUserId);
            $usersListData[] = array(
                'id' => $user->getId(),
                'nickname' => $user->getNickname(),
                'karma' => HistoryService::$_karma,
                'image' => $user->getWebPath(),
                'isGoodVote' => HistoryService::$_isGoodVote

            );

        }
        $sortedUsersListData = SortService::sortByKarma($usersListData);
        return $this->render('homePage/topUsersList.html.twig',
            array(
                'usersList' => $sortedUsersListData,
                'authUserId' => $authUserId
            )
        );
    }
}
