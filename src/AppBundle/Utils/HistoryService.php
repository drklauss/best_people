<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 30.10.16
 * Time: 21:29
 */

namespace AppBundle\Utils;


use AppBundle\Entity\Messages;
use AppBundle\Entity\Users;
use AppBundle\Entity\Votes;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class HistoryService
{
    /**
     * @var array
     */
    public static $_votesHistory;

    /**
     * @var bool
     */
    public static $_isGoodVote = null;
    /**
     * @var int
     */
    public static $_karma = 0;

    /**
     * @var array
     */
    public static $_messages;

    /**
     * Set Message into Messages Array for selected user
     * @param object Users $user
     */
    public static function setMessagesHistory(Users $user)
    {
        $messagesArray = $user->getToUserMessages()->getValues();
        $sortedMessagesArray = SortService::sortByDate($messagesArray);
        foreach ($sortedMessagesArray as $message) {
            /**
             * @var Messages $message
             * @var Users $fromUser
             */
            HistoryService::$_messages[] = array(
                'date' => $message->getDate()->getTimestamp(),
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
    public static function setVotesHistory(Users $user, $authUserId)
    {
        // reset to default values
        HistoryService::$_karma = 0;
        HistoryService::$_isGoodVote = null;
        $votesArray = $user->getToUserVotes()->getValues();
        $sortedVotesArray = SortService::sortByDate($votesArray);
        foreach ($sortedVotesArray as $vote) {
            /**
             * @var Votes $vote
             * @var Users $fromUser
             */

            HistoryService::$_votesHistory[] = array(
                'date' => $vote->getDate(),
                'fromUser' => $vote->getFromUser()->getNickname(),
                'fromUserId' => $vote->getFromUser()->getId(),
                'isGoodVote' => $vote->getIsGoodVote()
            );
            if ($authUserId == $vote->getFromUser()->getId()) {
                HistoryService::$_isGoodVote = $vote->getIsGoodVote();
            }
            $vote->getIsGoodVote() ? HistoryService::$_karma++ : HistoryService::$_karma--;
        }

    }

}