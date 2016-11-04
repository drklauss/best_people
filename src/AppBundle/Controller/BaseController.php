<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class BaseController extends Controller
{
    const SALT = 'bestPeople';

    /**
     * Google captcha
     * @var string
     */
    protected $_captcha;

     /**
     * List of errors
     * @var array
     */
    private $_errors;


    function __construct()
    {
        $this->_errors['isError'] = false;
    }

    /**
     * Get salt password
     * @param $password
     * @return string $password
     */
    protected function saltPassword($password)
    {
        return md5(self::SALT . $password . $password);
    }

    /**
     * Add error
     * @param $error
     */
    protected function addError($error){
        $this->_errors['isError'] = true;
        $this->_errors['errors'][] = $error;
    }

    /**
     * Get Json Response
     * @param $data
     * @return JsonResponse
     */
    protected function getJsonResult($data){
        $response = new JsonResponse();
        $response->setData($data);
        return $response;
    }

    /**
     * Get Errors Json Response
     * @return JsonResponse
     */
    protected function getErrorsJsonResult(){
        $response = new JsonResponse();
        $response->setData($this->_errors);
        return $response;
    }
    /**
     * @param string $captcha
     * @return bool
     */
    protected function isGoodCaptcha($captcha)
    {
        $params = array(
            'secret' => '6LfOtQkUAAAAAEZnATcL509IQlmDDkiujuwTbLMI',
            'response' => $captcha,
            'remoteip' => $_SERVER["REMOTE_ADDR"]
        );
        $resp = $this->sendCaptcha($params);
        return $resp['success'];
    }


    /**
     * Validate reCaptcha in Google
     * @param $params
     * @return JsonResponse
     */
    protected function sendCaptcha($params)
    {
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $params
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        return $response;
    }

    /**
     * Save new object(s)
     * @param object | array Entity
     */
    protected function save($data)
    {
        $em = $this->getDoctrine()->getManager();
        if (is_array($data)) {
            foreach ($data as $object) {
                $em->persist($object);
            }
        } else {
            $em->persist($data);
        }
        $em->flush();
    }

//    /**
//     * @Route("/test")
//     */
//    public function testAction(){
//        $usersRepository = $this->getDoctrine()->getRepository('AppBundle:Users');
//        $user = $usersRepository->find('4');
//        $em = $this->getDoctrine()->getManager();
//        dump($user);
//        $em->remove($user);
//        $em->flush();
//
//        exit;
//
//    }
}

