<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class UsersController extends FOSRestController
{

    /**
     * @Rest\Post("/api/users/new")
     * @param Request $request
     * @return View
     */
    public function postNewUser(Request $request)
    {
        $requestData = json_encode($request->getContent(), true);
        if (!isset($requestData['name']) || empty($requestData['name'])){
            return View::create([
                'status' => false,
                'message' => 'Name can not be blank'
            ]);
        }
    }
}