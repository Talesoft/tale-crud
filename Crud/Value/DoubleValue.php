<?php

namespace Tale\Crud\Value;

use Tale\Crud\ValueBase;
use Tale\Crud\Validator;

class DoubleValue extends ValueBase
{

    protected function sanitize($value)
    {

        if (empty($value) || !$this->isScalar())
            return null;

        return floatval($value);
    }

    protected function validate(Validator $v)
    {

        $v->whenNotNull(function(Validator $v) {

            $v->notFloat('Value has to be a floating point number');
        });
    }
}