<?php

namespace Codemax\WHMSonic;


interface Interfaces
{
    public function setHost($host);
    public function setUser($user);
    public function setPassword($pass);
    public function setPort($port);
    public function getHost();
    public function getUser();
    public function getPassword();
    public function getPort();
}