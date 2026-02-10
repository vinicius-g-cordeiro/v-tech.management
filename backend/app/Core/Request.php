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

namespace App\Core;

use App\Types\Methods;

class Request {
    private ?object $post = null;
    private ?object $get = null;
    private ?object $files = null;
    private ?object $server = null;
    private ?object $cookie = null;
    private ?object $session = null;
    private ?object $request = null;
    private ?object $body = null;
    private ?object $headers = null;
    private ?Methods $method = null;
    private ?string $uri = null;
    private ?object $params = null;
    private static ?Request $instance = null;
    private ?string $bearerToken = null;

    function __construct() {
        Session::instance();
        $this->getRequestBody();    
    }

    function getRequestBody() : void {
        $this->post = (object)filter_var_array($_POST ?? [], FILTER_DEFAULT, true); //)$_POST;
        $this->get = (object)filter_var_array($_GET ?? [], FILTER_DEFAULT, true); //$_GET;
        $this->files = (object)$_FILES;
        $this->server = (object)filter_var_array($_SERVER ?? [], FILTER_DEFAULT, true); //$_SERVER;
        $this->cookie = (object)filter_var_array($_COOKIE ?? [], FILTER_DEFAULT, true); //$_COOKIE;
        $this->session = (object)filter_var_array($_SESSION ?? [], FILTER_DEFAULT, true);
        $this->request = (object)filter_var_array($_REQUEST ?? [], FILTER_DEFAULT, true); //)$_REQUEST;
        $this->body = (object)json_decode(file_get_contents('php://input'), true);
        $this->headers = (object)getallheaders();
        $this->method = Methods::{$_SERVER['REQUEST_METHOD']};
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->bearerToken = preg_replace('/Bearer\s?/', '', ($_SERVER['HTTP_AUTHORIZATION'] ?? null) ?: '');
    }

    static function instance() : Request {
        if(!isset(self::$instance)) {
            self::$instance = new Request();
        }
        return self::$instance;
    }

    function __get($property) {
        return $this->{$property};
    }

    function __set($property, $value) {
        $this->{$property} = $value;
    }

    function __isset($property) {
        return isset($this->{$property});
    }

    function __unset($property) {
        unset($this->{$property});
    }

    function __toString() {
        return json_encode($this);
    }

    function __destruct() {
        unset($this->post, $this->get, $this->files, $this->server, $this->cookie, $this->session, $this->request, $this->body, $this->headers, $this->method, $this->uri);
    }

    function setParams(object $params) : object {
        $this->params = (object)array_merge((array)$this->params, (array)$params);
        return $this->params;
    }

    function all() : object {
        return object(
            post: $this->post,
            get: $this->get,
            files: $this->files,
            server: $this->server,
            cookie: $this->cookie,
            session: $this->session,
            request: $this->request,
            body: $this->body,
            headers: $this->headers,
            method: $this->method,
            uri: $this->uri
        );
    }

    function getBearerToken() : ?string {
        return $this->bearerToken;
    }
}