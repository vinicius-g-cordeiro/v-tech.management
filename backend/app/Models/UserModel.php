<?php

declare(strict_types=1);

/**
 * @file UserModel.php
 * @package 
 * @author Vinícius Gonçalves Cordeiro <vinicordeirogo@gmail.com>
 * @link https://github.com/vinicius-g-cordeiro/
 * @version 0.0.1
 * @copyright 2026 Vinicius Gonçalves Cordeiro
 */

namespace App\Models;

use ADOConnection;
use App\Core\Model;


class UserModel extends Model {
    protected ADOConnection $dbConnection;

    protected string $tableName { 
        get {
            return 'users';
        }
    }

    protected string $tableKey {
        get {
            return 'id';
        }
    }

    protected string $tableAlias {
        get {
            return 'u';
        }
    }

    protected ?object $tableColumns {
        get {
            return object(
                id: 'int NOT NULL AUTO_INCREMENT PRIMARY KEY',
                username: 'varchar(255) NOT NULL',
                email: 'varchar(255) NOT NULL',
                password: 'varchar(72) NOT NULL',
                phone: 'varchar(25) NULL',
                role: 'tinyint NOT NULL DEFAULT 3 COMMENT "1 = Super Admin, 2 = Admin, 3 = User"',
                name: 'varchar(255) NOT NULL',
                lastname: 'varchar(255) NULL',
                surname: 'varchar(255) NULL',
                othername: 'varchar(255) NULL DEFAULT NULL',
                birthdate: 'date NULL DEFAULT NULL',
                gender: 'tinyint(1) NOT NULL DEFAULT 1 COMMENT "1 = Male, 2 = Female, 3 = Other"',
                sexual_orientation: 'tinyint(1) NOT NULL DEFAULT 1 COMMENT "1 = Straight, 2 = Gay, 3 = Lesbian, 4 = Bisexual, 5 = Transsexual, 6 = Other"',
                marital_status: 'tinyint(1) NOT NULL DEFAULT 1 COMMENT "1 = Single, 2 = Married, 3 = Divorced, 4 = Widowed, 5 = Other"',
                address: 'varchar(255) NULL DEFAULT NULL',
                address_number: 'varchar(100) NULL DEFAULT NULL',
                address_complement: 'varchar(255) NULL DEFAULT NULL',
                address_neighborhood: 'varchar(255) NULL DEFAULT NULL',
                address_city: 'varchar(255) NULL DEFAULT NULL',
                address_state: 'varchar(255) NULL DEFAULT NULL',
                address_zipcode: 'varchar(100) NULL DEFAULT NULL',
                address_country: 'varchar(255) NULL DEFAULT NULL',
                driver_license: 'tinyint(1) NOT NULL DEFAULT 1 COMMENT "1 = Yes, 2 = No"',
                driver_license_number: 'varchar(255) NULL DEFAULT NULL',
                driver_license_state: 'varchar(255) NULL DEFAULT NULL',
                driver_license_category: 'varchar(255) NULL DEFAULT NULL',
                driver_license_expiration_date: 'date NULL DEFAULT NULL',
                person_type: 'tinyint(1) NOT NULL DEFAULT 1 COMMENT "1 = Natural, 2 = Entity"',
                person_number: 'varchar(20) NULL DEFAULT NULL',
                entity_number: 'varchar(40) NULL DEFAULT NULL',
                id_number: 'varchar(20) NULL DEFAULT NULL',
                id_issuer: 'varchar(255) NULL DEFAULT NULL',
                id_issuer_state: 'varchar(255) NULL DEFAULT NULL',
                id_expiration_date: 'date NULL DEFAULT NULL',
                religion: 'tinyint(1) NOT NULL DEFAULT 1 COMMENT "1: Catholic, 2: Protestant, 3: Jewish, 4: Muslim, 5: Buddhist, 6: Hindu, 7: Other"',
                religion_other: 'varchar(255) NULL DEFAULT NULL',
                last_ip: 'varchar(255) NULL DEFAULT NULL',
                last_browser: 'varchar(255) NULL DEFAULT NULL',                
                avatar: 'varchar(255) NULL DEFAULT NULL',
                locale: 'varchar(255) NULL DEFAULT \'pt-br\'',
                timezone: 'varchar(255) NULL DEFAULT \'America/Sao_Paulo\'',
                pref_theme: 'varchar(255) NULL DEFAULT \'light\'',
                rebember_token: 'varchar(255) NULL DEFAULT NULL',
                email_verified_at: 'timestamp NULL DEFAULT NULL',
                email_token: 'varchar(255) NULL DEFAULT NULL',
                email_token_expires_at: 'timestamp NULL DEFAULT NULL',
                created_at: 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                updated_at: 'timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
                deleted_at: 'timestamp NULL DEFAULT NULL',
                lastlogin_at: 'timestamp NULL DEFAULT NULL',
                laststatus_at: 'timestamp NULL DEFAULT NULL',
                blocked_at: 'timestamp NULL DEFAULT NULL',
                terms_accepted_at: 'timestamp NULL DEFAULT NULL',
                privacy_policy_accepted_at: 'timestamp NULL DEFAULT NULL',
                created_by: 'int NOT NULL DEFAULT 1',
                updated_by: 'int NULL DEFAULT NULL',
                deleted_by: 'int NULL DEFAULT NULL',
                blocked_by: 'int NULL DEFAULT NULL',
                status: 'tinyint(1) NOT NULL DEFAULT 1 COMMENT "0: Never logged in, 1: Online, 2: Offline, 3: Away, 4 Busy"',
                active: 'tinyint(1) NOT NULL DEFAULT 1',
                blocked: 'tinyint(1) NOT NULL DEFAULT 0',
                terms_accepted: 'tinyint(1) NOT NULL DEFAULT 0',
                privacy_policy_accepted: 'tinyint(1) NOT NULL DEFAULT 0',
            );
        }
    }
    

    protected ?object $tableIndexes {
        get {
           return object(
                idx_username_email: ' username, email ',
                idx_name_number: ' name, person_number ',
                idx_person_number: ' person_number, person_type ',
                idx_entity_number: ' entity_number, person_type ',
           );
        }
    }
    protected string $tableComment {
        get {
            return 'Table of the users of the system, for authentication and authorization.';
        }
    }


    function __construct(?ADOConnection $dbConnection = null) {
        parent::__construct($dbConnection);
    }   
}