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


class UserController extends BaseController
{

    public function showUserInfoAction($userId)
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
        return $this->render('AppBundle:User:infoBlock.html.twig',
            array(
                'sessionData' => $sessionData,
                'userData' => $userData
            )
        );
    }

}