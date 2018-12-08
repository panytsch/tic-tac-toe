<?php

namespace App\Controller;

use App\Entity\Users;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class UsersController extends BaseController
{
    /**
     * @Rest\Post("/api/users/new")
     * @param Request $request
     * @return View
     */
    public function postNewUser(Request $request)
    {
        $this->getDoctrine()->getRepository(Users::class)->dropNonActiveUsers();
        $requestData = $this->getRequestDecoded($request);
        if (!isset($requestData['name']) || empty($requestData['name']) || !is_string($requestData['name'])){
            return View::create([
                'status' => false,
                'message' => 'Name can not be blank'
            ]);
        }
        $user = new Users();
        $user
            ->setName($requestData['name'])
            ->setIsActive(Users::STATUS_READY);
        $em = $this->getManager();
        $em->persist($user);
        $em->flush();
        return View::create(['status' => !!$user->getId(), 'userId' => $user->getId() ?? null]);
    }

    /**
     * @Rest\Get("/")
     */
    public function getOk()
    {
        return View::create(['status' => 'api work']);
    }
}