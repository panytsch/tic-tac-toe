<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\Users;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class GamesController extends BaseController
{
    /**
     * @Rest\Post("/api/games/join")
     * @param Request $request
     * @return View
     */
    public function postJoinGame(Request $request)
    {
        $data = $this->getRequestDecoded($request);
        $user = $this->getDoctrine()->getRepository(Users::class)->find($data['userId']);
        if (!$user) {
            return View::create([
                'status' => false,
                'message' => 'User not found'
            ]);
        }
    }

    /**
     * @Rest\Get("/api/games/test")
     * @return View
     */
    public function getTest()
    {
        return View::create($this->getActiveGames());
    }

    private function getActiveGames()
    {
        return $this->getDoctrine()->getRepository(Games::class)->getActiveGames();
    }
}