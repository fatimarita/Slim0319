<?php


use Slim\App;
use App\Controller\AboutController;
use App\Controller\ContactController;
use App\Controller\ProjectController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

// Autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

//Création de l'application

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ]
    ];

// Création de l'application
$app = new App($config);
//configutration de twig 
// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(dirname(__DIR__) .'/templates', [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

    return $view;
};
$container[ProjectController::class] = function(ContainerInterface $container){
    return new ProjectController ($container->get('view'));
};
$container[ContactController::class] = function(ContainerInterface $container){
    return new ContactController ($container->get('view'));
};
$container[AboutController::class] = function(ContainerInterface $container){
    return new AboutController ($container->get('view'));
};


// Création d'une route
$route = $app->get("/", function (ServerRequestInterface $request, ResponseInterface $response, ?array $args) {
    // On retourne une réponse
    // return $response->getBody()->write('<h1>Bonjour</h1>');
    return $this->view->render($response, 'home.twig');
});
$route->setName('homepage');

$app->group('/projet', function () {
    // Création d'une page de détail des projets
    // Nouveauté : on ajoute une variable dans l'URL avec les accolades
    $this->get("/{id:\d+}", ProjectController::class .':show')->setName('app_project_show');

    // Page de création
    $this->get("/creation", ProjectController::class .':create')->setName('app_project_create');
});
$app->get('/contact', ContactController::class .':contact')->setName('app_contact');
$app->get('/about', AboutController::class .':about')->setName('app_about');

// Création et renvoi de la réponse au navigateur

$app->run();