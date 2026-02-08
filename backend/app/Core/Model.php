<?php

declare(strict_types=1);

/**
 * @file Model.php
 * @package 
 * @author Vinícius Gonçalves Cordeiro <vinicordeirogo@gmail.com>
 * @link https://github.com/vinicius-g-cordeiro/
 * @version 0.0.1
 * @copyright 2026 Vinicius Gonçalves Cordeiro
 */

namespace App\Core;

use ADOConnection;
use App\Core\Connection;

use Exception;
use App\Exceptions\NoQueryEspecifiedException;
use App\Exceptions\DataTableCreationFailedException;
use App\Exceptions\DataTableDropFailedException;
use App\Exceptions\QueryFailedException;
use App\Exceptions\NotImplementedException;
use App\Exceptions\User\UserNotFoundException;
use App\Exceptions\Authentication\InvalidPasswordException;

use ADORecordSet;

class Model extends Connection {
    protected ADOConnection $dbConnection;

    protected string $tableName { 
        get {
            return 'example';
        }
    }

    protected string $tableKey {
        get {
            return 'id';
        }
    }

    protected string $tableAlias {
        get {
            return 'ex';
        }
    }

    protected ?object $tableColumns {
        get {
            return object(
                id: 'int NOT NULL AUTO_INCREMENT PRIMARY KEY',
                name: 'varchar(255) NOT NULL',
                description: 'varchar(255) NULL DEFAULT NULL',
                created_at: 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                updated_at: 'timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
                deleted_at: 'timestamp NULL DEFAULT NULL',
                created_by: 'int NOT NULL',
                updated_by: 'int NULL DEFAULT NULL',
                deleted_by: 'int NULL DEFAULT NULL',
                active: 'tinyint(1) NOT NULL DEFAULT 1',
            );
        }
    }
    protected string $tableComment {
        get {
            return 'Example Table comment for ' . $this->tableName;
        }
    }

    protected ?object $tableIndexes {
        get {
           return object(
                idx_name: ' name ',
           ); 
        }
    }

    function __construct(?ADOConnection $dbConnection = null) {
        parent::__construct($dbConnection);
        if($this->doesTableExist() === false){
            $this->upTable();
        } else {

            // Check which fields are not in the table
            $response = $this->getConnection()->Execute('DESCRIBE ' . $this->tableName);

            $toAddFields = [];
            $foundFields = [];
            foreach ($this->tableColumns as $key => $value) {
                foreach ($response as $column) {
                    // Get all the Fields coluns and check if the field is not in the table
                    if ($column['Field'] == $key) {
                        $foundFields[] = $key;
                        continue;
                    }
                }
                // If the field is not in the table, add it to the array of fields to add
                if (!in_array($key, $foundFields)) {
                    $toAddFields[$key] = $value;
                }
            }

            if (!empty($toAddFields)) {
                foreach ($toAddFields as $key => $value) {
                    $this->getConnection()->Execute('ALTER TABLE ' . $this->tableName . ' ADD ' . $key . ' ' . $value);
                }
            }
        }
    }

    function doesTableExist() : bool {
        $response = $this->getConnection()->Execute('SHOW TABLES');
        
        while (!$response->EOF) {
            if ($response->fields['Tables_in_' . getenv('DB_DATABASE')] == $this->tableName) {
                return true;
            }
            $response->moveNext();
        }
        return false;
    }

    function doesColumnExist(string $columnName) : bool {
        $sql = 'SHOW COLUMNS FROM ' . $this->tableName . ' LIKE "' . $columnName . '"';
        $result = $this->dbConnection->GetOne($sql);
        return $result === 1;
    }

    function fieldExists($field) {
        if (isset($this->tableColumns[$field])) {
            return true;
        }
        return false;
    }

