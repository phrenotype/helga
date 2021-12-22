<?php

namespace Chase\Validator;

/**
 * A function that wraps instantiation of Chase\Validator\Validator Objects
 * 
 * @param mixed $subject The item to be validated
 * @param string $key The key, when being used for multiple values.
 * 
 * @return Chase\Validator\Validator Returns An Instance of Chase\Validator\Validator
 */
function validate($subject, $key = null)
{
    return (new Validator($subject, $key));
}
