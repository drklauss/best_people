<?php

namespace AppBundle\Controller;

use AppBundle\Utils\SessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RenderPagesController extends Controller
{
    /**
     * @Route("/", name="homePage")
     */
    public function renderIndexPageAction()
    {
        // replace this example code with whatever you need
        return $this->render('index.html.twig');
    }

    /**
     * Render authRegisterBlock block
     * @param $page
     * @return Response
     */
    public function renderAuthRegisterBlockAction($page)
    {
        $session = new SessionService();
        $sessionData = $session->getSessionData();
        return $this->render('AppBundle:Auth:authRegisterBlock.html.twig', array(
            'sessionData' => $sessionData,
            'page' => $page
        ));
    }

    /**
     * @Route("/register", name="registerPage")
     */
    public function renderRegisterPageAction()
    {
        // todo if user is authorized -> offer him to logout first
        return $this->render('register.html.twig');
    }

    /**
     * @Route("/login", name="loginPage")
     */
    public function renderLoginPageAction()
    {
        return $this->render('login.html.twig');
    }

    /**
     * @Route("/user/{userId}", name="lk", requirements={"userId": "\d+"})
     * @param $userId int
     * @return Response
     */
    public function renderLKAction($userId)
    {
        return $this->render('user.html.twig', array('userId' => $userId));
    }
}
