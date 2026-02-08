<?php

declare(strict_types=1);

use function PHPSTORM_META\override;

/**
 * @file 
 * @package 
 * @author Vinícius Gonçalves Cordeiro <vinicordeirogo@gmail.com>
 * @link https://github.com/vinicius-g-cordeiro/
 * @version 0.0.1
 * @copyright 2026 Vinicius Gonçalves Cordeiro
 */

require __DIR__ . '/../vendor/autoload.php';

function object(...$arguments) {
    if(empty($arguments)) return new stdClass();
    $object = new stdClass();
    foreach($arguments as $key => $value) {
        $object->{$key} = $value;
    }
    return $object;
}

function object_merge(object $object, object $otherObject) : object {
    return (object) array_merge((array)$object,(array)$otherObject);
}

function object_merge_recursive(object $object, object $otherObject) : object {
    return (object) array_merge_recursive((array)$object,(array)$otherObject);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 0);
error_reporting(E_NOTICE | E_ERROR);

date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');