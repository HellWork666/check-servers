<?php

namespace src\Routes\Servers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use src\Entity\Host;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HostRoute
{
    public function __construct(private Environment $view, private Host $entityHost)
    {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function __invoke(Request $request, Response $response, $args = []): Response
    {
        $host = $this->entityHost->getHostById((int)$args['id']);
        $body = $this->view->render('host.twig', [
            'host' => $host,
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}