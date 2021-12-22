<?php

namespace Chase\Validator;

/**
 * A function that wraps instantiation of Chase\ValidatorValidator Objects
 * 
 * @param mixed $subject The item to be validated
 * @param string $key The key, when being used for multiple values.
 * 
 * @return Chase\ValidatorValidator Returns An Instance of Chase\ValidatorValidator
 */
function validate($subject, $key = null)
{
    return (new Validator($subject, $key));
}
