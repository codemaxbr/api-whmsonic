<?php

namespace Codemax\WHMSonic;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class API implements Interfaces
{
    use Functions;

    private $host;
    private $user;
    private $password;
    private $port;
    public $response = array();
    public $error = array();
    public $success = array();

    /**
     * API constructor.
     * @param $host
     */
    public function __construct($options = array())
    {
        if(!$this->checkOptions($options))
        {
            $this->setHost($options['host']);
            $this->setUser($options['user']);
            $this->setPassword($options['password']);
            $this->setPort($options['port']);
        }
    }

    public function getHost()
    {
        return $this->host;
    }

    public function reportError($param, $message)
    {
        $array = [
            'param' => $param,
            'verbose' => $message,
        ];

        array_push($this->error, $array);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function responseJSON()
    {
        if(count($this->error) != 0)
        {
            $response = [
                'status' => 'error',
                'errors' => $this->error,
            ];

            echo \GuzzleHttp\json_encode($response);
        }else{
            $response = [
                'status' => 'success',
                'response' => $this->success
            ];

            echo \GuzzleHttp\json_encode($response);
        }
    }

    private function checkOptions($options)
    {
        if (empty($options['host'])) {
            $this->reportError('host', 'Servidor não configurado.');
        }

        if (empty($options['user'])) {
            $this->reportError('user', 'Usuário não configurado.');
        }

        if (empty($options['password'])) {
            $this->reportError('password', 'Senha não configurada.');
        }

        if (empty($options['port'])) {
            $this->reportError('port', 'Porta não configurada.');
        }
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    public function setPassword($pass)
    {
        $this->password = $pass;
        return $this;
    }

    protected function runQuery($arguments, $throw=false)
    {
        $host = $this->getHost();
        $user = $this->getUser();
        $pass = $this->getPassword();
        $port = $this->getPort();

        $client = new Client(['base_uri' => $host.':'.$port]);

        try{
            $response = $client->request('POST', '/whmsonic/modules/api.php', [
                'auth' => [$user, $pass],
                'query' => $arguments,
            ]);

            $return = (string) $response->getBody();

            if ($return == "Complete"){
                $success = [
                    'status' => 1,
                    'verbose' => "sucesso"
                ];

                array_push($this->success, $success);
            }
            elseif ($return == "active"){
                $success = [
                    'status' => 1,
                    'verbose' => $return
                ];

                array_push($this->success, $success);
            }

            elseif ($return == "suspended"){
                $success = [
                    'status' => 1,
                    'verbose' => $return
                ];

                array_push($this->success, $success);
            }

            else{
                if(strpos($return,"Login Attempt Failed!") == true){
                    $this->reportError('unauthorized', 'O Servidor WHMSonic ('.$this->getHost().') falhou na tentativa de login.');
                }elseif (strpos($return,"has already radio setup") == true){
                    $this->reportError('existe', 'Já existe um usuário cadastrado com este nome de usuário: '.$arguments['rad_username']);
                }

                else{
                    $this->reportError('desconhecido', $return);
                }
            }

            $this->responseJSON();
        }
        catch(RequestException $e)
        {
            $erro = $e->getHandlerContext();
            $this->reportError('host', 'Não foi possível se conectar ao Servidor: '.$this->getHost());
            $this->responseJSON();
        }
    }
}