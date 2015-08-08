<?php

require_once 'core/db/db.php';
require_once 'core/curl.php';


$db = new \App\Core\DB\DB();

function getWeatherByCity($id) {

    $curl = new \App\Core\Curl();

    $result = [];

    $curl->get('http://pogoda.ngs.ru/api/v1/forecasts/current', array(
        'city' => $id
    ));

    $json = $curl->toArray();

    $result['temperature'] = $json['forecasts'][0]['temperature'];
    $result['pressure'] = $json['forecasts'][0]['pressure'];
    $result['humidity'] = $json['forecasts'][0]['humidity'];


    return $result;
}



$cities = $db->db->selectCollection('city');

$archive = $db->db->selectCollection('archive');


foreach ($cities->find() as $city) {

    $weather = getWeatherByCity($city['alias']);

    $archive->insert([
        "updated_at" =>  new MongoDate(),
        "cityid" => $city['_id'],
        "temperature" => $weather['temperature'],
        "humidity" => $weather['humidity']
    ]);

}