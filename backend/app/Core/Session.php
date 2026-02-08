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

require_once __DIR__ . '/../../vendor/adodb/adodb-php/session/adodb-session2.php';
require_once __DIR__ . '/../../vendor/adodb/adodb-php/adodb-exceptions.inc.php';
require_once __DIR__ . '/../../vendor/adodb/adodb-php/adodb.inc.php';

use ADODB_Session;
use App\Core\Connection;

class Session {

    public static ?Session $instance = null;

    protected static ?object $notifications = null;

    protected bool $sessionStarted = false;
    
    function __construct() {
        $this->init();
    }

    public static function instance() : Session {
        if(!isset(self::$instance) || self::$instance === null){
            self::$instance = new Session();
        }

        return self::$instance;
    }

    function get(string $key = '') {
        return $_SESSION[$key] ?? null;
    }

    function set(string $key, $value) {
        $_SESSION[$key] = $value;

        return $_SESSION[$key];
    }

    function notification(string $key = '', string $value = '') : object {
        if(self::$notifications === null){
            self::$notifications = new \stdClass();
        }
        
        if($key === ''){
            return self::$notifications;
        }

        if($value === ''){
            return self::$notifications->{$key};
        }

        if($key !== '' && $value !== ''){
            self::$notifications->{$key} = $value;
        }

        return self::$notifications->{$key};
    }

    
    function init() : bool {
        if(session_status() === PHP_SESSION_NONE){
            
            $password = trim(file_get_contents(getenv('DB_PASSWORD_FILE')));
            ADODB_Session::config('mysql', getenv('DB_HOST'), getenv('DB_USERNAME'), $password , getenv('DB_DATABASE'));
// dd(getenv('DB_HOST'), getenv('DB_USERNAME'), $password , getenv('DB_DATABASE'));
            Connection::instance()->Execute("CREATE TABLE IF NOT EXISTS sessions2 (sesskey VARCHAR( 64 ) COLLATE utf8mb4_bin NOT NULL DEFAULT '', expiry DATETIME NOT NULL , expireref VARCHAR( 250 ) DEFAULT '', created DATETIME NOT NULL , modified DATETIME NOT NULL , sessdata LONGTEXT, PRIMARY KEY ( sesskey ) , INDEX sess2_expiry( expiry ), INDEX sess2_expireref( expireref ) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='ADODB2 Session Table';");
            ADODB_Session::open('/tmp', 'sessions', null);

            // Set the session cookie parameters
            session_set_cookie_params([
                'samesite' => 'Lax',
                'httponly' => true,
                'secure' => true,
                'path' => '/',
                'lifetime' => 60 * 60 * 2 // 2 hours in seconds
            ]);

            // Start the session
            session_start();
            if(session_status() === PHP_SESSION_ACTIVE){
                $this->sessionStarted = true;
            }
        }
        return $this->sessionStarted;
    }

}