    function upTable() {
        $tableCreationQuery = 'CREATE TABLE IF NOT EXISTS ' . $this->tableName . ' (';

        foreach ($this->tableColumns as $key => $value) {
            $tableCreationQuery .= '`' . $key . '`' . ' ' . $value . ',' . "\n";
        }

        $tableCreationQuery = substr($tableCreationQuery, 0, -1);
        $tableCreationQuery = rtrim($tableCreationQuery, ',') . "\n";
        $tableCreationQuery .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT=\'' . $this->tableComment . '\';';
        try {
            $this->getConnection()->Execute($tableCreationQuery);
        } catch (Exception $e) {
            throw new DataTableCreationFailedException(message: 'Failed to create table ' . $this->tableName , code: 500, previous: $e);
        }

        if (isset($this->tableIndexes)) {
            foreach ($this->tableIndexes as $key => $value) {
                $this->upIndex($key, $value);
            }
        }
    }

    function tableExists() {
        $response = $this->getConnection()->Execute('SHOW TABLES');

        while (!$response->EOF) {
            if ($response->fields['Tables_in_' . getenv('DB_DATABASE')] == $this->tableName) {
                return true;
            }
            $response->moveNext();
        }
        return false;
    }


    function dropTable() {
        try {
            if ($this->tableExists())
                $this->getConnection()->Execute('DROP TABLE ' . $this->tableName);
        } catch (Exception $e) {
            if (getenv("APP_DEBUG") == "true") {
                throw new DataTableDropFailedException(message: 'Failed to drop table ' . $this->tableName , code: 500, previous: $e);
                die;
            }
        }
    }

    function upColumn(string $columnName, string $columnType) {
        $sql = 'ALTER TABLE ' . $this->tableName . ' ADD ' . $columnName . ' ' . $columnType . ';';
        $this->dbConnection->Execute($sql);
    }

    function dropColumn(string $columnName) {
        $sql = 'ALTER TABLE ' . $this->tableName . ' DROP ' . $columnName . ';';
        $this->dbConnection->Execute($sql);
    }

    function doesIndexExist(string $indexName) : bool {
        $sql = 'SHOW INDEX FROM ' . $this->tableName . ' WHERE Key_name = "' . $indexName . '"';
        $result = $this->dbConnection->Execute($sql);
        $result = $result->RecordCount();
        return $result === 1;
    }

    function upIndex(string $indexName, string $indexColumns) {
        if($this->doesIndexExist($indexName)) {
            $this->dropIndex($indexName);
        }
        $sql = 'CREATE INDEX ' . $indexName . ' ON ' . $this->tableName . ' (' . $indexColumns . ');';
        $this->dbConnection->Execute($sql);
    }

    function dropIndex(string $indexName) {
        $sql = 'DROP INDEX ' . $indexName . ' ON ' . $this->tableName . ';';
        $this->dbConnection->Execute($sql);
    }
    
    function __destruct() {
        $this->getConnection()->Close();
    }

    function getConnection() : ADOConnection {
        return $this->dbConnection;
    }
    
    function get(?object $request = null): object|bool {
        throw new NotImplementedException(message: 'Functionaly not yet implemented for method: ' . __METHOD__, code: 500);
    }

    function list(?object $request = null): object|bool {
        throw new NotImplementedException(message: 'Functionaly not yet implemented for method: ' . __METHOD__, code: 500);
    }

    function findById(?int $id = null): object|bool {
        throw new NotImplementedException(message: 'Functionaly not yet implemented for method: ' . __METHOD__, code: 500);
    }

    function store(?object $request = null): object|bool|int {
         $fields = [];
        if (isset($_SESSION['user'], $_SESSION['user']->id)) {
            $fields['created_by'] = $_SESSION['user']->id;
        }
        $fields['created_at'] = date('Y-m-d H:i:s');
        foreach ($request as $key => $value) {
            if ($key == 'id') {
                continue;
            }
            if ($key == 'password' && password_needs_rehash($value, CRYPT_SHA512, ['cost' => 12])) {
                $fields['password'] = password_hash($value, CRYPT_SHA512, ['cost' => 12]);
                continue;
            }

            if ($value == 'on' || $value == '1') {
                $fields[$key] = 1;
                continue;
            }
            if (is_array($value)) {
                $fields[$key] = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                continue;
            }
            $fields[$key] = $value;
        }

        $this->getConnection()->AutoExecute($this->tableName, $fields, 'INSERT');

        return $this->getConnection()->Insert_ID();
    }

