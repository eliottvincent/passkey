<?php

/**
 * Created by PhpStorm.
 * User: chloecorfmat
 * Date: 30/04/2017
 * Time: 22:04
 */
class Autoloader
{
    /**
     * Register autoloader
     */
    static function register(){

        // Needed for Composer loading (always needed).
        if (file_exists('vendor/autoload.php')) {
            require_once 'vendor/autoload.php';
        }

        spl_autoload_register('Autoloader::autoload');
    }

    /**
     * Import class file.
     * @param $class string Classname to include.
     */
    static function autoload($class){

        /**
         * HOW TO USE
         * Check if file exists,
         * If it exists (and only in this case), import the file.
         *
         * If the file doesn't exist whithout condition : error 500.
         */

        // MODEL.
        if (file_exists('sources/Model/DAO/'. $class . '.php')) {
            require_once 'sources/Model/DAO/'. $class . '.php';
        }

        if (file_exists('sources/Model/Service/'. $class . '.php')) {
            require_once 'sources/Model/Service/'. $class . '.php';
        }

        if (file_exists('sources/Model/VO/'. $class . '.php')) {
            require_once 'sources/Model/VO/'. $class . '.php';
        }

        // VIEW.

        // CONTROLLER.
    }
}