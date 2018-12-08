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
        $em = $this->getManager();
        /** @var Users $user */
        $user = $this->getDoctrine()->getRepository(Users::class)->find($data['userId']);
        if (!$user) {
            return View::create([
                'status' => false,
                'message' => 'User not found'
            ]);
        }
        $activeGames = $this->getActiveGames();
        if (!$activeGames){
            $game = new Games();
            $game->setUserO($user);
            $game->setStatus(Games::STATUS_PENDING_USER);
            $game->setWhoseMove(!!array_rand([Games::MOVE_O, Games::MOVE_X]));
        } else {
            /** @var Games $game */
            $game = $activeGames[array_rand($activeGames)];
//            var_dump($activeGames[0]->getId());
//            die();
            if ($game->getUserO()){
                $game->setUserX($user);
            } else {
                $game->setUserO($user);
            }
            $game->setStatus(Games::STATUS_ACTIVE_GAME);
        }
        $em->persist($game);
        $em->flush();
        return View::create([
            'status' => !!$game->getId(),
            'gameId' => $game->getId()
        ]);
    }

    /**
     * @Rest\Post("/api/games/turn")
     * @param Request $request
     * @return View
     */
    public function postTurn(Request $request)
    {
        $data = $this->getRequestDecoded($request);
        if (empty($data['gameId']) || empty($data['userId']) || empty($data['itemNumber'])){
            return View::create([
                'status' => false,
                'message' => 'Wrong data'
            ]);
        }
        /** @var Games $game */
        $game = $this->getDoctrine()->getRepository(Games::class)->find($data['gameId']);
        if (!$game || $game->getStatus() !== Games::STATUS_ACTIVE_GAME) {
            return View::create([
                'status' => false,
                'message' => 'game not found'
            ]);
        }
        if ($game->getUserO() === (int)$data['userId'] && $game->getWhoseMove() === Games::MOVE_O){
            $game->setUserOCount($game->getUserOCount() + Games::$boardCost[$data['itemNumber']]);
            if (in_array($game->getUserOCount(), Games::$winCombinations)){
                $game->setStatus(Games::STATUS_FINISHED_GAME);
            }
            $game->setWhoseMove(Games::MOVE_X);
        } else if ($game->getUserX() === (int)$data['userId'] && $game->getWhoseMove() === Games::MOVE_X) {
            $game->setUserXCount($game->getUserXCount() + Games::$boardCost[$data['itemNumber']]);
            if (in_array($game->getUserXCount(), Games::$winCombinations)){
                $game->setStatus(Games::STATUS_FINISHED_GAME);
            }
            $game->setWhoseMove(Games::MOVE_O);
        } else {
            return View::create([
                'status' => false,
                'message' => 'Fatal bug'
            ]);
        }
        $winner = null;
        if ($game->getStatus() === Games::STATUS_FINISHED_GAME){
            if ($game->getWhoseMove() === Games::MOVE_O){
                $winner = $game->getUserO()->getName();
            } else {
                $winner = $game->getUserX()->getName();
            }
        }
        $em = $this->getManager();
        $em->persist($game);
        $em->flush();

        return View::create([
            'status' => true,
            'win' => $game->getStatus() === Games::STATUS_FINISHED_GAME,
            'winner' => $winner
        ]);
    }

    /**
     * @Rest\Get("/api/games/test")
     * @return View
     */
    public function getTest()
    {
        $result = [3,8];
        for ($i = 1; $i < 8; $i++){
            $result[]= $result[$i]*2+$result[$i-1];
        }
        return View::create($result);
    }

    private function getActiveGames()
    {
        return $this->getDoctrine()->getRepository(Games::class)->getActiveGames();
    }
}