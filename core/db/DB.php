<?php

namespace App\Core\DB;

use MongoClient;

class DB {

    private $config = [
        'db_name' => 'ngs'
    ];


    /**
     * Returns db
     * @var
     */
    public $db;



    function __construct() {

        $this->connect();
    }


    /**
     * Trying to connect to mongo server
     */
    private function connect() {

        try {
            if ( !class_exists('Mongo')){
                echo ("The MongoDB PECL extension has not been installed or enabled");
                return false;
            }

            $connection = new MongoClient("mongodb://localhost");


            return $this->db = $connection->selectDB($this->config['db_name']);


        } catch (MongoConnectionException $e) {

            die('Error connecting to MongoDB server');

        } catch (Exception $e) {

            echo $e->getMessage();
        }

    }

}