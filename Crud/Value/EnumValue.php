<?php

namespace Tale\Crud\Value;

use Tale\Crud\Validator;

class EnumValue extends StringValue
{

    private $_options;

    public function __construct($value = null, array $options = null)
    {

        parent::__construct($value);

        $this->_options = $options ? $options : [];
    }

    public function getOptions()
    {

        return $this->_options;
    }

    public function addOption($option, $label = null)
    {

        if (!$label)
            $this->_options[] = $option;
        else
            $this->_options[$label] = $option;

        return $this;
    }

    protected function validate(Validator $v)
    {

        $v->whenNotNull(function(Validator $v) {

            $v->notIn($this->_options, 'The value needs to be one of '.implode(', ', array_values($this->_options)));
        });
    }
}