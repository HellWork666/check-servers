<?php

namespace src\Routes\Servers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AddRoute
{
    public function __construct(public Environment $view)
    {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function __invoke(Request $request, Response $response, $args = []): Response
    {
        $body = $this->view->render('create_host.twig');
        $response->getBody()->write($body);
        return $response;
    }
}