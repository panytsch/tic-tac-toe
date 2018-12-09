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
        if (empty($data['userId'])) {
            return View::create([
                'status' => false
            ]);
        }
        $em = $this->getManager();
        /** @var Users $user */
        $user = $this->getDoctrine()->getRepository(Users::class)->find($data['userId']);
        if (!$user) {
            return View::create([
                'status' => false,
                'message' => 'User not found'
            ]);
        }
        $type = null;
        $activeGames = $this->getActiveGames();
        if (!$activeGames){
            $game = new Games();
            $game->setUserO($user);
            $game->setStatus(Games::STATUS_PENDING_USER);
            $game->setWhoseMove(!!array_rand([Games::MOVE_O, Games::MOVE_X]));
        } else {
            /** @var Games $game */
            $game = $activeGames[array_rand($activeGames)];

            if ($game->getUserO()){
                if ($game->getUserO()->getId() === $user->getId()){
                    return View::create([
                        'status' => true,
                        'pending' => true,
                        'gameId' => $game->getId(),
                    ]);
                }
                $game->setUserX($user);
                $type = 'x';
            } else {
                $game->setUserO($user);
                $type = 'o';
            }
            $game->setStatus(Games::STATUS_ACTIVE_GAME);
        }
        $em->persist($game);
        $em->flush();
        return View::create([
            'status' => !!$game->getId(),
            'gameId' => $game->getId(),
            'type' => $type
        ]);
    }

    /**
     * @Rest\Post("/api/games/turn")
     * @param Request $request
     * @return View
     * @throws \Exception
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
        } else if ($game->getStatus() === Games::STATUS_FINISHED_GAME){
            $winner = $game->getWhoseMove() === Games::MOVE_X
                ? $game->getUserX()->getName()
                : $game->getUserO()->getName();
            return View::create([
                'status' => true,
                'win' => $game->getStatus() === Games::STATUS_FINISHED_GAME,
                'winner' => $winner
            ]);
        }
        if ($game->getUserO() === (int)$data['userId'] && $game->getWhoseMove() === Games::MOVE_O){
            $game->setUserOCount($game->getUserOCount() + Games::$boardCost[$data['itemNumber']]);
            if (in_array($game->getUserOCount(), Games::$winCombinations)){
                $game->setStatus(Games::STATUS_FINISHED_GAME);
            } else {
                $game->setWhoseMove(Games::MOVE_X);
            }
        } else if ($game->getUserX() === (int)$data['userId'] && $game->getWhoseMove() === Games::MOVE_X) {
            $game->setUserXCount($game->getUserXCount() + Games::$boardCost[$data['itemNumber']]);
            if (in_array($game->getUserXCount(), Games::$winCombinations)){
                $game->setStatus(Games::STATUS_FINISHED_GAME);
            } else {
                $game->setWhoseMove(Games::MOVE_O);
            }
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
        $game->setLastMove(new \DateTime());
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
     * @Rest\Get("/api/games/request-to-win")
     * @param Request $request
     * @return View
     */
    public function getIsWinner(Request $request)
    {
        if (empty($request->get('gameId')))
        {
            return View::create([
                'status' => false
            ]);
        }
        /** @var Games $game */
        $game = $this
            ->getDoctrine()
            ->getRepository(Games::class)
            ->find($request->get('gameId'));
        if (!$game){
            return View::create([
                'status' => false,
                'message' => 'Game not found'
            ]);
        }
        // 60 sec per one turn
        if ((time() - $game->getLastMove()->getTimestamp()) > 60){
            $game->setWhoseMove(!$game->getWhoseMove());
            $game->setStatus(Games::STATUS_FINISHED_GAME);
            $em = $this->getManager();
            $em->persist($game);
            $em->flush();
            return View::create([
                'status' => true
            ]);
        }
        return View::create([
            'status' => false,
        ]);
    }

    /**
     * @Rest\Post("/api/games/leave")
     * @param Request $request
     * @return View
     */
    public function postLeaveGame(Request $request)
    {
        $data = $this->getRequestDecoded($request);
        if (empty($data['userId']) || empty($data['gameId'])){
            return View::create([
                'status' => false
            ]);
        }
        /** @var Games $game */
        $game = $this->getDoctrine()->getRepository(Games::class)->find($data['gameId']);
        if ($game->getUserO()->getId() === (int)$data['userId']){
            $game->setWhoseMove(Games::MOVE_X)
                ->setStatus(Games::STATUS_FINISHED_GAME)
            ;
        } else if ($game->getUserX()->getId() === (int)$data['userId']) {
            $game->setWhoseMove(Games::MOVE_O)
                ->setStatus(Games::STATUS_FINISHED_GAME);
        }
        $em = $this->getManager();
        $em->persist($game);
        $em->flush();
        return View::create([
            'status' => true,
            'userId' => $data['userId'],
            'gameId' => $data['gameId'],
        ]);
    }

    private function getActiveGames()
    {
        return $this->getDoctrine()->getRepository(Games::class)->getActiveGames();
    }
}