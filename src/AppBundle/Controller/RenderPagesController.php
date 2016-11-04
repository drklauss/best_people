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
     * @Route("/", name="homepage")
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
     * @Route("/register", name="registerpage")
     */
    public function renderRegisterPageAction()
    {
        // todo if user is authorized -> offer him to logout first
        return $this->render('register.html.twig');
    }

    /**
     * @Route("/login", name="loginpage")
     */
    public function renderLoginPageAction()
    {
        // todo if user is authorized -> offer him to logout first
        return $this->render('login.html.twig');
    }
}
