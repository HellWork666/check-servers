<?php

namespace src;

use Exception;
use src\Entity\Host;

class HandlerHost
{
    private HandlerPing $ping;

    public function __construct(HandlerPing $ping)
    {
        $this->ping = $ping;
    }

    private function pingHost(): array
    {
        $result = [
            'host' => $this->ping->host,
        ];

        try {
            $server = $this->ping->getPing();
            $latency = $server->ping();

            if ($latency === false) {
                throw new Exception('HandlerHost could not be reached.');
            }

            $data = [
                'info' => 'Latency is ' . $latency . ' ms.',
                'ip' => $server->getIpAddress(),
                'status' => Host::STATUS_TRUE,
            ];

        } catch (Exception $exception) {
            $data = [
                'error' => $exception->getMessage(),
                'status' => Host::STATUS_FALSE,
            ];
        }
        return array_merge($result, $data);
    }

    public function resultPings(): array
    {
        $data = [];
        for ($i = 1; $i <= 5; $i++) {
            $data[$i] = $this->pingHost();
        }

        $statuses = array_column($data, 'status');
        $status = max($statuses);

        return array('host' => $this->ping->host, 'status' => $status);
    }
}