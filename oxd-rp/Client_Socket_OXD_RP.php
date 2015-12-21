<?php
/**
 * Created Vlad Karapetyan
 */


class Client_Socket_OXD_RP{

    protected static $socket = null;
    protected  $base_url = './';
    /*
     * Socket_oxd constructor.
    */
    public function __construct()
    {
        $this->base_url = __FILE__.'oxd-rp/';
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

}