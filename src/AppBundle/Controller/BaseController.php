<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('home/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * Render register block
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
     * Render register block
     * @return Response
     */
    public function logoutAction()
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
}

