<?php
use Slim\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\serverRequestInterface;

//Autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';
//Création de l'application
$app = new App();
$app->get("/hello", function (
    serverRequestInterface $request,
    ResponseInterface $response,
    ?array $args
) {
    //on retourne une réponse
    return $response->getBody()->write('<h1>Bonjour</h1>');
});
//Création et renvoi de la réponse au navigateur
$app->run();
