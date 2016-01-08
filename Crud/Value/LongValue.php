<?php

namespace Tale\Crud\Value;


class LongValue extends IntValue
{

    const MIN = -2147483648;
    const MAX = 2147483647;
    const UNSIGNED_MIN = 0;
    const UNSIGNED_MAX = 4294967295;
}