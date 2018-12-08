<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class BoardController extends BaseController
{
    public function postJoinGame(Request $request)
    {
        $data = $this->getRequestDecoded($request);

    }
}