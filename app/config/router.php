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
        'controller' => 'index',
        'action' => 'index'
    )
);

/*
 *  /api/account/login.json
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
        'controller' => 'registar',
        'action' => 'signup'
    )
);


/*
 *  /api/list
 */
$router->addPost(
    '/api/list',
    array(
        'controller' => 'lists',
        'action' => 'add'
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

