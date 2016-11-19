<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 06.11.16
 * Time: 15:30
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Entity\Votes;
use AppBundle\Utils\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends BaseController
{
    /**
     * Allow User with userId to vote if userId isLogin
     * @Route("/vote")
     * @return JsonResponse
     */
    public function voteAction()
    {
        $sessionData = $this->getSessionServiceData();
        $request = Request::createFromGlobals();
        $getRequest = $request->query;
        $fromUserId = $sessionData['userData']['id'];
        $toUserId = $getRequest->get('id');
        $isGoodVote = true;
        if ($getRequest->get('isGoodVote') == 'false') {
            $isGoodVote = false;
        }
        if ($fromUserId == $toUserId) {
            $this->addError('You cannot vote for yourself!', 'voteError');
        } else {
            if ($sessionData['isLogin'] == true) {
                $this->addVote($fromUserId, $toUserId, $isGoodVote);
            } else {
                $this->addError('You should login before vote!', 'voteError');
            }
        }

        return $this->getErrorsJsonResult();
    }

    /**
     * Add vote into Votes
     * @param int $fromUserId
     * @param int $toUserId
     * @param bool $isGoodVote
     */
    private function addVote($fromUserId, $toUserId, $isGoodVote)
    {
        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
        $fromUser = $usersRepository->find($fromUserId);
        $toUser = $usersRepository->find($toUserId);
        $vote = $this->checkVoters($fromUser, $toUser);

        if (!$vote) {
            $vote = new Votes();
            $vote->setIsGoodVote($isGoodVote);
            $vote->setFromUser($fromUser);
            $vote->setToUser($toUser);
            $this->save($vote);
        } else {
            $vote->setIsGoodVote($isGoodVote);
            $this->save($vote);
        }
    }

    /**
     * Check vote one user to another or not
     * @param Users $fromUser
     * @param Users $toUser
     * @return Votes $vote
     */
    private function checkVoters(Users $fromUser, Users $toUser)
    {
        $votesRepository = $this->getDoctrine()->getRepository('AppBundle:Votes');
        $vote = $votesRepository->findOneBy(
            array(
                'fromUser' => $fromUser,
                'toUser' => $toUser
            )
        );
        return $vote;
    }

//    /**
//     * Get User karma by its ID
//     * @param $userId
//     * @return JsonResponse
//     * @Route("/recalculate/{userId}", requirements={"userId" = "\d+"})
//     */
//    public function getUserKarmaAction($userId)
//    {
//
//        $votesRepository = $this->getDoctrine()->getRepository('AppBundle:Votes');
//        $votesArray = $votesRepository->findBy(
//            array(
//                'toUser' => $userId
//            )
//        );
//        $karma = 0;
//        foreach ($votesArray as $vote) {
//            /**
//             * @var $vote Votes
//             */
//            $vote->getIsGoodVote() ? $karma++ : $karma--;
//        }
//        $response = new JsonResponse();
//        $response->setData(
//            array(
//                'karma' => $karma
//            )
//        );
//        return $response;
//    }

}