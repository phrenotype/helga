<?php

namespace Sliive\Validator;


function validate($subject){
    return (new Validator($subject));
}