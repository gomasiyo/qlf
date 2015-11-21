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
    array(
        'controller' => 'register',
        'action' => 'signup'
    )
);

/*
 *  /api/account/login
 */
$router->addPost(
    '/api/account/login',
    array(
        'controller' => 'account',
        'action' => 'login'
    )
);

/*
 *  /api/account/signup
 */
$router->addPost(
    '/api/account/signup',
    array(
        'controller' => 'register',
        'action' => 'signup'
    )
);

/*
 *  /api/lists/add
 */
$router->addPost(
    '/api/lists/add',
    array(
        'controller' => 'lists',
        'action' => 'add'
    )
);

/*
 *  /api/lists/add
 */
$router->addPost(
    '/api/lists/all',
    array(
        'controller' => 'lists',
        'action' => 'all'
    )
);

$router->notFound(
    array(
        'controller' => 'status',
        'action' => 'code404'
    )
);

/**
 *  末尾のスラッシュを取り除く
 */
$router->removeExtraSlashes(true);

