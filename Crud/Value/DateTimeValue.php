<?php

namespace Tale\Crud\Value;

use Tale\Crud\ValueBase;
use Tale\Crud\Validator;

class DateTimeValue extends ValueBase
{

    protected function sanitize($value)
    {

        if (empty($value) || !$this->isScalar())
            return null;

        if (!is_string($value))
            return new \DateTimeImmutable("@".intval($value));

        $value = \DateTimeImmutable::createFromFormat(\DateTimeImmutable::DATE_ATOM, $value);
        return $value ? $value : null;
    }

    protected function validate(Validator $v)
    {

        $v->whenNotNull(function(Validator $v) {

            $v->allow(function(Validator $v) {

                $v->notInt('The value is not a valid UNIX-TimeStamp');
            })->allow(function(Validator $v) {

                $v->notDateTime('The value is not a valid Date and Time string');
            })->notPassed('The value is not a valid Date and/or Time');
        });
    }
}