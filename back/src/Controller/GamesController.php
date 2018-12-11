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
        $currentGame = null;
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
        if (!empty($data['gameId'])){
            /** @var Games $currentGame */
            $currentGame = $this->getDoctrine()
                ->getRepository(Games::class)
                ->find($data['gameId']);
            if (!$currentGame){
                return View::create([
                    'status' =>false
                ]);
            }
            if ($currentGame->getStatus() === Games::STATUS_ACTIVE_GAME){
                return View::create([
                    'status' => true,
                    'gameId' => $currentGame->getId(),
                    'type' => $currentGame->getUserO()->getId() === $user->getId() ? 'o' : 'x'
                ]);
            }
        }
        $activeGames = $this->getActiveGames();
        if (!$activeGames){
            $game = new Games();
            $game->setUserO($user);
            $game->setStatus(Games::STATUS_PENDING_USER);
            $game->setWhoseMove(Games::MOVE_O);
            $em->persist($game);
            $em->flush();
            return View::create([
                'status' => true,
                'pending' => true,
                'gameId' => $game->getId(),
            ]);
        }
        /** @var Games $game */
        $game = $activeGames[array_rand($activeGames)];
        if ($game->getUserO()->getId() === $user->getId()){
            return View::create([
                'status' => true,
                'pending' => true,
                'gameId' => $game->getId(),
            ]);
        }
        if ($game->getUserO()){
            $game->setUserX($user);
            $type = 'x';
        } else {
            $game->setUserO($user);
            $type = 'o';
        }
        $game->setStatus(Games::STATUS_ACTIVE_GAME);
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
            return View::create([
                'status' => true,
                'win' => $game->getStatus() === Games::STATUS_FINISHED_GAME,
                'winner' => $game->getWinner() ? $game->getWinner()->getName() : null
            ]);
        }
        if ($game->getUserO()->getId() === (int)$data['userId'] && $game->getWhoseMove() == Games::MOVE_X){
            $game->updateUserOCount($data['itemNumber']);
            if ($game->hasGameWinner() || $game->isPat()){
                $game->setStatus(Games::STATUS_FINISHED_GAME);
            } else {
                $game->switchWhoseMove();
            }
        } else if ($game->getUserX()->getId() === (int)$data['userId'] && $game->getWhoseMove() == Games::MOVE_O) {
            $game->updateUserXCount($data['itemNumber']);
            if ($game->hasGameWinner() || $game->isPat()){
                $game->setStatus(Games::STATUS_FINISHED_GAME);
            } else {
                $game->switchWhoseMove();
            }
        } else {
            return View::create([
                'status' => false,
                'message' => 'Fatal bug'
            ]);
        }
        $game->setLastMove(new \DateTime());
        $em = $this->getManager();
        $em->persist($game);
        $em->flush();

        return View::create([
            'status' => true,
            'win' => $game->getStatus() === Games::STATUS_FINISHED_GAME,
            'winner' => $game->getWinner() ? $game->getWinner()->getName() : null,
            'pat' => $game->isPat()
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
        if (((time() - $game->getLastMove()->getTimestamp()) > 60) && !$game->hasGameWinner()){
            $game->setWhoseMove(!$game->getWhoseMove());
            $game->setStatus(Games::STATUS_FINISHED_GAME);
            $em = $this->getManager();
            $em->persist($game);
            $em->flush();
        }
        return View::create([
            'status' => $game->hasGameWinner(),
            'winner' => $game->getWinner() ? $game->getWinner()->getName() : null,
            'pat' => $game->isPat()
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

    /**
     * @Rest\Get("/api/games/request-to-turn")
     * @param Request $request
     * @return View
     */
    public function getIsItMyTurn(Request $request)
    {
        $data = [
            'userId' => $request->get('userId'),
            'gameId' => $request->get('gameId')
        ];
        if (empty($data['userId']) || empty($data['gameId'])){
            return View::create([
                'status' => false,
                'message' => 'wrong data'
            ]);
        }
        /** @var Games $game */
        $game = $this->getDoctrine()->getRepository(Games::class)->find($data['gameId']);
        if (!$game || $game->hasGameWinner()){
            return View::create([
                'status' => false,
                'message' => 'Game not found',
            ]);
        }
        if ($game->getWhoseMove() == Games::MOVE_X && $game->getUserO()->getId() === (int)$data['userId']){
            return View::create([
                'status' => true,
                'data' => [
                    'me' => $game->getUserOCount(),
                    'opponent' => $game->getUserXCount()
                ]
            ]);
        } else if (($game->getWhoseMove() == Games::MOVE_O) && ($game->getUserX()->getId() === (int)$data['userId'])) {
            return View::create([
                'status' => true,
                'data' => [
                    'opponent' => $game->getUserOCount(),
                    'me' => $game->getUserXCount()
                ]
            ]);
        }
        return View::create([
            'status' => false,$game->getWhoseMove(),$game->getUserX()->getId() === (int)$data['userId']
        ]);
    }

    private function getActiveGames()
    {
        return $this->getDoctrine()->getRepository(Games::class)->getActiveGames();
    }
}