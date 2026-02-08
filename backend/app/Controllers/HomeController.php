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

namespace App\Controllers;

use ADOConnection;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Service;

class HomeController extends Controller {
    function __construct(?ADOConnection $connection = null, ?Request $request = null, ?Session $session = null, ?Service $service = null) {
        parent::__construct($connection, $request, $session, $service);
    }
    
    /**
     * @Route('/api/pt-br/)
     */
    function index() {
        return $this->response(object());
    }
}