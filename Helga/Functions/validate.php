<?php

namespace Helga;

/**
 * A function that wraps instantiation of Chase\Helga\Validator Objects
 * 
 * @param mixed $subject The item to be validated
 * @param string $key The key, when being used for multiple values.
 * 
 * @return Chase\Helga\Validator Returns An Instance of Chase\Helga\Validator
 */
function validate($subject, $key = null)
{
    return (new Validator($subject, $key));
}
