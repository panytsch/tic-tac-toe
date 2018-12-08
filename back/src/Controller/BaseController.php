<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends FOSRestController
{

    /**
     * @var ObjectManager
     */
    private $em = null;

    /**
     * @return ObjectManager
     */
    protected function getManager() :ObjectManager
    {
        if (!$this->em){
            $this->em = $this->getDoctrine()->getManager();
        }
        return $this->em;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getRequestDecoded(Request $request) :array
    {
        return json_decode($request->getContent(), true);
    }
}