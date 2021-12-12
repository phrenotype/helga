<?php

namespace Chase;

/**
 * A function that wraps instantiation of Chase\Validator Objects
 * 
 * @param mixed $subject The item to be validated
 * @param string $key The key, when being used for multiple values.
 * 
 * @return Chase\Validator Returns An Instance of Chase\Validator
 */
function validate($subject, $key = null)
{
    return (new Validator($subject, $key));
}
