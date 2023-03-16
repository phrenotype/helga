<?php

namespace Helga;

/**
 * A function that wraps instantiation of Helga\Validator Objects
 * 
 * @param mixed $subject The item to be validated
 * @param string $key The key, when being used for multiple values.
 * 
 * @return Helga\Validator Returns An Instance of Helga\Validator
 */
function validate($subject, $key = null)
{
    return (new Validator($subject, $key));
}
