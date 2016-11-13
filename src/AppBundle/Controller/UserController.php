<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 09.11.16
 * Time: 22:25
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Messages;
use AppBundle\Entity\Users;
use AppBundle\Entity\Votes;
use AppBundle\Utils\SessionService;
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
        $messagesArray = $user->getToUserMessages()->getValues();
        $votesArray = $user->getToUserVotes()->getValues();
        $this->setVotesHistory($votesArray, $authUserId);
        $this->setMessagesHistory($messagesArray);

    }

    /**
     * Set Message into Messages Array for selected user
     * @param array Messages $messagesArray
     */
    private function setMessagesHistory($messagesArray)
    {
        foreach ($messagesArray as $message){
            /**
             * @var Messages $message
             * @var Users $fromUser
             */
            $this->_messages[] = array(
                'date' => $message->getDate(),
                'fromUser' => $message->getFromUser()->getNickname(),
                'fromUserId' => $message->getFromUser()->getId(),
                'messageBody' => $message->getBody(),
            );
        }
    }

    /**
     * Set Vote into Votes History array for selected user
     * Also set isGoodVote for logged user
     * @param array Votes $votesArray
     * @param int $authUserId
     */
    private function setVotesHistory($votesArray, $authUserId)
    {
        foreach ($votesArray as $vote) {
            /**
             * @var Votes $vote
             * @var Users $fromUser
             */

            $this->_votesHistory[] = array(
                'date' => $vote->getDate(),
                'fromUser' => $vote->getFromUser()->getNickname(),
                'fromUserId' => $vote->getFromUser()->getId(),
                'isGoodVote' => $vote->getIsGoodVote()
            );
            if ($authUserId == $vote->getFromUser()->getId()) {
                $this->_isGoodVote = $vote->getIsGoodVote();
                $this->_isVoted = true;
            }
            $vote->getIsGoodVote() ? $this->_karma++ : $this->_karma--;
        }

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