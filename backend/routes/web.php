<?php

declare(strict_types=1);

/**
 * @file 
 * @package 
 * @author Vinícius Gonçalves Cordeiro <vinicordeirogo@gmail.com>
 * @link https://github.com/vinicius-g-cordeiro/
 * @version 0.0.1
 * @copyright 2026 Vinicius Gonçalves Cordeiro
 */

namespace Routes;

use App\Controllers\HomeController;
use App\Controllers\Authentication\RegisterController;
use App\Controllers\Authentication\LoginController;
use App\Controllers\Authentication\ResetPasswordController;
use App\Controllers\UserController;

$router = Router::instance();

$router->group(['prefix' => '/pt-br'], function() use ($router) {
    $router->get('/', [HomeController::class, 'index'])->locale('pt-br')->title('Home')->permissions(['*']);

    $router->group(['prefix' => '/register'], function() use ($router) {
        $router->get('/', [RegisterController::class, 'index'])->locale('pt-br')->title('Register')->permissions(['*']);
        $router->post('/', [RegisterController::class, 'store'])->locale('pt-br')->title('Register')->permissions(['*']);
    });

    $router->group(['prefix' => '/login'], function() use ($router) {
        $router->get('/', [LoginController::class, 'index'])->locale('pt-br')->title('Login')->permissions(['*']);
        $router->post('/', [LoginController::class, 'login'])->locale('pt-br')->title('Login')->permissions(['*']);
    });

    $router->group(['prefix' => '/reset-password'], function() use ($router) {
        $router->get('/', [ResetPasswordController::class, 'index'])->locale('pt-br')->title('Reset Password')->permissions(['*']);
        $router->post('/', [ResetPasswordController::class, 'store'])->locale('pt-br')->title('Reset Password')->permissions(['*']);
    });
});


$router->group(['prefix' => '/auth'], function() use ($router) {
    $router->post('/register', [RegisterController::class, 'store'])->locale('pt-br')->title('Register');
    $router->post('/login', [LoginController::class, 'login'])->locale('pt-br')->title('Login');
    $router->get('/me', [UserController::class, 'me'])->locale('pt-br')->title('Me');
    $router->post('/logout', [LoginController::class, 'logout'])->locale('pt-br')->title('Logout');
    $router->post('/reset-password', [ResetPasswordController::class, 'store'])->locale('pt-br')->title('Reset Password');
});

