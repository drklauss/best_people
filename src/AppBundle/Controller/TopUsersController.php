<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Entity\Votes;
use AppBundle\Utils\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Locale\Stub\DateFormat\SecondTransformer;


class TopUsersController extends Controller
{

    /**
     * Shows top-15 users list
     */
    public function showTopUsersListAction()
    {
        $sessionService = new SessionService();
        $sessionData = $sessionService->getSessionData();
        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
        $usersListData = array();
        foreach ($usersRepository->findAll() as $user) {
            $isVoted = false;
            $karma = 0;
            /**
             * @var $user Users
             */
            $votesArray = $user->getVotes()->getValues();
            foreach ($votesArray as $vote) {
                /**
                 * @var $vote Votes
                 */
                if ($sessionData['userData']['id'] == $vote->getFromUserId()->getId()) {
                    $isVoted = true;
                }
                $vote->getIsGoodVote() ? $karma++ : $karma--;
            }
            $usersListData[] = array(
                'id' => $user->getId(),
                'nickname' => $user->getNickname(),
                'karma' => $karma,
                'image' => $user->getWebPath(),
                'isVoted' => $isVoted,

            );
        }
        $this->arraySort($usersListData);
        return $this->render('AppBundle:TopUsers:topUsersList.html.twig', array('usersList' => $usersListData));
    }

    /**
     * Sort users by karma parameter
     * @param $array
     * @return array
     */
    private function arraySort($array)
    {
        usort($array, function ($a, $b) {
            return $a['karma'] - $b['karma'];
        });
    }
}