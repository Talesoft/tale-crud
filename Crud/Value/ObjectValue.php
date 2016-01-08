<?php

namespace Tale\Crud\Value;

use Tale\Crud\ValueBase;
use Tale\Crud\Validator;

class ObjectValue extends ValueBase
{

    protected function sanitize($value)
    {

        if (!$this->isObject())
            return null;

        return $value;
    }

    protected function validate(Validator $v)
    {

        $v->whenNotNull(function(Validator $v) {

            $v->notObject('The value needs to be an object');
        });

        return parent::validate($v);
    }

    public function __isset($key)
    {

        $value = $this->getSanitized();
        return $value ? isset($value->{$key}) : false;
    }

    public function __get($key)
    {

        $value = $this->getSanitized();
        return $value ? $value->{$key} : null;
    }
}