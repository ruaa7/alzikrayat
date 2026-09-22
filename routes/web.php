<?php
require_once __DIR__ . '/../core/Router.php';

/**
 * routes/web.php
 *
 * Central route registry. Every route the application handles is
 * declared explicitly here as [METHOD, path pattern, [Controller, action]].
 * The Router converts "{param}" tokens into a regex capture group at
 * dispatch time (see core/Router.php).
 */

$router = new Router();

//Static / home 
$router->add('GET', '/', ['HomeController', 'index']);

//Auth 
$router->add('GET',  '/register', ['AuthController', 'showRegister']);
$router->add('POST', '/register', ['AuthController', 'register']);
$router->add('GET',  '/login',    ['AuthController', 'showLogin']);
$router->add('POST', '/login',    ['AuthController', 'login']);
$router->add('GET',  '/logout',   ['AuthController', 'logout']);

//Photos 
$router->add('GET',  '/photos',              ['PhotoController', 'index']);
$router->add('GET',  '/photo/create',        ['PhotoController', 'create']);
$router->add('POST', '/photo/store',         ['PhotoController', 'store']);
$router->add('GET',  '/photo/{id}',          ['PhotoController', 'show']);
$router->add('GET',  '/photo/{id}/delete',   ['PhotoController', 'delete']);

//Comments
$router->add('POST', '/photo/{id}/comment', ['CommentController', 'store']);

return $router;
