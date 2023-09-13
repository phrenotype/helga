<?php

namespace Helga;

class ValidationState
{
    public $isSingle;
    public $errors = [];

    public function passed(){
        return empty($this->errors) === true;
    }

    public function failed(){
        return empty($this->errors) === false;
    }
}
