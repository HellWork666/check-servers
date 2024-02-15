<?php

namespace src\Routes\Servers;

use Generator;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use src\Entity\Host;
use src\HandlerHost;
use src\HandlerPing;
use Twig\Environment;

class StatusUpdateRoute
{
    public function __construct(private Environment $view, private Host $entityHost)
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, $args = []): Response
    {
        $hosts = $this->entityHost->hosts();
        function getResultHosts($hosts): Generator
        {
            foreach ($hosts as $host) {
                $ping = new HandlerPing($host['name']);
                $server = new HandlerHost($ping);

                $data = $server->resultPings();

                $data['id'] = $host['id'];

                yield $data;
            }
        }

        $resultHosts = getResultHosts($hosts);

        foreach ($resultHosts as $resultsHost) {
            $this->entityHost->update($resultsHost);
        }

        return $response
            ->withStatus(302)
            ->withHeader('Location', '/servers');
    }
}