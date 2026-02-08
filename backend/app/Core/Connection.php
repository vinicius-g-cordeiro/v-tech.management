<?php


/**
 * @file  Connection.php
 * @package Management Template
 * @author Vinícius Gonçalves Cordeiro <vinicordeirogo@gmail.com>
 * @link https://github.com/vinicius-g-cordeiro/management-template
 * @version 0.0.1
 * @license Apache-2.0
 * @copyright 2025 Vinicius
 */


namespace App\Core;

use ADOConnection;
use ADODB_Exception;

require_once('../vendor/adodb/adodb-php/adodb.inc.php');
include_once('../vendor/adodb/adodb-php/adodb-exceptions.inc.php');


class Connection  {

    public static $instance = null;

    protected ?object $connectionInfo {
        get {
            return object(
                driver: 'mysql',
                database: getenv('DB_DATABASE'),
                username: getenv('DB_USERNAME'),
                host: getenv('DB_HOST'),
                password: '',
                charset: 'utf8mb4',
                persistent: 'false',
                port: 3306,
            );
        }
    }

    protected ADOConnection $dbConnection;

    function __construct($dbConnection = null){
        
        $this->connect($dbConnection);

    }

    static function instance() : ADOConnection {
        if(self::$instance == null){
            self::$instance = new Connection();
        }
        return self::$instance->dbConnection;
    }

    function getConnection(){
        if(self::$instance == null){
            self::$instance = new Connection(null);
        }
        return self::$instance->dbConnection;
    }

    function connect($dbConnection = null){
        
        if($dbConnection === null && (!isset($thid->dbConnection) || $this->dbConnection == null)){
            try{
                $connection = ADONewConnection('mysql');
                $pass = trim(file_get_contents(getenv('DB_PASSWORD_FILE')));
                $connection->Connect($this->connectionInfo->host, $this->connectionInfo->username, $pass, $this->connectionInfo->database);
                $connection->SetFetchMode(ADODB_FETCH_ASSOC);
                $connection->setCharSet($this->connectionInfo->charset);
                $connection->Execute('SET sql_mode = \'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO\';');
                $connection->autoCommit = false;
            }catch(\Exception $e){
                throw $e;
            }
            $this->dbConnection = $connection;
        }else{
            if(getenv('APP_DEBUG') == "true" && getenv('MYSQL_DEBUG_DATABASE_CONNECTION') == "true"){
                dump('Using pooled connection!');
            }
            $this->dbConnection = $dbConnection;
        }
    }

    function getConnectionId(){
        return $this->dbConnection->_connectionID;
    }

}

