<?php
/**
 *  Router file
 */

$router = new \Phalcon\Mvc\Router(false);

/**
 *  /
 */
$router->add(
    '/',
    [
        'controller' => 'register',
        'action' => 'signup'
    ]
);

/**
 *  /api/account/login
 */
$router->addPost(
    '/api/account/login',
    [
        'controller' => 'account',
        'action' => 'login'
    ]
);

/**
 *  /api/account/signup
 */
$router->addPost(
    '/api/account/signup',
    [
        'controller' => 'register',
        'action' => 'signup'
    ]
);

/**
 *  /api/dashboard/create
 */
$router->addPost(
    '/api/dashboard/create',
    [
        'controller' => 'dashboard',
        'action' => 'create'
    ]
);

/**
 *  /api/dashboard/marge/([0-9]+)/([0-9]+)
 */
$router->addPost(
    '/api/dashboard/marge/:int/:int',
    [
        'controller' => 'dashboard',
        'action' => 'marge',
        'origin' => 1,
        'target' => 2
    ]
);

/**
 *  /api/lists/add
 */
$router->addPost(
    '/api/lists/add',
    [
        'controller' => 'lists',
        'action' => 'add'
    ]
);

/**
 *  /api/lists/all
 */
$router->addPost(
    '/api/lists/all',
    [
        'controller' => 'lists',
        'action' => 'all'
    ]
);

$router->notFound(
    [
        'controller' => 'status',
        'action' => 'code404'
    ]
);

/**
 *  末尾のスラッシュを取り除く
 */
$router->removeExtraSlashes(true);

