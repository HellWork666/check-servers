<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use src\Routes\Servers\{AddRoute,
    CreateRoute,
    DeleteRoute,
    HomeRoute,
    HostRoute,
    StatusUpdateOneRoute,
    StatusUpdateRoute,
    UpdateRoute
};

include_once __DIR__ . '../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions('config/di.php');

try {
    $container = $builder->build();
    AppFactory::setContainer($container);

    $app = AppFactory::create();

    $app->get('/', HomeRoute::class);

    $app->get('/servers', HomeRoute::class);

    $app->get('/server/add', AddRoute::class);

    $app->post('/server/create', CreateRoute::class);

    $app->post('/server/update', UpdateRoute::class);

    $app->get('/server/delete/{id}', DeleteRoute::class);

    $app->get('/server/{id}', HostRoute::class);

    $app->get('/servers/status_update/{id}', StatusUpdateOneRoute::class);

    $app->get('/servers/status_update', StatusUpdateRoute::class);

    $app->run();

} catch (PDOException|Exception $exception) {
    echo $exception->getMessage();
}



