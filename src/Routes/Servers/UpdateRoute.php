<?php

namespace src\Routes\Servers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use src\Entity\Host;

class UpdateRoute
{
    public function __construct(private Host $entityHost)
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, $args = [])
    {
        $data = $request->getParsedBody();
        $this->entityHost->update($data);

        return $response
            ->withStatus(302)
            ->withHeader('Location', '/servers');
    }
}