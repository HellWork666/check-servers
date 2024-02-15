<?php

namespace src;

use JJG\Ping;

class HandlerPing
{

    public string $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    /**
     * @throws \Exception
     */
    public function getPing(): Ping
    {
        try {
            return new Ping($this->host);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}