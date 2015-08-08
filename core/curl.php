<?php
namespace App\Core;


class Curl
{

    public $curl;

    private $options;

    public $result;


    function __construct() {

        if (!extension_loaded('curl')) {
            throw new \ErrorException('cURL library is not loaded');
        }

        $this->curl = curl_init();

        $this->setOpt(CURLOPT_SSL_VERIFYPEER, false);

        $this->setOpt(CURLOPT_RETURNTRANSFER, true);

    }

    /**
     * @param $option
     * @param $value
     * @return bool
     */
    public function setOpt($option, $value) {

        $required_options = array(
            CURLINFO_HEADER_OUT    => 'CURLINFO_HEADER_OUT',
            CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER',
        );
        if (in_array($option, array_keys($required_options), true) && !($value === true)) {
            trigger_error($required_options[$option] . ' is a required option', E_USER_WARNING);
        }
        $this->options[$option] = $value;
        return curl_setopt($this->curl, $option, $value);
    }

    /**
     * Get
     *
     * @access public
     * @param  $url
     * @param  $data
     *
     * @return string
     */
    public function get($url, $data = array())
    {

        $data = http_build_query($data);


        curl_setopt($this->curl, CURLOPT_URL,$url.'?'.$data);

        return $this->exec();
    }


    /**
     * @return mixed
     */
    public function exec() {

        $this->result = curl_exec($this->curl);

    }


    public function toArray() {

        return json_decode($this->result,true);
    }


    /**
     * Close
     *
     * @access public
     */
    public function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * close curl on destruct
     */
    function __destruct() {
        $this->close();
    }
}