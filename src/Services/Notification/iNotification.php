<?php

namespace src\Services\Notification;

interface iNotification
{
    public function sendMessage(string $text): void;
}