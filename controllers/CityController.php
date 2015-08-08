<?php

namespace App\Controllers;

use App\Core\Base\BaseController;
use App\Core\Curl;
use App\Models\AllcityModel;
use App\Models\ArchiveModel;
use App\Models\CityModel;
use MongoId;
use MongoDate;


class CityController extends BaseController{

    private $curl;

    private $weather;


    function __construct() {

        parent::__construct();

        $this->curl = new Curl();

        $model = CityModel::getCollection();

        if (isset($_COOKIE['city_alias'])) {

            $alias = $model->findOne(array('alias' => $_COOKIE['city_alias']));

        } else  $alias = $model->findOne();

        if ($alias)
            $this->setWeather($alias);

    }


    /**
     * Index page
     */
    public function index() {

        $cursor = CityModel::getAll();

        $this->view->render('index',['cursor' => $cursor,
                                     'weather' => $this->weather]);

    }

    /**
     * Add new city
     */
    public function add() {

        if ($_POST) {

            CityModel::create(['name' => $_POST['name'],
                               'alias' => $_POST['alias']]);

            $this->redirect('/city');
        }


        $model = AllcityModel::getCollection()->find();

        $col = $model->fields(array("title" => true));

        $cities = [];

        foreach ($col as $city) {

            $cities[] = $city['title'];

        }

        $this->view->render('add',['weather' => $this->weather,
                                   'cities' => json_encode($cities)]);
    }

    /**
     * Show city
     * @param $alias
     * @throws \Exception
     */
    public function show($alias) {

        $city = CityModel::getCollection();

        $name = $city->findOne(array('alias' => $alias));

        if (!$name)
            throw new \Exception('City not found');


        setcookie("city_alias", $name['alias'], strtotime( '+30 days' ),"/" );

        $forecast = $this->getForecastByCity($name['alias'],3);

        $this->setWeather($name);

        $model = ArchiveModel::getCollection();


        $start = new MongoDate(strtotime("-3 days"));
        $end = new MongoDate(strtotime(date("Y-m-d H:i:s")));

        #получаем погоду за 3 дня из архива
        $archive = $model->aggregate(array(
            array(
                '$match' => array(
                    'updated_at' => array(
                        '$gt' => $start,
                        '$lte' => $end
                    ),
                    'cityid' => new MongoId($name['_id'])
                ),
            ),
            array(
                '$project' => array(
                    'cityid' => 1,
                    'updated_at' => 1,
                    'temperature' => 1,
                    'humidity' => 1,
                    'year' => array('$year' => '$updated_at' ),
                    'month' => array('$month' => '$updated_at' ),
                    'day' => array('$dayOfMonth' => '$updated_at'),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => array('year' => '$year', 'month' => '$month', 'day' => '$day'),
                    'averageTemp' => array( '$avg' => '$temperature'),
                    'averageHum' => array( '$avg' => '$humidity')

                ),
            ),
        ));

        $this->view->render('show',['weather' => $this->weather,
                                    'archive' => $archive,
                                    'city' => $name,
                                    'forecast' => $forecast]);

    }

    /**
     * delete city
     * @param $id
     * @return mixed
     */
    public function delete($id) {

        CityModel::getCollection()->remove(['_id' => new MongoId($id)]);

        $this->redirect('/city');
    }

    /**
     * @param $id
     */
    public function edit($id) {

        $model = CityModel::getCollection();

        $city = $model->findOne(['_id' => new MongoId($id)]);

        $this->view->render('edit', ['city' => $city,
                                     'weather' => $this->weather]);

    }


    /**
     * @param $id
     */
    public function update($id) {

        if ($_POST) {
            CityModel::update($id,[
                'name' => $_POST['name'],
                'alias' => $_POST['alias']
            ]);

            $this->redirect('/city');
        }
    }


    /**
     * Set weather by city alias
     * @param $alias
     * @return mixed
     */
    private function setWeather($alias) {

        $this->weather['weather'] = $this->getWeatherByCity($alias['alias']);
        $this->weather['city'] = $alias['name'];

        return $this->weather;
    }


    /**
     * @param $id
     * @return array
     */
    private function getWeatherByCity($id) {

        $result = [];

        $this->curl->get('http://pogoda.ngs.ru/api/v1/forecasts/current', array(
            'city' => $id
        ));

        $json = $this->curl->toArray();

        $result['temperature'] = $json['forecasts'][0]['temperature'];
        $result['pressure'] = $json['forecasts'][0]['pressure'];
        $result['humidity'] = $json['forecasts'][0]['humidity'];


        return $result;
    }


    /**
     * @param $city
     * @param $days
     * @return array
     */
    private function getForecastByCity($city, $days)
    {

        $result = [];

        $this->curl->get('http://pogoda.ngs.ru/api/v1/forecasts/forecast', array(
            'city' => $city
        ));

        $json = $this->curl->toArray();


        for ($i = 0; $i < $days; $i++) {
            $result[$i]['date'] = $json['forecasts'][$i]['date'];
            $result[$i]['temperature'] = $json['forecasts'][$i]['hours'][2]['temperature']['avg'];
            $result[$i]['humidity'] = $json['forecasts'][$i]['hours'][2]['humidity']['avg'];
        }

        return $result;
    }


    /**
     * Parse all cities and insert in db
     */
    private function addCities() {

        $this->curl->get('http://pogoda.ngs.ru/api/v1/cities');

        $json = $this->curl->toArray();

        foreach ($json['cities'] as $city) {
            AllcityModel::create($city);
        }
    }

}