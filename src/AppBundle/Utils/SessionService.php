<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 30.10.16
 * Time: 21:29
 */

namespace AppBundle\Utils;


use AppBundle\Entity\Users;
use AppBundle\Entity\Votes;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class SessionService
{
    /**
     * @var array
     */
    protected $_sessionData;

    /**
     * @var Session
     */
    public $_session;

    /**
     * @var Registry
     */
    private $_doctrine;


    public function __construct()
    {
        $this->_session = new Session();
    }

    /**
     * Doctrine DI from BaseController
     * @param $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->_doctrine = $doctrine;
    }

    /**
     * Set User data in Session
     * @param $user Users
     */
    public function setUserData(Users $user)

    {
        $this->_session->set('id', $user->getId());
        $this->_session->set('nickname', $user->getNickname());
        $this->_session->set('avatarLink', $user->getWebPath());
        $this->_session->set('startTime', time());
        $this->_session->set('isLogin', true);
        $this->getUserKarma($user);
        $userGender = $user->getIsFemale() ? 'Female' : 'Male';
        $this->_session->set('gender', $userGender);

    }

    /**
     * Get session Data for user
     * [nickname, id, gender, karma, isLogin]
     * @return array
     */
    public function getSessionData()
    {
        $startSession = $this->_session->get('startTime');
        if ($startSession && (time() - $startSession) > 60) {
            $this->getUserKarma();
        }
        $userData = array(
            'id' => $this->_session->get('id'),
            'nickname' => $this->_session->get('nickname'),
            'avatarLink' => $this->_session->get('avatarLink'),
            'gender' => $this->_session->get('gender'),
            'karma' => $this->_session->get('karma')
        );
        $this->_sessionData['isLogin'] = $this->_session->get('isLogin');
        $this->_sessionData['userData'] = $userData;
        return $this->_sessionData;
    }

    /**
     * Get User karma
     * @param Users|null $user
     */
    private function getUserKarma(Users $user = null)
    {
        if (!$user) {
            $user = $this->_doctrine->getRepository('AppBundle:Users')->find($this->getUserId());
        }
        $karma = 0;
        $votesArray = $user->getToUserVotes()->getValues();
        foreach ($votesArray as $vote) {
            /**
             * @var $vote Votes
             */
            $vote->getIsGoodVote() ? $karma += 1 : $karma -= 1;
        }
        $this->_session->set('karma', $karma);
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->_session->get('id');
    }

}