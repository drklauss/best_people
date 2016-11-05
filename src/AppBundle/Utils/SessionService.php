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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionService
{
    /**
     * @var
     */
    protected $_sessionData;

    /**
     * @var Session
     */
    public $_session;

    public function __construct()
    {
        $this->_session = new Session();
    }

    /**
     * Set User data in Session
     * @param $user Users
     */
    public function setUserData($user)
    {
        $this->_session->set('id', $user->getId());
        $this->_session->set('nickname', $user->getNickname());
        $this->_session->set('isLogin', true);
        $karma = 0;
        $votesArray = $user->getVotes()->getValues();
        foreach ($votesArray as $vote) {
            /**
             * @var $vote Votes
             */
            $vote->getIsGoodVote() ? $karma += 1 : $karma -= 1;
        }
        $this->_session->set('karma', $karma);
        $userGender = $user->getIsFemale() ? 'Female' : 'Male';
        $this->_session->set('gender', $userGender);

    }

    public function getSessionData()
    {
        $userData = array(
            'id' => $this->_session->get('id'),
            'nickname' => $this->_session->get('nickname'),
            'gender' => $this->_session->get('gender'),
            'karma' => $this->_session->get('karma')
        );
        $this->_sessionData['isLogin'] = $this->_session->get('isLogin');
        $this->_sessionData['userData'] = $userData;
        return $this->_sessionData;
    }

}