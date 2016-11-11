<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 09.11.16
 * Time: 22:25
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Entity\Votes;
use AppBundle\Utils\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends BaseController
{

//    /**
//     * @param $userId
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function showUserInfoAction($userId)
//    {
//        $sessionService = new SessionService();
//        $sessionData = $sessionService->getSessionData();
//        $authUserId = $sessionData['userData']['id'];
//        $user = $this->getDoctrine()->getRepository('AppBundle:Users')->find($userId);
//        $userData = array('hasUser' => false);
//        if ($user) {
//            $this->getVotesAndKarma($user, $authUserId);
//            $userData = array(
//                'id' => $user->getId(),
//                'nickname' => $user->getNickname(),
//                'karma' => $this->_karma,
//                'image' => $user->getWebPath(),
//                'hasUser' => true,
//                'isVoted' => $this->_isVoted,
//                'isGoodVote' => $this->_isGoodVote
//
//            );
//        }
//        return $this->render('AppBundle:User:infoBlock.html.twig',
//            array(
//                'sessionData' => $sessionData,
//                'discoverUserData' => $userData
//            )
//        );
//    }

    /**
     * @param $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showKarmaHistoryAction($userId)
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
        return $this->render('block/infoBlock1.html.twig',
            array(
                'sessionData' => $sessionData,
                'discoverUserData' => $userData
            )
        );
    }

    /**
     * Shows top-15 users list
     * @return Response
     */
    public function showTopUsersListAction()
    {
        $sessionService = new SessionService();
        $sessionData = $sessionService->getSessionData();
        $authUserId = $sessionData['userData']['id'];
        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
        $usersListData = array();
        /**
         * @var $user Users
         */
        foreach ($usersRepository->findAll() as $user) {
            $this->getVotesAndKarma($user, $authUserId);
            $usersListData[] = array(
                'id' => $user->getId(),
                'nickname' => $user->getNickname(),
                'karma' => $this->_karma,
                'image' => $user->getWebPath(),
                'isVoted' => $this->_isVoted,
                'isGoodVote' => $this->_isGoodVote

            );
        }
        $sortedUsersListData = $this->arraySort($usersListData);
        return $this->render('homePage/topUsersList.html.twig',
            array(
                'usersList' => $sortedUsersListData,
                'authUserId' => $authUserId
            )
        );
    }

    /**
     * Sort users by karma parameter
     * @param $array
     * @return array
     */
    private function arraySort($array)
    {
        usort($array, function ($a, $b) {
            return $a['karma'] < $b['karma'];
        });
        return $array;
    }

}