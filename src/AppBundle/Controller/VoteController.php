<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 06.11.16
 * Time: 15:30
 */

namespace AppBundle\Controller;

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
        $get = $request->query;
        dump($get);
        exit;
        if ($sessionData['isLogin'] == true) {
        } else {
            $this->addError('You should login before vote');
        }
        return $this->getErrorsJsonResult();
    }
}