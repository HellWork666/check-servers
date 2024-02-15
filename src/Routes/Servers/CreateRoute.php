<?php

namespace src\Routes\Servers;

use Exception;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use src\Entity\Host;

class CreateRoute
{
    public function __construct(private Host $entityHost)
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, $args = []): Response
    {
        $data = $request->getParsedBody();
        $result = $this->entityHost->create($data['name'], $data['description']);

        if (!$result) {
            throw new Exception('Don`t add servers.');
        }

        return $response
            ->withStatus(302)
            ->withHeader('Location', '/servers');
    }
}