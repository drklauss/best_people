<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 06.11.16
 * Time: 15:30
 */

namespace AppBundle\Controller;

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
        $sessionService = new SessionService();
        $sessionData = $sessionService->getSessionData();
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
                if ($this->canVote($fromUserId, $toUserId)) {
                    $this->addVote($fromUserId, $toUserId, $isGoodVote);
                } else {
                    $this->addError('You already vote for this user!', 'voteError');
                }
            } else {
                $this->addError('You should login before vote!', 'voteError');
            }
        }

        return $this->getErrorsJsonResult();
    }

    /**
     * Checks if user can vote or not
     * @param $fromUserId
     * @param $toUserId
     * @return bool
     */
    private function canVote($fromUserId, $toUserId)
    {
        $votesRepository = $this->getDoctrine()->getRepository('AppBundle:Votes');
        $hasVote = $votesRepository->findBy(array(
            'fromUserId' => $fromUserId,
            'toUserId' => $toUserId
        ));
        return !$hasVote;
    }

    /**
     * Add vote into Votes
     * @param $fromUserId
     * @param $toUserId
     * @param $isGoodVote
     */
    private function addVote($fromUserId, $toUserId, $isGoodVote)
    {
        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
        $fromUser = $usersRepository->find($fromUserId);
        $toUser = $usersRepository->find($toUserId);
        if ($fromUser && $toUser) {
            $vote = new Votes();
            $vote->setIsGoodVote($isGoodVote);
            $vote->setFromUserId($fromUser);
            $vote->setToUserId($toUser);
            $em = $this->getDoctrine()->getManager();
            $em->persist($vote);
            $em->flush();
        }
    }

    /**
     * Get User karma by its ID
     * @param $userId
     * @return JsonResponse
     * @Route("/recalculate/{userId}", requirements={"userId" = "\d+"})
     */
    public function getUserKarmaAction($userId)
    {

        $votesRepository = $this->getDoctrine()->getRepository('AppBundle:Votes');

        $votesArray = $votesRepository->findBy(
            array(
                'toUserId' => $userId
            )
        );
        $karma = 0;
        foreach ($votesArray as $vote) {
            /**
             * @var $vote Votes
             */
            $vote->getIsGoodVote() ? $karma++ : $karma--;
        }
        $response = new JsonResponse();
        $response->setData(
            array(
                'karma' => $karma
            )
        );
        return $response;
    }

}