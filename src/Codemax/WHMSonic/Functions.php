<?php

namespace Codemax\WHMSonic;

trait Functions
{
    /**
     * Função que verifica o status da Conta
     *
     * @param $user
     * @return mixed
     */
    public function status($user)
    {
        return $this->runQuery([
            'cmd' => 'status',
            'rad_username' => $user
        ]);
    }

    /**
     * Função para Criar uma Streaming
     *
     * @param $args
     * @return mixed
     */
    public function createAccount($args)
    {
        try{
            if (empty($args['ouvintes']) || !isset($args['ouvintes'])) {
                $this->reportError('ouvintes', 'Quantidade de Ouvintes não definido.');
            }

            if (empty($args['bitrate']) || !isset($args['bitrate'])) {
                $this->reportError('bitrate', 'Qualidade Bitrate não definido.');
            }

            if (empty($args['disco']) || !isset($args['disco'])) {
                $this->reportError('disco', 'Espaço em Disco não definido.');
            }

            if (empty($args['senha']) || !isset($args['senha'])) {
                $this->reportError('senha', 'Senha não definida.');
            }

            if (empty($args['autodj']) || !isset($args['autodj'])) {
                $this->reportError('autodj', 'AutoDJ não definido.');
            }

            if (empty($args['trafego']) || !isset($args['trafego'])) {
                $this->reportError('trafego', 'Limite de Tráfego não definido.');
            }

            if (empty($args['nome']) || !isset($args['nome'])) {
                $this->reportError('nome', 'Cliente Nome não definido.');
            }

            if (empty($args['email']) || !isset($args['email'])) {
                $this->reportError('email', 'Cliente E-mail não definido.');
            }

            if (empty($args['usuario']) || !isset($args['usuario'])) {
                $this->reportError('usuario', 'Cliente Usuário não definido.');
            }

            return $this->runQuery([
                'cmd' => 'create',
                'ctype' => 'External',
                'ip' => $this->getHost(),
                'bitrate' => @$args['bitrate'],
                'autodj' => @$args['autodj'],
                'bw' => @$args['trafego'],
                'limit' => @$args['ouvintes'],
                'cname' => @$args['nome'],
                'cemail' => @$args['email'],
                'rad_username' => @$args['usuario'],
                'pass' => @$args['senha'],
                'hspace' => @$args['disco']
            ]);

        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * Função para Remover uma Conta
     *
     * @param $user
     * @return mixed
     */
    public function removeAccount($user)
    {
        return $this->runQuery([
            'cmd' => 'terminate',
            'rad_username' => $user
        ]);
    }

    /**
     * Função para Suspender uma Conta
     *
     * @param $user
     * @return mixed
     */
    public function suspendAccount($user)
    {
        return $this->runQuery([
            'cmd' => 'suspend',
            'rad_username' => $user
        ]);
    }

    /**
     * Função para Reativar uma Conta
     *
     * @param $user
     * @return mixed
     */
    public function unsuspendAccount($user)
    {
        return $this->runQuery([
            'cmd' => 'unsuspend',
            'rad_username' => $user
        ]);
    }
}