<?php

namespace Chase\Validator;

/**
 * Holds the value following a rule evaluation
 * 
 */
class ExecutionResponse
{
    /**
     * @var string $message The error message.
     */
    public $message;

    /**
     * @var bool $return The boolean representing success or failure.
     */
    public $return;
}
