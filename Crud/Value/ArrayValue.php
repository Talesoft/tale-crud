<?php

namespace Tale\Crud\Value;

use Tale\Crud\ValueBase;
use Tale\Crud\Validator;

class ArrayValueBase extends ValueBase implements \Countable, \IteratorAggregate, \ArrayAccess
{

    protected function sanitize($value)
    {

        if (!$this->isArray())
            return null;

        return $value;
    }

    protected function validate(Validator $v)
    {

        $v->whenNotNull(function(Validator $v) {

            $v->notArray('The value needs to be an array');
        });
    }


    public function count()
    {

        $value = $this->getSanitized();
        return $value ? count($value) : 0;
    }

    public function getIterator()
    {

        $value = $this->getSanitized();

        if ($value)
            foreach ($value as $key => $item)
                yield $key => $item;

    }

    public function offsetExists($offset)
    {
        $value = $this->getSanitized();
        return $value ? isset($value[$offset]) : false;
    }

    public function offsetGet($offset)
    {

        $value = $this->getSanitized();
        return $value ? $value[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {

        throw new \Exception(
            "Failed to set value on ArrayType: "
            ."The value is read-only"
        );
    }

    public function offsetUnset($offset)
    {

        throw new \Exception(
            "Failed to unset value on ArrayType: "
            ."The value is read-only"
        );
    }
}