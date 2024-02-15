<?php

namespace src\Routes\Servers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use src\Entity\Host;
use src\HandlerHost;
use src\HandlerPing;
use src\Services\Notification\iNotification;

class StatusUpdateOneRoute
{
    public function __construct(
        private Host          $entityHost,
        private iNotification $notification
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, $args = []): Response
    {
        $host = $this->entityHost->getHostById((int)$args['id']);

        $ping = new HandlerPing($host['name']);
        $server = new HandlerHost($ping);

        $data = $server->resultPings();

        $data['id'] = $host['id'];

        if ($data['status'] === 0) {
            $this->notification->sendMessage('Server error: ' . $host['name']);
        }

        $this->entityHost->update($data);

        return $response
            ->withStatus(302)
            ->withHeader('Location', '/servers');
    }

}