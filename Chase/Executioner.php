<?php

namespace Chase;

class Executioner
{

    public static function exec(callable $condition, callable $messenger, $key = null)
    {
        $r = new ExecutionResponse;
        if ($condition()) {
            $r->return = true;
        } else {
            $r->return = false;
            $messenger($r);
        }
        return $r;
    }

    public static function eq($target, $value, $key = null)
    {
        return self::exec(function () use ($value, $target) {            
            return ($value == $target);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must be equal to %s.", ucwords($key ?? 'Value'), $value);
        }, $key);
    }

    public static function neq($target, $value, $key = null)
    {
        return self::exec(function () use ($value, $target) {
            return ($value != $target);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s cannot be equal to %s.", ucwords($key ?? 'Value'), $value);
        }, $key);
    }

    public static function lt($target, $value, $key = null)
    {
        return self::exec(function () use ($value, $target) {
            return ($value < $target);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must be less than %s.", ucwords($key ?? 'Value'), number_format($value));
        }, $key);
    }

    public static function gt($target, $value, $key = null)
    {
        return self::exec(function () use ($value, $target) {
            return ($value > $target);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must be greater than %s.", ucwords($key ?? 'Value'), number_format($value));
        }, $key);
    }

    public static function lte($target, $value, $key = null)
    {
        return self::exec(function () use ($value, $target) {
            return ($value <= $target);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must be less than or equal to %s.", ucwords($key ?? 'Value'), number_format($value));
        }, $key);
    }

    public static function gte($target, $value, $key = null)
    {
        return self::exec(function () use ($value, $target) {
            return ($value >= $target);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must be greater than or equal to %s.", ucwords($key ?? 'Value'), number_format($value));
        }, $key);
    }

    public static function min($target, $value, $key = null)
    {
        return self::exec(function () use ($value, $target) {
            return ($value >= $target);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must be at least %s", ucwords($key ?? 'Value'), number_format($value));
        }, $key);
    }

    public static function max($target, $value, $key = null)
    {
        return self::exec(function () use ($value, $target) {
            return ($value <= $target);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must be at most %s", ucwords($key ?? 'Value'), number_format($value));
        }, $key);
    }

    public static function minLen($target, $value, $key = null)
    {
        return self::exec(function () use ($target, $value) {
            return (mb_strlen($target) >= (int)$value);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must have at least %s characters.", ucwords($key ?? 'value'), number_format($value));
        }, $key);
    }

    public static function maxLen($target, $value, $key = null)
    {
        return self::exec(function () use ($target, $value) {
            return (mb_strlen($target) <= (int)$value);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must have at most %s characters.", ucwords($key ?? 'value'), number_format($value));
        }, $key);
    }

    public static function len($target, $value, $key = null)
    {
        return self::exec(function () use ($target, $value) {
            return (mb_strlen($target) === (int)$value);
        }, function ($r) use ($value, $key) {
            $r->message = sprintf("%s must have %s characters.", ucwords($key ?? 'value'), number_format($value));
        }, $key);
    }


    public static function regex($target, $regex, $key = null)
    {
        return self::exec(function () use ($target, $regex) {
            return preg_match($regex, $target);
        }, function ($r) use ($regex, $key) {
            $r->message = sprintf("%s's format is invalid.", ucwords($key ?? 'value'), $regex);
        }, $key);
    }


    public static function in($target, $value, $key = null)
    {
        $array = explode(',', $value);
        return self::exec(function () use ($target, $array) {
            return (in_array($target, $array));
        }, function ($r) use ($array, $key) {
            $r->message = sprintf("%s must be one of %s.", ucwords($key ?? 'value'), join(", ", $array));
        }, $key);
    }


    public static function integer($target, $key = null)
    {
        return self::exec(function () use ($target) {
            return (ctype_digit($target));
        }, function ($r) use ($key) {
            $r->message = sprintf("%s can only contain numbers.", ucwords($key ?? 'value'));
        }, $key);
    }

    public static function alnum($target, $key = null)
    {
        return self::exec(function () use ($target) {
            return (ctype_alnum($target));
        }, function ($r) use ($key) {
            $r->message = sprintf("%s can only contain alphabets and numbers.", ucwords($key ?? 'value'));
        }, $key);
    }

    public static function alpha($target, $key = null)
    {
        return self::exec(function () use ($target) {
            return (ctype_alpha($target));
        }, function ($r) use ($key) {
            $r->message = sprintf("%s can only contain alphabets.", ucwords($key ?? 'value'));
        }, $key);
    }

    public static function float($target, $key = null)
    {
        return self::exec(function () use ($target) {
            return (filter_var($target, FILTER_VALIDATE_FLOAT) !== false);
        }, function ($r) use ($key) {
            $r->message = sprintf("%s must have a decimal point.", ucwords($key ?? 'value'));
        }, $key);
    }

    public static function email($target, $key = null)
    {
        return self::exec(function () use ($target) {
            return (filter_var($target, FILTER_VALIDATE_EMAIL) !== false);
        }, function ($r) use ($key) {
            $r->message = sprintf("%s must be an email.", ucwords($key ?? 'value'));
        }, $key);
    }

    public static function url($target, $key = null)
    {
        return self::exec(function () use ($target) {
            return (filter_var($target, FILTER_VALIDATE_URL) !== false);
        }, function ($r) use ($key) {
            $r->message = sprintf("%s must be a valid URL.", ucwords($key ?? 'value'));
        }, $key);
    }

    public static function required($target, $key = null)
    {
        return self::exec(function () use ($target) {
            return (isset($target) && trim($target) !== '');
        }, function ($r) use ($key) {
            $r->message = sprintf("%s is required.", ucwords($key ?? 'value'));
        }, $key);
    }

    public static function unique($target, $value, $key = null)
    {
        return self::exec(function () use ($value) {
            if ($value == 'true') {
                return false;
            } else {
                return true;
            }
        }, function ($r) use ($key) {
            $r->message = sprintf("%s already exists.", ucwords($key ?? 'value'));
        }, $key);
    }

    public static function exists($target, $value, $key = null)
    {
        return self::exec(function () use ($value) {
            if ($value == 'true') {
                return true;
            } else {
                return false;
            }
        }, function ($r) use ($key) {
            $r->message = sprintf("%s must exist.", ucwords($key ?? 'value'));
        }, $key);
    }
}