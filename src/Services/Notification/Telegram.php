<?php

namespace src\Services\Notification;

use JetBrains\PhpStorm\NoReturn;

class Telegram implements iNotification
{
    public function __construct(
        private string $token,
        private string $chatId
    )
    {
    }

    #[NoReturn] public function sendMessage(string $text): void
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.telegram.org/bot$this->token/sendMessage?chat_id=$this->chatId&text=$text",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
    }
}