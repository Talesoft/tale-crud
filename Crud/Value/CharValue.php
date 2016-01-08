<?php

namespace Tale\Crud\Value;

use Tale\Crud\ValueBase;
use Tale\Crud\Validator;

class CharValue extends ValueBase
{

    protected function sanitize($value)
    {

        if (empty($value) || !$this->isScalar())
            return null;

        return strval($value);
    }

    protected function validate(Validator $v)
    {

        $v->whenNotNull(function(Validator $v) {

            $v->notLongerThan(1,1, 'The value needs to be exatly one character long');
        });
    }
}