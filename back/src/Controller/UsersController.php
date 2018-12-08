<?php

namespace App\Controller;

use App\Entity\Users;
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
        $requestData = json_decode($request->getContent(), true);
        if (!isset($requestData['name']) || empty($requestData['name']) || !is_string($requestData['name'])){
            return View::create([
                'status' => false,
                'message' => 'Name can not be blank'
            ]);
        }
        $user = new Users();
        $user
            ->setName($requestData['name'])
            ->setIsActive(false)
        ;
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return View::create(['status' => !!$user->getId()]);
    }
}