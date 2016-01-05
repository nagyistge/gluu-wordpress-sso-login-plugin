<?php
/**
 * Created Vlad Karapetyan
 */

abstract class Client_OXD_RP{

    private $command_types = array( 'get_authorization_url','logout', 'get_tokens_by_code','get_user_info', 'register_site' );
    protected $data = array();
    protected $command;
    protected $params = array();
    protected $response_json;
    protected $response_object;
    protected $response_data = array();
    protected static $socket = null;


    /**
     * abstract Client_oxd constructor.
     */
    public function __construct()
    {
        $oxd_config = get_option('oxd_config');

        if(!$oxd_config['authorization_redirect_uri']){
            $this->error_message("authorization_redirect_uri can not be empty " );
        }
        if (filter_var($oxd_config['oxd_host_ip'], FILTER_VALIDATE_IP) === false) {
            $this->error_message($oxd_config['oxd_host_ip']." is not a valid IP address");
        }
        if($oxd_config['oxd_host_port']>=0 && $oxd_config['oxd_host_port']<=65535){

        }else{
            $this->error_message($oxd_config['oxd_host_port']."is not a valid port for socket. Port must be integer and between from 0 to 65535.");
        }
        $this->setCommand();
        $exist = false;
        for ($i = 0; $i < count($this->command_types); $i++) {

            if ($this->command_types[$i] == $this->getCommand()) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            $this->error_message('Command: ' . $this->getCommand() . ' is not exist!');
        }
    }
    /**
     * request to oxd socket
     **/
    public function oxd_socket_request($data,$char_count = 8192){
        $oxd_config = get_option('oxd_config');
        if (!self::$socket = stream_socket_client( $oxd_config['oxd_host_ip'] . ':' . $oxd_config['oxd_host_port'], $errno, $errstr, STREAM_CLIENT_PERSISTENT)) {
            die($errno);
        }
        fwrite(self::$socket, $data);
        $result = fread(self::$socket, $char_count);
        fclose(self::$socket);
        return $result;
    }

    /**
     * showing errors and exit.
     **/
    public function error_message($error)
    {
        die($error);
    }
    /**
     * send function sends the command to the oxD server.
     *
     * Args:
     * command (dict) - Dict representation of the JSON command string
     **/
    public function request()
    {
        $this->setParams();

        $jsondata = json_encode($this->getData(), JSON_UNESCAPED_SLASHES);

        if(!$this->is_JSON($jsondata)){
            $this->error_message('Sending parameters must be JSON.');
        }
        $lenght = strlen($jsondata);
        if($lenght<=0){
            $this->error_message("Length must be more than zero.");
        }else{
            $lenght = $lenght <= 999 ? "0" . $lenght : $lenght;
        }

        //var_dump(utf8_encode($lenght . $jsondata));
        $this->response_json =  $this->oxd_socket_request(utf8_encode($lenght . $jsondata));
        //var_dump( $this->response_json);exit;
        $this->response_json = str_replace(substr($this->response_json, 0, 4), "", $this->response_json);

        if ($this->response_json) {
            $object = json_decode($this->response_json);
            if ($object->status == 'error') {
                $this->error_message($object->data->error . ' : ' . $object->data->error_description);
            } elseif ($object->status == 'ok') {
                $this->response_object = json_decode($this->response_json);
            }
        } else {
            $this->error_message('Response is empty...');
        }
    }

    /**
     * @return mixed
     */
    public function getResponseData()
    {
        if (!$this->getResponseObject()) {
            $this->response_data = 'Data is empty';
            $this->error_message($this->response_data);
        } else {
            $this->response_data = $this->getResponseObject()->data;
        }
        return $this->response_data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->data = array('command' => $this->getCommand(), 'params' => $this->getParams());
        return $this->data;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    abstract function setCommand();

    /**
     * getResult function geting result from oxD server.
     * Return: response_object - The JSON response parsing to object
     **/
    public function getResponseObject()
    {
        return $this->response_object;
    }

    /**
     * function getting result from oxD server.
     * return: response_json - The JSON response from the oxD Server
     **/
    public function getResponseJSON()
    {
        return $this->response_json;
    }

    /**
     * @param array $params
     */
    abstract function setParams();

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * checking format string.
     * @param  string  $string
     * @return bool
     **/
    public function is_JSON($string){
        return is_string($string) && is_object(json_decode($string)) ? true : false;
    }

}