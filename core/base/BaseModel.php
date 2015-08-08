<?php

namespace App\Core\Base;

use App\Core\DB\DB;

abstract class BaseModel {

    /**
     * @var DB
     */
    protected static $db;

    /**
     * @var
     */
    protected static $collection;


    /**
     * DB fields
     * @var array
     */
    protected $data = [];



    public function __construct() {

        self::init();
    }


    public static function init() {

        self::$db = new DB();

        self::$collection = self::$db->db->selectCollection(static::$collection);

    }

    /**
     * @param $collection
     * @return mixed
     */
    public static function setCollection($collection) {

        self::init();

        return self::$collection = self::$db->db->selectCollection($collection);
    }


    /**
     * @return mixed
     */
    public static function getCollection() {

        self::init();

        return self::$collection;
    }


    /**
     * Create new record
     * @param $data
     * @return mixed
     */
    public static function create($data) {

        self::init();

        if (is_array($data))
            return  self::$collection->insert($data);
    }


    /**
     * Update a record
     * @param $id
     * @param array $data
     * @return bool
     */
    public static function update($id,$data= array()){
        self::init();

        if (strlen($id) == 24){
            $id = new \MongoId($id);
        }

        if (is_array($data))
            $result  = self::$collection->update(
                ['_id' => $id],
                ['$set' => $data]
            );

        if (!$id){
            return false;
        }
        return $result;
    }


    /**
     * @return mixed
     */
    public static function getAll() {

        self::init();

        return self::$collection->find();
    }


    /**
     * get one article by id
     * @return array
     */
    public static function getById($id){

        self::init();

        if (strlen($id) == 24){
            $id = new \MongoId($id);
        }

        $cursor  = self::$collection->find(array('_id' => $id));

        $record = $cursor->getNext();
        if (!$record ){
            return false ;
        }

        return $record;
    }







}