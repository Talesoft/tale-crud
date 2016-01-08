<?php

namespace Tale\Crud\Type;

use Tale\Crud\TypeBase;
use Tale\Crud\Validator;
use Tale\Crud\Value\DateTimeValue;

class TimeStampType extends DateTimeValue
{

    protected function sanitize($value)
    {

        if (empty($value))
            return null;

        if (is_numeric($value))


        $time = intval($value);

        return new \DateTime("@$time");
    }

    protected function validate(Validator $v)
    {

        $v->whenNotNull(function(Validator $v) {

            $v->notInt('The value needs to be an int representing a UNIX time stamp');
        });
    }
}