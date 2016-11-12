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

    /**
     * Calculate user votes and karma
     * @param int $authUserId
     * @param Users $user
     */
    protected function setPersonalData(Users $user, $authUserId)
    {
        // set messages here too
        $this->_messages = array(
            array(
                'name' => 'lila',
                'message' => 'hahaha'
            ),
            array(
                'name' => 'Nick',
                'message' => 'WOW'
            )
        );
        $votesArray = $user->getVotes()->getValues();
        foreach ($votesArray as $vote) {
            /**
             * @var $vote Votes
             */
            $this->setVotesHistory($vote);
            if ($authUserId == $vote->getFromUserId()->getId()) {
                $this->_isGoodVote = $vote->getIsGoodVote();
                $this->_isVoted = true;
            }
            $vote->getIsGoodVote() ? $this->_karma++ : $this->_karma--;
        }
    }

    /**
     * Get Votes History for selected user
     * @param Votes $vote
     */
    private function setVotesHistory(Votes $vote)
    {
        /**
         * @var Users $fromUser
         */
        $fromUser = $this->getDoctrine()->getRepository('AppBundle:Users')->find($vote->getFromUserId());
        $this->_votesHistory[] = array(
            'date' => $vote->getDate(),
            'fromUser' => $fromUser->getNickname(),
            'fromUserId' => $fromUser->getId(),
            'isGoodVote' => $vote->getIsGoodVote()
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
         * @var Users $user
         */
        foreach ($usersRepository->findAll() as $user) {
            $this->setPersonalData($user, $authUserId);
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
     * @param array $array
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