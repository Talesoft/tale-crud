<?php

namespace Tale\Crud;

abstract class ValueBase
{

    private static $_types = [
        'bool' => 'Tale\\Crud\\Value\\BoolValue',
        'byte' => 'Tale\\Crud\\Value\\ByteValue',
        'ubyte' => 'Tale\\Crud\\Value\\UByteValue',
        'short' => 'Tale\\Crud\\Value\\ShortValue',
        'ushort' => 'Tale\\Crud\\Value\\UShortValue',
        'int' => 'Tale\\Crud\\Value\\IntValue',
        'uint' => 'Tale\\Crud\\Value\\UIntValue',
        'long' => 'Tale\\Crud\\Value\\LongValue',
        'ulong' => 'Tale\\Crud\\Value\\ULongValue',
        'double' => 'Tale\\Crud\\Value\\DoubleValue',
        'char' => 'Tale\\Crud\\Value\\CharValue',
        'string' => 'Tale\\Crud\\Value\\StringValue',
        'datetime' => 'Tale\\Crud\\Value\\DateTimeValue',
        'timestamp' => 'Tale\\Crud\\Value\\TimeStampValue',
        'enum' => 'Tale\\Crud\\Value\\EnumValue',
        'binary' => 'Tale\\Crud\\Value\\BinaryValue',
        'array' => 'Tale\\Crud\\Value\\ArrayValue',
        'object' => 'Tale\\Crud\\Value\\ObjectValue'
    ];

    private $_raw;
    private $_rawType;
    private $_sanitized;
    private $_validator;

    public function __construct($raw = null, Validator $v = null)
    {

        $this->_raw = $raw;
        $this->_rawType = strtolower(gettype($raw));
        $this->_sanitized = $this->isNull() ? null : $this->sanitize($raw);
        $this->_validator = $v ? $v : new Validator($this->_sanitized);
    }

    public function getRaw()
    {

        return $this->_raw;
    }

    public function getRawType()
    {

        return $this->_rawType;
    }

    public function getSanitized()
    {

        return $this->_sanitized;
    }

    public function getValidator()
    {

        return $this->_validator;
    }

    public function isBool()
    {

        return $this->_rawType === 'boolean';
    }

    public function isInt()
    {

        return $this->_rawType === 'integer';
    }

    public function isDouble()
    {

        return $this->_rawType === 'double';
    }

    public function isString()
    {

        return $this->_rawType === 'string';
    }

    public function isScalar()
    {

        return !$this->isArray() && !$this->isObject() && !$this->isResource();
    }

    public function isArray()
    {

        return $this->_rawType === 'array';
    }

    public function isObject()
    {

        return $this->_rawType === 'object';
    }

    public function isResource()
    {

        return $this->_rawType === 'resource';
    }

    public function isNull()
    {

        return $this->_rawType === 'null';
    }

    public function validates()
    {

        $this->_validator->reset();
        $this->validate($this->_validator);

        return $this->_validator->hasErrors();
    }

    public function getValidationErrors()
    {

        return $this->_validator->getErrors();
    }

    public function __toString()
    {

        return $this->isNull() ? '' : (string)$this->_raw;
    }


    abstract protected function sanitize($value);
    abstract protected function validate(Validator $v);

    public function create($className, $value)
    {

        if (isset(self::$_types[$className]))
            $className = self::$_types[$className];

        if (!is_subclass_of($className, ValueBase::class))
            throw new \InvalidArgumentException(
                "Argument 1 passed to Value::create is not a valid ".
                "ValueBase subclass"
            );

        return new $className($value);
    }

    public static function convert($value)
    {
        switch (gettype($value)) {
            case 'boolean': return self::create('bool', $value); break;
            case 'integer': return self::create('int', $value); break;
            case 'double': return self::create('double', $value); break;
            case 'string': return self::create('string', $value); break;
            case 'array': return self::create('array', $value); break;
            case 'object': return self::create('object', $value); break;
            case 'resource':
                //TODO: Maybe check for file-resource and create a binary-type?
                break;
        }

        return null;
    }
}