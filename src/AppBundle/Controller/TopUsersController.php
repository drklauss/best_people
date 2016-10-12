<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TopUsersController extends Controller
{

    /**
     * Shows top-15 users list
     */
    public function showTopUsersListAction()
    {
        $users = array(
            array(
                'name' => 'Maria',
                'carma' => 800,

            ),
            array(
                'name' => 'Andrey',
                'carma' => 600
            ),
            array(
                'name' => 'Dima',
                'carma' => 700
            )
        );

        $this->arraySort($users);


        return $this->render('AppBundle:TopUsers:topUsersList.html.twig', array(
            'registered' => true,
            'users' => $users
        ));
    }

    private function arraySort($array)
    {
        usort($array, function ($a, $b) {
            return $a['carma'] - $b['carma'];
        });
    }
}