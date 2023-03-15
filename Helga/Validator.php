<?php

namespace Helga;

class Validator
{
    /**
     * Stores the key for single values
     */
    private $__key__;

    /**
     * Stores the rules    
     * 
     * @var array
     */
    private $__rules__;

    /**
     * Stores the variable or array to be validated
     * 
     * @var mixed
     */
    private $__subject__;

    /**
     * A boolean value that determines if an array or a single value is being validated
     * 
     * @var bool
     */
    private $__isSingleSubject__ = false;

    /**
     * An array that stores all the error messages
     * 
     * @var array
     */
    private $__errors__ = [];

    /**
     * The constructor method. It takes the item being validated as it's only parameter.
     * 
     * @param mixed $subject The item being validated.
     * @param string $key The key, when being used for multiple values.
     * 
     * @return void
     */
    public function __construct($subject, $key = null)
    {
        if (!is_array($subject)) {
            $this->__isSingleSubject__ = true;
        } else {
            $this->__isSingleSubject__ = false;
        }
        $this->__key__ = $key;
        $this->__subject__ = $subject;
    }

    /**
     * Returns a boolean value that indicates if the subject has passed validation.
     * 
     * @return bool
     */
    public function passes()
    {
        $vs = $this->eval();
        if ($vs->passed) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a boolean value that indicates if the subject failed validation.
     * 
     * @return bool
     */
    public function fails()
    {
        $vs = $this->eval();
        if ($vs->failed) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the validation errors.
     * 
     * @return array
     */
    public function errors()
    {
        $vs = $this->eval();
        return $this->__errors__;
    }

    /**
     * Returns a flattened array of validation errors.
     * 
     * @return string[]
     */
    public function flatErrors(bool $firstOnly = true)
    {
        if ($this->__isSingleSubject__) {
            return $this->errors();
        } else {
            if ($firstOnly) {
                $errors = array_values($this->errors());
                return array_reduce($errors, function ($c, $i) {
                    $c[] = $i[0];
                    return $c;
                }, []);
            } else {
                $errors = array_values($this->errors());
                return array_reduce($errors, function ($c, $i) {
                    return array_merge($c, $i);
                }, []);
            }
        }
    }

    /**
     * Adds a rule to check if a value is unique
     * 
     * @return Chase\Helga\Validator
     */
    public function unique(...$args)
    {
        if (count($args) === 2) {
            if ($this->__isSingleSubject__) {
                throw new \Error(sprintf("Single argument expected for %s::%s.", self::class, 'unique'));
            }
            $key = $args[0];
            $callable = $args[1];
            $val = $callable($this->__subject__);
            $val = ($val) ? "true" : "false";

            if (!isset($this->__rules__[$key])) {
                $this->__rules__[$key] = [];
            }
            $this->__rules__[$key][] = 'unique:' . $val;
        } else if (count($args) === 1) {
            if (!$this->__isSingleSubject__) {
                throw new \Error(sprintf("Two arguments expected for %s::%s.", self::class, 'unique'));
            }
            $callable = $args[0];
            $val = $callable($this->__subject__);
            $val = ($val) ? "true" : "false";

            $this->__rules__[] = 'unique:' . $val;
        } else {
            throw new \Error(sprintf("Invalid number of arguments for %s::%s", self::class, 'unique'));
        }
        return $this;
    }

    /**
     * Adds a rule to check if a value exists
     * 
     * @return Chase\Helga\Validator
     */
    public function exists(...$args)
    {
        if (count($args) === 2) {
            if ($this->__isSingleSubject__) {
                throw new \Error(sprintf("Single argument expected for %s::%s.", self::class, 'exists'));
            }
            $key = $args[0];
            $callable = $args[1];
            $val = $callable($this->__subject__);
            $val = ($val) ? "true" : "false";

            if (!isset($this->__rules__[$key])) {
                $this->__rules__[$key] = [];
            }
            $this->__rules__[$key][] = 'exists:' . $val;
        } else if (count($args) === 1) {
            if (!$this->__isSingleSubject__) {
                throw new \Error(sprintf("Two arguments expected for %s::%s.", self::class, 'exists'));
            }
            $callable = $args[0];
            $val = $callable($this->__subject__);
            $val = ($val) ? "true" : "false";

            $this->__rules__[] = 'exists:' . $val;
        } else {
            throw new \Error(sprintf("Invalid number of arguments for %s::%s", self::class, 'exists'));
        }

        return $this;
    }

    /**
     * Adds a rule for custom validation
     * 
     * @param callable $checker A function that performs the custom validation.
     * @return Chase\Helga\Validator
     */
    public function check(callable $checker)
    {
        $val = $checker($this->__subject__);
        $val = ($val) ? "true" : "false";

        $this->__rules__[] = 'check:' . $val;

        return $this;
    }

    /**
     * Pass an associative array of rules to an instance of Chase\Helga\Validator.
     * 
     * @param array $rules
     * @return Chase\Helga\Validator Returns the validator it was called on
     */
    public function withRules(array $rules)
    {

        if (!empty($this->__rules__)) {
            throw new \Error("Rules already specified.");
        }

        if (empty($rules)) {
            throw new \Error("Cannot pass empty rules.");
        }

        if ($this->__isSingleSubject__ && !is_int(array_keys($rules)[0])) {
            throw new \Error("Invalid rule format for single subject.");
        }

        if (!$this->__isSingleSubject__ && !is_string(array_keys($rules)[0])) {
            throw new \Error("Invalid rule format for array subject.");
        }

        $this->__rules__ = $rules;

        return $this;
    }


    private function eval()
    {
        $vs = (new RuleParser($this->__rules__))->parse($this->__subject__, $this->__key__);
        $this->__errors__ = $vs->errors;
        return $vs;
    }
}
