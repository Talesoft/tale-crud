<?php

namespace Tale\Crud\Value;

use Tale\Crud\ValueBase;
use Tale\Crud\Validator;

class BoolValueBase extends ValueBase
{

    protected function sanitize($value)
    {

        if (empty($value) || !$this->isScalar())
            return false;

        if ($this->isString()) {
            switch (strtolower($value)) {
                case 'yes':
                case 'true':
                case 'on':
                case '1':

                    return true;
                case 'no':
                case 'false':
                case 'off':
                case '0':

                    return false;
            }
        }

        return intval($value) ? true : false;
    }

    protected function validate(Validator $v)
    {

        $v->whenNotNull(function(Validator $v) {

            $v->notIn(
                [
                    true, false, 0, 1, '0', '1', 'yes',
                    'true', 'on', 'no', 'false', 'off'
                ],
                'The value needs to be one of 0/1/yes/true/on/no/false/off'
            );
        });
    }
}