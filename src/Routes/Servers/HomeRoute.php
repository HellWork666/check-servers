<?php

namespace src\Routes\Servers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use src\Entity\Host;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeRoute
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
        $hosts = $this->entityHost->hosts();

        $body = $this->view->render('hosts.twig', [
            'hosts' => $hosts,
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}