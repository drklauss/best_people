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
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class SortService
{

    /**
     * Sort array by karma parameter
     * @param array $array
     * @return array
     */
    public static function sortByKarma($array)
    {
        usort($array, function ($a, $b) {
            return $a['karma'] < $b['karma'];
        });
        return $array;
    }

    /**
     * Sort array by Date parameter
     * @param array $array Array Entities with Date param
     * @return array
     */
    public static function sortByDate($array)
    {
        usort($array, function ($a, $b) {
            return $a->getDate()->getTimestamp() < $b->getDate()->getTimestamp();
        });
        return $array;
    }

}