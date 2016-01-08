<?php

namespace Tale\Crud\Value;

use Tale\Crud\SignedValueBase;
use Tale\Crud\Validator;

class IntValue extends SignedValueBase
{

    const MIN = -2147483648;
    const MAX = 2147483647;

    protected function sanitize($value)
    {

        if (empty($value) || !$this->isScalar())
            return null;

        return intval($value);
    }

    protected function validate(Validator $v)
    {

        $v->whenNotNull(function(Validator $v) {

            $v->notInt('Value has to be an integer')
                ->outOf(
                static::MIN, static::MAX,
                sprintf('Value has to be between %d and %d', static::MIN, static::MAX
            ));
        });
    }
}