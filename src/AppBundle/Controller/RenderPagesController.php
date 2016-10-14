<?php

namespace AppBundle\Controller;

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
     * @return Response
     */
    public function renderAuthRegisterBlockAction()
    {
        $user = array(
            'name' => 'Alexey',
            'photo' => 'url',
            'carma' => 900
        );
        // replace this example code with whatever you need
        return $this->render('AppBundle:Auth:authRegisterBlock.html.twig', array(
            'registered' => true,
            'user' => $user
        ));
    }

    /**
     * @Route("/register", name="registerpage")
     */
    public function renderRegisterPageAction()
    {
        return $this->render('register.html.twig');
    }
}
