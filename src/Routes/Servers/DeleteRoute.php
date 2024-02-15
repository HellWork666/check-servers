<?php

namespace src\Routes\Servers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use src\Entity\Host;

class DeleteRoute
{
    public function __construct(private Host $entityHost)
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, $args = []): Response
    {
        $this->entityHost->delete($args['id']);

        return $response
            ->withStatus(302)
            ->withHeader('Location', '/servers');
    }
}