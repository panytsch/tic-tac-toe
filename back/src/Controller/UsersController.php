<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class UsersController extends FOSRestController
{

    /**
     * @Rest\Post("/users/new")
     * @param Request $request
     */
    public function postNewUser(Request $request)
    {
        var_dump($request->getContent());
        die();
    }
}