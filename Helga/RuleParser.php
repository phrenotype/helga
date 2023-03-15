<?php

namespace Helga;

class RuleParser
{

    const RULES = [

        'mimes',
        'mimeTypes',
    ];


    private $__rules__;

    public function __construct($rules)
    {
        $this->__rules__ = $rules;
    }

    public function parse($subject, $key = null)
    {

        $state = new ValidationState;
        $errors = [];

        if (is_string(array_keys($this->__rules__)[0])) {
            $state->isSingle = false;
            $state->failed = false;
            $state->passed = true;
            foreach ($this->__rules__ as $k => $rule) {
                if (!isset($state->errors[$k])) {
                    $state->errors[$k] = [];
                }
                $value = $subject[$k] ?? null;
                $validator = new Validator($value, $k);
                $validator->withRules($rule);
                $state->errors[$k] = array_merge($state->errors[$k], $validator->errors());
                $state->failed = ($state->failed || $validator->fails());
                $state->passed = ($state->passed && $validator->passes());
            }
            foreach ($state->errors as $i => $arr) {
                if (empty($arr)) {
                    unset($state->errors[$i]);
                }
            }
            return $state;
        } else if (is_int(array_keys($this->__rules__)[0])) {

            $state->isSingle = true;

            foreach ($this->__rules__ as $rule) {

                preg_match("/^\w+(?=:.*)?/", $rule, $function);
                $function = $function[0] ?? null;

                if ($function !== 'required' && ($subject == null || trim($subject) == '')) {
                    continue;
                }

                if (preg_match("/^(file|mime)/", $function) && $function !== 'fileRequired' && (!is_readable($subject))) {
                    continue;
                }


                preg_match("/^(\w+):([^:]*)/", $rule, $params);
                if ($params[2] ?? false) {
                    $params = [$params[2]];
                } else {
                    $params = [];
                }

                preg_match("/^(\w+):([^:]*):(.*)$/", $rule, $matches);
                $message = $matches[3] ?? null;

                array_unshift($params, $subject);

                if ($key) {
                    $params[] = $key;
                }

                //echo $function . '|' . join(',', $params) . PHP_EOL;

                if (!method_exists(Executioner::class, $function)) {
                    throw new \Error(sprintf("Unknown rule directive '%s'.", $function));
                }

                $eval = call_user_func_array(Executioner::class . "::" . $function, $params);
                
                if (!$eval->return) {
                    if ($message) {
                        $eval->message = $message;
                    }
                    $errors[] = $eval->message;
                }
            }
        } else {
            throw new \Error("Invalid rules format");
        }

        if (empty($errors)) {
            $state->failed = false;
            $state->passed = true;
        } else {
            $state->failed = true;
            $state->passed = false;
        }
        $state->errors = $errors;
        return $state;
    }
}
