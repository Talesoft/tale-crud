<?php

namespace Tale\Crud;

/**
 * Class Validator
 *
 * @package Tale\Crud
 */
class Validator
{

    /**
     * @var mixed The value to be validated
     */
    private $_value;

    /**
     * @var bool Indicates wether the value is empty or not
     */
    private $_empty;

    /**
     * @var bool Indicated wether the value is required or not
     */
    private $_required;

    /**
     * @var Validator[] The possible validations for the validated value
     */
    private $_allowances;

    /**
     * @var bool Indicates wether ->otherwise should validate
     */
    private $_otherwise;

    /**
     * @var string[] Contains the error messages generated
     */
    private $_errors;

    /**
     * @param $value
     */
    public function __construct($value)
    {

        $this->_value = $value;
        $this->_empty = is_null($value) || $value === '';
        $this->_allowances = [];
        $this->_otherwise = false;
        $this->_errors = [];
    }

    /**
     * @return mixed
     */
    public function getValue()
    {

        return $this->_value;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {

        return count($this->_errors) ? true : false;
    }

    /**
     * @return string[]
     */
    public function getErrors()
    {

        return $this->_errors;
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function addError($message)
    {

        $this->_errors[] = $message;

        return $this;
    }

    /**
     * @param array $errors
     *
     * @return $this
     */
    public function addErrors(array $errors)
    {

        $this->_errors = array_merge($this->_errors, $errors);

        return $this;
    }

    /**
     * @return $this
     */
    public function reset()
    {

        $this->_allowances = [];
        $this->_otherwise = false;
        $this->_errors = [];

        return $this;
    }

    /**
     * @param $condition
     * @param $validation
     *
     * @return $this
     */
    public function when($condition, $validation)
    {

        if (!is_callable($validation))
            throw new \InvalidArgumentException("Argument 1 passed to Validator->when must be callable");

        if($condition) {

            call_user_func($validation, $this);
            $this->_otherwise = false;
        } else
            $this->_otherwise = true;

        return $this;
    }

    /**
     * @param $validation
     *
     * @return $this
     */
    public function otherwise($validation)
    {

        if ($this->_otherwise)
            return $this->when(true, $validation);

        return $this;
    }

    /**
     * @param $validation
     *
     * @return $this
     */
    public function whenNotNull($validation)
    {

        return $this->when(!is_null($this->_value), $validation);
    }

    /**
     * @param $validation
     *
     * @return $this
     */
    public function allow($validation)
    {

        if (!is_callable($validation))
            throw new \InvalidArgumentException("Argument 1 passed to Validator->allow must be callable");

        $v = new static($this->_value);
        call_user_func($validation, $v);

        $this->_allowances[] = $v;

        return $this;
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notPassed($message)
    {


        $errors = [];
        foreach ($this->_allowances as $allowance) {

            if (!$allowance->hasErrors()) {

                return $this;
            }

            $errors = $allowance->getErrors();
        }

        $this->addErrors($errors);
        $this->addError($message);

        return $this;
    }


    /**
     * @param $condition
     * @param $message
     *
     * @return $this
     */
    public function not($condition, $message)
    {

        return $this->is(!$condition, $message);
    }

    /**
     * @param $condition
     * @param $message
     *
     * @return $this
     */
    public function is($condition, $message)
    {

        if ($condition)
            $this->addError($message);

        return $this;
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notSet($message)
    {

        return $this->is(is_null($this->_value), $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function isEmpty($message)
    {

        return $this->is($this->_empty, $message);
    }

    /**
     * @param      $min
     * @param null $max
     * @param      $message
     *
     * @return $this
     */
    public function notLongerThan($min, $max = null, $message)
    {

        $len = $this->_empty ? 0 : strlen($this->_value);
        $min = $len >= $min;
        $max = $max ? $len <= $max : true;

        return $this->not($min && $max, $message);
    }

    /**
     * @param $min
     * @param $max
     * @param $message
     *
     * @return $this
     */
    public function outOf($min, $max, $message)
    {

        $int = $this->_empty ? 0 : intval($this->_value);
        return $this->not($int >= $min && $int <= $max, $message);
    }

    /**
     * @param array $values
     * @param       $message
     *
     * @return $this
     */
    public function notIn(array $values, $message)
    {

        return $this->not(in_array($this->_value, $values, true), $message);
    }

    /**
     * @param $filter
     * @param $message
     *
     * @return $this
     */
    public function fails($filter, $message)
    {

        return $this->is(filter_var($this->_value, $filter) === false, $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notEmail($message)
    {

        return $this->fails(\FILTER_VALIDATE_EMAIL, $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notDateTime($message)
    {

        $result = \DateTimeImmutable::createFromFormat(\DateTimeImmutable::DATE_ATOM, $this->_value);
        return $this->not($result ? true : false, $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notInt($message)
    {

        return $this->fails(\FILTER_VALIDATE_INT, $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notFloat($message)
    {

        return $this->fails(\FILTER_VALIDATE_FLOAT, $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notArray($message)
    {

        return $this->not(is_array($this->_value), $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notObject($message)
    {

        return $this->not(is_object($this->_value), $message);
    }

    /**
     * @param $message
     * @param $className
     *
     * @return $this
     */
    public function notObjectOf($message, $className)
    {

        return $this->not(is_a($this->_value, $className), $message);
    }

    /**
     * @param $message
     * @param $className
     *
     * @return $this
     */
    public function notClassNameOf($message, $className)
    {

        return $this->not(is_a($this->_value, $className, true), $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notIpv4($message)
    {

        return $this->fails(\FILTER_VALIDATE_IP | \FILTER_FLAG_IPV4, $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notIpv6($message)
    {

        return $this->fails(\FILTER_VALIDATE_IP | \FILTER_FLAG_IPV6, $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notMac($message)
    {

        return $this->fails(\FILTER_VALIDATE_MAC, $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notRegEx($message)
    {

        return $this->fails(\FILTER_VALIDATE_REGEXP, $message);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function notUrl($message)
    {

        return $this->fails(\FILTER_VALIDATE_URL, $message);
    }

    /**
     * @param $pattern
     * @param $message
     *
     * @return $this
     */
    public function mismatches($pattern, $message)
    {

        return $this->not(preg_match($pattern, is_string($this->_value) ? $this->_value : ''), $message);
    }

    /**
     * @param        $message
     * @param string $additionalChars
     *
     * @return $this
     */
    public function notAlpha($message, $additionalChars = '')
    {

        return $this->mismatches("/^[a-z{$additionalChars}]+$/i", $message);
    }

    /**
     * @param        $message
     * @param string $additionalChars
     *
     * @return $this
     */
    public function notAlphaNumeric($message, $additionalChars = '')
    {

        return $this->notAlpha($message, '0-9'.$additionalChars);
    }

    /**
     * @param        $message
     * @param string $additionalChars
     *
     * @return $this
     */
    public function notCanonical($message, $additionalChars = '')
    {

        return $this->notAlphaNumeric($message, '_\-'.$additionalChars);
    }
}