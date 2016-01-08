<?php

namespace Tale\Crud\Value;

use Tale\Crud\Validator;
use Tale\Crud\ValueBase;

class StringValue extends ValueBase
{

    protected function sanitize($value)
    {

        if (empty($value) || !$this->isScalar())
            return null;

        return strval($value);
    }

    protected function validate(Validator $v)
    {
    }
}