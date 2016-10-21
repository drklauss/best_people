<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends BaseController
{
    /**
     * @Route("/register/user")
     */
    public function registerUser(){
        $request = Request::createFromGlobals();
        dump($request);
        exit;
        return new Response();
    }

}