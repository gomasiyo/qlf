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
 *  /account/login.json
 */
$router->addPost(
    '/api/account/login',
    array(
        'controller' => 'account',
        'action' => 'login'
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

