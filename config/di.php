<?php

use src\Database;
use src\Entity\Host;
use src\Services\Notification\iNotification;
use src\Services\Notification\Telegram;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use function DI\autowire;
use function DI\get;

return [
    'db.configs' => require_once __DIR__ . './database.php',

    Database::class => autowire()
        ->constructorParameter('configs', get('db.configs')),

    Host::class => autowire()
        ->constructorParameter('database', get(Database::class)),

    FilesystemLoader::class => autowire()
        ->constructorParameter('paths', 'templates'),

    Environment::class => autowire()
        ->constructorParameter('loader', get(FilesystemLoader::class)),

    Telegram::class => autowire()
        ->constructorParameter('token', '6731014327:AAHSMrn4hs_YrB64WChed7Lr6E6-4z7mr3U')
        ->constructorParameter('chatId', '-4179746525'),

    iNotification::class => get(Telegram::class),
];