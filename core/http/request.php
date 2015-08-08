<?
namespace App\Core\Http;



class Request {

    private $_controller;
    private $_method;
    private $_args;
    private $url;


    public function __construct($url) {

        if ($url)
            $this->url = $url;

        $parts = explode('/',$this->url);
        $parts = array_filter($parts);

        $this->_controller = ($c = array_shift($parts))? $c: 'index';

        $this->_controller = str_replace('?','',$this->_controller);

        $this->_method = ($c = array_shift($parts))? $c: 'index';

        $this->_args = (isset($parts[0])) ? $parts : array();



    }


    /**
     * @return mixed
     */
    public function getController(){
        return $this->_controller;
    }


    /**
     * @return mixed|string
     */
    public function getMethod(){
        return $this->_method;
    }

    /**
     * @return array
     */
    public function getArgs(){
        return $this->_args;
    }

}