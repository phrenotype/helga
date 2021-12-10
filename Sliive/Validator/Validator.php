<?php

namespace Sliive\Validator;

class Validator {

    private $__rules__;

    /**
     * 
     */
    private $__subject__;


    private $__isSingleSubject__ = false;

    public function __construct($subject)
    {
        
    }

    public function passes(){

    }

    public function fails(){

    }

    public function errors(){

    }

    public function unique(string $key, callable $callable){

        return $this;
    }

    public function exists(string $key, callable $callable){

        return $this;
    }

    public function withRules(array $rules){

        return $this;
    }
}