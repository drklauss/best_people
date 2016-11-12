<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 12.11.16
 * Time: 23:22
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Messages;
use AppBundle\Entity\Users;
use AppBundle\Utils\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessagesController extends BaseController
{
    /**
     * Checks Message for errors
     * @Route("/message/set")
     */
    public function checkMessageAction()
    {
        $postRequest = Request::createFromGlobals()->request;
        $sessionService = new SessionService();
        $toUserId = $postRequest->get('toUserId');
        $fromUserId = $sessionService->getUserId();
        $comment = $postRequest->get('comment');
        $toUser = $this->getDoctrine()->getRepository('AppBundle:Users')->find($toUserId);
        $fromUser = $this->getDoctrine()->getRepository('AppBundle:Users')->find($fromUserId);
        if ($fromUser && $toUser && !empty($comment)) {
            $this->saveMessage($fromUser, $toUser, $comment);
        } else {
            $this->addError('No user or empty comment sent', 'message');
        }
        return $this->getErrorsJsonResult();

    }

    /**
     * @param Users $fromUser
     * @param Users $toUser
     * @param string $comment
     */
    private function saveMessage(Users $fromUser, Users $toUser, $comment)
    {
        $message = new Messages();
        $message->setFromUserId($fromUser);
        $message->setToUserId($toUser);
        $message->setBody($comment);
        $this->save($message);
    }
}