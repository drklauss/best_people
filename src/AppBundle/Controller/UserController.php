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
use Symfony\Component\Routing\Annotation\Route;


class UserController extends BaseController
{

    /**
     * Set Message into Messages Array for selected user
     * @param object Users $user
     */
    protected function setMessagesHistory(Users $user)
    {
        $messagesArray = $user->getToUserMessages()->getValues();
        foreach ($messagesArray as $message) {
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
     * @param object Users $user
     * @param int $authUserId
     */
    protected function setVotesHistory(Users $user, $authUserId)
    {
        // reset to default values
        $this->_karma = 0;
        $this->_isGoodVote = null;
        $votesArray = $user->getToUserVotes()->getValues();
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
        $sessionData = $this->getSessionServiceData();
        $authUserId = $sessionData['userData']['id'];
        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
        $usersListData = array();
        /**
         * @var Users $user
         */
        foreach ($usersRepository->findAll() as $user) {

            $this->setVotesHistory($user, $authUserId);
            $usersListData[] = array(
                'id' => $user->getId(),
                'nickname' => $user->getNickname(),
                'karma' => $this->_karma,
                'image' => $user->getWebPath(),
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
     * @Route("/update_karma")
     */
    public function updateKarmaAction()
    {
        $sessionServiceData = $this->getSessionServiceData();
        return $this->getJsonResult($sessionServiceData);
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