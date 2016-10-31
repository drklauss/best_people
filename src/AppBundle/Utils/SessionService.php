<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 30.10.16
 * Time: 21:29
 */

namespace AppBundle\Utils;


use AppBundle\Entity\Users;
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
        // todo: need to calculate and set user karma
        $this->_session->set('karma', $user->getNickname());
        $userGender = $user->getIsFemale() ? 'Female' : 'Male';
        $this->_session->set('gender', $userGender);

    }

    public function getSessionData()
    {
        $userData = array(
            'id' => $this->_session->get('id'),
            'nickname' => $this->_session->get('nickname'),
            'gender' => $this->_session->get('gender'),
        );
        $this->_sessionData['userData'] = $userData;
        $this->_sessionData['someVar'] = 'testVarrrr1';


        return $this->_sessionData;
    }

}