    function update(?object $request = null): object|bool|int {
        $where = $request->where ?? null;
        $fields = [];
        foreach ($request as $key => $value) {

            // Check if the key is 'id' and skip it as we don't want to update the id
            if ($key == 'id') {
                continue;
            }
            if ($key == 'password' && $value !== null && trim($value) !== '' && password_needs_rehash($value, CRYPT_SHA512, ['cost' => 12])) {
                $fields['password'] = password_hash($value, CRYPT_SHA512, ['cost' => 6]);
                continue;
            }

            if ($value == 'on' || $value == '1') {
                $fields[$key] = 1;
                continue;
            }
            $fields[$key] = $value;
        }

        if (isset($_SESSION['user'], $_SESSION['user']->id)) {
            $fields['updated_by'] = $_SESSION['user']->id;
        }
        // Do not continue if there is no where clause
        if ($where == null) {
            throw new NoQueryEspecifiedException(message: 'No where clause specified', code: 500);
        }
        $this->getConnection()->AutoExecute($this->tableName, $fields, 'UPDATE', $where);

        return $this->getConnection()->Affected_Rows();
    }

    function delete(?int $id = null): object|bool {
        throw new NotImplementedException(message: 'Functionaly not yet implemented for method: ' . __METHOD__, code: 500);
    }

    function active(?int $id = null): object|bool {
        throw new NotImplementedException(message: 'Functionaly not yet implemented for method: ' . __METHOD__, code: 500);
    }

    function block(?int $id = null): object|bool {
        throw new NotImplementedException(message: 'Functionaly not yet implemented for method: ' . __METHOD__, code: 500);
    }

    function unblock(?int $id = null): object|bool {
        throw new NotImplementedException(message: 'Functionaly not yet implemented for method: ' . __METHOD__, code: 500);
    }

    function login(?object $request = null): object|bool {
        $query = $this->dbConnection->Prepare('SELECT * FROM ' . $this->tableName . ' WHERE email = ? OR username = ? or phone = ? LIMIT 1;');
        $response = $this->dbConnection->Execute($query,[$request->username, $request->username, $request->username]);

        $result = $this->fr2Arr($response);
        if (count($result) == 0) {
            throw new UserNotFoundException(message: 'User not found', code: 404);
        }
        if (!password_verify($request->password, $result[0]['password'])) {
            throw new InvalidPasswordException(message: 'Invalid password', code: 401);
        }

        // remove the password from the response
        unset($result[0]['password']);
        
        return (object)$result[0] ?? throw new UserNotFoundException(message: 'User not found', code: 404);
    }

    function loginAs(?int $id = null): object|bool {
        throw new NotImplementedException(message: 'Functionaly not yet implemented for method: ' . __METHOD__, code: 500);
    }
    
    function logout(?int $id = null): object|bool {
        $fields = object(
            updated_by: 0,
            updated_at: date('Y-m-d H:i:s'),
            status: 2,
        );

        $this->getConnection()->AutoExecute($this->tableName, $fields, 'UPDATE', 'id = ' . $id);

        return object(success: true, id: $this->getConnection()->Affected_Rows());
    }

    function getCount(?string $where = null): int {
        throw new NotImplementedException(message: 'Functionaly not yet implemented for method: ' . __METHOD__, code: 500);
    }

    function find(?object $request = null): object|bool {
        $query = $this->dbConnection->Prepare('SELECT * FROM ' . $this->tableName . ' WHERE id = ? LIMIT 1;');
        $response = $this->dbConnection->Execute($query,[$request->id]);
        $result = $this->fr2Arr($response);

        unset($result[0]['password']);

        return $result ? (object)$result[0] : false;
    }

    function findByEmail(?string $email = null): object|bool {
        $query = $this->dbConnection->Prepare('SELECT * FROM ' . $this->tableName . ' WHERE email = ?;');
        $response = $this->dbConnection->Execute($query,[$email]);
        $result = $this->fr2Arr($response);
        return $result ? (object)$result[0] : false;
    }

    function fr2Arr(ADORecordSet $recordSet) : array|bool {
        $result = [];
        while (!$recordSet->EOF) {
            $result[] = $recordSet->fields;
            $recordSet->MoveNext();
        }
        return $result;
    }
}