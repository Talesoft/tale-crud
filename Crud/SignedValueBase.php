<?php

namespace Tale\Crud;

abstract class SignedValueBase extends ValueBase
{

    private $_signed;

    public function __construct($raw, $signed = false)
    {

        parent::__construct($raw);

        $this->_signed = $signed;
    }

    public function isSigned()
    {

        return $this->_signed;
    }

    public function isUnsigned()
    {

        return !$this->_signed;
    }
}